<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="NGO"){
    header("Location: login.php");
    exit();
}

/* SEARCH */

$search = "";

if(isset($_GET['search'])){
    $search = mysqli_real_escape_string($conn,$_GET['search']);
}

/* FETCH DONATIONS */

$query = "SELECT donations.*, users.name AS donor_name 
FROM donations
JOIN users ON donations.donor_id = users.id
WHERE donations.medicine_name LIKE '%$search%'
ORDER BY donations.created_at DESC";

$user_result = mysqli_query($conn,$query);
?>

<!DOCTYPE html>
<html>
<head>

<title>View Donations</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

.dashboard{
display:flex;
}

/* SIDEBAR */

.sidebar{
width:260px;
background:#36b9cc;
color:white;
padding-top:20px;
min-height:100vh;
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

/* SEARCH */

.search-box{
margin-bottom:20px;
}

.search-box input{
padding:10px;
width:300px;
border:1px solid #ccc;
border-radius:5px;
}

.search-box button{
padding:10px 15px;
background:#4e73df;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
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

img{
width:70px;
height:70px;
object-fit:cover;
border-radius:6px;
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

<h2>View Donations</h2>

<!-- SEARCH -->

<form method="GET" class="search-box">

<input type="text" name="search" placeholder="Search Medicine Name"
value="<?php echo htmlspecialchars($search); ?>">

<button type="submit">Search</button>

</form>

<table>

<tr>
<th>Donor</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Expiry Date</th>
<th>Image</th>
</tr>

<?php

if(mysqli_num_rows($user_result)>0){

while($row=mysqli_fetch_assoc($user_result)){

?>

<tr>

<td><?php echo $row['donor_name']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td><?php echo $row['expiry_date']; ?></td>

<td>

<?php if(!empty($row['medicine_image'])){ ?>

<img src="<?php echo $row['medicine_image']; ?>">

<?php } else { ?>

No Image

<?php } ?>

</td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='5'>No Medicine Found</td></tr>";

}

?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>