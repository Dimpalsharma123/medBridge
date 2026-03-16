<!DOCTYPE html>
<html>
<head>
    <title>Donation System</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background: linear-gradient(to right, #1e3c72, #2a5298);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            overflow: hidden;
            font-family: Arial, sans-serif;
        }

        .splash-container {
            text-align: center;
            animation: fadeIn 2s ease-in-out;
        }

        .splash-container img {
            width: 300px;
            border-radius: 15px;
            box-shadow: 0px 10px 30px rgba(0,0,0,0.4);
        }

        .title {
            color: white;
            margin-top: 20px;
            font-size: 28px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
    </style>

    <script>
        // Redirect after 3 seconds
        setTimeout(function(){
            window.location.href = "index.php";
        }, 3000);
    </script>

</head>
<body>

<div class="splash-container">
    <img src="images/logo.png" alt="Donation Logo">
    <div class="title">Medicine Donation System</div>
</div>

</body>
</html>