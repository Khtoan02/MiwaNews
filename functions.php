<?php
// Kích hoạt hỗ trợ menu
function miwanews_setup() {
    register_nav_menus([
        'primary' => __('Primary Menu', 'miwanews')
    ]);
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array('search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script'));
}
add_action('after_setup_theme', 'miwanews_setup');

// Nhúng TailwindCSS và Font Awesome
function miwanews_enqueue_scripts() {
    // Tailwind qua CDN (dev nhanh, production nên build riêng)
    // wp_enqueue_style('miwanews-tailwind', 'https://cdn.jsdelivr.net/npm/tailwindcss@3.4.3/dist/tailwind.min.css', [], '3.4.3');
    // Font Awesome
    wp_enqueue_style('fontawesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css', [], '6.4.2');
    // Style mặc định (cho editor...)
    wp_enqueue_style('miwanews-style', get_stylesheet_uri());
    
    // Main JS (Mobile Menu + Ajax)
    wp_enqueue_script('miwanews-main', get_template_directory_uri() . '/assets/js/main.js', [], '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'miwanews_enqueue_scripts');

/**
 * SEO & Open Graph Meta Tags
 */
function miwanews_seo_meta_tags() {
    if (is_admin()) return;

    global $post;
    $title = wp_get_document_title();
    $desc  = get_bloginfo('description');
    $url   = home_url();
    $image = get_template_directory_uri() . '/screenshot.png'; // Fallback image

    if (is_single() || is_page()) {
        $desc = get_the_excerpt();
        if (!$desc) $desc = wp_trim_words($post->post_content, 25);
        $url = get_permalink();
        if (has_post_thumbnail()) {
            $image = get_the_post_thumbnail_url($post->ID, 'large');
        }
    } elseif (is_category()) {
        $desc = category_description();
        $url = get_category_link(get_query_var('cat'));
    }
    
    $desc = strip_tags($desc);
    $desc = str_replace('"', "'", $desc);

    ?>
    <!-- Open Graph SEO by MiwaNews -->
    <meta property="og:title" content="<?php echo esc_attr($title); ?>" />
    <meta property="og:description" content="<?php echo esc_attr($desc); ?>" />
    <meta property="og:type" content="<?php echo (is_single() ? 'article' : 'website'); ?>" />
    <meta property="og:url" content="<?php echo esc_url($url); ?>" />
    <meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
    <meta property="og:image" content="<?php echo esc_url($image); ?>" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="<?php echo esc_attr($title); ?>" />
    <meta name="twitter:description" content="<?php echo esc_attr($desc); ?>" />
    <meta name="twitter:image" content="<?php echo esc_url($image); ?>" />
    <!-- End Open Graph SEO -->
    <?php
}
add_action('wp_head', 'miwanews_seo_meta_tags', 1);

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

function miwanews_sanitize_csv_slugs($value) {
    $parts = array_filter(array_map('sanitize_title', array_map('trim', explode(',', $value))));
    return implode(', ', $parts);
}

add_action('customize_register', function($wp_customize) {
    $wp_customize->add_section('miwanews_homepage_layout', [
        'title'       => __('MiwaNews Homepage', 'miwanews'),
        'priority'    => 26,
        'description' => __('Tuỳ chỉnh các khối hiển thị cho template MiwaNews Home.', 'miwanews'),
    ]);

    $categories = get_categories(['hide_empty' => false]);
    $cat_choices = ['' => '— Mặc định (bài mới nhất) —'];
    foreach ($categories as $cat) {
        $cat_choices[$cat->slug] = $cat->name;
    }

    $wp_customize->add_setting('miwanews_featured_category_slug', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('miwanews_featured_category_slug', [
        'section' => 'miwanews_homepage_layout',
        'label'   => __('Danh mục cho Hero', 'miwanews'),
        'type'    => 'select',
        'choices' => $cat_choices,
    ]);

    $wp_customize->add_setting('miwanews_trending_tag_slug', [
        'default'           => '',
        'sanitize_callback' => 'sanitize_text_field',
    ]);
    $wp_customize->add_control('miwanews_trending_tag_slug', [
        'section'     => 'miwanews_homepage_layout',
        'label'       => __('Tag xu hướng', 'miwanews'),
        'type'        => 'text',
        'description' => __('Nhập slug tag để lọc phần Xu hướng. Để trống sẽ lấy bài nhiều bình luận nhất.', 'miwanews'),
    ]);

    $wp_customize->add_setting('miwanews_showcase_categories', [
        'default'           => '',
        'sanitize_callback' => 'miwanews_sanitize_csv_slugs',
    ]);
    $wp_customize->add_control('miwanews_showcase_categories', [
        'section'     => 'miwanews_homepage_layout',
        'label'       => __('Danh sách danh mục nổi bật', 'miwanews'),
        'type'        => 'text',
        'description' => __('Nhập slug danh mục, phân tách bởi dấu phẩy (ví dụ: tin-nong, kinh-doanh, cong-nghe). Để trống sẽ tự chọn các danh mục nhiều bài nhất.', 'miwanews'),
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

add_action('after_setup_theme', function() {
    add_image_size('miwanews-hero', 1140, 360, true); // 19:6, crop cứng (giữ lại nếu còn dùng nơi khác)
    add_image_size('miwanews-hero-169', 1280, 720, true); // 16:9, crop cứng
});

// Function to handle post views
function get_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0; // Initialize with 0 for new posts
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    }
    // Fake base view logic: 3425 + actual views
    // If you want to permanently save the fake view to DB, uncomment the next line, but usually dynamic calculation is better to avoid DB clutter
    // return $count + 3425; 
    
    // Simplest way: Return actual count + fake base
    return intval($count) + 3425; 
}

function set_post_views($postID) {
    $count_key = 'post_views_count';
    $count = get_post_meta($postID, $count_key, true);
    if ($count == '') {
        $count = 0;
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
    } else {
        $count++;
        update_post_meta($postID, $count_key, $count);
    }
}
// Track views on single post load
add_action('wp_head', function() {
    if (is_single()) {
        set_post_views(get_the_ID());
    }
});
