@extends('layouts.app')

@section('title', 'Welcome - Dental Clinic')

@section('content')
<style>
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 80px 0;
        position: relative;
        overflow: hidden;
    }
    .hero-section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><circle cx="50" cy="50" r="2" fill="rgba(255,255,255,0.1)"/></svg>');
        opacity: 0.3;
    }
    .hero-content {
        position: relative;
        z-index: 1;
    }
    .service-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }
    .service-card:hover {
        transform: translateY(-10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }
    .service-icon {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        color: white;
        font-size: 2rem;
    }
    .section-title {
        color: #2c3e50;
        font-weight: 700;
        margin-bottom: 3rem;
    }
    .about-section {
        background: #f8f9fa;
        padding: 80px 0;
    }
    .contact-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 60px 0;
    }
    .contact-card {
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        padding: 30px;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .tooth-icon-large {
        font-size: 4rem;
        color: #667eea;
        animation: float 3s ease-in-out infinite;
    }
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
    }
    .welcome-nav {
        background: white;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        padding: 15px 0;
        position: sticky;
        top: 0;
        z-index: 1000;
    }
</style>

<!-- Navigation Bar -->
<nav class="welcome-nav">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-tooth text-primary fs-3 me-2"></i>
                <span class="fw-bold fs-4 text-dark">Dental Care Clinic</span>
            </div>
            <a href="{{ route('login') }}" class="btn btn-outline-primary">
                <i class="bi bi-person-circle me-2"></i>Staff Login
            </a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 hero-content">
                <h1 class="display-3 fw-bold mb-4">Your Smile is Our Priority</h1>
                <p class="lead mb-4">Experience exceptional dental care in a comfortable and modern environment. Our team of experienced professionals is dedicated to providing you with the best oral health solutions.</p>
                <div class="d-flex gap-3 flex-wrap">
                    <a href="#services" class="btn btn-light btn-lg">
                        <i class="bi bi-calendar-check me-2"></i>Our Services
                    </a>
                    <a href="#contact" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-telephone me-2"></i>Contact Us
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center hero-content">
                <i class="bi bi-tooth tooth-icon-large"></i>
            </div>
        </div>
    </div>
</section>

<!-- Services Section -->
<section id="services" class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Services</h2>
            <p class="text-muted lead">Comprehensive dental care for you and your family</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card service-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="bi bi-tooth"></i>
                        </div>
                        <h5 class="card-title fw-bold">General Dentistry</h5>
                        <p class="card-text text-muted">Regular checkups, cleanings, and preventive care to keep your smile healthy and bright.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h5 class="card-title fw-bold">Cosmetic Dentistry</h5>
                        <p class="card-text text-muted">Transform your smile with our cosmetic procedures including whitening and veneers.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="bi bi-shield-check"></i>
                        </div>
                        <h5 class="card-title fw-bold">Orthodontics</h5>
                        <p class="card-text text-muted">Straighten your teeth with our modern orthodontic solutions and braces options.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="bi bi-droplet"></i>
                        </div>
                        <h5 class="card-title fw-bold">Root Canal Treatment</h5>
                        <p class="card-text text-muted">Comfortable and effective root canal therapy to save your natural teeth.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="bi bi-stars"></i>
                        </div>
                        <h5 class="card-title fw-bold">Dental Implants</h5>
                        <p class="card-text text-muted">Restore your smile with permanent dental implants that look and feel natural.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card service-card shadow-sm">
                    <div class="card-body text-center p-4">
                        <div class="service-icon">
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <h5 class="card-title fw-bold">Emergency Care</h5>
                        <p class="card-text text-muted">Urgent dental care when you need it most. We're here to help in emergencies.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Section -->
<section class="about-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h2 class="section-title">Why Choose Us?</h2>
                <div class="mb-4">
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="service-icon" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Experienced Professionals</h5>
                            <p class="text-muted mb-0">Our team consists of highly qualified dentists with years of experience in various dental specialties.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="service-icon" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Modern Technology</h5>
                            <p class="text-muted mb-0">We use the latest dental technology and equipment to ensure the best treatment outcomes.</p>
                        </div>
                    </div>
                    <div class="d-flex mb-3">
                        <div class="flex-shrink-0">
                            <div class="service-icon" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Comfortable Environment</h5>
                            <p class="text-muted mb-0">Our clinic is designed to make you feel relaxed and comfortable during your visit.</p>
                        </div>
                    </div>
                    <div class="d-flex">
                        <div class="flex-shrink-0">
                            <div class="service-icon" style="width: 60px; height: 60px; font-size: 1.5rem;">
                                <i class="bi bi-check-circle"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5 class="fw-bold">Patient-Centered Care</h5>
                            <p class="text-muted mb-0">Your comfort and satisfaction are our top priorities. We listen to your concerns and tailor treatments to your needs.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <div class="p-5">
                    <i class="bi bi-hospital tooth-icon-large"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section id="contact" class="contact-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 mx-auto text-center">
                <h2 class="display-5 fw-bold mb-4">Get In Touch</h2>
                <p class="lead mb-5">Ready to schedule your appointment? Contact us today!</p>
                <div class="row g-4">
                    <div class="col-md-4">
                        <div class="contact-card">
                            <i class="bi bi-telephone-fill fs-1 mb-3"></i>
                            <h5>Phone</h5>
                            <p class="mb-0">Call us for appointments</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-card">
                            <i class="bi bi-envelope-fill fs-1 mb-3"></i>
                            <h5>Email</h5>
                            <p class="mb-0">Send us a message</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="contact-card">
                            <i class="bi bi-clock-fill fs-1 mb-3"></i>
                            <h5>Hours</h5>
                            <p class="mb-0">Mon-Fri: 9AM-6PM</p>
                        </div>
                    </div>
                </div>
                <div class="alert alert-light mt-5" role="alert">
                    <i class="bi bi-info-circle me-2"></i>
                    <strong>Patient Registration:</strong> To book an appointment, please use the registration link provided by our clinic staff.
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-dark text-white py-4">
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h5><i class="bi bi-tooth me-2"></i>Dental Care Clinic</h5>
                <p class="text-muted mb-0">Your trusted partner in dental health and wellness.</p>
            </div>
            <div class="col-md-6 text-md-end">
                <p class="text-muted mb-0">&copy; {{ date('Y') }} Dental Care Clinic. All rights reserved.</p>
            </div>
        </div>
    </div>
</footer>
@endsection
