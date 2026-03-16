<?php
session_start();
include_once("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

/* DELETE DONATION */

if(isset($_GET['delete'])){

$id = $_GET['delete'];

mysqli_query($conn,
"DELETE FROM donations WHERE id='$id' AND donor_id='$user_id'");

header("Location: my_donations.php");
exit();

}

/* FETCH DONATIONS */

$query = "SELECT * FROM donations 
WHERE donor_id='$user_id'
ORDER BY created_at DESC";

$user_result = mysqli_query($conn,$query);

?>

<!DOCTYPE html>
<html>
<head>

<title>My Donations</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

.dashboard{
display:flex;
min-height:90vh;
}

.sidebar{
width:230px;
background:#37a9b8;
color:white;
padding:20px;
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

.content{
flex:1;
padding:30px;
}

.add-btn{
background:#1cc88a;
color:white;
padding:10px 15px;
border-radius:5px;
text-decoration:none;
float:right;
}

.add-btn:hover{
background:#17a673;
}

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
margin-top:20px;
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

.medicine-img{
width:60px;
height:60px;
object-fit:cover;
border-radius:5px;
}

.available{color:green;font-weight:bold;}
.assigned{color:orange;font-weight:bold;}
.completed{color:gray;font-weight:bold;}

.delete-btn{
background:#e74a3b;
color:white;
padding:6px 10px;
text-decoration:none;
border-radius:4px;
}

</style>

</head>

<body>

<?php include("header.php"); ?>

<div class="dashboard">

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

<h2>My Donations</h2>

<a href="donate_medicine.php" class="add-btn">
+ Donate Medicine
</a>

<table>

<tr>
<th>Date</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Expiry</th>
<th>Description</th>
<th>Image</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

if(mysqli_num_rows($user_result) > 0){

while($row = mysqli_fetch_assoc($user_result)){

?>

<tr>

<td><?php echo $row['created_at']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td><?php echo $row['expiry_date']; ?></td>

<td><?php echo $row['description']; ?></td>

<td>
<img src="<?php echo $row['medicine_image']; ?>" class="medicine-img">
</td>

<td>

<?php

if($row['status']=="Available"){
echo "<span class='available'>Available</span>";
}
elseif($row['status']=="Assigned"){
echo "<span class='assigned'>Assigned</span>";
}
else{
echo "<span class='completed'>Completed</span>";
}

?>

</td>

<td>

<a class="delete-btn"
href="?delete=<?php echo $row['id']; ?>"
onclick="return confirm('Delete this donation?')">
Delete
</a>

</td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='8'>No Donations Found</td></tr>";

}

?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>