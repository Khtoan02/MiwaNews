document.addEventListener('DOMContentLoaded', function() {
    // --- Mobile Menu Toggle ---
    const menuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');
    const closeBtn = document.getElementById('mobile-menu-close');
    const overlay = document.getElementById('mobile-menu-overlay');

    function openMenu() {
        if (mobileMenu) mobileMenu.classList.remove('translate-x-full');
        if (overlay) overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeMenu() {
        if (mobileMenu) mobileMenu.classList.add('translate-x-full');
        if (overlay) overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    if (menuBtn) {
        menuBtn.addEventListener('click', openMenu);
    }

    if (closeBtn) {
        closeBtn.addEventListener('click', closeMenu);
    }

    if (overlay) {
        overlay.addEventListener('click', closeMenu);
    }

    // --- Ajax Load More Logic ---
    const wrapper = document.getElementById('latest-posts');
    const listing = document.getElementById('latest-listing');
    
    // Check if we are on a page that needs load more and if ajax url is defined
    if (wrapper && listing && window.miwanews_ajax_url) {
        let isLoading = false;
        let paged = 1;
        let ended = false;
        
        // Create Sentinel for Infinite Scroll
        const sentinel = document.createElement('div');
        sentinel.id = 'load-more-sentinel';
        sentinel.className = 'py-6 text-center text-gray-500 text-sm font-medium';
        wrapper.appendChild(sentinel);

        function loadMore() {
            if (isLoading || ended) return;
            isLoading = true;
            paged++;
            sentinel.textContent = 'Đang tải thêm bài viết...';
            
            fetch(window.miwanews_ajax_url, {
                method: 'POST',
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `action=miwanews_load_more_posts&paged=${paged}`
            })
            .then(res => res.json())
            .then(data => {
                if (!data.success || !data.data.html.trim()) {
                    sentinel.textContent = 'Bạn đã xem hết bài viết.';
                    ended = true;
                } else {
                    listing.insertAdjacentHTML('beforeend', data.data.html);
                    sentinel.textContent = ''; // Clear loading text
                }
                isLoading = false;
            })
            .catch(() => {
                sentinel.textContent = 'Có lỗi xảy ra. Vui lòng tải lại trang.';
                isLoading = false;
            });
        }

        // Remove old manual button if it exists
        const btn = document.getElementById('load-more-posts');
        if (btn) btn.remove();

        // Use IntersectionObserver for performance
        if ('IntersectionObserver' in window) {
            const io = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        loadMore();
                    }
                });
            }, { rootMargin: '200px' });
            io.observe(sentinel);
        } else {
            // Fallback for very old browsers
             window.addEventListener('scroll', function() {
                if (window.innerHeight + window.scrollY >= document.body.offsetHeight - 500) {
                    loadMore();
                }
            });
        }
    }

    // --- Reading Progress Bar ---
    const progressBar = document.getElementById('reading-progress');
    if (progressBar) {
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY || document.documentElement.scrollTop;
            const scrollHeight = document.documentElement.scrollHeight - document.documentElement.clientHeight;
            const scrolled = (scrollTop / scrollHeight) * 100;
            progressBar.style.width = scrolled + "%";
        });
    }

    // --- Table of Contents (Auto Generate) ---
    const content = document.getElementById('post-content');
    const tocListMobile = document.getElementById('toc-list');
    const tocListDesktop = document.getElementById('desktop-toc-list');
    const tocContainerMobile = document.getElementById('table-of-contents');
    const tocContainerDesktop = document.getElementById('desktop-toc');

    if (content && (tocListMobile || tocListDesktop)) {
        const headings = content.querySelectorAll('h2, h3');
        
        if (headings.length > 0) {
            if (tocContainerMobile) tocContainerMobile.classList.remove('hidden');
            if (tocContainerDesktop) tocContainerDesktop.classList.remove('hidden');

            headings.forEach((heading, index) => {
                const id = 'heading-' + index;
                heading.id = id;
                const level = heading.tagName.toLowerCase();
                
                // Create list item logic
                const createListItem = (isDesktop) => {
                    const li = document.createElement('li');
                    li.className = level === 'h2' ? 'font-medium' : 'pl-4 text-gray-500';
                    const a = document.createElement('a');
                    a.href = '#' + id;
                    a.textContent = heading.textContent;
                    a.className = 'hover:text-blue-600 transition block truncate';
                    a.addEventListener('click', (e) => {
                        e.preventDefault();
                        document.getElementById(id).scrollIntoView({ behavior: 'smooth' });
                    });
                    li.appendChild(a);
                    return li;
                };

                if (tocListMobile) tocListMobile.appendChild(createListItem(false));
                if (tocListDesktop) tocListDesktop.appendChild(createListItem(true));
            });
        }
    }
    // --- Image Error Handling & Placeholder (Global) ---
    const images = document.querySelectorAll('img');
    // Placeholder: A clean SVG Data URI (Light Gray background with text)
    const placeholder = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='800' height='450' viewBox='0 0 800 450'%3E%3Crect width='800' height='450' fill='%23f3f4f6'/%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='24' fill='%239ca3af'%3EMiwaNews%3C/text%3E%3C/svg%3E";

    images.forEach(img => {
        // 1. Missing src: Set placeholder immediately
        if (!img.getAttribute('src')) {
            img.setAttribute('src', placeholder);
            img.classList.add('image-placeholder-active');
        }

        // 2. Error loading (404/broken): Swap to placeholder
        img.onerror = function() {
            this.onerror = null; // Prevent infinite loop
            this.src = placeholder;
            this.alt = "Hình ảnh đang cập nhật";
            this.classList.add('image-error-active');
            
            // Maintain layout stability if possible
            if (!this.style.width) this.style.width = '100%';
            if (!this.style.height) this.style.height = 'auto'; // Let aspect ratio take over
        };

        // 3. Loading State (Optional visual cue)
        // Add a class while loading, remove on load
        if (!img.complete) {
            img.classList.add('image-loading');
        }
        img.onload = function() {
            this.classList.remove('image-loading');
        }
    });
});

