<?php
/**
 * Template Name: MiwaNews Home
 * Description: Trang tạp chí với hero, xu hướng, danh mục nổi bật và CTA newsletter.
 *
 * @package MiwaNews
 */

get_header();

$featured_slug  = get_theme_mod('miwanews_featured_category_slug', '');
$trending_tag   = get_theme_mod('miwanews_trending_tag_slug', '');
$showcase_input = get_theme_mod('miwanews_showcase_categories', '');
$showcase_slugs = array_filter(array_map('sanitize_title', array_map('trim', explode(',', $showcase_input))));

/**
 * Helper: build list data từ WP_Query để dùng lại nhiều lần.
 */
if (!function_exists('miwanews_collect_posts')) {
    function miwanews_collect_posts(WP_Query $query) {
        $items = [];
        while ($query->have_posts()) {
            $query->the_post();
            $items[] = [
                'id'         => get_the_ID(),
                'title'      => get_the_title(),
                'link'       => get_permalink(),
                'excerpt'    => wp_strip_all_tags(get_the_excerpt()),
                'thumb'      => get_the_post_thumbnail_url(get_the_ID(), 'miwanews-hero-169'),
                'date'       => get_the_date('d/m/Y'),
                'time'       => get_the_date('H:i'),
                'author'     => get_the_author(),
                'categories' => get_the_category(),
            ];
        }
        wp_reset_postdata();
        return $items;
    }
}

// Section Hero query.
$hero_args = [
    'posts_per_page'      => 5,
    'ignore_sticky_posts' => true,
];
if ($featured_slug) {
    $category = get_category_by_slug($featured_slug);
    if ($category) {
        $hero_args['cat'] = $category->term_id;
    }
}
$hero_query = new WP_Query($hero_args);
$hero_posts = $hero_query->have_posts() ? miwanews_collect_posts($hero_query) : [];

// Section Trending query.
$trending_args = [
    'posts_per_page'      => 8,
    'orderby'             => 'comment_count',
    'ignore_sticky_posts' => true,
];
if ($trending_tag) {
    $trending_args['tag'] = $trending_tag;
}
$trending_query = new WP_Query($trending_args);
$trending_posts = $trending_query->have_posts() ? miwanews_collect_posts($trending_query) : [];

// Spotlight sticky posts.
$spotlight_posts = [];
$sticky_ids = get_option('sticky_posts');
if (!empty($sticky_ids)) {
    rsort($sticky_ids);
    $spotlight_query = new WP_Query([
        'post__in'            => $sticky_ids,
        'posts_per_page'      => 3,
        'ignore_sticky_posts' => false,
    ]);
    $spotlight_posts = $spotlight_query->have_posts() ? miwanews_collect_posts($spotlight_query) : [];
}

// Showcase categories: ưu tiên slug custom, fallback top categories.
$showcase_categories = [];
if (!empty($showcase_slugs)) {
    $showcase_categories = get_categories([
        'slug'       => $showcase_slugs,
        'hide_empty' => false,
    ]);
} else {
    $showcase_categories = get_categories([
        'orderby'    => 'count',
        'order'      => 'DESC',
        'number'     => 3,
        'hide_empty' => false,
    ]);
}
?>

<main class="bg-gray-50">
  <div class="mx-auto max-w-6xl px-4 py-10 space-y-16">
    <!-- Hero Section -->
    <section class="space-y-6">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase text-gray-400" style="letter-spacing: 0.3em;">editor's pick</p>
          <h2 class="text-3xl font-bold text-gray-800">Tiêu điểm hôm nay</h2>
        </div>
        <?php if ($featured_slug && !empty($category)) : ?>
          <a href="<?php echo esc_url(get_category_link($category)); ?>" class="text-sm font-semibold text-blue-600 hover:text-blue-800">
            Xem tất cả &rarr;
          </a>
        <?php endif; ?>
      </div>

      <!-- HERO LEFT: Main Post -->
      <?php if (!empty($hero_posts)) : ?>
        <div class="grid gap-6 lg:grid-cols-3">
          <?php $main = $hero_posts[0]; ?>
          <article class="relative overflow-hidden rounded-3xl bg-gray-900 text-white lg:col-span-2 relative-card group">
            <?php if (!empty($main['thumb'])) : ?>
              <img src="<?php echo esc_url($main['thumb']); ?>" alt="<?php echo esc_attr($main['title']); ?>" class="absolute inset-0 h-full w-full object-cover opacity-80 transition duration-700 group-hover:scale-105 group-hover:opacity-90" loading="lazy">
              <span class="absolute inset-0 bg-gradient-to-tr from-black/80 via-black/20 to-transparent"></span>
            <?php endif; ?>
            <div class="relative z-10 flex h-full flex-col justify-end space-y-4 p-8">
              <div class="flex gap-3 text-xs uppercase tracking-widest text-white/80 z-over-link">
                <?php foreach ($main['categories'] as $cat_item) : ?>
                  <a href="<?php echo esc_url(get_category_link($cat_item->term_id)); ?>" class="rounded-full border border-white/40 px-3 py-1 hover:bg-white/10 z-over-link relative">
                    <?php echo esc_html($cat_item->name); ?>
                  </a>
                <?php endforeach; ?>
              </div>
              <h3 class="text-3xl font-bold leading-tight">
                <a href="<?php echo esc_url($main['link']); ?>" class="hover:text-blue-200 stretched-link"><?php echo esc_html($main['title']); ?></a>
              </h3>
              <p class="line-clamp-3 text-base text-white/90"><?php echo esc_html($main['excerpt']); ?></p>
              <div class="flex items-center justify-between text-sm text-white/80 z-over-link">
                <span><?php echo esc_html($main['author']); ?> • <?php echo esc_html($main['time']); ?> • <?php echo esc_html($main['date']); ?></span>
                <span class="rounded-full bg-white/20 px-4 py-2 font-semibold hover:bg-white/40 z-over-link relative pointer-events-none">Đọc ngay</span>
              </div>
            </div>
          </article>

          <div class="grid gap-4">
            <?php foreach (array_slice($hero_posts, 1, 3) as $item) : ?>
              <article class="flex gap-4 rounded-2xl bg-white p-4 shadow-sm relative-card group transition hover:shadow-lg">
                <?php if (!empty($item['thumb'])) : ?>
                  <div class="relative block h-24 w-32 flex-shrink-0 overflow-hidden rounded-xl">
                    <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                  </div>
                <?php endif; ?>
                <div class="flex flex-1 flex-col">
                  <div class="uppercase text-gray-400" style="font-size: 11px; letter-spacing: 0.2em;"><?php echo esc_html($item['date']); ?></div>
                  <h4 class="mt-1 line-clamp-2 text-base font-semibold text-gray-900">
                    <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600 stretched-link"><?php echo esc_html($item['title']); ?></a>
                  </h4>
                  <p class="mt-1 line-clamp-2 text-sm text-gray-500"><?php echo esc_html($item['excerpt']); ?></p>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <p class="text-gray-500">Chưa có bài viết phù hợp để hiển thị hero.</p>
      <?php endif; ?>
    </section>

    <!-- Trending rail -->
    <section class="space-y-4">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase text-gray-400" style="letter-spacing: 0.3em;">trending now</p>
          <h2 class="text-2xl font-bold text-gray-800">Xu hướng đọc nhiều</h2>
        </div>
        <div class="flex gap-2">
          <button type="button" class="rounded-full border border-gray-300 p-2 text-gray-700 hover:bg-gray-100" data-trending-prev aria-label="Prev">
            <i class="fa-solid fa-chevron-left"></i>
          </button>
          <button type="button" class="rounded-full border border-gray-300 p-2 text-gray-700 hover:bg-gray-100" data-trending-next aria-label="Next">
            <i class="fa-solid fa-chevron-right"></i>
          </button>
        </div>
      </div>
      <?php if (!empty($trending_posts)) : ?>
        <div class="relative">
          <div class="flex gap-4 overflow-x-auto scroll-smooth pb-4 no-scrollbar" data-trending-carousel>
            <?php foreach ($trending_posts as $item) : ?>
              <article class="w-72 flex-shrink-0 rounded-2xl bg-white p-4 shadow hover:-translate-y-1 hover:shadow-lg transition relative-card group">
                <div class="flex items-center gap-2 text-xs text-amber-600">
                  <i class="fa-solid fa-fire"></i>
                  <span><?php echo esc_html($item['date']); ?></span>
                </div>
                <h3 class="mt-2 line-clamp-2 text-lg font-semibold text-gray-900">
                  <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600 stretched-link"><?php echo esc_html($item['title']); ?></a>
                </h3>
                <p class="mt-2 line-clamp-3 text-sm text-gray-500"><?php echo esc_html($item['excerpt']); ?></p>
                <div class="mt-4 flex items-center justify-between text-xs text-gray-400">
                  <span><?php echo esc_html($item['author']); ?></span>
                  <span><?php echo esc_html($item['time']); ?></span>
                </div>
              </article>
            <?php endforeach; ?>
          </div>
        </div>
      <?php else : ?>
        <p class="text-sm text-gray-500">Chưa có dữ liệu xu hướng.</p>
      <?php endif; ?>
    </section>

    <!-- Spotlight sticky posts -->
    <?php if (!empty($spotlight_posts)) : ?>
      <section class="rounded-3xl bg-gray-900 p-8 text-white">
        <div class="flex items-center justify-between">
          <h2 class="text-2xl font-bold">Spotlight</h2>
          <span class="text-xs uppercase text-white/60" style="letter-spacing: 0.4em;">sticky picks</span>
        </div>
        <div class="mt-8 grid gap-6 md:grid-cols-3">
          <?php foreach ($spotlight_posts as $item) : ?>
            <article class="space-y-3 rounded-2xl border border-white/10 bg-white/5 p-4 backdrop-blur relative-card group hover:bg-white/10 transition">
              <div class="uppercase text-white/60" style="font-size: 11px; letter-spacing: 0.25em;">Sticky</div>
              <h3 class="text-xl font-semibold leading-tight">
                <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-amber-300 stretched-link"><?php echo esc_html($item['title']); ?></a>
              </h3>
              <p class="line-clamp-3 text-sm text-white/80"><?php echo esc_html($item['excerpt']); ?></p>
              <div class="text-xs text-white/60"><?php echo esc_html($item['author']); ?> • <?php echo esc_html($item['date']); ?></div>
            </article>
          <?php endforeach; ?>
        </div>
      </section>
    <?php endif; ?>

    <!-- Category showcase -->
    <section class="space-y-8">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-xs uppercase text-gray-400" style="letter-spacing: 0.3em;">curated topics</p>
          <h2 class="text-2xl font-bold text-gray-800">Danh mục nổi bật</h2>
        </div>
      </div>
      <?php if (!empty($showcase_categories)) : ?>
        <div class="grid gap-10 lg:grid-cols-3">
          <?php foreach ($showcase_categories as $cat_item) :
            $cat_query = new WP_Query([
              'cat'                 => $cat_item->term_id,
              'posts_per_page'      => 3,
              'ignore_sticky_posts' => true,
            ]);
            $cat_posts = $cat_query->have_posts() ? miwanews_collect_posts($cat_query) : [];
          ?>
            <div class="rounded-3xl border border-gray-100 bg-white p-6 shadow-sm hover:shadow-lg transition">
              <div class="flex items-center justify-between">
                <h3 class="text-xl font-semibold text-gray-800"><?php echo esc_html($cat_item->name); ?></h3>
                <a href="<?php echo esc_url(get_category_link($cat_item->term_id)); ?>" class="text-sm font-medium text-blue-600 hover:text-blue-800">Xem tất cả</a>
              </div>
              <p class="mt-1 text-sm text-gray-500"><?php echo esc_html(wp_strip_all_tags($cat_item->description)); ?></p>
              <div class="mt-4 space-y-4">
                <?php if (!empty($cat_posts)) : ?>
                  <?php foreach ($cat_posts as $item) : ?>
                    <article class="flex gap-3 border-b border-gray-100 pb-3 last:border-0 relative-card group">
                      <?php if (!empty($item['thumb'])) : ?>
                        <div class="block h-16 w-24 flex-shrink-0 overflow-hidden rounded-xl">
                          <img src="<?php echo esc_url($item['thumb']); ?>" alt="<?php echo esc_attr($item['title']); ?>" class="h-full w-full object-cover transition duration-500 group-hover:scale-110" loading="lazy">
                        </div>
                      <?php endif; ?>
                      <div class="flex-1">
                        <div class="uppercase text-gray-400" style="font-size: 11px; letter-spacing: 0.2em;"><?php echo esc_html($item['date']); ?></div>
                        <h4 class="line-clamp-2 text-base font-semibold text-gray-900">
                          <a href="<?php echo esc_url($item['link']); ?>" class="hover:text-blue-600 stretched-link"><?php echo esc_html($item['title']); ?></a>
                        </h4>
                        <p class="line-clamp-2 text-sm text-gray-500"><?php echo esc_html($item['excerpt']); ?></p>
                      </div>
                    </article>
                  <?php endforeach; ?>
                <?php else : ?>
                  <p class="text-sm text-gray-500">Chưa có bài viết.</p>
                <?php endif; ?>
              </div>
            </div>
          <?php endforeach; ?>
        </div>
      <?php else : ?>
        <p class="text-sm text-gray-500">Không tìm thấy danh mục nào.</p>
      <?php endif; ?>
    </section>

    <!-- Newsletter CTA -->
    <section class="rounded-3xl bg-white p-10 shadow-lg">
      <div class="grid gap-8 md:grid-cols-2">
        <div>
          <p class="text-xs uppercase text-gray-400" style="letter-spacing: 0.3em;">newsletter</p>
          <h2 class="mt-2 text-3xl font-bold text-gray-900">Nhận tin nóng mỗi sáng</h2>
          <p class="mt-3 text-gray-600">Hệ thống sẽ gửi bản tin chọn lọc gồm tin tiêu điểm, phân tích chuyên sâu và các bài viết chưa kịp đọc.</p>
          <ul class="mt-4 space-y-2 text-sm text-gray-500">
            <li><i class="fa-solid fa-check text-emerald-500 mr-2"></i>Không spam, hủy đăng ký bất cứ lúc nào.</li>
            <li><i class="fa-solid fa-check text-emerald-500 mr-2"></i>Tự động phân loại theo sở thích của bạn.</li>
            <li><i class="fa-solid fa-check text-emerald-500 mr-2"></i>Độc quyền cho độc giả đăng ký.</li>
          </ul>
        </div>
        <div class="rounded-2xl border border-gray-100 bg-gray-50 p-6">
          <form class="space-y-4" action="#" method="post">
            <div>
              <label for="miwanews-newsletter-name" class="text-sm font-semibold text-gray-700">Họ tên</label>
              <input type="text" id="miwanews-newsletter-name" name="miwanews_newsletter_name" class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:outline-none" placeholder="Nguyễn Văn A">
            </div>
            <div>
              <label for="miwanews-newsletter-email" class="text-sm font-semibold text-gray-700">Email</label>
              <input type="email" id="miwanews-newsletter-email" name="miwanews_newsletter_email" class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:outline-none" placeholder="ban@email.com">
            </div>
            <div>
              <label for="miwanews-newsletter-topics" class="text-sm font-semibold text-gray-700">Chủ đề quan tâm</label>
              <select id="miwanews-newsletter-topics" name="miwanews_newsletter_topics[]" multiple class="mt-1 w-full rounded-2xl border border-gray-200 px-4 py-3 focus:border-gray-900 focus:outline-none">
                <?php foreach (get_categories(['hide_empty' => true]) as $topic_cat) : ?>
                  <option value="<?php echo esc_attr($topic_cat->slug); ?>"><?php echo esc_html($topic_cat->name); ?></option>
                <?php endforeach; ?>
              </select>
              <p class="mt-1 text-xs text-gray-400">Giữ Ctrl/Command để chọn nhiều mục.</p>
            </div>
            <button type="submit" class="w-full rounded-2xl bg-gray-900 py-3 text-center text-white font-semibold hover:bg-gray-700">Đăng ký ngay</button>
          </form>
        </div>
      </div>
    </section>
  </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function () {
  const carousel = document.querySelector('[data-trending-carousel]');
  const prevBtn = document.querySelector('[data-trending-prev]');
  const nextBtn = document.querySelector('[data-trending-next]');
  if (carousel && prevBtn && nextBtn) {
    const scrollAmount = 320;
    prevBtn.addEventListener('click', () => {
      carousel.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });
    nextBtn.addEventListener('click', () => {
      carousel.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });
  }
});
</script>

<?php
get_footer();

