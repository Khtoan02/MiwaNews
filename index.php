<?php
get_header();
?>
<div class="container mx-auto px-4 mt-16" style="max-width: 1200px">
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
              'img' => get_the_post_thumbnail_url(get_the_ID(), 'miwanews-hero-169'),
              'date' => get_the_date('H:i d/m/Y'),
            ];
          endwhile;
          ?>
          <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6"><?php echo esc_html($cat->name); ?></h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <!-- Cột trái: Bài 1 - hero overlay 16:9 -->
              <div class="relative rounded-xl shadow-lg overflow-hidden aspect-video relative-card group">
                <?php if (!empty($posts_mag[0])) : $b1 = $posts_mag[0]; ?>
                  <?php 
                    $img_src = !empty($b1['img']) ? $b1['img'] : "data:image/svg+xml,%3Csvg width='800' height='450' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='a' x1='0%25' y1='0%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0' stop-color='%23f3f4f6'/%3E%3Cstop offset='0.5' stop-color='%23e5e7eb'/%3E%3Cstop offset='1' stop-color='%23f3f4f6'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23a)'%3E%3Canimate attributeName='x' from='-100%25' to='100%25' dur='1.5s' repeatCount='indefinite'/%3E%3C/rect%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='18' fill='%239ca3af'%3E%C4%90ang%20t%E1%BA%A3i%20%E1%BA%A3nh...%3C/text%3E%3C/svg%3E";
                  ?>
                  <img src="<?php echo $img_src; ?>" alt="<?php echo esc_attr($b1['title']); ?>" class="absolute inset-0 w-full h-full object-cover z-0 <?php echo empty($b1['img']) ? 'image-placeholder-active' : ''; ?>" loading="lazy">
                  <a href="<?php echo $b1['permalink']; ?>" class="stretched-link z-10"><span class="sr-only"><?php echo $b1['title']; ?></span></a>
                  <div class="absolute top-3 right-3 z-20"><span class="bg-black/70 text-white rounded-full px-3 py-1 text-xs backdrop-blur-sm"><?php echo $b1['date']; ?></span></div>
                  <!-- Darker gradient for better text readability -->
                  <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/50 to-transparent z-10 pointer-events-none"></div>
                  
                  <div class="absolute bottom-0 left-0 right-0 z-20 p-5 md:p-6 flex flex-col gap-2 pointer-events-none">
                    <h3 class="text-xl md:text-2xl font-bold text-white leading-snug line-clamp-2 drop-shadow-sm">
                       <?php echo $b1['title']; ?>
                    </h3>
                    <div class="text-sm text-gray-200 mb-2 line-clamp-2 leading-relaxed drop-shadow-sm"><?php echo $b1['excerpt']; ?></div>
                    <div class="flex justify-start">
                      <span class="inline-block px-4 py-1.5 bg-white/20 text-white text-xs font-semibold rounded-full border border-white/30 hover:bg-white/30 transition backdrop-blur-md">Đọc ngay</span>
                    </div>
                  </div>
                <?php endif; ?>
              </div>

              <!-- Cột phải: 2 bài viết ngang (ảnh trái, text phải) -->
              <div class="flex flex-col gap-4">
                <?php for($i=1;$i<=2;$i++): if(!empty($posts_mag[$i])):$b=$posts_mag[$i];?>
                <article class="rounded-xl shadow bg-white overflow-hidden flex items-stretch relative-card group">
                  <!-- Ảnh bên trái (tỉ lệ 16:9) + badge trên ảnh -->
                  <div class="relative block w-48 md:w-64 aspect-video flex-shrink-0 overflow-hidden rounded-l-xl">
                    <?php 
                      $img_src = !empty($b['img']) ? $b['img'] : "data:image/svg+xml,%3Csvg width='800' height='450' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='a' x1='0%25' y1='0%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0' stop-color='%23f3f4f6'/%3E%3Cstop offset='0.5' stop-color='%23e5e7eb'/%3E%3Cstop offset='1' stop-color='%23f3f4f6'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23a)'%3E%3Canimate attributeName='x' from='-100%25' to='100%25' dur='1.5s' repeatCount='indefinite'/%3E%3C/rect%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%239ca3af'%3E%C4%90ang%20t%E1%BA%A3i...%3C/text%3E%3C/svg%3E";
                    ?>
                    <img src="<?php echo $img_src; ?>" alt="<?php echo esc_attr($b['title']); ?>" class="absolute inset-0 w-full h-full object-cover <?php echo empty($b['img']) ? 'image-placeholder-active' : ''; ?>" loading="lazy">
                    <span class="absolute top-2 right-2 bg-black/70 text-white rounded-full px-2 py-1 text-xs z-10"><?php echo $b['date']; ?></span>
                  </div>
                  <!-- Text bên phải: cùng cột trái, căn trái -->
                  <div class="flex flex-col flex-1 p-4 gap-2 items-start">
                    <h4 class="text-lg font-semibold leading-tight line-clamp-2">
                        <a href="<?php echo $b['permalink']; ?>" class="hover:text-blue-600 stretched-link"><?php echo $b['title']; ?></a>
                    </h4>
                    <div class="text-sm text-gray-600 line-clamp-3 pr-2"><?php echo $b['excerpt']; ?></div>
                    <span class="px-4 py-1.5 text-sm bg-gray-900 text-white rounded-full hover:bg-gray-700">Đọc ngay</span>
                  </div>
                </article>
                <?php endif; endfor; ?>
              </div>
            </div>

            <!-- Hàng dưới: 4 bài nhỏ, ảnh 16:9, không overlay -->
            <div class="mt-4 grid grid-cols-1 md:grid-cols-4 gap-4">
              <?php for($i=3;$i<7;$i++): if(!empty($posts_mag[$i])):$b=$posts_mag[$i];?>
              <div class="rounded-xl shadow bg-white flex flex-col overflow-hidden relative-card group">
                <div class="relative w-full aspect-video">
                  <?php 
                      $img_src = !empty($b['img']) ? $b['img'] : "data:image/svg+xml,%3Csvg width='800' height='450' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='a' x1='0%25' y1='0%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0' stop-color='%23f3f4f6'/%3E%3Cstop offset='0.5' stop-color='%23e5e7eb'/%3E%3Cstop offset='1' stop-color='%23f3f4f6'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23a)'%3E%3Canimate attributeName='x' from='-100%25' to='100%25' dur='1.5s' repeatCount='indefinite'/%3E%3C/rect%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%239ca3af'%3E%C4%90ang%20t%E1%BA%A3i...%3C/text%3E%3C/svg%3E";
                  ?>
                  <img src="<?php echo $img_src; ?>" alt="<?php echo esc_attr($b['title']); ?>" class="absolute inset-0 w-full h-full object-cover <?php echo empty($b['img']) ? 'image-placeholder-active' : ''; ?>" loading="lazy" style="border-top-left-radius:0.75rem;border-top-right-radius:0.75rem">
                  <div class="absolute top-2 right-2 z-10"><span class="bg-black/60 text-white rounded-full px-2 py-1 text-xs"><?php echo $b['date']; ?></span></div>
                </div>
                <div class="flex flex-col flex-1 px-3 pb-3 pt-2">
                  <h4 class="text-base font-semibold mb-1 leading-tight line-clamp-2">
                      <a href="<?php echo $b['permalink']; ?>" class="stretched-link hover:text-blue-600"><?php echo $b['title']; ?></a>
                  </h4>
                  <div class="text-xs text-gray-600 mb-1 line-clamp-2"><?php echo $b['excerpt']; ?></div>
                   <div class="mt-auto pt-2">
                    <span class="inline-block px-3 py-1 text-sm bg-black/60 text-white rounded-full hover:bg-black/80 transition">Đọc ngay</span>
                   </div>
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
        <article class="mb-8 pb-8 border-b border-gray-200 flex flex-col sm:flex-row gap-4 relative-card group">
            <!-- Thumbnail Div -->
            <div class="block w-full sm:w-48 flex-shrink-0">
              <?php if (has_post_thumbnail()) : ?>
                <?php the_post_thumbnail('medium_large', ['class' => 'w-full h-32 sm:h-32 object-cover rounded']); ?>
              <?php else : ?>
                <img src="data:image/svg+xml,%3Csvg width='800' height='450' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='a' x1='0%25' y1='0%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0' stop-color='%23f3f4f6'/%3E%3Cstop offset='0.5' stop-color='%23e5e7eb'/%3E%3Cstop offset='1' stop-color='%23f3f4f6'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23a)'%3E%3Canimate attributeName='x' from='-100%25' to='100%25' dur='1.5s' repeatCount='indefinite'/%3E%3C/rect%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='16' fill='%239ca3af'%3E%C4%90ang%20t%E1%BA%A3i%20%E1%BA%A3nh...%3C/text%3E%3C/svg%3E" alt="" class="w-full h-32 sm:h-32 object-cover rounded image-placeholder-active">
              <?php endif; ?>
            </div>
          <div class="flex-1">
            <h3 class="text-lg font-semibold mb-2"><a href="<?php the_permalink(); ?>" class="hover:text-blue-600 stretched-link"><?php the_title(); ?></a></h3>
            <div class="text-sm text-gray-500 mb-2"><?php echo get_the_date(); ?></div>
            <div class="line-clamp-3 text-gray-700 text-sm mb-2"><?php the_excerpt(); ?></div>
            <span class="text-blue-600 hover:underline font-medium">Đọc tiếp &rarr;</span>
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

<?php get_footer(); ?>
