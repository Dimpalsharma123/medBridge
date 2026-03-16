<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id'])){
header("Location: login.php");
exit();
}

$user_id = $_SESSION['user_id'];

$userResult = mysqli_query($conn,"SELECT * FROM users WHERE id='$user_id'");
$user = mysqli_fetch_assoc($userResult);

/* UPDATE PROFILE */

if(isset($_POST['update_profile'])){

$name = $_POST['name'];
$phone = $_POST['phone'];

mysqli_query($conn,"UPDATE users SET name='$name', phone='$phone' WHERE id='$user_id'");

echo "<script>alert('Profile Updated');window.location='profile.php';</script>";
}

/* DELETE ACCOUNT */

if(isset($_POST['delete_account'])){

mysqli_query($conn,"DELETE FROM users WHERE id='$user_id'");

session_destroy();

echo "<script>alert('Account Deleted');window.location='login.php';</script>";
}

/* STATS */

$totalDonations = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM donations WHERE donor_id='$user_id'"));

$totalRequests = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM requests WHERE requester_id='$user_id'"));

$pendingRequests = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM requests WHERE requester_id='$user_id' AND status='Pending'"));

$pendingData = mysqli_query($conn,
"SELECT created_at,medicine_name,quantity,status 
FROM requests 
WHERE requester_id='$user_id' AND status='Pending'");
?>

<!DOCTYPE html>
<html>
<head>

<title>User Profile</title>

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

/* MAIN AREA */

.main{
flex:1;
padding:30px;
}

/* PROFILE BANNER */

.profile-banner{
height:150px;
background:linear-gradient(135deg,#4e73df,#1cc88a);
border-radius:10px;
}

/* PROFILE CARD */

.profile-card{
background:white;
margin-top:-60px;
padding:25px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
max-width:700px;
}

/* AVATAR */

.avatar{
width:90px;
height:90px;
border-radius:50%;
background:#1cc88a;
display:flex;
align-items:center;
justify-content:center;
font-size:36px;
color:white;
margin-top:-50px;
border:4px solid white;
}

/* PROFILE INFO */

.profile-info h2{
margin:10px 0 5px;
}

.profile-info p{
margin:3px 0;
color:#666;
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

/* STATS */

.stats{
display:flex;
gap:20px;
margin-top:25px;
}

.stat{
flex:1;
background:white;
padding:20px;
border-radius:8px;
text-align:center;
box-shadow:0 4px 10px rgba(0,0,0,0.08);
cursor:pointer;
}

.stat h3{
margin:0;
font-size:26px;
}

/* TABLE */

table{
width:100%;
border-collapse:collapse;
margin-top:20px;
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

<a href="user_dashboard.php">🏠 Dashboard</a>

<a href="my_donations.php">📦 My Donations</a>

<a href="my_requests.php">📥 My Requests</a>

<a href="available_medicines.php">💊 Available Medicines</a>

<a href="profile.php">👤 Profile</a>

<a href="logout.php">🚪 Logout</a>

</div>


<div class="main">

<!-- PROFILE BANNER -->

<div class="profile-banner"></div>

<div class="profile-card">

<div class="avatar">
<?php echo strtoupper(substr($user['name'],0,1)); ?>
</div>

<div class="profile-info">

<h2><?php echo $user['name']; ?></h2>

<p><?php echo $user['email']; ?></p>

<p><?php echo $user['phone']; ?></p>

<button class="btn edit" onclick="openModal()">Edit Profile</button>

<a href="logout.php">
<button class="btn logout">Logout</button>
</a>

<button class="btn delete" onclick="openDeleteModal()">Delete Account</button>

</div>

</div>

<!-- STATS -->

<div class="stats">

<div class="stat">
<h3><?php echo $totalDonations; ?></h3>
<p>Total Donations</p>
</div>

<div class="stat">
<h3><?php echo $totalRequests; ?></h3>
<p>Total Requests</p>
</div>

<div class="stat" onclick="togglePending()">
<h3><?php echo $pendingRequests; ?></h3>
<p>Pending Requests</p>
</div>

</div>

<!-- PENDING REQUEST TABLE -->

<div id="pendingTable" style="display:none">

<table>

<tr>
<th>Date</th>
<th>Medicine</th>
<th>Qty</th>
<th>Status</th>
</tr>

<?php while($row=mysqli_fetch_assoc($pendingData)){ ?>

<tr>
<td><?php echo $row['created_at']; ?></td>
<td><?php echo $row['medicine_name']; ?></td>
<td><?php echo $row['quantity']; ?></td>
<td><?php echo $row['status']; ?></td>
</tr>

<?php } ?>

</table>

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

<p>This action cannot be undone</p>

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

function togglePending(){

var table=document.getElementById("pendingTable");

if(table.style.display==="none"){
table.style.display="block";
}
else{
table.style.display="none";
}

}

</script>

</body>
</html>