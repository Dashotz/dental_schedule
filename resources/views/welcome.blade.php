@extends('layouts.app')

@section('title', 'Welcome - Dental Clinic')

@push('styles')
    <link rel="stylesheet" href="{{ asset('css/welcome.css') }}">
@endpush

@section('content')

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
                <a href="#ratings" class="nav-link-custom d-none d-md-block">Reviews</a>
                <a href="#contact" class="nav-link-custom d-none d-md-block">Contact</a>
                <a href="{{ route('login') }}" class="btn btn-staff-login">
                    <i class="bi bi-person-circle me-2"></i>Staff Login
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Hero Banner Section -->
<section class="hero-banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 hero-content">
                <h1 class="hero-title">Your Smile is Our Priority</h1>
                <p class="hero-subtitle">
                    Experience exceptional dental care in a state-of-the-art facility. Our team of experienced professionals 
                    combines cutting-edge technology with compassionate care to deliver the best oral health solutions for you and your family.
                </p>
                <div class="hero-buttons">
                    <a href="#services" class="btn btn-primary-custom">
                        <i class="bi bi-calendar-check me-2"></i>Our Services
                    </a>
                    <a href="#contact" class="btn btn-outline-custom">
                        <i class="bi bi-telephone me-2"></i>Contact Us
                    </a>
                </div>
            </div>
            <div class="col-lg-5 text-center hero-content">
                <i class="bi bi-tooth" style="font-size: 15rem; color: var(--blue); opacity: 0.3; animation: float 3s ease-in-out infinite;"></i>
            </div>
        </div>
    </div>
</section>

<!-- Statistics Section -->
<section class="stats-section">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <i class="bi bi-people stat-icon"></i>
                    <div class="stat-number">10,000+</div>
                    <div class="stat-label">Happy Patients</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <i class="bi bi-calendar-check stat-icon"></i>
                    <div class="stat-number">50,000+</div>
                    <div class="stat-label">Appointments</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <i class="bi bi-award stat-icon"></i>
                    <div class="stat-number">15+</div>
                    <div class="stat-label">Years Experience</div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="stat-card">
                    <i class="bi bi-star-fill stat-icon"></i>
                    <div class="stat-number">4.9</div>
                    <div class="stat-label">Average Rating</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="services-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Our Services</h2>
            <p class="section-subtitle">Comprehensive dental care solutions for you and your family</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-tooth"></i>
                    </div>
                    <h5 class="service-title">General Dentistry</h5>
                    <p class="service-description">Regular checkups, cleanings, and preventive care to keep your smile healthy and bright.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-heart-pulse"></i>
                    </div>
                    <h5 class="service-title">Cosmetic Dentistry</h5>
                    <p class="service-description">Transform your smile with our cosmetic procedures including whitening and veneers.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <h5 class="service-title">Orthodontics</h5>
                    <p class="service-description">Straighten your teeth with our modern orthodontic solutions and braces options.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-droplet"></i>
                    </div>
                    <h5 class="service-title">Root Canal Treatment</h5>
                    <p class="service-description">Comfortable and effective root canal therapy to save your natural teeth.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-stars"></i>
                    </div>
                    <h5 class="service-title">Dental Implants</h5>
                    <p class="service-description">Restore your smile with permanent dental implants that look and feel natural.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-emoji-smile"></i>
                    </div>
                    <h5 class="service-title">Emergency Care</h5>
                    <p class="service-description">Urgent dental care when you need it most. We're here to help in emergencies.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Ratings/Reviews Section -->
<section id="ratings" class="ratings-section">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">What Our Patients Say</h2>
            <p class="section-subtitle">Real reviews from satisfied patients</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="rating-card">
                    <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="rating-text">"Excellent service and professional staff. The clinic is modern and clean, and the dentists are very knowledgeable. Highly recommend!"</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="rating-author">Sarah Johnson</span>
                        <span class="rating-date">2 weeks ago</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="rating-card">
                    <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="rating-text">"I've been coming here for years. The team is friendly, the facilities are top-notch, and I always feel comfortable during my visits."</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="rating-author">Michael Chen</span>
                        <span class="rating-date">1 month ago</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="rating-card">
                    <div class="rating-stars">
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                        <i class="bi bi-star-fill"></i>
                    </div>
                    <p class="rating-text">"Best dental experience I've ever had! The staff made me feel at ease, and the treatment was painless. Will definitely return!"</p>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="rating-author">Emily Rodriguez</span>
                        <span class="rating-date">3 weeks ago</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="services-section" style="background: var(--shiny-black);">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">Get In Touch</h2>
            <p class="section-subtitle">Ready to schedule your appointment? Contact us today!</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <h5 class="service-title">Phone</h5>
                    <p class="service-description">Call us for appointments and inquiries</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>
                    <h5 class="service-title">Email</h5>
                    <p class="service-description">Send us a message anytime</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="service-card">
                    <div class="service-icon">
                        <i class="bi bi-clock-fill"></i>
                    </div>
                    <h5 class="service-title">Hours</h5>
                    <p class="service-description">Mon-Fri: 9AM-6PM<br>Sat: 9AM-2PM</p>
                </div>
            </div>
        </div>
        <div class="row mt-5">
            <div class="col-lg-8 mx-auto">
                <div class="service-card text-center">
                    <i class="bi bi-info-circle" style="font-size: 2rem; color: var(--blue); margin-bottom: 15px;"></i>
                    <h5 class="service-title">Patient Registration</h5>
                    <p class="service-description">To book an appointment, please use the registration link provided by our clinic staff.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="main-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-6 footer-content">
                <h5 class="footer-title">
                    <i class="bi bi-tooth me-2"></i>Dental Care Clinic
                </h5>
                <p>Your trusted partner in dental health and wellness.</p>
            </div>
            <div class="col-md-6 text-md-end footer-content">
                <p class="mb-0">&copy; {{ date('Y') }} Dental Care Clinic. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
@endsection

@push('scripts')
    <script src="{{ asset('js/welcome.js') }}"></script>
@endpush
