<?php
session_start();

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
    <style>
        body{
            font-family: Arial;
            background:#f4f6f9;
            margin:0;
        }

        .header{
            background:#2c3e50;
            color:white;
            padding:15px;
            text-align:center;
        }

        .container{
            display:flex;
            flex-wrap:wrap;
            justify-content:center;
            margin-top:40px;
        }

        .card{
            width:250px;
            height:120px;
            margin:20px;
            background:white;
            display:flex;
            align-items:center;
            justify-content:center;
            border-radius:8px;
            box-shadow:0 4px 10px rgba(0,0,0,0.1);
            text-align:center;
            font-weight:bold;
            font-size:16px;
            transition:0.3s;
        }

        .card:hover{
            background:#3498db;
            color:white;
            cursor:pointer;
            transform:scale(1.05);
        }

        a{
            text-decoration:none;
            color:inherit;
        }

        .logout{
            text-align:center;
            margin-top:30px;
        }

        .logout a{
            background:#e74c3c;
            padding:8px 15px;
            color:white;
            border-radius:5px;
        }

    </style>
</head>
<body>

<div class="header">
    <h2>Admin Dashboard</h2>
</div>

<div class="container">

    <a href="view_users.php">
        <div class="card">
            View All Users
        </div>
    </a>

    <a href="view_ngos.php">
        <div class="card">
            View NGOs
        </div>
    </a>

    <a href="approve_ngos.php">
        <div class="card">
            Approve NGO Requests
        </div>
    </a>

    <a href="view_requests.php">
        <div class="card">
            View Medicine Requests
        </div>
    </a>

</div>

<div class="logout">
    <a href="logout.php">Logout</a>
</div>

</body>
</html>