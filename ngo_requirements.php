<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="NGO"){
header("Location: login.php");
exit();
}

$ngo_id = $_SESSION['user_id'];

/* ADD REQUIREMENT */

if(isset($_POST['add_requirement'])){

$medicine = $_POST['medicine_name'];
$quantity = $_POST['quantity_needed'];
$description = $_POST['description'];

mysqli_query($conn,
"INSERT INTO ngo_requirements
(ngo_id,medicine_name,quantity_needed,description,status)
VALUES
('$ngo_id','$medicine','$quantity','$description','Open')");

echo "<script>alert('Requirement Added');window.location='ngo_requirements.php';</script>";

}

/* DELETE REQUIREMENT */

if(isset($_GET['delete'])){

$id = $_GET['delete'];

mysqli_query($conn,"DELETE FROM ngo_requirements WHERE id='$id'");

echo "<script>alert('Requirement Deleted');window.location='ngo_requirements.php';</script>";

}

/* FETCH REQUIREMENTS */

$requirements = mysqli_query($conn,
"SELECT * FROM ngo_requirements 
WHERE ngo_id='$ngo_id' 
ORDER BY id DESC");

?>

<!DOCTYPE html>
<html>
<head>

<title>Medicine Requirements</title>

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

/* FORM */

.form-box{
background:white;
padding:20px;
border-radius:8px;
margin-bottom:25px;
box-shadow:0 4px 10px rgba(0,0,0,0.1);
}

input,textarea{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:5px;
}

button{
padding:8px 16px;
background:#1cc88a;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

button:hover{
background:#17a673;
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

.delete{
background:#e74a3b;
padding:6px 12px;
border-radius:4px;
color:white;
text-decoration:none;
}

.status-open{
color:green;
font-weight:bold;
}

.status-fulfilled{
color:gray;
font-weight:bold;
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

<h2>Medicine Requirements</h2>

<!-- ADD REQUIREMENT -->

<div class="form-box">

<form method="POST">

<input type="text" name="medicine_name" placeholder="Medicine Name" required>

<input type="number" name="quantity_needed" placeholder="Required Quantity" required>

<textarea name="description" placeholder="Description (optional)"></textarea>

<button name="add_requirement">Add Requirement</button>

</form>

</div>

<!-- REQUIREMENTS TABLE -->

<table>

<tr>
<th>Medicine</th>
<th>Quantity Needed</th>
<th>Description</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

if(mysqli_num_rows($requirements)>0){

while($row=mysqli_fetch_assoc($requirements)){

?>

<tr>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity_needed']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>

<?php
if($row['status']=="Open"){
echo "<span class='status-open'>Open</span>";
}else{
echo "<span class='status-fulfilled'>Fulfilled</span>";
}
?>

</td>

<td>

<a class="delete"
href="ngo_requirements.php?delete=<?php echo $row['id']; ?>">
Delete
</a>

</td>

</tr>

<?php

}

}else{

?>

<tr>
<td colspan="5">No Requirements Added</td>
</tr>

<?php } ?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>