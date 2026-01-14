<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin | Poseidonian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background: #f8f9fa;
        }

        /* NAVBAR */
        nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 60px;
            background: #ffffff;
            border-bottom: 1px solid #e5e7eb;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: 1px;
            color: #3ba0ff;
        }

        .nav-links {
            display: flex;
            gap: 40px;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-size: 14px;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .nav-links a:hover {
            color: #3ba0ff;
        }

        .logout-btn {
            background: #333;
            color: #fff;
            padding: 10px 20px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 12px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .logout-btn:hover {
            background: #1a1a1a;
        }

        /* HERO SECTION */
        .admin-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('/images/admin-hero.jpg') center/cover no-repeat;
            height: 280px;
            display: flex;
            align-items: center;
            padding: 60px;
            color: #fff;
        }

        .hero-text h1 {
            font-size: 56px;
            font-weight: 700;
            margin-bottom: 10px;
        }

        .hero-text p {
            font-size: 16px;
            font-weight: 300;
            opacity: 0.95;
        }

        /* SECTIONS */
        .admin-section {
            padding: 60px;
            background: #ffffff;
            margin-top: 40px;
        }

        .admin-section small {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            display: block;
            margin-bottom: 10px;
        }

        .admin-section h2 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }

        /* ROOM CARDS */
        .room-cards {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 24px;
        }

        .room-card {
            position: relative;
            border-radius: 12px;
            overflow: hidden;
            height: 300px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .room-card:hover {
            transform: translateY(-5px);
        }

        .room-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .room-card .overlay {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: linear-gradient(to top, rgba(0, 0, 0, 0.8), transparent);
            padding: 30px 20px 20px;
            color: #fff;
        }

        .room-card .overlay h3 {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .room-card .overlay p {
            font-size: 13px;
            margin: 3px 0;
            opacity: 0.9;
        }

        /* GUEST TABLE */
        .guest-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #f8f9fa;
        }

        .guest-table thead {
            background: #f8f9fa;
        }

        .guest-table th {
            padding: 18px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 2px solid #e5e7eb;
        }

        .guest-table td {
            padding: 20px 18px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
        }

        .guest-table tbody tr:hover {
            background: #f9f9f9;
        }

        .guest-table td strong {
            color: #333;
            font-weight: 600;
        }

        .btn-primary {
            background: #3ba0ff;
            color: #fff;
            padding: 10px 18px;
            border-radius: 6px;
            border: none;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: background 0.3s ease;
        }

        .btn-primary:hover {
            background: #2a7dd9;
        }

        /* ROOM PAGE STYLES */
        .room-page {
            padding: 40px 60px;
            background: #f8f9fa;
        }

        .breadcrumb {
            font-size: 13px;
            color: #999;
            margin-bottom: 30px;
            letter-spacing: 0.5px;
        }

        .breadcrumb strong {
            color: #333;
        }

        .room-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            background: #fff;
            padding: 50px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .room-info {
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        .room-info h2 {
            font-size: 32px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 25px;
            letter-spacing: 0.5px;
        }

        .room-info h4 {
            font-size: 16px;
            font-weight: 600;
            color: #333;
            margin-bottom: 15px;
            letter-spacing: 0.5px;
        }

        .room-info p {
            font-size: 14px;
            line-height: 1.8;
            color: #555;
            margin-bottom: 20px;
            letter-spacing: 0.3px;
        }

        .room-info .price {
            font-size: 28px;
            font-weight: 700;
            color: #1a1a1a;
            margin-bottom: 5px;
        }

        .room-info .price span {
            font-size: 14px;
            font-weight: 500;
            color: #999;
        }

        .room-info .price-note {
            font-size: 12px;
            color: #999;
            margin-bottom: 30px;
        }

        .room-info .btn-primary {
            padding: 12px 24px;
            font-size: 14px;
            width: 100%;
            text-align: center;
        }

        .room-images {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .room-images .main-img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 8px;
        }

        .room-images .thumbs {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .room-images .thumbs img {
            width: 100%;
            height: 150px;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .room-images .thumbs img:hover {
            transform: scale(1.05);
        }

        /* FOOTER */
        footer {
            background: #1a1a1a;
            color: #fff;
            padding: 20px 60px;
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            letter-spacing: 0.5px;
            margin-top: 40px;
        }

        @media (max-width: 1024px) {
            nav, .admin-section, footer {
                padding: 30px 40px;
            }

            .room-cards {
                grid-template-columns: repeat(2, 1fr);
            }

            .admin-hero {
                padding: 40px;
            }
        }

        @media (max-width: 768px) {
            nav {
                flex-direction: column;
                gap: 15px;
            }

            .nav-links {
                gap: 20px;
            }

            .room-cards {
                grid-template-columns: 1fr;
            }

            .admin-hero {
                height: 200px;
                padding: 30px;
            }

            .hero-text h1 {
                font-size: 36px;
            }

            .room-page {
                padding: 20px 30px;
            }

            .room-container {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 30px;
            }

            .room-info h2 {
                font-size: 24px;
            }

            .room-images .main-img {
                height: 250px;
            }
        }
    </style>
</head>
<body>

    {{-- NAVBAR --}}
    <nav>
        <div class="brand">POSEIDONIAN</div>
        <div class="nav-links">
            <a href="{{ route('dashboard') }}">Home</a>
            <a href="{{ route('bookings.status') }}">Bookings</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Log Out</button>
            </form>
        </div>
    </nav>

    @yield('content')

    {{-- FOOTER --}}
    <footer>
        <div>POSEIDONIAN</div>
        <div>© {{ date('Y') }} ALL RIGHTS RESERVED</div>
    </footer>

</body>
</html>
