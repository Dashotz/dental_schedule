// Welcome page JavaScript
// Smooth scrolling for anchor links
document.addEventListener('DOMContentLoaded', function() {
    // Smooth scroll for navigation links
    const navLinks = document.querySelectorAll('a[href^="#"]');
    
    navLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Skip if it's just "#"
            if (href === '#') return;
            
            const target = document.querySelector(href);
            
            if (target) {
                e.preventDefault();
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });

    // Add scroll effect to navbar
    const navbar = document.querySelector('.main-navbar');
    let lastScroll = 0;

    window.addEventListener('scroll', function() {
        const currentScroll = window.pageYOffset;
        
        if (currentScroll > 100) {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 102, 255, 0.5)';
        } else {
            navbar.style.boxShadow = '0 4px 20px rgba(0, 102, 255, 0.3)';
        }
        
        lastScroll = currentScroll;
    });

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

