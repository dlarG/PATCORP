<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Poppins', sans-serif;
            background: #f5f7fa;
        }
        .navbar {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .logo {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        .logo i {
            font-size: 28px;
        }
        .logo h1 {
            font-size: 20px;
            font-weight: 600;
        }
        .user-menu {
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            margin-left: 20px;
        }
        .logout-btn {
            background: rgba(255,255,255,0.2);
            border: none;
            color: white;
            padding: 8px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-family: 'Poppins', sans-serif;
        }
        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }
        .welcome-card {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            margin-bottom: 2rem;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 2rem;
        }
        .stat-card {
            background: white;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            text-align: center;
        }
        .stat-card i {
            font-size: 40px;
            margin-bottom: 10px;
        }
        .stat-card h3 {
            font-size: 32px;
            color: #333;
            margin-bottom: 5px;
        }
        .stat-card p {
            color: #666;
        }
        .card-icon-1 { color: #667eea; }
        .card-icon-2 { color: #4CAF50; }
        .card-icon-3 { color: #f44336; }
        .card-icon-4 { color: #FF9800; }
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-top: 2rem;
        }
        .action-btn {
            background: white;
            border: 2px solid #667eea;
            color: #667eea;
            padding: 15px;
            border-radius: 5px;
            text-decoration: none;
            text-align: center;
            font-weight: 500;
            transition: all 0.3s;
        }
        .action-btn:hover {
            background: #667eea;
            color: white;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-file-alt"></i>
            <h1>File & Driver Management System</h1>
        </div>
        <div class="user-menu">
            <div class="nav-links">
                <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                <a href="#"><i class="fas fa-users"></i> Drivers</a>
                <a href="#"><i class="fas fa-folder"></i> Files</a>
                <a href="#"><i class="fas fa-dollar-sign"></i> Payments</a>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </button>
            </form>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h2>Welcome back, {{ auth()->user()->first_name }}! 👋</h2>
            <p>Here's what's happening with your management system today.</p>
        </div>

        {{-- <div class="stats-grid">
            <div class="stat-card">
                <i class="fas fa-users card-icon-1"></i>
                <h3>{{ $stats['total_drivers'] }}</h3>
                <p>Total Drivers</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-check-circle card-icon-2"></i>
                <h3>{{ $stats['active_drivers'] }}</h3>
                <p>Active Drivers</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-exclamation-triangle card-icon-3"></i>
                <h3>{{ $stats['unpaid_drivers'] }}</h3>
                <p>Unpaid Drivers</p>
            </div>
            <div class="stat-card">
                <i class="fas fa-folder card-icon-4"></i>
                <h3>{{ $stats['total_files'] }}</h3>
                <p>Total Files</p>
            </div>
        </div> --}}

        <div class="quick-actions">
            <a href="{{ route('drivers.create') }}" class="action-btn">
                <i class="fas fa-user-plus"></i> Add New Driver
            </a>
            <a href="{{ route('files.create') }}" class="action-btn">
                <i class="fas fa-upload"></i> Upload File
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-file-invoice-dollar"></i> Process Payment
            </a>
            <a href="#" class="action-btn">
                <i class="fas fa-chart-bar"></i> View Reports
            </a>
        </div>
    </div>
</body>
</html>