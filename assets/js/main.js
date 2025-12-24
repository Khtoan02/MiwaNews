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
    
    // Placeholder: Animated Skeleton Shimmer (SVG) with Text
    // Hiệu ứng "Loading" (Skeleton) màu xám nhẹ + Text
    const placeholder = "data:image/svg+xml,%3Csvg width='800' height='450' xmlns='http://www.w3.org/2000/svg'%3E%3Cdefs%3E%3ClinearGradient id='a' x1='0%25' y1='0%25' x2='100%25' y2='0%25'%3E%3Cstop offset='0' stop-color='%23f3f4f6'/%3E%3Cstop offset='0.5' stop-color='%23e5e7eb'/%3E%3Cstop offset='1' stop-color='%23f3f4f6'/%3E%3C/linearGradient%3E%3C/defs%3E%3Crect width='100%25' height='100%25' fill='url(%23a)'%3E%3Canimate attributeName='x' from='-100%25' to='100%25' dur='1.5s' repeatCount='indefinite'/%3E%3C/rect%3E%3Ctext x='50%25' y='50%25' dominant-baseline='middle' text-anchor='middle' font-family='sans-serif' font-size='20' fill='%239ca3af'%3E%C4%90ang%20t%E1%BA%A3i%20%E1%BA%A3nh...%3C/text%3E%3C/svg%3E";

    // Hàm thay thế ảnh lỗi
    const replaceWithError = (img) => {
        // Nếu đã thay thế rồi thì thôi tránh loop
        if (img.getAttribute('data-replaced')) return;
        
        img.src = placeholder;
        img.removeAttribute('srcset'); // Xóa srcset để tránh trình duyệt cố tải lại ảnh 2x/3x
        img.setAttribute('data-replaced', 'true');
        img.classList.add('image-placeholder-active');
        img.alt = ""; // Alt rỗng cho placeholder trang trí
        
        // Giữ layout ổn định
        if (!img.style.width) img.style.width = '100%';
        if (!img.style.height) img.style.height = 'auto';
    };

    images.forEach(img => {
        // 1. Bắt sự kiện lỗi (nếu lỗi xảy ra sau khi script chạy)
        img.addEventListener('error', function() {
            replaceWithError(this);
        });

        // 2. Kiểm tra các ảnh ĐÃ lỗi (xảy ra trước khi script chạy) hoặc không có src
        const src = img.getAttribute('src');
        
        if (!src || src === '') {
            replaceWithError(img);
        } else if (img.complete && img.naturalWidth === 0) {
            // Ảnh đã "tải xong" nhưng width = 0 nghĩa là lỗi kết nối/404
            replaceWithError(img);
        }

        // 3. Loading state (thêm class để transition mờ dần)
        if (!img.complete && !img.hasAttribute('data-replaced')) {
            img.classList.add('image-loading');
            img.onload = function() {
                this.classList.remove('image-loading');
            }
        }
    });

    // Quan trọng: Lắng nghe cả các ảnh load bằng Ajax sau này (nếu có)
    // Sử dụng MutationObserver để tự động xử lý ảnh mới
    const observer = new MutationObserver(mutations => {
        mutations.forEach(mutation => {
            mutation.addedNodes.forEach(node => {
                if (node.nodeType === 1) { // ELEMENT_NODE
                    if (node.tagName === 'IMG') {
                        // Attach error connect for new IMG
                        node.addEventListener('error', function() { replaceWithError(this); });
                    } else {
                        // Find imgs inside new node
                        const nestedImgs = node.querySelectorAll('img');
                        nestedImgs.forEach(nImg => {
                             nImg.addEventListener('error', function() { replaceWithError(this); });
                        });
                    }
                }
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });

});

