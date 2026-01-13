<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reception | Poseidonian</title>
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
        .reception-hero {
            background: linear-gradient(rgba(0, 0, 0, 0.3), rgba(0, 0, 0, 0.3)), url('/images/reception-hero.jpg') center/cover no-repeat;
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

        /* SEARCH */
        .reception-search {
            padding: 20px 60px;
            display: flex;
            gap: 10px;
            background: #ffffff;
            margin-top: -40px;
            position: relative;
            z-index: 10;
        }

        .reception-search input {
            flex: 1;
            padding: 12px 20px;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            font-size: 14px;
            transition: border 0.3s ease;
        }

        .reception-search input:focus {
            outline: none;
            border-color: #3ba0ff;
        }

        .reception-search button {
            background: #3ba0ff;
            color: #fff;
            border: none;
            padding: 12px 20px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 18px;
            transition: background 0.3s ease;
        }

        .reception-search button:hover {
            background: #2a7dd9;
        }

        /* SECTIONS */
        .reception-section {
            padding: 60px;
            background: #ffffff;
            margin-top: 40px;
        }

        .reception-section small {
            font-size: 12px;
            color: #999;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
            display: block;
            margin-bottom: 10px;
        }

        .reception-section h2 {
            font-size: 24px;
            font-weight: 700;
            color: #333;
            margin-bottom: 30px;
        }

        /* TABLE */
        .reception-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
            background: #f8f9fa;
        }

        .reception-table thead {
            background: #f8f9fa;
        }

        .reception-table th {
            padding: 18px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            color: #666;
            border-bottom: 2px solid #e5e7eb;
        }

        .reception-table td {
            padding: 20px 18px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
            background: #fff;
        }

        .reception-table tbody tr:hover {
            background: #f9f9f9;
        }

        .reception-table td strong {
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

        hr {
            margin: 40px 0;
            border: none;
            border-top: 1px solid #e5e7eb;
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
            nav, .reception-section, footer {
                padding: 30px 40px;
            }

            .reception-hero {
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

            .reception-hero {
                height: 200px;
                padding: 30px;
            }

            .hero-text h1 {
                font-size: 36px;
            }

            .reception-search {
                flex-direction: column;
                padding: 20px 30px;
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
            <a href="#reservations">Rooms</a>
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
