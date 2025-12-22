// Welcome page JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');
    
    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', function() {
            mobileMenu.classList.toggle('show');
        });
        
        // Close mobile menu when clicking on a link
        const mobileMenuLinks = mobileMenu.querySelectorAll('.mobile-menu-link');
        mobileMenuLinks.forEach(link => {
            link.addEventListener('click', function() {
                mobileMenu.classList.remove('show');
            });
        });
    }
    
    // Smooth scrolling for anchor links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Skip if it's just "#"
            if (href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                const offset = 80; // Account for sticky navbar
                const targetPosition = target.offsetTop - offset;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    });

    // Add scroll effect to navbar
    const navbar = document.querySelector('.main-navbar');
    
    if (navbar) {
        window.addEventListener('scroll', function() {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 100) {
                navbar.style.boxShadow = '0 4px 20px rgba(32, 178, 170, 0.3)';
            } else {
                navbar.style.boxShadow = '0 2px 10px rgba(0, 0, 0, 0.1)';
            }
        });
    }

    // Newsletter form submission
    const newsletterForm = document.querySelector('.newsletter-form');
    if (newsletterForm) {
        newsletterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const email = this.querySelector('input[type="email"]').value;
            
            // Here you would typically send this to your backend
            alert('Thank you for subscribing! We will keep you updated.');
            this.reset();
        });
    }

    // Prevent scrolling within services sections while allowing overflow for animations
    const servicesSections = document.querySelectorAll('.services-section');
    
    servicesSections.forEach(section => {
        // Reset scroll position if section becomes scrollable
        const resetScroll = () => {
            if (section.scrollTop !== 0 || section.scrollLeft !== 0) {
                section.scrollTop = 0;
                section.scrollLeft = 0;
            }
        };
        
        // Monitor and reset scroll position
        section.addEventListener('scroll', resetScroll, { passive: true });
        
        // Prevent wheel events from causing scroll within section
        section.addEventListener('wheel', function(e) {
            if (this.scrollHeight > this.clientHeight || this.scrollWidth > this.clientWidth) {
                e.stopPropagation();
            }
        }, { passive: false });
    });
});
