<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5">
    <meta name="format-detection" content="telephone=no">
    
    <!-- PWA / Mobile Web App Capable -->
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#ffffff">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Merriweather:ital,wght@0,300;0,400;0,700;1,300;1,400&family=Fira+Code&display=swap" rel="stylesheet">
    
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- Tailwind Play CDN (Universal Compatibility Mode) -->
<script src="https://cdn.tailwindcss.com"></script>

<header class="bg-white shadow-sm sticky top-0 z-50">
    <div class="container mx-auto px-4 flex items-center justify-between" style="height: 52px;">
        <div class="flex items-center gap-4">
            <a href="<?php echo home_url('/'); ?>" class="text-xl font-bold text-blue-600">
                <?php bloginfo('name'); ?>
            </a>
        </div>
        
        <nav class="hidden md:block">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'flex gap-6 text-sm font-medium text-gray-700',
                'fallback_cb' => false
            ]);
            ?>
        </nav>

        <button id="mobile-menu-btn" class="md:hidden text-gray-600 p-2">
            <i class="fa-solid fa-bars text-xl"></i>
        </button>
    </div>
    
    <!-- Mobile Drawer Backdrop -->
    <div id="mobile-menu-overlay" class="fixed inset-0 bg-black/50 z-[60] hidden transition-opacity"></div>

    <!-- Mobile Drawer -->
    <div id="mobile-menu" class="fixed inset-y-0 right-0 w-[80%] max-w-sm bg-white shadow-2xl transform translate-x-full transition-transform duration-300 ease-in-out z-[70] flex flex-col">
        <div class="p-4 flex items-center justify-between border-b">
            <span class="font-bold text-lg text-gray-800">Menu</span>
            <button id="mobile-menu-close" class="p-2 text-gray-500 hover:text-red-500">
                <i class="fa-solid fa-xmark text-2xl"></i>
            </button>
        </div>
        <div class="flex-1 overflow-y-auto p-4">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'flex flex-col gap-4 text-base font-medium text-gray-700',
                'fallback_cb' => false
            ]);
            ?>
        </div>
    </div>
</header>
