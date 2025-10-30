<?php
// Kích hoạt hỗ trợ menu
function miwanews_setup() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'miwanews')
    ]);
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
}
add_action('after_setup_theme', 'miwanews_setup');

// Nhúng TailwindCSS và Font Awesome
function miwanews_enqueue_scripts() {
    // Tailwind qua CDN (dev nhanh, production nên build riêng)
    wp_enqueue_style('miwanews-tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@3.4.3/dist/tailwind.min.css', [], '3.4.3');
    // Font Awesome
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', [], '6.4.2');
    // Style mặc định (cho editor...)
    wp_enqueue_style('miwanews-style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'miwanews_enqueue_scripts');

// Customizer: Setting chọn category cho Section 1 (Grid Blog)
add_action('customize_register', function($wp_customize) {
    $wp_customize->add_section('section1_category', [
        'title'    => __('Blog Section 1: Chọn danh mục', 'miwanews'),
        'priority' => 25,
    ]);
    $wp_customize->add_setting('section1_category_slug', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    // Lấy danh sách category
    $categories = get_categories(['hide_empty' => false]);
    $cat_choices = ['' => '— Chọn danh mục —'];
    foreach ($categories as $cat) {
        $cat_choices[$cat->slug] = $cat->name;
    }
    $wp_customize->add_control('section1_category_slug', [
        'section'     => 'section1_category',
        'label'       => __('Chọn danh mục', 'miwanews'),
        'type'        => 'select',
        'choices'     => $cat_choices,
        'description' => __('Chọn danh mục để hiển thị ở Section 1', 'miwanews')
    ]);
});

// AJAX: Nạp thêm bài mới nhất (section 2)
add_action('wp_ajax_miwanews_load_more_posts', 'miwanews_load_more_posts');
add_action('wp_ajax_nopriv_miwanews_load_more_posts', 'miwanews_load_more_posts');
function miwanews_load_more_posts() {
    $paged = isset($_POST['paged']) ? max(1, intval($_POST['paged'])) : 1;
    $query = new WP_Query([
        'posts_per_page' => 10,
        'paged' => $paged
    ]);
    ob_start();
    if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post(); ?>
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
        <?php endwhile;
        wp_reset_postdata();
    endif;
    $html = ob_get_clean();
    wp_send_json_success([ 'html' => $html, 'found_posts' => $query->found_posts ]);
} // end ajax load more

// Truyền ajax_url cho JS để gọi AJAX đúng endpoint
add_action('wp_footer', function(){
    echo '<script>window.miwanews_ajax_url = "' . admin_url('admin-ajax.php') . '";</script>';
});
