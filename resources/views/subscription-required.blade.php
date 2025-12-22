<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Subscription Required - {{ $subdomain->name ?? 'Dental Clinic' }}</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <style>
        body {
            background: linear-gradient(135deg, #f0f9f8 0%, #ffffff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
        }
        
        .subscription-container {
            max-width: 600px;
            width: 100%;
            padding: 2rem;
        }
        
        .subscription-card {
            background: white;
            border-radius: 20px;
            box-shadow: 0 10px 40px rgba(32, 178, 170, 0.15);
            padding: 3rem;
            text-align: center;
        }
        
        .icon-container {
            width: 100px;
            height: 100px;
            margin: 0 auto 2rem;
            background: linear-gradient(135deg, #20b2aa 0%, #1a9b94 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            animation: pulse 2s ease-in-out infinite;
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .icon-container i {
            font-size: 3rem;
            color: white;
        }
        
        h1 {
            color: #1a9b94;
            font-weight: 700;
            margin-bottom: 1rem;
        }
        
        .subtitle {
            color: #6c757d;
            font-size: 1.1rem;
            margin-bottom: 2rem;
        }
        
        .reason-box {
            background: #fff3cd;
            border: 2px solid #ffc107;
            border-radius: 10px;
            padding: 1.5rem;
            margin: 2rem 0;
        }
        
        .reason-box.danger {
            background: #f8d7da;
            border-color: #dc3545;
        }
        
        .contact-info {
            background: #e0f7f5;
            border-radius: 10px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .contact-info h5 {
            color: #1a9b94;
            margin-bottom: 1rem;
        }
        
        .contact-info p {
            margin: 0.5rem 0;
            color: #495057;
        }
        
        .contact-info i {
            color: #20b2aa;
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="subscription-container">
        <div class="subscription-card">
            <div class="icon-container">
                <i class="bi bi-{{ $reason === 'subscription_expired' ? 'credit-card' : 'exclamation-triangle' }}"></i>
            </div>
            
            <h1>
                @if($reason === 'subscription_expired')
                    Subscription Expired
                @else
                    Service Temporarily Unavailable
                @endif
            </h1>
            
            <p class="subtitle">
                @if($reason === 'subscription_expired')
                    Your subscription has expired. Please renew to continue using our services.
                @else
                    This service is currently disabled. Please contact support for assistance.
                @endif
            </p>
            
            <div class="reason-box {{ $reason === 'subscription_expired' ? '' : 'danger' }}">
                <h5 class="mb-3">
                    <i class="bi bi-{{ $reason === 'subscription_expired' ? 'info-circle' : 'x-circle' }}"></i>
                    {{ $subdomain->name }}
                </h5>
                <p class="mb-0">
                    @if($reason === 'subscription_expired')
                        Your subscription period has ended. To restore access to your dental clinic management system, please renew your subscription.
                    @else
                        This subdomain has been temporarily disabled. Please contact the administrator to reactivate your service.
                    @endif
                </p>
            </div>
            
            @if($subdomain->email || $subdomain->phone)
            <div class="contact-info">
                <h5><i class="bi bi-telephone"></i> Contact Support</h5>
                @if($subdomain->email)
                <p>
                    <i class="bi bi-envelope"></i>
                    <strong>Email:</strong> 
                    <a href="mailto:{{ $subdomain->email }}" style="color: #20b2aa; text-decoration: none;">
                        {{ $subdomain->email }}
                    </a>
                </p>
                @endif
                @if($subdomain->phone)
                <p>
                    <i class="bi bi-telephone"></i>
                    <strong>Phone:</strong> 
                    <a href="tel:{{ $subdomain->phone }}" style="color: #20b2aa; text-decoration: none;">
                        {{ $subdomain->phone }}
                    </a>
                </p>
                @endif
            </div>
            @endif
            
            <div class="mt-4">
                <a href="{{ url('/admin') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-box-arrow-in-right"></i> Admin Panel
                </a>
            </div>
        </div>
    </div>
</body>
</html>

