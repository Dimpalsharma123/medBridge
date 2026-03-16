<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['donate'])){

$medicine = $_POST['medicine_name'];
$quantity = $_POST['quantity'];
$expiry = $_POST['expiry_date'];
$description = $_POST['description'];

/* Expiry Check */

if($expiry < date("Y-m-d")){
echo "<script>alert('Expired medicine cannot be donated');</script>";
exit();
}

/* IMAGE UPLOAD */

$image = $_FILES['medicine_image']['name'];
$tmp = $_FILES['medicine_image']['tmp_name'];

$imageName = time()."_".$image;
$folder = "uploads/".$imageName;

move_uploaded_file($tmp,$folder);

/* INSERT QUERY */

$sql = "INSERT INTO donations
(donor_id, medicine_name, quantity, expiry_date, description, medicine_image, status)

VALUES
('$user_id','$medicine','$quantity','$expiry','$description','$folder','Available')";

$result = mysqli_query($conn,$sql);

if(!$result){
die("Insert Error: ".mysqli_error($conn));
}

echo "<script>
alert('Medicine Donated Successfully');
window.location='my_donations.php';
</script>";

}
?>

<!DOCTYPE html>
<html>

<head>

<title>Donate Medicine</title>

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
padding:40px;
}

.card{
background:white;
padding:30px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
width:450px;
}

input,textarea{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:5px;
}

textarea{
height:80px;
resize:none;
}

button{
background:#1cc88a;
color:white;
border:none;
padding:12px;
border-radius:5px;
cursor:pointer;
font-size:16px;
width:100%;
}

button:hover{
background:#17a673;
}

.preview{
width:120px;
height:120px;
border:2px dashed #ccc;
margin-top:10px;
display:flex;
align-items:center;
justify-content:center;
overflow:hidden;
}

.preview img{
width:100%;
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

<h2>Donate Medicine</h2>

<div class="card">

<form method="POST" enctype="multipart/form-data">

<label>Medicine Name</label>
<input type="text" name="medicine_name" required>

<label>Quantity</label>
<input type="number" name="quantity" required>

<label>Expiry Date</label>
<input type="date" name="expiry_date" required>

<label>Description</label>
<textarea name="description"></textarea>

<label>Upload Medicine Image</label>

<input type="file" name="medicine_image" onchange="previewImage(event)" required>

<div class="preview" id="previewBox">
<span>Preview</span>
</div>

<button type="submit" name="donate">
Donate Medicine
</button>

</form>

</div>

</div>

</div>

<?php include("footer.php"); ?>

<script>

function previewImage(event){

var reader = new FileReader();

reader.onload = function(){

var output = document.getElementById('previewBox');

output.innerHTML = "<img src='"+reader.result+"'>";

}

reader.readAsDataURL(event.target.files[0]);

}

</script>

</body>
</html>