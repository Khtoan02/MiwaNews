<?php
get_header();
?>
<div class="container w-full max-w-[1200px] mx-auto px-1 sm:px-4 ">
    <!-- Section 1: Nội dung bài viết -->
    <section class="mb-12">
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="bg-white rounded-xl px-2 sm:px-4 md:px-6 lg:px-8 py-4 md:py-10 mb-6 w-full max-w-[1200px] mx-auto relative">
                <div class="flex flex-col items-center w-full">
                    <div class="mb-3">
                        <span class="px-4 py-1 text-sm rounded bg-yellow-50 text-gray-500 font-medium">Bài viết</span>
                    </div>
                    <h1 class="text-center text-3xl md:text-4xl font-bold mb-2 leading-tight text-gray-900">
                        <?php the_title(); ?>
                    </h1>
                    <div class="text-xs text-center text-gray-500 mb-3">
                        <?php echo get_the_date('l, d/m/Y H:i'); ?>
                    </div>
                    <?php if (has_excerpt() && get_the_excerpt()) : ?>
                        <div class="font-medium text-gray-700 text-center max-w-2xl mb-6">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="mb-6 w-full flex flex-col items-center relative">
                            <div class="rounded-2xl overflow-hidden w-full max-w-[1200px] mx-auto">
                                <?php the_post_thumbnail('large', [
                                    'class' => 'w-full max-h-[520px] object-cover mx-auto rounded-2xl',
                                    'alt' => get_the_title()
                                ]);
                                ?>
                                <?php $caption = get_post(get_post_thumbnail_id())->post_excerpt ?? ''; if (!empty($caption)) : ?>
                                    <figcaption style="position:absolute;left:12px;bottom:12px;z-index:30;"
                                        class="inline-flex items-center gap-2 px-3 py-1 rounded-lg text-xs bg-black/75 text-white shadow-lg max-w-[95%] font-normal">
                                        <svg height="15" width="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="inline mr-1 text-blue-300"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12" y2="16"></line></svg>
                                        <?php echo esc_html($caption); ?>
                                    </figcaption>
                                <?php endif; ?>
                            </div>
                        </figure>
                    <?php endif; ?>
                    <div class="markdown-content">
                        <?php the_content(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; endif; ?>
        <div class="flex justify-between mt-8 max-w-[1200px] mx-auto">
            <div><?php previous_post_link('%link', '← Bài trước'); ?></div>
            <div><?php next_post_link('%link', 'Bài sau →'); ?></div>
        </div>
        <!-- Đã tắt phần bình luận, xoá comments_template -->
    </section>

    <!-- Section 2: Danh sách bài viết mới nhất (giống index, lazy-load) -->
    <section id="latest-posts" class="">
        <h2 class="text-2xl font-bold mb-6">Bài Viết Mới Nhất</h2>
        <div id="latest-listing">
            <?php
            // Hiện 10 bài đầu, các bài tiếp theo sẽ load bằng ajax
            $latest = new WP_Query([
                'posts_per_page' => 10,
                'paged' => 1,
                'post__not_in' => [get_the_ID()]
            ]);
            if ($latest->have_posts()):
                while ($latest->have_posts()): $latest->the_post(); ?>
                <article class="mb-8 pb-8 border-b border-gray-200 flex flex-col sm:flex-row gap-4">
                    <?php if (has_post_thumbnail()): ?>
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
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <div class="text-center my-6">
            <button id="load-more-posts" class="inline-flex items-center px-6 py-2 bg-gray-100 hover:bg-gray-200 rounded text-gray-700 font-medium text-lg" data-paged="1">Tải thêm...</button>
        </div>
        <?php else: ?>
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
  var btn = document.getElementById('load-more-posts');
  if(btn) btn.remove();
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
    window.addEventListener('scroll', function() {
      if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 200) {
        loadMore();
      }
    });
  }
});
</script>
<?php
// Không có footer.php, chỉ cần wp_footer
wp_footer();
?>
