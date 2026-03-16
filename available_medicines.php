<?php
session_start();
include("db.php");

/* SEARCH */

$search = "";

if(isset($_GET['search'])){
$search = $_GET['search'];
}

/* FETCH AVAILABLE MEDICINES */

$query = "SELECT donations.*, users.name AS donor_name
FROM donations
JOIN users ON donations.donor_id = users.id
WHERE donations.status='Available'
AND donations.medicine_name LIKE '%$search%'
ORDER BY donations.created_at DESC";

$user_result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>

<head>

<title>Available Medicines</title>

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
width:230px;
background:#37a9b8;
color:white;
padding:20px;
min-height:100vh;
}

.sidebar h2{
margin-bottom:25px;
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

/* CONTENT */

.content{
flex:1;
padding:30px;
}

/* SEARCH BOX */

.search-box{
margin-bottom:20px;
}

.search-box input{
padding:10px;
width:250px;
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

.search-box button:hover{
background:#2e59d9;
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

/* IMAGE */

.medicine-img{
width:60px;
height:60px;
object-fit:cover;
border-radius:5px;
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

<div class="content">

<h2>Available Medicines</h2>

<!-- SEARCH -->

<form method="GET" class="search-box">

<input type="text" name="search"
placeholder="Search medicine name..."
value="<?php echo $search; ?>">

<button type="submit">Search</button>

</form>

<table>

<tr>

<th>Donor</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Expiry</th>
<th>Description</th>
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

<td><?php echo $row['description']; ?></td>

<td>

<img src="<?php echo $row['medicine_image']; ?>" class="medicine-img">

</td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='6'>No Medicines Found</td></tr>";

}

?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>