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
              'img' => get_the_post_thumbnail_url(get_the_ID(), 'large'),
              'date' => get_the_date('H:i d/m/Y'),
            ];
          endwhile;
          ?>
          <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($cat->name); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
              <!-- Bài 1: dạng hero overlay, ảnh nền full -->
              <div class="relative md:col-span-2 rounded-xl shadow-lg overflow-hidden min-h-[320px] md:min-h-[350px] flex">
                <?php if (!empty($posts_mag[0])) : $b1 = $posts_mag[0]; ?>
                  <?php if (!empty($b1['img'])): ?>
                    <img src="<?php echo $b1['img']; ?>" alt="<?php echo esc_attr($b1['title']); ?>" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-black/0 z-10"></div>
                  <?php endif; ?>
                  <!-- Ngày top-right -->
                  <div class="absolute top-3 right-3 z-20"><span class="bg-black/70 text-white rounded-full px-3 py-1 text-xs"><?php echo $b1['date']; ?></span></div>
                  <!-- Text/nút -->
                  <div class="absolute bottom-0 left-0 right-0 z-20 p-5 md:p-8 flex flex-col gap-3">
                    <h3 class="text-2xl md:text-3xl font-bold mb-1 text-white leading-tight"> <?php echo $b1['title']; ?> </h3>
                    <div class="text-base md:text-lg text-white/90 mb-3"><?php echo $b1['excerpt']; ?></div>
                    <div class="flex justify-end">
                      <a href="<?php echo $b1['permalink']; ?>" class="inline-block mt-auto px-6 py-2 bg-white/20 text-white font-medium rounded-full border border-white/40 hover:bg-white/40 transition">Đọc ngay</a>
                    </div>
                  </div>
                <?php endif; ?>
              </div>
              <div class="flex flex-col gap-4">
                <!-- Bài 2 và Bài 3 (hero card nhỏ overlay) -->
                <?php for($i=1;$i<=2;$i++): if(!empty($posts_mag[$i])):$b=$posts_mag[$i];?>
                <div class="relative rounded-xl shadow overflow-hidden min-h-[140px] flex flex-col">
                  <?php if (!empty($b['img'])): ?>
                    <img src="<?php echo $b['img']; ?>" alt="<?php echo esc_attr($b['title']); ?>" class="absolute inset-0 w-full h-full object-cover z-0" loading="lazy">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/25 to-black/0 z-10"></div>
                  <?php endif; ?>
                  <!-- Ngày top-right -->
                  <div class="absolute top-2 right-2 z-20"><span class="bg-black/60 text-white rounded-full px-2 py-1 text-xs"><?php echo $b['date']; ?></span></div>
                  <div class="absolute bottom-0 left-0 right-0 z-20 p-4 flex flex-col gap-1">
                    <h4 class="text-lg font-semibold mb-0 text-white leading-tight line-clamp-2"><?php echo $b['title']; ?></h4>
                    <div class="text-xs text-white/90 mb-2 line-clamp-2"><?php echo $b['excerpt']; ?></div>
                    <a href="<?php echo $b['permalink']; ?>" class="inline-block px-4 py-1 text-sm bg-white/20 text-white rounded-full hover:bg-white/40 transition mt-auto">Đọc ngay</a>
                  </div>
                </div>
                <?php endif; endfor; ?>
              </div>
            </div>
            <!-- Hàng dưới: 4 bài nhỏ ngang -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
              <?php for($i=3;$i<7;$i++): if(!empty($posts_mag[$i])):$b=$posts_mag[$i];?>
              <div class="rounded-xl shadow bg-white flex flex-col overflow-hidden">
                <div class="relative">
                  <?php if (!empty($b['img'])): ?>
                    <a href="<?php echo $b['permalink']; ?>" class="block w-full h-36 md:h-40 overflow-hidden"><img src="<?php echo $b['img']; ?>" alt="<?php echo esc_attr($b['title']); ?>" class="w-full h-full object-cover" loading="lazy" style="border-top-left-radius:0.75rem;border-top-right-radius:0.75rem"></a>
                  <?php endif; ?>
                  <!-- Ngày ở trên ảnh góc phải -->
                  <div class="absolute top-2 right-2 z-10"><span class="bg-black/60 text-white rounded-full px-2 py-1 text-xs"><?php echo $b['date']; ?></span></div>
                  <!-- Nút Đọc ngay trên ảnh, dưới bên trái -->
                  <a href="<?php echo $b['permalink']; ?>" class="absolute left-2 bottom-2 z-10 px-3 py-1 text-sm bg-black/60 text-white rounded-full hover:bg-black/80 transition">Đọc ngay</a>
                </div>
                <div class="flex flex-col flex-1 px-3 pb-3 pt-2">
                  <h4 class="text-base font-semibold mb-1 leading-tight line-clamp-2"><?php echo $b['title']; ?></h4>
                  <div class="text-xs text-gray-600 mb-1 line-clamp-2"><?php echo $b['excerpt']; ?></div>
                </div>
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
