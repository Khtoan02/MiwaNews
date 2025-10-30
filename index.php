<?php
get_header();
?>
<div class="container mx-auto max-w-[1200px] px-4 mt-16">
  <!-- Section 1: Grid bài viết một danh mục -->
  <?php
    $cat_slug = get_theme_mod('section1_category_slug', '');
    if ($cat_slug) {
      $cat = get_category_by_slug($cat_slug);
      if ($cat) {
        $section1_query = new WP_Query([
          'cat' => $cat->term_id,
          'posts_per_page' => 7
        ]);
        if ($section1_query->have_posts()) {
          $posts_mag = [];
          while ($section1_query->have_posts()) : $section1_query->the_post();
            $posts_mag[] = [
              'ID' => get_the_ID(),
              'permalink' => get_permalink(),
              'title' => get_the_title(),
              'excerpt' => get_the_excerpt(),
              'img' => get_the_post_thumbnail_url(get_the_ID(), 'medium_large'),
              'date' => get_the_date(),
            ];
          endwhile;
          ?>
          <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($cat->name); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Bài 1: bài lớn (trái) -->
              <div class="md:col-span-2 flex flex-col justify-between bg-white rounded-xl shadow-lg p-6 min-h-[320px] md:min-h-[350px]">
                <?php if (!empty($posts_mag[0])) : $b1 = $posts_mag[0]; ?>
                  <div>
                    <div class="text-xs text-right text-gray-500 mb-2"><?php echo $b1['date']; ?></div>
                    <h3 class="text-2xl font-bold mb-2"><?php echo $b1['title']; ?></h3>
                    <div class="text-sm text-gray-700 mb-4"><?php echo $b1['excerpt']; ?></div>
                  </div>
                  <div class="flex justify-end">
                    <a href="<?php echo $b1['permalink']; ?>" class="inline-block mt-auto px-5 py-2 bg-gray-200 hover:bg-gray-300 rounded-full text-base font-medium">Đọc ngay</a>
                  </div>
                <?php endif; ?>
              </div>
              <div class="flex flex-col gap-4">
                <!-- Bài 2 và Bài 3 (bên phải, dưới bài lớn) -->
                <?php for($i=1;$i<=2;$i++): if(!empty($posts_mag[$i])):$b=$posts_mag[$i];?>
                <div class="bg-white rounded-xl shadow p-4 flex flex-col min-h-[110px]">
                  <div class="text-xs text-right text-gray-500 mb-1"><?php echo $b['date']; ?></div>
                  <h4 class="text-base font-semibold mb-1"><?php echo $b['title']; ?></h4>
                  <div class="text-xs text-gray-600 mb-2 line-clamp-2"><?php echo $b['excerpt']; ?></div>
                  <a href="<?php echo $b['permalink']; ?>" class="inline-block px-4 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300">Đọc ngay</a>
                </div>
                <?php endif; endfor; ?>
              </div>
            </div>
            <!-- Hàng dưới: 4 bài nhỏ ngang -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
              <?php for($i=3;$i<7;$i++): if(!empty($posts_mag[$i])):$b=$posts_mag[$i];?>
              <div class="bg-white rounded-xl shadow p-4 flex flex-col min-h-[110px]">
                <div class="text-xs text-right text-gray-500 mb-1"><?php echo $b['date']; ?></div>
                <h4 class="text-base font-semibold mb-1"><?php echo $b['title']; ?></h4>
                <div class="text-xs text-gray-600 mb-2 line-clamp-2"><?php echo $b['excerpt']; ?></div>
                <a href="<?php echo $b['permalink']; ?>" class="inline-block px-4 py-1 text-sm bg-gray-200 rounded hover:bg-gray-300">Đọc ngay</a>
              </div>
              <?php endif; endfor; ?>
            </div>
          </section>
          <?php wp_reset_postdata(); ?>
          <?php
        }
      }
    }
  ?>

  <!-- Section 2: Listing bài viết mới nhất (infinite scroll/lazy-load) -->
  <section id="latest-posts" class="">
    <h2 class="text-2xl font-bold mb-6">Bài Viết Mới Nhất</h2>
    <div id="latest-listing">
      <?php
        // Hiện 10 bài đầu, các bài tiếp theo sẽ load bằng ajax
        $latest = new WP_Query([
          'posts_per_page' => 10,
          'paged' => 1
        ]);
        if ($latest->have_posts()) :
          while ($latest->have_posts()) : $latest->the_post();
      ?>
        <article class="mb-8 pb-8 border-b border-gray-200 flex flex-col sm:flex-row gap-4">
          <?php if (has_post_thumbnail()) : ?>
            <a href="<?php the_permalink(); ?>" class="block w-full sm:w-48 flex-shrink-0">
              <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-32 sm:h-32 object-cover rounded']); ?>
            </a>
          <?php endif; ?>
          <div class="flex-1">
            <h3 class="text-lg font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:text-blue-600"><?php the_title(); ?></a></h3>
            <div class="text-sm text-gray-500 mb-2"><?php echo get_the_date(); ?></div>
            <div class="line-clamp-3 text-gray-700 text-sm mb-2"><?php the_excerpt(); ?></div>
            <a href="<?php the_permalink(); ?>" class="text-blue-600 hover:underline font-medium">Đọc tiếp &rarr;</a>
          </div>
        </article>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
    </div>
    <div class="text-center my-6">
      <button id="load-more-posts" class="inline-flex items-center px-6 py-2 bg-gray-100 hover:bg-gray-200 rounded text-gray-700 font-medium text-lg" data-paged="1">Tải thêm...</button>
    </div>
    <?php else : ?>
      <p>Không có bài viết nào được tìm thấy.</p>
    <?php endif; ?>
  </section>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const wrapper = document.getElementById('latest-posts');
  const listing = document.getElementById('latest-listing');
  let isLoading = false;
  let paged = 1;
  let ended = false;

  // tạo sentinel
  const sentinel = document.createElement('div');
  sentinel.id = 'load-more-sentinel';
  wrapper.appendChild(sentinel);

  function loadMore() {
    if (isLoading || ended) return;
    isLoading = true;
    paged++;
    sentinel.textContent = 'Đang tải...';
    fetch(window.miwanews_ajax_url, {
      method: 'POST',
      headers: { "Content-Type": "application/x-www-form-urlencoded" },
      body: `action=miwanews_load_more_posts&paged=${paged}`
    })
    .then(res=>res.json())
    .then(data=>{
      if (!data.success || !data.data.html.trim()) {
        sentinel.textContent = 'Không còn bài viết nào.';
        ended = true;
      } else {
        listing.insertAdjacentHTML('beforeend', data.data.html);
        sentinel.textContent = '';
      }
      isLoading = false;
    })
    .catch(() => {
      sentinel.textContent = 'Tải lỗi. Thử lại.';
      isLoading = false;
    });
  }

  // Xoá nút tải thêm nếu có
  var btn = document.getElementById('load-more-posts');
  if(btn) btn.remove();

  // Intersection Observer
  if('IntersectionObserver' in window) {
    const io = new IntersectionObserver((entries) => {
      entries.forEach(entry => {
        if(entry.isIntersecting) {
          loadMore();
        }
      });
    }, { rootMargin: '100px' });
    io.observe(sentinel);
  } else {
    // Fallback: scroll event
    window.addEventListener('scroll', function() {
      if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
        loadMore();
      }
    });
  }
});
</script>
