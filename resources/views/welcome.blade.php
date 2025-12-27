<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Welcome</title>
    <style>
        html,
        body {
            height: 100%;
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: #f7fafc;
            color: #111;
        }

        .container {
            min-height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .card {
            background: #fff;
            padding: 2rem 2.5rem;
            border-radius: 12px;
            box-shadow: 0 6px 20px rgba(16, 24, 40, .08);
            text-align: center;
            max-width: 440px;
            width: 100%;
        }

        .logo-img {
            width: 72px;
            height: 72px;
            object-fit: contain;
            margin: 0 auto 1rem;
            display: block
        }

        .svg-logo {
            width: 72px;
            height: 72px;
            margin: 0 auto 1rem;
            display: none
        }

        .title {
            font-size: 1.9rem;
            margin: 0;
            font-weight: 600
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card">
            <!-- Use the site favicon by default; if it fails to load we show the inline SVG logo -->
            <img src="{{ asset('logo/logo.jpeg') }}" alt="Logo" class="logo-img"
                onerror="this.style.display='none';document.querySelector('.svg-logo').style.display='block'">

            <h1 class="title">Welcome to VSH</h1>
        </div>
    </div>
</body>

</html>
