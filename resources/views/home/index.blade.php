<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PATCORP File and Driver Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #FFD41D;
            --primary-dark: #FFA240;
            --secondary: #FFA240;
            --tertiary: #D73535;
            --dark: #2d3748;
            --light: #f8fafc;
            --gray: #718096;
            --success: #10b981;
            --shadow: 0 20px 60px -12px rgba(0, 0, 0, 0.25);
            --shadow-sm: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        body {
            font-family: 'Poppins', sans-serif;
            line-height: 1.6;
            color: var(--dark);
            background: var(--light);
            overflow-x: hidden;
        }

        /* Background with gradient overlay */
        .background-wrapper {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            background: linear-gradient(135deg, #c2c0b4 0%, #d1ceca 100%);
        }

        .background-wrapper::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/asset/owner1.jpg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            opacity: 0.1;
        }

        /* Navigation */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            padding: 1.5rem 5%;
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            display: flex;
            justify-content: space-between;
            align-items: center;
            z-index: 1000;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .navbar.scrolled {
            padding: 1rem 5%;
            background: rgba(255, 255, 255, 0.98);
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
            text-decoration: none;
        }

        .logo-img {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid var(--primary);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .logo-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            line-height: 1.2;
        }

        .logo-text span {
            font-size: 0.8rem;
            color: var(--gray);
            font-weight: 500;
        }

        .nav-links {
            display: flex;
            gap: 2.5rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: var(--dark);
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a:hover {
            color: var(--primary);
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            transition: width 0.3s ease;
        }

        .nav-links a:hover::after {
            width: 100%;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.8rem 1.8rem;
            border-radius: 8px;
            font-weight: 600;
            font-size: 0.95rem;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            border: none;
            cursor: pointer;
            letter-spacing: 0.5px;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            box-shadow: var(--shadow-sm);
        }

        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 212, 29, 0.4);
        }

        .btn-outline {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        /* Hero Section */
        .hero {
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 0 5%;
            padding-top: 80px;
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
            align-items: center;
        }

        .hero-text {
            animation: fadeInUp 1s ease-out;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255, 212, 29, 0.1);
            color: var(--primary);
            padding: 0.5rem 1.2rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(255, 212, 29, 0.2);
        }

        .hero-text h1 {
            font-size: 3.5rem;
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--dark), var(--primary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .hero-text p {
            font-size: 1.2rem;
            color: var(--gray);
            margin-bottom: 2.5rem;
            line-height: 1.8;
        }

        .hero-image {
            position: relative;
            animation: float 6s ease-in-out infinite;
        }

        .image-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: var(--shadow);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255,255,255,0.2);
        }

        .image-card img {
            width: 100%;
            border-radius: 12px;
            box-shadow: var(--shadow-sm);
        }

        .image-caption {
            text-align: center;
            margin-top: 1rem;
            color: var(--gray);
            font-size: 0.9rem;
        }

        .image-caption-text {
            font-weight: 600;
            color: var(--tertiary);
        }

        /* How It Works Section */
        .how-it-works {
            padding: 8rem 5%;
            background: #f8fafc;
            position: relative;
            overflow: hidden;
        }

        .how-it-works::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(255, 212, 29, 0.05), rgba(255, 162, 64, 0.05));
            z-index: 0;
        }

        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 5rem;
            position: relative;
            z-index: 1;
        }

        .section-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 0.5rem 1.5rem;
            border-radius: 50px;
            font-size: 0.85rem;
            font-weight: 600;
            margin-bottom: 1rem;
            box-shadow: var(--shadow-sm);
        }

        .section-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            color: var(--primary);
        }

        .section-subtitle {
            font-size: 1.1rem;
            color: var(--gray);
            line-height: 1.8;
        }

        .steps-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }

        .steps-timeline {
            display: flex;
            justify-content: space-between;
            position: relative;
            margin-top: 4rem;
        }

        .steps-timeline::before {
            content: '';
            position: absolute;
            top: 50px;
            left: 10%;
            right: 10%;
            height: 4px;
            background: linear-gradient(90deg, var(--primary), var(--secondary));
            border-radius: 2px;
            z-index: 0;
        }

        .step {
            text-align: center;
            position: relative;
            width: 20%;
            z-index: 1;
        }

        .step-number {
            width: 100px;
            height: 100px;
            background: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--primary);
            box-shadow: var(--shadow);
            border: 4px solid var(--primary);
            position: relative;
            transition: all 0.3s ease;
        }

        .step:hover .step-number {
            transform: scale(1.1);
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border-color: var(--secondary);
        }

        .step-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            margin: 0 auto 1.5rem;
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
        }

        .step:hover .step-icon {
            transform: rotate(15deg);
            box-shadow: 0 15px 30px rgba(255, 212, 29, 0.3);
        }

        .step-content h3 {
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 1rem;
            color: var(--dark);
        }

        .step-content p {
            color: var(--gray);
            line-height: 1.7;
            font-size: 0.95rem;
        }

        /* Features Section */
        .features {
            padding: 8rem 5%;
            background: white;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            background: var(--light);
            padding: 2.5rem;
            border-radius: 15px;
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
            position: relative;
            overflow: hidden;
        }

        .feature-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 5px;
            height: 100%;
            background: linear-gradient(to bottom, var(--primary), var(--secondary));
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: var(--shadow);
            border-color: var(--primary);
        }

        .feature-icon {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            margin-left: 38%;
            font-size: 28px;
            margin-bottom: 1.5rem;
            box-shadow: var(--shadow-sm);
        }

        .feature-card h3 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
            text-align: center;
            color: var(--dark);
        }

        .feature-card p {
            color: var(--gray);
            font-size: 0.95rem;
            line-height: 1.7;
        }

        /* Contact Section */
        .contact {
            padding: 8rem 5%;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            color: white;
            position: relative;
            overflow: hidden;
        }

        .contact::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/asset/owner1.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
        }

        .contact-container {
            max-width: 1200px;
            margin: 0 auto;
            position: relative;
            z-index: 1;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 4rem;
        }

        .contact-info {
            padding-right: 2rem;
        }

        .contact-info h2 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .contact-info p {
            font-size: 1.1rem;
            color: #cbd5e1;
            line-height: 1.8;
            margin-bottom: 2rem;
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            margin-top: 3rem;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: all 0.3s ease;
            padding: 1rem;
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.05);
        }

        .contact-item:hover {
            background: rgba(255, 255, 255, 0.1);
            transform: translateX(10px);
        }

        .contact-icon {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 20px;
        }

        .contact-text h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.3rem;
            color: white;
        }

        .contact-text p {
            margin: 0;
            color: #94a3b8;
            font-size: 0.95rem;
        }

        .contact-form-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            padding: 3rem;
            box-shadow: var(--shadow);
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: var(--dark);
            font-weight: 600;
            font-size: 0.95rem;
        }

        .form-group input,
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 1rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: all 0.3s ease;
            background: white;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(255, 212, 29, 0.1);
        }

        .form-group textarea {
            height: 150px;
            resize: vertical;
        }

        .btn-submit {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            border: none;
            padding: 1rem 2.5rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
        }

        .btn-submit:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255, 212, 29, 0.3);
        }

        /* CTA Section */
        .cta {
            padding: 8rem 5%;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .cta::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: url('/asset/owner1.jpg');
            background-size: cover;
            background-position: center;
            opacity: 0.1;
        }

        .cta-content {
            position: relative;
            z-index: 1;
            max-width: 700px;
            margin: 0 auto;
        }

        .cta h2 {
            font-size: 2.8rem;
            font-weight: 700;
            margin-bottom: 1.5rem;
        }

        .cta p {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 3rem;
            line-height: 1.8;
        }

        .cta-buttons {
            display: flex;
            gap: 1.5rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-white {
            background: white;
            color: var(--primary);
            box-shadow: var(--shadow-sm);
        }

        .btn-white:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 35px rgba(255,255,255,0.2);
        }

        .btn-transparent {
            background: transparent;
            color: white;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .btn-transparent:hover {
            background: rgba(255,255,255,0.1);
            transform: translateY(-3px);
        }

        /* Footer */
        .footer {
            background: var(--dark);
            color: white;
            padding: 5rem 5% 2rem;
        }

        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 4rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 1.5rem;
        }

        .logo-footer-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--primary);
        }

        .footer-logo h3 {
            font-size: 1.3rem;
            font-weight: 700;
            color: white;
        }

        .footer-text {
            color: #a0aec0;
            font-size: 0.95rem;
            line-height: 1.7;
        }

        .footer-links h4 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: white;
        }

        .footer-links ul {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.8rem;
        }

        .footer-links a {
            color: #a0aec0;
            text-decoration: none;
            transition: color 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .footer-links a:hover {
            color: white;
        }

        .footer-links i {
            width: 20px;
        }

        .footer-bottom {
            text-align: center;
            padding-top: 3rem;
            margin-top: 3rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: #a0aec0;
            font-size: 0.9rem;
        }

        /* Animations */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .mobile-menu-btn {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--dark);
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .hero-content {
                grid-template-columns: 1fr;
                text-align: center;
                gap: 3rem;
            }

            .hero-text h1 {
                font-size: 2.8rem;
            }

            .steps-timeline {
                flex-direction: column;
                gap: 3rem;
            }

            .steps-timeline::before {
                display: none;
            }

            .step {
                width: 100%;
            }

            .contact-container {
                grid-template-columns: 1fr;
                gap: 3rem;
            }

            .contact-info {
                padding-right: 0;
            }

            .features-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .navbar {
                padding: 0.8rem 5%; /* Reduced padding for mobile */
            }

            .navbar.scrolled {
                padding: 0.6rem 5%; /* Ensure scrolled state also has reduced padding */
            }

            .nav-buttons {
                display: none; /* Use display: none instead of visibility: hidden */
            }

            .nav-links {
                display: none;
            }

            .hero {
                padding-top: 100px;
            }

            .hero-text h1 {
                font-size: 2.2rem;
            }

            .section-title {
                font-size: 2rem;
            }

            .cta h2 {
                font-size: 2.2rem;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 300px;
            }

            .step-number {
                width: 80px;
                height: 80px;
                font-size: 2rem;
            }

            .contact-form-container {
                padding: 2rem;
            }

            /* Mobile Menu - Consolidated into the same media query */
            .mobile-menu-btn {
                display: block;
            }

            .mobile-menu {
                position: fixed;
                top: 80px;
                left: 0;
                width: 100%;
                background: white;
                padding: 2rem;
                box-shadow: var(--shadow);
                transform: translateY(-100%);
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 999;
            }

            .mobile-menu.active {
                transform: translateY(0);
                opacity: 1;
            }

            .mobile-menu .nav-links {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                padding: 0.7rem 5%; /* Even smaller padding for very small screens */
            }
            
            .navbar.scrolled {
                padding: 0.5rem 5%;
            }

            .hero-text h1 {
                font-size: 1.8rem;
            }

            .section-title {
                font-size: 1.8rem;
            }

            .feature-card {
                padding: 2rem;
            }

            .footer-content {
                grid-template-columns: 1fr;
            }

            .contact-info h2 {
                font-size: 2rem;
            }
            
            /* Adjust mobile menu position for smaller navbar */
            .mobile-menu {
                top: 80px; /* Adjusted to match smaller navbar height */
            }
        }
    </style>
</head>
<body>
    <div class="background-wrapper"></div>

    <nav class="navbar" id="navbar">
        <a href="{{ route('home') }}" class="logo">
            <img src="/asset/logo.jpg" alt="PATCORP Logo" class="logo-img">
            <div class="logo-text">
                <h1>PATCORP</h1>
                <span>Streamline Your Operations</span>
            </div>
        </a>

        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#contact">Contact</a>
        </div>

        <div class="nav-buttons">
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            @endauth
        </div>

        <button class="mobile-menu-btn" id="mobileMenuBtn">
            <i class="fas fa-bars"></i>
        </button>
    </nav>

    <div class="mobile-menu" id="mobileMenu">
        <div class="nav-links">
            <a href="#features">Features</a>
            <a href="#how-it-works">How It Works</a>
            <a href="#contact">Contact</a>
            @auth
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" class="btn btn-outline">
                    <i class="fas fa-sign-in-alt"></i> Login
                </a>
                <a href="{{ route('register') }}" class="btn btn-primary">
                    <i class="fas fa-user-plus"></i> Register
                </a>
            @endauth
        </div>
    </div>

    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <span class="hero-badge">
                    <i class="fas fa-star"></i> Trusted by 500+ Companies
                </span>
                <h1>Streamline Your File & Driver Management in One Platform</h1>
                <p>An all-in-one solution designed to simplify file organization and driver management. Upload, organize, and track files while efficiently managing driver information and payments.</p>
                
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn btn-primary" style="padding: 1rem 2.5rem; font-size: 1.1rem;">
                        <i class="fas fa-rocket"></i> Get Started Free
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <div class="image-card">
                    <img src="/asset/owner2.jpg" alt="Dashboard Preview">
                    <div class="image-caption">
                        <span class="image-caption-text"><i class="fas fa-crown"></i>&nbsp;&nbsp; Dr. Benjie C. Belarmino, DHuM - President / CEO</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
    <section class="how-it-works" id="how-it-works">
        <div class="section-header">
            <span class="section-badge">SIMPLE PROCESS</span>
            <h2 class="section-title">How It Works</h2>
            <p class="section-subtitle">Get started in just a few simple steps. Our platform is designed to be intuitive and easy to use.</p>
        </div>

        <div class="steps-container">
            <div class="steps-timeline">
                <div class="step">
                    <div class="step-number">01</div>
                    <div class="step-icon">
                        <i class="fas fa-user-plus"></i>
                    </div>
                    <div class="step-content">
                        <h3>Create Account</h3>
                        <p>Sign up for a free account. Choose between admin or driver role based on your needs.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">02</div>
                    <div class="step-icon">
                        <i class="fas fa-cloud-upload-alt"></i>
                    </div>
                    <div class="step-content">
                        <h3>Upload Files</h3>
                        <p>Easily upload and organize your files with our drag-and-drop interface.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">03</div>
                    <div class="step-icon">
                        <i class="fas fa-users-cog"></i>
                    </div>
                    <div class="step-content">
                        <h3>Manage Drivers</h3>
                        <p>Add driver information, track payments, and monitor driver status in real-time.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">04</div>
                    <div class="step-icon">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="step-content">
                        <h3>Analyze & Optimize</h3>
                        <p>Use our analytics dashboard to gain insights and optimize your operations.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="features">
        <div class="section-header">
            <span class="section-badge">POWERFUL FEATURES</span>
            <h2 class="section-title">Everything You Need in One Place</h2>
            <p class="section-subtitle">Our platform combines file management and driver tracking into a seamless experience designed for efficiency and productivity.</p>
        </div>

        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-upload"></i>
                </div>
                <h3>Smart File Management</h3>
                <p>Upload, organize, and manage files with advanced categorization, search, and access controls. Support for all file types with secure cloud storage.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-users-cog"></i>
                </div>
                <h3>Driver Management</h3>
                <p>Track driver information, licenses, vehicle details, and employment status in real-time. Keep all driver data organized and easily accessible.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-file-invoice-dollar"></i>
                </div>
                <h3>Payment Tracking</h3>
                <p>Monitor paid and unpaid drivers with detailed payment records. Generate payment reports and track salary disbursements efficiently.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-shield-alt"></i>
                </div>
                <h3>Secure & Compliant</h3>
                <p>Enterprise-grade security with role-based access controls, audit logs, and data encryption. GDPR compliant with regular security audits.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3>Advanced Analytics</h3>
                <p>Gain insights with comprehensive reports and analytics. Track performance metrics, payment trends, and operational efficiency.</p>
            </div>

            <div class="feature-card">
                <div class="feature-icon">
                    <i class="fas fa-mobile-alt"></i>
                </div>
                <h3>Mobile Responsive</h3>
                <p>Access your dashboard from any device. Fully responsive design that works perfectly on desktop, tablet, and mobile devices.</p>
            </div>
        </div>
    </section>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <div class="section-header">
            <span class="section-badge">GET IN TOUCH</span>
            <h2 class="section-title">Contact Us</h2>
            <p class="section-subtitle">Have questions? We're here to help. Reach out to us anytime.</p>
        </div>

        <div class="contact-container">
            <div class="contact-info">
                <h2>Let's Start a Conversation</h2>
                <p>Whether you're interested in our platform, have technical questions, or need support, our team is ready to assist you. We're committed to providing excellent service.</p>
                
                <div class="contact-details">
                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Our Location</h4>
                            <p>123 Business District, Cebu City 6000</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Phone Number</h4>
                            <p>+63 (32) 123 4567</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Email Address</h4>
                            <p>support@patcorp.com</p>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="contact-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="contact-text">
                            <h4>Business Hours</h4>
                            <p>Mon - Fri: 8:00 AM - 6:00 PM</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="contact-form-container">
                <form id="contactForm">
                    <div class="form-group">
                        <label for="name"><i class="fas fa-user"></i> Full Name</label>
                        <input type="text" id="name" name="name" placeholder="Enter your full name" required>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
                        <input type="email" id="email" name="email" placeholder="Enter your email" required>
                    </div>

                    <div class="form-group">
                        <label for="subject"><i class="fas fa-tag"></i> Subject</label>
                        <select id="subject" name="subject" required>
                            <option value="">Select a subject</option>
                            <option value="general">General Inquiry</option>
                            <option value="support">Technical Support</option>
                            <option value="sales">Sales Questions</option>
                            <option value="feedback">Feedback & Suggestions</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="message"><i class="fas fa-comment"></i> Message</label>
                        <textarea id="message" name="message" placeholder="Type your message here..." required></textarea>
                    </div>

                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Send Message
                    </button>
                </form>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-content">
            <h2>Ready to Transform Your Management Process?</h2>
            <p>Join thousands of businesses that trust our platform to streamline their file and driver management. Start your free trial today and experience the difference.</p>
            
            <div class="cta-buttons">
                <a href="{{ route('register') }}" class="btn btn-white">
                    <i class="fas fa-rocket"></i> Start Free Trial
                </a>
                <a href="#contact" class="btn btn-transparent">
                    <i class="fas fa-calendar"></i> Schedule a Demo
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-info">
                <div class="footer-logo">
                    <img src="/asset/logo.jpg" alt="PATCORP Logo" class="logo-footer-img">
                    <h3>PATCORP</h3>
                </div>
                <p class="footer-text">
                    A comprehensive solution for file storage and driver management. Streamline your operations with our powerful, user-friendly platform.
                </p>
            </div>

            <div class="footer-links">
                <h4>Quick Links</h4>
                <ul>
                    <li><a href="#features"><i class="fas fa-chevron-right"></i> Features</a></li>
                    <li><a href="#how-it-works"><i class="fas fa-chevron-right"></i> How It Works</a></li>
                    <li><a href="#contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                    <li><a href="{{ route('login') }}"><i class="fas fa-chevron-right"></i> Login</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Resources</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Documentation</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Tutorials</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Blog</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> FAQ</a></li>
                </ul>
            </div>

            <div class="footer-links">
                <h4>Legal</h4>
                <ul>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Privacy Policy</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Terms of Service</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> Cookie Policy</a></li>
                    <li><a href="#"><i class="fas fa-chevron-right"></i> GDPR Compliance</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2025 PATCORP. All rights reserved. | Made with <i class="fas fa-heart" style="color: var(--tertiary);"></i> for efficient businesses</p>
        </div>
    </footer>

    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.getElementById('navbar');
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });

        // Mobile menu toggle
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
                const icon = this.querySelector('i');
                if (mobileMenu.classList.contains('active')) {
                    icon.classList.remove('fa-bars');
                    icon.classList.add('fa-times');
                } else {
                    icon.classList.remove('fa-times');
                    icon.classList.add('fa-bars');
                }
            });

            // Close mobile menu when clicking links
            const mobileLinks = mobileMenu.querySelectorAll('a');
            mobileLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    mobileMenuBtn.querySelector('i').classList.remove('fa-times');
                    mobileMenuBtn.querySelector('i').classList.add('fa-bars');
                });
            });
        }

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                
                const targetId = this.getAttribute('href');
                if (targetId === '#') return;
                
                const targetElement = document.querySelector(targetId);
                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop - 80,
                        behavior: 'smooth'
                    });
                }
            });
        });

        // Animate elements on scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.feature-card').forEach(card => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            card.style.transition = 'all 0.6s ease';
            observer.observe(card);
        });

        // Observe steps
        document.querySelectorAll('.step').forEach(step => {
            step.style.opacity = '0';
            step.style.transform = 'translateY(30px)';
            step.style.transition = 'all 0.6s ease';
            observer.observe(step);
        });

        // Contact form submission
        document.getElementById('contactForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = {
                name: document.getElementById('name').value,
                email: document.getElementById('email').value,
                subject: document.getElementById('subject').value,
                message: document.getElementById('message').value
            };
            
            // Show success message
            alert('Thank you for your message! We will get back to you soon.');
            
            // Reset form
            this.reset();
            
            // In a real application, you would send this data to your server
            console.log('Contact form submitted:', formData);
        });

        // Animate steps with delays
        document.querySelectorAll('.step').forEach((step, index) => {
            step.style.transitionDelay = `${index * 0.2}s`;
        });
    </script>
</body>
</html>