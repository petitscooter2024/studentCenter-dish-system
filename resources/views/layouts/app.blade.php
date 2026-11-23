
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>@yield('title', 'Student Center Dish Check')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800&family=Playfair+Display:wght@600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --green: #174b3a;
            --green-dark: #10382c;
            --green-light: #e8f1eb;
            --sage: #dce9df;
            --cream: #faf9f5;
            --white: #ffffff;
            --text: #27342e;
            --muted: #758078;
            --border: #e6e9e4;
            --red: #bd4141;
            --shadow: 0 5px 20px rgba(25,55,40,.06);
            --shadow-hover: 0 12px 28px rgba(25,55,40,.12);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--cream);
            color: var(--text);
            line-height: 1.7;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        button, input, select, textarea {
            font: inherit;
        }

        button {
            cursor: pointer;
        }

        img {
            max-width: 100%;
        }

        [hidden] {
            display: none !important;
        }

        /* NAVIGATION */

        .navbar {
            background: var(--green);
            color: white;
            padding: 17px 6%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 18px;
            box-shadow: 0 4px 18px rgba(0,0,0,.09);
            position: relative;
            z-index: 10;
        }

        .navbar h2 {
            font-family: 'Playfair Display', serif;
            font-size: 29px;
            letter-spacing: -.5px;
        }

        .navbar h2 a {
            color: white;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: white;
            font-size: 14px;
            font-weight: 700;
            transition: color .2s;
        }

        .nav-links a:hover {
            color: #c9e3c8;
        }

        .nav-links form {
            display: inline;
        }

        /* GENERAL LAYOUT */

        main {
            flex: 1;
        }

        .container {
            width: 90%;
            max-width: 1250px;
            margin: 42px auto;
        }

        h1, h2, h3, h4 {
            line-height: 1.3;
        }

        .section-heading {
            margin: 32px 0 22px;
        }

        .section-heading h1,
        .section-heading h2 {
            color: var(--green);
            font-family: 'Playfair Display', serif;
            margin-bottom: 8px;
        }

        .section-heading h1 {
            font-size: clamp(28px,4vw,40px);
        }

        .section-heading h2 {
            font-size: 27px;
        }

        .section-heading p {
            color: var(--muted);
        }

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h1 {
            color: var(--green);
            font-family: 'Playfair Display', serif;
            font-size: clamp(28px,4vw,40px);
        }

        .page-header p {
            color: var(--muted);
        }

        /* HERO */

        .hero {
            background: linear-gradient(120deg,#10382c,#28644b);
            color: white;
            padding: 90px 7%;
            text-align: center;
        }

        .hero h1 {
            font-family: 'Playfair Display',serif;
            font-size: clamp(34px,5vw,58px);
            margin-bottom: 18px;
        }

        .hero p {
            max-width: 680px;
            margin: 0 auto 25px;
            color: #e3eee5;
            font-size: 17px;
        }

        /* GRIDS */

        .food-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(250px,1fr));
            gap: 22px;
            margin: 25px 0;
        }

        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit,minmax(240px,1fr));
            gap: 22px;
            margin: 25px 0;
        }

        /* DASHBOARD GRID */

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(4,minmax(0,1fr));
            gap: 18px;
            margin: 25px 0;
        }

        /* CARDS */

        .food-card,
        .feature-card,
        .dashboard-card,
        .form-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 16px;
            padding: 20px;
            box-shadow: var(--shadow);
            transition: transform .25s,box-shadow .25s;
        }

        .food-card:hover,
        .feature-card:hover,
        .dashboard-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-hover);
        }

        .food-card {
            overflow: hidden;
        }

        .food-image {
            display: block;
            width: 100%;
            height: 190px;
            object-fit: cover;
            border-radius: 11px;
            margin-bottom: 15px;
            background: #edf0eb;
        }

        .food-card h3,
        .feature-card h3 {
            color: var(--green);
            margin-bottom: 8px;
            font-size: 19px;
        }

        .food-card p {
            margin-bottom: 5px;
            font-size: 14px;
        }

        .food-card-content {
            padding: 2px 1px;
        }

        .food-price {
            font-weight: 800;
            color: var(--green);
            font-size: 17px !important;
            margin: 12px 0;
        }

        .food-category {
            color: var(--muted);
            font-size: 12px !important;
        }

        /* AVAILABILITY */

        .availability {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 30px;
            font-size: 12px;
            font-weight: 800;
            margin: 7px 0;
        }

        .available {
            background: #e2f3e6;
            color: #237442;
        }

        .unavailable {
            background: #fbe7e5;
            color: #ad3535;
        }

        .badge {
            display: inline-block;
            padding: 6px 13px;
            border-radius: 30px;
            background: #fff1d8;
            color: #936018;
            font-size: 12px;
            font-weight: 800;
        }

        /* BUTTONS */

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            background: var(--green);
            color: white;
            border: 1px solid var(--green);
            border-radius: 9px;
            padding: 12px 20px;
            margin-top: 10px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 800;
            transition: .2s;
        }

        .btn:hover {
            background: var(--green-dark);
            border-color: var(--green-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 13px rgba(16,56,44,.17);
        }

        .btn-danger {
            background: var(--red);
            border-color: var(--red);
            color: white;
        }

        .btn-danger:hover {
            background: #963131;
            border-color: #963131;
        }

        /* FORMS */

        .form-card {
            margin: 22px 0;
            padding: 28px;
        }

        .form-card h2,
        .form-card h3 {
            color: var(--green);
            margin-bottom: 18px;
            font-family: 'Playfair Display',serif;
        }

        label {
            display: block;
            margin: 14px 0 7px;
            font-weight: 700;
            font-size: 14px;
        }

        input:not([type="checkbox"]):not([type="radio"]),
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #dce2dc;
            border-radius: 9px;
            font-size: 14px;
            background: white;
            color: var(--text);
            outline: none;
            transition: border .2s,box-shadow .2s;
        }

        input[type="checkbox"],
        input[type="radio"] {
            width: auto;
            accent-color: var(--green);
        }

        input:focus,
        select:focus,
        textarea:focus {
            border-color: var(--green);
            box-shadow: 0 0 0 3px rgba(23,75,58,.09);
        }

        textarea {
            resize: vertical;
            min-height: 85px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        /* ALERTS */

        .alert {
            padding: 14px 18px;
            border-radius: 10px;
            margin: 16px 0;
            font-size: 14px;
        }

        .alert-success {
            background: #e2f3e6;
            color: #237442;
        }

        .alert-danger {
            background: #fbe7e5;
            color: #ad3535;
        }

        /* EMPTY STATE */

        .empty-state {
            background: white;
            padding: 42px 24px;
            text-align: center;
            border-radius: 15px;
            border: 1px solid var(--border);
            margin: 22px 0;
        }

        .empty-state h3 {
            color: var(--green);
            margin-bottom: 8px;
        }

        .empty-state p {
            color: var(--muted);
        }

        /* DASHBOARD */

        .dashboard-card {
            min-width: 0;
            padding: 23px;
            border-top: 4px solid var(--green);
        }

        .dashboard-number {
            font-size: clamp(26px,3vw,36px);
            font-weight: 800;
            color: var(--green);
            margin-top: 10px;
        }

        .dashboard-card h3 {
            color: var(--muted);
            font-size: 14px;
            font-weight: 700;
        }

        /* TABLES */

        .table-responsive {
            width: 100%;
            overflow-x: auto;
            border-radius: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin: 18px 0;
        }

        th, td {
            padding: 14px;
            border-bottom: 1px solid var(--border);
            text-align: left;
        }

        th {
            background: var(--green);
            color: white;
            font-weight: 700;
            white-space: nowrap;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tbody tr:hover {
            background: #f7faf7;
        }

        /* MENU DETAILS */

        .menu-item-row {
            display: grid;
            grid-template-columns: 100px 1fr 1fr auto;
            gap: 16px;
            align-items: center;
            margin: 20px 0;
            padding-bottom: 18px;
            border-bottom: 1px solid var(--border);
        }

        .menu-item-row .food-image {
            height: 80px;
            margin: 0;
        }

        .date-filter {
            max-width: 400px;
            margin: 18px 0;
        }

        .menu-heading {
            margin: 22px 0;
        }

        .menu-category-section {
            margin: 35px 0;
        }

        /* LOGIN */

        .login-container {
            max-width: 520px;
        }

        .login-card {
            padding: 35px;
        }

        .login-card h1 {
            color: var(--green);
            font-family: 'Playfair Display',serif;
            margin-bottom: 12px;
        }

        .login-note {
            margin: 18px 0;
            font-size: 13px;
            color: var(--muted);
        }

        /* FEATURES */

        .features {
            background: #edf3ed;
            padding: 50px 0;
            margin-top: 50px;
        }

        /* FOOTER */

        footer {
            background: var(--green-dark);
            color: #e7eee8;
            text-align: center;
            padding: 25px;
            margin-top: 55px;
            font-size: 13px;
        }

        /* RESPONSIVE */

        @media (max-width: 950px) {
            .dashboard-grid {
                grid-template-columns: repeat(2,minmax(0,1fr));
            }
        }

        @media (max-width: 700px) {
            .navbar {
                flex-direction: column;
                text-align: center;
                padding: 18px 5%;
            }

            .nav-links {
                justify-content: center;
                gap: 14px;
            }

            .hero {
                padding: 60px 7%;
            }

            .container {
                width: 92%;
                margin: 28px auto;
            }

            .food-grid {
                grid-template-columns: repeat(auto-fit,minmax(220px,1fr));
                gap: 15px;
            }

            .food-image {
                height: 170px;
            }

            .menu-item-row {
                grid-template-columns: 1fr;
            }

            .login-card {
                padding: 24px;
            }

            .form-card {
                padding: 20px;
            }

            th, td {
                padding: 9px;
                font-size: 13px;
            }
        }

        @media (max-width: 480px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }

            .food-grid {
                grid-template-columns: 1fr;
            }

            .food-image {
                height: 200px;
            }

            .nav-links {
                gap: 12px;
            }

            .nav-links a {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <nav class="navbar">

        <h2>
            <a href="{{ route('home') }}">Dish Check</a>
        </h2>

        <div class="nav-links">

            <a href="{{ route('home') }}">Home</a>

            <a href="{{ route('menu.index') }}">Daily Menu</a>

            @auth

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}">
                        Admin Dashboard
                    </a>
                @elseif(auth()->user()->role === 'staff')
                    <a href="{{ route('staff.dashboard') }}">
                        Staff Dashboard
                    </a>
                @endif

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn">Logout</button>
                </form>

            @else

                <a href="{{ route('login') }}">Staff Login</a>

            @endauth

        </div>

    </nav>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>
            &copy; {{ date('Y') }} Student Center Dish Check.
            All rights reserved.
        </p>
    </footer>

</body>
</html>
