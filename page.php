<?php
get_header();
?>

<div class="container w-full mx-auto px-4 py-8 md:py-12" style="max-width: 900px">
    
    <!-- Breadcrumbs (Simplified) -->
    <div class="mb-6 text-sm text-gray-500">
        <a href="<?php echo home_url(); ?>" class="hover:text-blue-600">Trang chủ</a> 
        <span class="mx-2">/</span>
        <span class="text-gray-900 font-medium"><?php the_title(); ?></span>
    </div>

    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class('bg-white'); ?>>
            
            <header class="mb-8 md:mb-10 text-center">
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold mb-6 text-gray-900 leading-tight">
                    <?php the_title(); ?>
                </h1>
                
                <!-- Featured Image for Page -->
                <?php if (has_post_thumbnail()) : ?>
                    <figure class="mt-8 relative rounded-2xl overflow-hidden shadow-sm">
                        <?php the_post_thumbnail('full', [
                            'class' => 'w-full h-auto object-cover',
                            'alt' => get_the_title()
                        ]); ?>
                    </figure>
                <?php endif; ?>
            </header>

            <!-- Page Content -->
            <div class="markdown-content text-base md:text-lg leading-relaxed text-gray-800">
                <?php the_content(); ?>
            </div>

        </article>
    <?php endwhile; endif; ?>

</div>

<?php
get_footer();
?>
