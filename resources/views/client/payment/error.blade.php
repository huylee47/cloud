<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thanh toán thất bại</title>
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .error-container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
        }
        .error-icon {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            background: #ff4b4b;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 0 auto 15px;
            position: relative;
            animation: popIn 0.5s ease-in-out;
        }
        .error-icon::after {
            content: '\2716'; /* Dấu X */
            font-size: 50px;
            color: white;
            font-weight: bold;
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes popIn {
            from { transform: scale(0); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .message {
            font-size: 18px;
            margin-bottom: 15px;
            color: #d9534f;
        }
        .redirect {
            color: #00a1e4;
            font-weight: bold;
            cursor: pointer;
            text-decoration: none;
        }
    </style>
    <script>
        setTimeout(function() {
            window.location.href = "{{ route('home') }}";
        }, 3000);
    </script>
</head>
<body>
    <div class="error-container">
        <div class="error-icon"></div>
        <p class="message">Thanh toán thất bại! Vui lòng thử lại hoặc kiểm tra phương thức thanh toán.</p>
        <p>Bạn sẽ được chuyển hướng về trang chủ sau 3 giây.</p>
        <p>Nếu không, hãy nhấp vào <a href="{{ route('home') }}" class="redirect">đây</a>.</p>
    </div>
</body>
</html>
