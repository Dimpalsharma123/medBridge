<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="NGO"){
header("Location: login.php");
exit();
}

$ngo_id = $_SESSION['user_id'];

$userResult = mysqli_query($conn,"SELECT * FROM users WHERE id='$ngo_id'");
$user = mysqli_fetch_assoc($userResult);


/* UPDATE PROFILE */

if(isset($_POST['update_profile'])){

$name = $_POST['name'];
$phone = $_POST['phone'];

mysqli_query($conn,"UPDATE users SET name='$name', phone='$phone' WHERE id='$ngo_id'");

echo "<script>alert('Profile Updated');window.location='ngo_profile.php';</script>";

}


/* DELETE ACCOUNT */

if(isset($_POST['delete_account'])){

mysqli_query($conn,"DELETE FROM users WHERE id='$ngo_id'");

session_destroy();

echo "<script>alert('Account Deleted');window.location='login.php';</script>";

}
?>

<!DOCTYPE html>
<html>
<head>

<title>NGO Profile</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

/* DASHBOARD LAYOUT */

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

/* MAIN AREA */

.main{
flex:1;
padding:40px;
}

/* PROFILE CARD */

.profile-card{
background:white;
padding:30px;
border-radius:10px;
max-width:600px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

.profile-card h2{
margin-top:0;
}

/* PROFILE INFO */

.profile-info p{
margin:8px 0;
font-size:15px;
}

/* BUTTONS */

.btn{
padding:8px 16px;
border:none;
border-radius:6px;
cursor:pointer;
margin-top:10px;
margin-right:10px;
}

.edit{background:#4e73df;color:white;}
.logout{background:#e74a3b;color:white;}
.delete{background:#c0392b;color:white;}

/* MODAL */

.modal{
display:none;
position:fixed;
top:0;
left:0;
width:100%;
height:100%;
background:rgba(0,0,0,0.5);
}

.modal-box{
background:white;
width:350px;
margin:150px auto;
padding:25px;
border-radius:8px;
}

.modal-box input{
width:100%;
padding:10px;
margin:10px 0;
border:1px solid #ccc;
border-radius:6px;
}

.close{
float:right;
cursor:pointer;
font-size:20px;
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

<div class="profile-card">

<h2>NGO Profile</h2>

<div class="profile-info">

<p><strong>Name:</strong> <?php echo $user['name']; ?></p>

<p><strong>Email:</strong> <?php echo $user['email']; ?></p>

<p><strong>Phone:</strong> <?php echo $user['phone']; ?></p>

<p><strong>Registration Number:</strong> <?php echo $user['registration_number']; ?></p>

<p>
<strong>Certificate:</strong>
<a href="<?php echo $user['certificate_path']; ?>" target="_blank">
View Document
</a>
</p>

<button class="btn edit" onclick="openModal()">Edit Profile</button>

<a href="logout.php">
<button class="btn logout">Logout</button>
</a>

<button class="btn delete" onclick="openDeleteModal()">Delete Account</button>

</div>

</div>

</div>

</div>

<?php include("footer.php"); ?>


<!-- EDIT PROFILE MODAL -->

<div id="profileModal" class="modal">

<div class="modal-box">

<span class="close" onclick="closeModal()">×</span>

<h3>Edit Profile</h3>

<form method="POST">

<input type="text" name="name"
value="<?php echo $user['name']; ?>" required>

<input type="text" name="phone"
value="<?php echo $user['phone']; ?>" required>

<button class="btn edit" name="update_profile">
Update
</button>

</form>

</div>

</div>


<!-- DELETE ACCOUNT MODAL -->

<div id="deleteModal" class="modal">

<div class="modal-box">

<span class="close" onclick="closeDeleteModal()">×</span>

<h3>Delete Account</h3>

<p>This action cannot be undone.</p>

<form method="POST">

<button class="btn delete" name="delete_account">
Yes Delete
</button>

<button type="button" class="btn edit" onclick="closeDeleteModal()">
Cancel
</button>

</form>

</div>

</div>


<script>

function openModal(){
document.getElementById("profileModal").style.display="block";
}

function closeModal(){
document.getElementById("profileModal").style.display="none";
}

function openDeleteModal(){
document.getElementById("deleteModal").style.display="block";
}

function closeDeleteModal(){
document.getElementById("deleteModal").style.display="none";
}

</script>

</body>
</html>