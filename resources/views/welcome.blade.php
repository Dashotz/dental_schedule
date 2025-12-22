@extends('layouts.app')

@section('title', 'Welcome - Dental Care Clinic')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endpush

@section('content')

<!-- Top Bar -->
<div class="top-info-bar">
    <div class="container">
        <div class="row align-items-center justify-content-center">
            <div class="col-md-4 col-lg-4">
                <span class="top-info-item">
                    <i class="bi bi-clock"></i> Mon-Sun 9:00AM-6:00PM
                </span>
            </div>
            <div class="col-md-4 col-lg-4">
                <span class="top-info-item">
                    <i class="bi bi-telephone"></i> (123) 456-7890
                </span>
            </div>
            <div class="col-md-4 col-lg-4">
                <span class="top-info-item">
                    <i class="bi bi-envelope"></i> info@dentalcare.com
                </span>
            </div>
        </div>
    </div>
</div>

<!-- Navigation Bar -->
<nav class="main-navbar">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <a href="/" class="nav-brand">
                <i class="bi bi-tooth"></i>
                <span>Dental Care Clinic</span>
            </a>
            <div class="d-flex align-items-center">
                <a href="#services" class="nav-link-custom d-none d-md-block">Services</a>
                <a href="#about" class="nav-link-custom d-none d-md-block">About</a>
                <a href="#testimonials" class="nav-link-custom d-none d-md-block">Testimonials</a>
                <a href="#contact" class="nav-link-custom d-none d-md-block">Contact</a>
                <button class="mobile-menu-btn d-md-none" id="mobileMenuBtn">
                    <i class="bi bi-list"></i>
                </button>
                <a href="{{ route('login') }}" class="btn btn-staff-login" title="Staff Login">
                    <i class="bi bi-person-circle"></i>
                </a>
            </div>
        </div>
        <!-- Mobile Menu -->
        <div class="mobile-menu d-md-none" id="mobileMenu">
            <a href="#services" class="mobile-menu-link">Services</a>
            <a href="#about" class="mobile-menu-link">About</a>
            <a href="#testimonials" class="mobile-menu-link">Testimonials</a>
            <a href="#contact" class="mobile-menu-link">Contact</a>
        </div>
    </div>
</nav>

<!-- Hero Banner Section -->
<section class="hero-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="hero-title">Your One-Stop Shop For Dental Treatment</h1>
                <p class="hero-subtitle">
                    Advanced dental knowledge, cutting-edge technology, and world-class service make Dental Care Clinic 
                    the perfect choice for both locals and dental tourists. Experience every aspect of our professional dental care.
                </p>
                <div class="hero-buttons">
                    <a href="#services" class="btn btn-primary-hero">
                        Learn More
                    </a>
                    <a href="https://www.facebook.com/messages/t/28771958192418167" target="_blank" class="btn btn-secondary-hero">
                        Make an Appointment
                    </a>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1606811971618-4486d14f3f99?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                         alt="Modern dental clinic with professional dentist" 
                         class="hero-image"
                         loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Preview Section -->
<section class="services-preview">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="service-preview-card">
                    <div class="service-preview-image">
                        <img src="https://images.unsplash.com/photo-1609840114035-3c981b782dfe?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Cosmetic Dentistry" 
                             loading="lazy">
                    </div>
                    <div class="service-preview-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h4>Cosmetic Dentistry</h4>
                    <p>We promise you a smile makeover that goes beyond improving how your teeth and gums function.</p>
                    <a href="#services" class="service-preview-link">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-preview-card">
                    <div class="service-preview-image">
                        <img src="https://images.unsplash.com/photo-1551601651-2a8555f1a136?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="TMJ Therapy" 
                             loading="lazy">
                    </div>
                    <div class="service-preview-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h4>TMJ Therapy</h4>
                    <p>Our clinic has advanced diagnostic and treatment facilities for TMJ dysfunction.</p>
                    <a href="#services" class="service-preview-link">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-preview-card">
                    <div class="service-preview-image">
                        <img src="https://images.unsplash.com/photo-1588776814546-1ffcf47267a5?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" 
                             alt="Dental X-ray" 
                             loading="lazy">
                    </div>
                    <div class="service-preview-icon">
                        <i class="bi bi-camera"></i>
                    </div>
                    <h4>CBCT and Dental X-ray</h4>
                    <p>Dental imaging plays a crucial role in accurate diagnosis and treatment planning.</p>
                    <a href="#services" class="service-preview-link">Learn More <i class="bi bi-arrow-right"></i></a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Specialized Dental Solutions</h2>
            <p class="section-subtitle">From routine check-ups to advanced procedures, we provide a full range of dental services designed to meet your individual goals.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-clipboard-pulse"></i>
                    </div>
                    <h5 class="service-title">General Dentistry</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5 class="service-title">Cosmetic Dentistry</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="service-title">Orthodontics</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <h5 class="service-title">Root Canal Treatment</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h5 class="service-title">Dental Implants</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-emoji-smile"></i>
                    </div>
                    <h5 class="service-title">Emergency Care</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-hospital"></i>
                    </div>
                    <h5 class="service-title">Oral Surgery</h5>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-brush"></i>
                    </div>
                    <h5 class="service-title">Teeth Whitening</h5>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Why Choose Us Section -->
<section id="about" class="why-choose-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Why Choose Dental Care Clinic</h2>
            <p class="section-subtitle">Extensive dental expertise, truly state-of-the-art technology, and the best locations. We guarantee you'll experience and enjoy every aspect of our world-class service.</p>
        </div>
        <div class="row g-4 align-items-center mb-5">
            <div class="col-lg-6">
                <div class="clinic-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1631815588090-d4bfec5b1ccb?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                         alt="Dental examination and patient care" 
                         class="clinic-facility-image"
                         loading="lazy">
                </div>
            </div>
            <div class="col-lg-6">
                <div class="clinic-image-wrapper">
                    <img src="https://images.unsplash.com/photo-1629909613654-28e377c37b09?ixlib=rb-4.0.3&auto=format&fit=crop&w=1200&q=80" 
                         alt="Dental equipment and technology" 
                         class="clinic-facility-image"
                         loading="lazy">
                </div>
            </div>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-people"></i>
                    </div>
                    <h4>Skilled Dental Experts</h4>
                    <p>Our experienced team provides top-quality care backed by advanced training and a passion for healthy smiles.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-clipboard-check"></i>
                    </div>
                    <h4>Personalized Treatment Plans</h4>
                    <p>Every smile is unique. We create customized care plans to meet your individual dental needs and goals.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cpu"></i>
                    </div>
                    <h4>State-of-the-Art Technology</h4>
                    <p>We use the latest dental equipment for accurate diagnoses, safer procedures, and more comfortable visits.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-grid-3x3"></i>
                    </div>
                    <h4>Full Range of Services</h4>
                    <p>From cleanings and fillings to cosmetic makeovers and implants—we offer dental care in one place.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-cash-coin"></i>
                    </div>
                    <h4>Transparent & Affordable</h4>
                    <p>We offer honest pricing, flexible payment options, and no surprise fees—quality care within your reach.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h4>Trusted by Families</h4>
                    <p>We're proud to be the go-to dental provider for many families in our community, year after year.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials Section -->
<section id="testimonials" class="testimonials-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Happy Patients, Healthy Smiles</h2>
            <p class="section-subtitle">Feel the difference—where expert care meets patient satisfaction.</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"Together with his excellent team of doctors, they showed me the perfect combination of professionalism and compassion. They were patient, gentle and very polite."</p>
                    <div class="testimonial-author">
                        <strong>Rosana Castaneda</strong>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"Excellent service and professional staff. The clinic is modern and clean, and the dentists are very knowledgeable. Highly recommend!"</p>
                    <div class="testimonial-author">
                        <strong>Sarah Johnson</strong>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="testimonial-card">
                    <div class="testimonial-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="testimonial-text">"I've been coming here for years. The team is friendly, the facilities are top-notch, and I always feel comfortable during my visits."</p>
                    <div class="testimonial-author">
                        <strong>Michael Chen</strong>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <div class="container">
        <div class="cta-content text-center">
            <h2>Begin Your Journey to a Brighter, Healthier Smile Today!</h2>
            <a href="https://www.facebook.com/messages/t/28771958192418167" target="_blank" class="btn btn-cta">Make an Appointment</a>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Clinic Locations</h2>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="location-card">
                    <h5><i class="bi bi-geo-alt-fill"></i> Main Clinic</h5>
                    <p>123 Main Street<br>Your City, State 12345<br>United States</p>
                    <p class="location-phone"><i class="bi bi-telephone"></i> (123) 456-7890</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="location-card">
                    <h5><i class="bi bi-geo-alt-fill"></i> Branch Office</h5>
                    <p>456 Second Avenue<br>Your City, State 12345<br>United States</p>
                    <p class="location-phone"><i class="bi bi-telephone"></i> (123) 456-7891</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="location-card">
                    <h5><i class="bi bi-info-circle-fill"></i> Patient Registration</h5>
                    <p>To book an appointment, please use the registration link provided by our clinic staff.</p>
                    <p class="location-hours"><i class="bi bi-clock"></i> Mon-Fri: 9AM-6PM<br>Sat: 9AM-2PM</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Newsletter Section -->
<section class="newsletter-section">
    <div class="container">
        <div class="newsletter-content text-center">
            <h3>Stay Updated</h3>
            <p>Subscribe to our newsletter for dental care tips and special offers.</p>
            <form class="newsletter-form">
                <div class="input-group">
                    <input type="email" class="form-control" placeholder="Enter your email address" required>
                    <button class="btn btn-newsletter" type="submit">Subscribe</button>
                </div>
            </form>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <h5 class="footer-title">
                    <i class="bi bi-tooth me-2"></i>Dental Care Clinic
                </h5>
                <p>Your trusted partner in dental health and wellness. We provide comprehensive dental care solutions for you and your family.</p>
            </div>
            <div class="col-lg-2 col-md-6">
                <h6 class="footer-heading">Navigation</h6>
                <ul class="footer-links">
                    <li><a href="#services">Services</a></li>
                    <li><a href="#about">About</a></li>
                    <li><a href="#testimonials">Testimonials</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Contact</h6>
                <ul class="footer-links">
                    <li><i class="bi bi-telephone"></i> (123) 456-7890</li>
                    <li><i class="bi bi-envelope"></i> info@dentalcare.com</li>
                    <li><i class="bi bi-clock"></i> Mon-Sun 9:00AM-6:00PM</li>
                </ul>
            </div>
            <div class="col-lg-3 col-md-6">
                <h6 class="footer-heading">Social Media</h6>
                <div class="social-links">
                    <a href="#" class="social-link"><i class="bi bi-facebook"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-twitter"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-instagram"></i></a>
                    <a href="#" class="social-link"><i class="bi bi-linkedin"></i></a>
                </div>
            </div>
        </div>
        <div class="footer-bottom">
            <p class="mb-0">&copy; {{ date('Y') }} Dental Care Clinic. All rights reserved.</p>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
    <script src="{{ asset('js/welcome.js') }}"></script>
@endpush
