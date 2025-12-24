<?php
get_header();
?>
<div class="container w-full mx-auto px-1 sm:px-4" style="max-width: 1200px">
    <!-- Section 1: Nội dung bài viết -->
    <!-- Reading Progress Bar -->
    <div id="reading-progress" class="fixed top-0 left-0 h-1 bg-blue-600 z-[60] w-0 transition-all duration-300"></div>

    <!-- Breadcrumbs -->
    <div class="py-4 text-xs text-gray-500 font-medium whitespace-nowrap overflow-x-auto hide-scrollbar">
        <a href="<?php echo home_url(); ?>" class="hover:text-blue-600">Trang chủ</a> 
        <span class="mx-1">/</span>
        <?php 
        $cats = get_the_category();
        if ($cats) {
            echo '<a href="' . get_category_link($cats[0]->term_id) . '" class="hover:text-blue-600">' . $cats[0]->name . '</a>';
            echo '<span class="mx-1">/</span>';
        }
        ?>
        <span class="text-gray-800"><?php the_title(); ?></span>
    </div>

    <div class="relative">
        
        <!-- Main Content -->
        <section class="w-full max-w-4xl mx-auto">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <article class="bg-white relative mb-12">
                    
                    <!-- Header Redesigned -->
                    <header class="mb-8 md:mb-12 text-center md:text-left">
                        <div class="mb-4 flex flex-wrap gap-2 justify-center md:justify-start">
                             <?php
                            $cats = get_the_category();
                            if ($cats) : 
                                foreach($cats as $cat): ?>
                                <a href="<?php echo get_category_link($cat->term_id); ?>" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-blue-100 text-blue-800 hover:bg-blue-200 transition">
                                    <?php echo $cat->name; ?>
                                </a>
                            <?php endforeach; endif; ?>
                        </div>
                        
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-6 text-gray-900 leading-tight tracking-tight">
                            <?php the_title(); ?>
                        </h1>
                        
                        <!-- Simple Meta -->
                        <div class="flex flex-wrap items-center justify-center md:justify-start gap-4 text-sm text-gray-500 py-4 border-b border-gray-100">
                             <div class="flex items-center gap-1">
                                <i class="fa-regular fa-calendar" aria-hidden="true"></i>
                                <span><?php echo get_the_date('d/m/Y'); ?></span>
                            </div>
                            <span class="hidden sm:inline text-gray-300">|</span>
                            <div class="flex items-center gap-1">
                                <i class="fa-regular fa-eye" aria-hidden="true"></i>
                                <span><?php echo number_format(get_post_views(get_the_ID())); ?> lượt xem</span>
                            </div>
                        </div>
                    </header>

                    <!-- Featured Image -->
                    <?php if (has_post_thumbnail()) : ?>
                        <figure class="mb-10 relative rounded-xl overflow-hidden">
                            <?php the_post_thumbnail('full', [
                                'class' => 'w-full h-auto object-cover shadow-sm',
                                'alt' => get_the_title()
                            ]);
                            ?>
                            <?php $caption = get_post(get_post_thumbnail_id())->post_excerpt ?? ''; if (!empty($caption)) : ?>
                                <figcaption class="mt-2 text-center text-xs text-gray-500 italic">
                                    <?php echo esc_html($caption); ?>
                                </figcaption>
                            <?php endif; ?>
                        </figure>
                    <?php endif; ?>

                    <!-- Excerpt / Intro -->
                    <?php if (has_excerpt() && get_the_excerpt()) : ?>
                        <div class="text-lg md:text-xl font-medium text-gray-600 leading-relaxed mb-8">
                            <?php the_excerpt(); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Main Article Content -->
                    <div class="markdown-content text-base md:text-lg leading-7 md:leading-8 text-gray-800" id="post-content">
                        <?php the_content(); ?>
                    </div>
                    
                    <!-- Tags Section -->
                    <?php 
                    $tags = get_the_tags();
                    if ($tags) : ?>
                    <div class="mt-8 pt-6 border-t border-gray-100">
                        <div class="flex flex-wrap gap-2">
                            <?php foreach($tags as $tag) : ?>
                                <a href="<?php echo get_tag_link($tag->term_id); ?>" class="inline-block px-3 py-1 bg-gray-100 hover:bg-gray-200 text-gray-600 hover:text-gray-900 text-sm rounded transition">
                                    #<?php echo $tag->name; ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endif; ?>

                </article>
            <?php endwhile; endif; ?>
        </section>
    </div>
        <div class="flex justify-between items-center mt-8 mb-12 max-w-4xl mx-auto text-sm font-medium">
            <div class="text-left w-1/2 pr-4"><?php previous_post_link('%link', '← %title'); ?></div>
            <div class="text-right w-1/2 pl-4"><?php next_post_link('%link', '%title →'); ?></div>
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

<?php
// Không có footer.php, chỉ cần wp_footer
get_footer();
?>
