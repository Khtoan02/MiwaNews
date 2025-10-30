<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <header class="w-full h-[52px] flex items-center bg-white shadow-sm fixed top-0 left-0 z-50">
        <div class="container mx-auto max-w-[1200px] px-4 flex items-center justify-between h-full">
            <!-- Logo -->
            <div class="flex items-center">
                <a href="<?php echo home_url(); ?>" class="font-bold text-xl text-gray-900">MiwaNews</a>
            </div>

            <!-- Desktop Menu -->
            <nav class="hidden md:flex flex-1 justify-center">
                <?php
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'container' => false,
                    'menu_class' => 'flex space-x-8',
                    'fallback_cb' => false
                ]);
                ?>
            </nav>

            <!-- Social Icons (Desktop) -->
            <div class="hidden md:flex space-x-2 items-center">
                <!-- Facebook -->
                <a href="#" title="Facebook" class="bg-blue-50 cursor-pointer rounded-md shadow-md shadow-transparent transition-all duration-300 hover:shadow-indigo-200 flex items-center justify-center w-10 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 92 92" fill="none">
                        <rect x="0.138672" width="91.5618" height="91.5618" rx="15" fill="#EDF4FF"></rect>
                        <path d="M56.4927 48.6403L57.7973 40.3588H49.7611V34.9759C49.7611 32.7114 50.883 30.4987 54.4706 30.4987H58.1756V23.4465C56.018 23.1028 53.8378 22.9168 51.6527 22.8901C45.0385 22.8901 40.7204 26.8626 40.7204 34.0442V40.3588H33.3887V48.6403H40.7204V68.671H49.7611V48.6403H56.4927Z" fill="#337FFF"></path>
                    </svg>
                </a>
                <!-- Instagram -->
                <a href="#" title="Instagram" class="w-10 h-10 flex items-center justify-center bg-gradient-to-tr from-red-50 to-pink-50 cursor-pointer rounded-md shadow-md shadow-transparent transition-all duration-300 hover:shadow-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 51 51" fill="none">
                    <path d="M17.4456 25.7808C17.4456 21.1786 21.1776 17.4468 25.7826 17.4468C30.3875 17.4468 34.1216 21.1786 34.1216 25.7808C34.1216 30.383 30.3875 34.1148 25.7826 34.1148C21.1776 34.1148 17.4456 30.383 17.4456 25.7808Z" fill="url(#paint0_radial_ig)"/>
                    <defs><radialGradient id="paint0_radial_ig" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(7.41436 51.017) scale(65.31 65.2708)"><stop offset="0.09" stop-color="#FA8F21"/><stop offset="0.78" stop-color="#D82D7E"/></radialGradient></defs>
                    </svg>
                </a>
                <!-- Youtube -->
                <a href="#" title="Youtube" class="cursor-pointer rounded-md shadow-md shadow-transparent transition-all duration-300 hover:shadow-red-200 flex items-center justify-center w-10 h-10" style="background: #FFECE8;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 92 93" fill="none">
                    <rect x="0.138672" y="1" width="91.5618" height="91.5618" rx="15" fill="#FFECE8"></rect>
                    <path d="M71.2471 33.8708C70.6493 31.6234 68.8809 29.8504 66.6309 29.2428C62.5626 28.1523 46.2396 28.1523 46.2396 28.1523C46.2396 28.1523 29.925 28.1523 25.8484 29.2428C23.6067 29.8421 21.8383 31.615 21.2322 33.8708C20.1445 37.9495 20.1445 46.4647 20.1445 46.4647C20.1445 46.4647 20.1445 54.98 21.2322 59.0586C21.83 61.306 23.5984 63.079 25.8484 63.6866C29.925 64.7771 46.2396 64.7771 46.2396 64.7771C46.2396 64.7771 62.5626 64.7771 66.6309 63.6866C68.8726 63.0873 70.641 61.3144 71.2471 59.0586C72.3348 54.98 72.3348 46.4647 72.3348 46.4647C72.3348 46.4647 72.3348 37.9495 71.2471 33.8708Z" fill="#FF3000"></path>
                    <path d="M41.0256 54.314L54.5838 46.4647L41.0256 38.6154V54.314Z" fill="white"></path>
                  </svg>
                </a>
            </div>

            <!-- Mobile: Logo, Social, Menu Button -->
            <div class="flex md:hidden items-center space-x-2 flex-1 justify-end">
                <!-- Facebook -->
                <a href="#" title="Facebook" class="bg-blue-50 cursor-pointer rounded-md shadow-md shadow-transparent transition-all duration-300 hover:shadow-indigo-200 flex items-center justify-center w-10 h-10">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 92 92" fill="none">
                        <rect x="0.138672" width="91.5618" height="91.5618" rx="15" fill="#EDF4FF"></rect>
                        <path d="M56.4927 48.6403L57.7973 40.3588H49.7611V34.9759C49.7611 32.7114 50.883 30.4987 54.4706 30.4987H58.1756V23.4465C56.018 23.1028 53.8378 22.9168 51.6527 22.8901C45.0385 22.8901 40.7204 26.8626 40.7204 34.0442V40.3588H33.3887V48.6403H40.7204V68.671H49.7611V48.6403H56.4927Z" fill="#337FFF"></path>
                    </svg>
                </a>
                <!-- Instagram -->
                <a href="#" title="Instagram" class="w-10 h-10 flex items-center justify-center bg-gradient-to-tr from-red-50 to-pink-50 cursor-pointer rounded-md shadow-md shadow-transparent transition-all duration-300 hover:shadow-red-200">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 51 51" fill="none">
                    <path d="M17.4456 25.7808C17.4456 21.1786 21.1776 17.4468 25.7826 17.4468C30.3875 17.4468 34.1216 21.1786 34.1216 25.7808C34.1216 30.383 30.3875 34.1148 25.7826 34.1148C21.1776 34.1148 17.4456 30.383 17.4456 25.7808Z" fill="url(#paint0_radial_ig_mob)"/>
                    <defs><radialGradient id="paint0_radial_ig_mob" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(7.41436 51.017) scale(65.31 65.2708)"><stop offset="0.09" stop-color="#FA8F21"/><stop offset="0.78" stop-color="#D82D7E"/></radialGradient></defs>
                    </svg>
                </a>
                <!-- Youtube -->
                <a href="#" title="Youtube" class="cursor-pointer rounded-md shadow-md shadow-transparent transition-all duration-300 hover:shadow-red-200 flex items-center justify-center w-10 h-10" style="background: #FFECE8;">
                  <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 92 93" fill="none">
                    <rect x="0.138672" y="1" width="91.5618" height="91.5618" rx="15" fill="#FFECE8"></rect>
                    <path d="M71.2471 33.8708C70.6493 31.6234 68.8809 29.8504 66.6309 29.2428C62.5626 28.1523 46.2396 28.1523 46.2396 28.1523C46.2396 28.1523 29.925 28.1523 25.8484 29.2428C23.6067 29.8421 21.8383 31.615 21.2322 33.8708C20.1445 37.9495 20.1445 46.4647 20.1445 46.4647C20.1445 46.4647 20.1445 54.98 21.2322 59.0586C21.83 61.306 23.5984 63.079 25.8484 63.6866C29.925 64.7771 46.2396 64.7771 46.2396 64.7771C46.2396 64.7771 62.5626 64.7771 66.6309 63.6866C68.8726 63.0873 70.641 61.3144 71.2471 59.0586C72.3348 54.98 72.3348 46.4647 72.3348 46.4647C72.3348 46.4647 72.3348 37.9495 71.2471 33.8708Z" fill="#FF3000"></path>
                    <path d="M41.0256 54.314L54.5838 46.4647L41.0256 38.6154V54.314Z" fill="white"></path>
                  </svg>
                </a>
                <button id="mobile-menu-btn" class="ml-2 p-2"><svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 6h16M4 12h16M4 18h16"/></svg></button>
            </div>
        </div>
    </header>
    <!-- Mobile Menu Overlay -->
    <div id="mobile-nav-overlay" class="fixed inset-0 z-40 hidden bg-opacity-30 backdrop-blur-sm transition-all duration-300"></div>
    <nav id="mobile-nav" class="fixed top-0 right-0 w-64 h-full bg-white shadow-lg z-50 transform translate-x-full transition-transform duration-300 flex flex-col">
        <div class="flex-1 overflow-y-auto">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'container' => false,
                'menu_class' => 'flex flex-col space-y-4 p-6',
                'fallback_cb' => false
            ]);
            ?>
        </div>
        <!-- Slot quảng cáo phía dưới menu -->
        <div class="p-4 border-t border-gray-100 bg-gray-50 min-h-[80px] flex items-center justify-center">
            <span class="text-gray-400 text-sm">[ Quảng cáo / Banner / Widget ]</span>
        </div>
    </nav>
    <script>
    // Mobile Menu Toggle Script hiệu ứng mượt hơn
    addEventListener('DOMContentLoaded', function() {
        const btn = document.getElementById('mobile-menu-btn');
        const nav = document.getElementById('mobile-nav');
        const overlay = document.getElementById('mobile-nav-overlay');
        let isOpen = false;
        function openMenu() {
            nav.classList.remove('translate-x-full');
            nav.classList.add('!translate-x-0');
            overlay.classList.remove('hidden');
            isOpen = true;
        }
        function closeMenu() {
            nav.classList.add('translate-x-full');
            nav.classList.remove('!translate-x-0');
            overlay.classList.add('hidden');
            isOpen = false;
        }
        function toggleMenu() {
            isOpen ? closeMenu() : openMenu();
        }
        if (btn && nav && overlay) {
            btn.addEventListener('click', toggleMenu);
            overlay.addEventListener('click', closeMenu);
        }
        // Đóng bằng ESC
        document.addEventListener('keydown', function(e){
            if(e.key === 'Escape' && isOpen) closeMenu();
        });
    });
    </script>
    <div class="h-[52px]"></div> <!-- prevent content hidden behind fixed header -->
<?php wp_footer(); ?>
</body>
</html>
