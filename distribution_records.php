<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="NGO"){
header("Location: login.php");
exit();
}

$ngo_id = $_SESSION['user_id'];

/* FETCH DISTRIBUTION RECORDS */

$records = mysqli_query($conn,
"SELECT 
users.name,
requests.medicine_name,
distributions.quantity_provided,
distributions.status,
distributions.created_at

FROM distributions

JOIN requests 
ON distributions.request_id = requests.id

JOIN users 
ON requests.requester_id = users.id

WHERE distributions.ngo_id='$ngo_id'

ORDER BY distributions.created_at DESC");
?>

<!DOCTYPE html>
<html>
<head>

<title>Distribution Records</title>

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
width:260px;
background:#36b9cc;
color:white;
padding-top:20px;
}

.sidebar h2{
text-align:center;
margin-bottom:30px;
}

.sidebar a{
display:block;
padding:12px 20px;
color:white;
text-decoration:none;
}

.sidebar a:hover{
background:#2c9faf;
}

/* MAIN */

.main{
flex:1;
padding:30px;
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

tr:hover{
background:#f1f1f1;
}

</style>

</head>

<body>

<?php include("header.php"); ?>

<div class="dashboard">

<!-- SIDEBAR -->

<div class="sidebar">

<h2>💊 MedBridge</h2>

<a href="ngo_dashboard.php">🏠 Dashboard</a>
<a href="view_donations.php">📦 View Donations</a>
<a href="incoming_requests.php">📥 Incoming Requests</a>
<a href="ngo_requirements.php">💊 Medicine Requirements</a>
<a href="distribution_records.php">🚚 Distribution Records</a>
<a href="reports.php">📊 Reports</a>
<a href="ngo_profile.php">👤 Profile</a>
<a href="logout.php">🚪 Logout</a>

</div>


<div class="main">

<h2>Distribution Records</h2>

<table>

<tr>
<th>User</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Status</th>
<th>Date</th>
</tr>

<?php

if(mysqli_num_rows($records)>0){

while($row=mysqli_fetch_assoc($records)){

?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity_provided']; ?></td>

<td><?php echo $row['status']; ?></td>

<td><?php echo $row['created_at']; ?></td>

</tr>

<?php
}

}else{
?>

<tr>
<td colspan="5">No Distribution Records Found</td>
</tr>

<?php } ?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>