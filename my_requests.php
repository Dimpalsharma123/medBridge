<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$requester_id = $_SESSION['user_id'];

/* CREATE REQUEST */

if(isset($_POST['create_request'])){

    $medicine_name = mysqli_real_escape_string($conn,$_POST['medicine_name']);
    $quantity = $_POST['quantity'];

    /* FILE UPLOAD */

    $targetDir = "uploads/prescriptions/";

    if(!is_dir($targetDir)){
        mkdir($targetDir,0777,true);
    }

    $fileName = time()."_".basename($_FILES["prescription"]["name"]);
    $targetFile = $targetDir.$fileName;

    move_uploaded_file($_FILES["prescription"]["tmp_name"],$targetFile);

    /* INSERT REQUEST */

    mysqli_query($conn,

    "INSERT INTO requests
    (requester_id,medicine_name,quantity,prescription_path,status)

    VALUES
    ('$requester_id','$medicine_name','$quantity','$targetFile','Pending')"

    );

    echo "<script>alert('Request Submitted Successfully'); window.location='my_requests.php';</script>";
}


/* FETCH USER REQUESTS */

$user_result = mysqli_query($conn,

"SELECT * FROM requests
WHERE requester_id='$requester_id'
ORDER BY created_at DESC");

?>
<!DOCTYPE html>
<html>
<head>

<title>My Requests</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

.dashboard{
display:flex;
min-height:80vh;
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

.create-btn{
background:#1cc88a;
color:white;
padding:10px 15px;
border:none;
border-radius:5px;
cursor:pointer;
margin-bottom:20px;
}

.create-btn:hover{
background:#17a673;
}

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

.modal{
display:none;
position:fixed;
z-index:1000;
left:0;
top:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.6);
}

.modal-content{
background:white;
width:400px;
margin:120px auto;
padding:25px;
border-radius:10px;
}

.modal-content input{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:5px;
}

.close{
float:right;
font-size:22px;
cursor:pointer;
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

<h2>My Requests</h2>

<button onclick="openModal()" class="create-btn">
+ Create Request
</button>

<table>

<tr>
<th>Date</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Prescription</th>
<th>Status</th>
</tr>

<?php

if(mysqli_num_rows($user_result)>0){

while($row=mysqli_fetch_assoc($user_result)){

?>

<tr>

<td><?php echo $row['created_at']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>
<a href="<?php echo $row['prescription_path']; ?>" target="_blank">
View
</a>
</td>

<td><?php echo $row['status']; ?></td>

</tr>

<?php
}

}else{

echo "<tr><td colspan='5'>No Requests Found</td></tr>";

}

?>

</table>

</div>

</div>

<?php include("footer.php"); ?>


<!-- MODAL -->

<div id="requestModal" class="modal">

<div class="modal-content">

<span class="close" onclick="closeModal()">&times;</span>

<h2>Create Medicine Request</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="medicine_name" placeholder="Medicine Name" required>

<input type="number" name="quantity" placeholder="Quantity Required" required>

<label>Upload Prescription</label>

<input type="file" name="prescription" required>

<button type="submit" name="create_request">
Submit Request
</button>

</form>

</div>

</div>

<script>

function openModal(){
document.getElementById("requestModal").style.display="block";
}

function closeModal(){
document.getElementById("requestModal").style.display="none";
}

window.onclick=function(event){
var modal=document.getElementById("requestModal");
if(event.target==modal){
modal.style.display="none";
}
}

</script>

</body>
</html>