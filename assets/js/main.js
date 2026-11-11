/**
 * NOVA Creative Digital Agency
 * Main Interactive Script
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Initialize Lucide Icons
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }

    // 2. Mobile Navigation Toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            const isHidden = mobileMenu.classList.contains('hidden');
            if (isHidden) {
                mobileMenu.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            } else {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            }
        });

        // Close mobile menu on clicking any link
        const mobileLinks = mobileMenu.querySelectorAll('a');
        mobileLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                document.body.style.overflow = '';
            });
        });
    }

    // 3. Header background blur on scroll
    const header = document.getElementById('site-header');
    if (header) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > 40) {
                header.classList.add('bg-black/90', 'backdrop-blur-md', 'border-b', 'border-[#262626]');
                header.classList.remove('bg-transparent');
            } else {
                header.classList.remove('bg-black/90', 'backdrop-blur-md', 'border-b', 'border-[#262626]');
                header.classList.add('bg-transparent');
            }
        });
    }

    // 4. Portfolio Filter Tabs (Work page)
    const filterButtons = document.querySelectorAll('.portfolio-filter-btn');
    const portfolioCards = document.querySelectorAll('.portfolio-item');

    if (filterButtons.length > 0 && portfolioCards.length > 0) {
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                // Update active button state
                filterButtons.forEach(b => {
                    b.classList.remove('bg-accent', 'text-black', 'border-accent');
                    b.classList.add('bg-transparent', 'text-offwhite/70', 'border-[#333333]');
                });
                btn.classList.add('bg-accent', 'text-black', 'border-accent');
                btn.classList.remove('bg-transparent', 'text-offwhite/70', 'border-[#333333]');

                const selectedCategory = btn.getAttribute('data-category');

                portfolioCards.forEach(card => {
                    const cardCat = card.getAttribute('data-category');
                    if (selectedCategory === 'all' || cardCat === selectedCategory) {
                        card.style.display = 'block';
                        setTimeout(() => {
                            card.style.opacity = '1';
                            card.style.transform = 'translateY(0)';
                        }, 50);
                    } else {
                        card.style.opacity = '0';
                        card.style.transform = 'translateY(20px)';
                        setTimeout(() => {
                            card.style.display = 'none';
                        }, 300);
                    }
                });
            });
        });
    }

    // 5. Dismissible notifications / alerts
    const alerts = document.querySelectorAll('.flash-alert');
    alerts.forEach(alert => {
        const closeBtn = alert.querySelector('.alert-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }
    });
});

