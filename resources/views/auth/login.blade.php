<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Log In | Poseidonian</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            margin: 0;
            background: #f5f7fb;
        }

        .container {
            display: flex;
            min-height: 100vh;
        }

        /* LEFT IMAGE */
        .left {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f7fb;
        }

        .image-card {
            width: 80%;
            height: 80%;
            position: relative;
            border: 3px solid #2f80ff;
            border-radius: 4px;
            overflow: hidden;
        }

        .image-card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .image-card .label {
            position: absolute;
            bottom: 10px;
            left: 10px;
            color: #fff;
            font-size: 11px;
        }

        .image-card .tagline {
            position: absolute;
            bottom: 10px;
            right: 10px;
            color: #fff;
            font-size: 10px;
        }

        /* RIGHT FORM */
        .right {
            width: 50%;
            padding: 80px 60px;
            background: #ffffff;
        }

        .brand {
            font-size: 12px;
            letter-spacing: 2px;
            font-weight: 600;
            margin-bottom: 20px;
        }

        h1 {
            color: #3aa0ff;
            font-size: 40px;
            margin: 0;
        }

        .signup-link {
            font-size: 14px;
            margin: 10px 0 40px;
        }

        .signup-link a {
            color: #3aa0ff;
            text-decoration: none;
            font-weight: 500;
        }


        input {
            width: 100%;
            padding: 15px 20px;
            margin-bottom: 25px;
            border-radius: 6px;
            border: 1px solid #ddd;
            background: #f2f2f2;
            font-size: 14px;
        }

        input:focus {
            outline: none;
            background: #fff;
            border-color: #3aa0ff;
        }

        button {
            width: 100%;
            padding: 14px;
            background: #8b8b8b;
            border: none;
            border-radius: 6px;
            color: #fff;
            font-size: 15px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background: #6f6f6f;
        }

        /* MODE BUTTONS */
        .modes {
            margin-top: 25px;
            display: flex;
            gap: 15px;
            align-items: center;
        }

        .mode {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #3aa0ff;
            cursor: pointer;
        }

        .avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            color: #fff;
        }

        .error-message {
            background: #f8d7da;
            color: #721c24;
            padding: 12px 14px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
            border: 1px solid #f5c6cb;
        }

        form {
            margin-top: 60px;
        }

        footer {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: #000;
            color: #fff;
            font-size: 11px;
            padding: 8px 20px;
            display: flex;
            justify-content: space-between;
        }

        @media (max-width: 900px) {
            .container {
                flex-direction: column;
            }
            .left, .right {
                width: 100%;
            }
            footer {
                position: static;
            }
        }
    </style>
</head>
<body>

<div class="container">

    {{-- LEFT IMAGE --}}
    <div class="left">
        <div class="image-card">
            <img src="{{ asset('images/login-image.jpg') }}" alt="Poseidonian">
            <div class="label">POSEIDONIAN</div>
            <div class="tagline">Your home away from home</div>
        </div>
    </div>

    {{-- RIGHT FORM --}}
    <div class="right">
        <div class="brand">POSEIDONIAN</div>

        <h1>Log In.</h1>

        <div class="signup-link">
            Welcome to Poseidonian!
            <a></a>


        @if ($errors->any())
            <div class="error-message">Incorrect credentials! Please try again.</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>

            <button type="submit">Log In</button>
        </form>

    </div>
</div>

<footer>
    <div>POSEIDONIAN</div>
    <div>© {{ date('Y') }} ALL RIGHTS RESERVED</div>
</footer>

</body>
</html>