<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

/* USER NAME */

$user = mysqli_query($conn,"SELECT name FROM users WHERE id='$user_id'");
$userData = mysqli_fetch_assoc($user);

/* TOTAL DONATIONS */

$donations = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM donations WHERE donor_id='$user_id'");
$donationData = mysqli_fetch_assoc($donations);

/* TOTAL REQUESTS */

$requests = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM requests WHERE requester_id='$user_id'");
$requestData = mysqli_fetch_assoc($requests);

/* AVAILABLE MEDICINES */

$available = mysqli_query($conn,
"SELECT COUNT(*) AS total FROM donations WHERE status='Available'");
$availableData = mysqli_fetch_assoc($available);

/* RECENT ACTIVITY */

$activity = mysqli_query($conn,

"SELECT created_at, medicine_name, status, 'Donation' AS type
FROM donations
WHERE donor_id='$user_id'

UNION

SELECT created_at, medicine_name, status, 'Request' AS type
FROM requests
WHERE requester_id='$user_id'

ORDER BY created_at DESC
LIMIT 5

");

?>

<!DOCTYPE html>
<html>
<head>

<title>User Dashboard</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

/* DASHBOARD */

.dashboard{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:240px;
background:#37a9b8;
color:white;
min-height:100vh;
padding:20px;
}

.sidebar h2{
margin-bottom:30px;
}

.sidebar a{
display:block;
color:white;
text-decoration:none;
padding:12px;
margin:8px 0;
border-radius:6px;
}

.sidebar a:hover{
background:rgba(255,255,255,0.2);
}

/* MAIN */

.main{
flex:1;
padding:30px;
}

/* WELCOME */

.welcome{
font-size:24px;
margin-bottom:30px;
}

/* CARDS */

.cards{
display:flex;
gap:25px;
margin-bottom:30px;
}

.card{
flex:1;
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
text-align:center;
}

.card h1{
margin:0;
font-size:35px;
}

.card p{
margin-top:10px;
color:#666;
font-size:16px;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th,td{
padding:12px;
text-align:center;
border-bottom:1px solid #ddd;
}

th{
background:#4e73df;
color:white;
}

</style>

</head>

<body>

<?php include("header.php"); ?>

<div class="dashboard">

<!-- SIDEBAR -->

<div class="sidebar">

<h2>💊 MedBridge</h2>

<a href="user_dashboard.php">🏠 Dashboard</a>
<a href="my_donations.php">📦 My Donations</a>
<a href="my_requests.php">📥 My Requests</a>
<a href="available_medicines.php">💊 Available Medicines</a>
<a href="profile.php">👤 Profile</a>
<a href="logout.php">🚪 Logout</a>

</div>

<!-- MAIN CONTENT -->

<div class="main">

<div class="welcome">
Welcome, <?php echo $userData['name']; ?> 👋
</div>

<!-- CARDS -->

<div class="cards">

<div class="card">
<h1><?php echo $donationData['total']; ?></h1>
<p>Total Donations</p>
</div>

<div class="card">
<h1><?php echo $requestData['total']; ?></h1>
<p>Total Requests</p>
</div>

<div class="card">
<h1><?php echo $availableData['total']; ?></h1>
<p>Available Medicines</p>
</div>

</div>

<h2>Recent Activity</h2>

<table>

<tr>
<th>Date</th>
<th>Type</th>
<th>Medicine</th>
<th>Status</th>
</tr>

<?php

if(mysqli_num_rows($activity)>0){

while($row=mysqli_fetch_assoc($activity)){

?>

<tr>

<td><?php echo $row['created_at']; ?></td>

<td><?php echo $row['type']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['status']; ?></td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='4'>No Activity Found</td></tr>";

}

?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>