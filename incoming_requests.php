<?php
session_start();
include("db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role']!="NGO"){
header("Location: login.php");
exit();
}

$ngo_id = $_SESSION['user_id'];


/* ===== FULFILL REQUEST ===== */

if(isset($_GET['fulfill'])){

$request_id = $_GET['fulfill'];

/* GET REQUEST DATA */

$requestData = mysqli_query($conn,
"SELECT * FROM requests WHERE id='$request_id'");

$request = mysqli_fetch_assoc($requestData);

$medicine = $request['medicine_name'];
$required_qty = $request['quantity'];


/* CHECK DONATION AVAILABLE */

$donationQuery = mysqli_query($conn,
"SELECT * FROM donations
WHERE medicine_name='$medicine'
AND status!='Completed'
ORDER BY created_at ASC
LIMIT 1");

if(mysqli_num_rows($donationQuery)==0){

echo "<script>
alert('This medicine is not available in donations');
window.location='incoming_requests.php';
</script>";

exit();

}

$donation = mysqli_fetch_assoc($donationQuery);

$donation_id = $donation['id'];
$available_qty = $donation['quantity'];


/* CHECK QUANTITY */

if($available_qty < $required_qty){

echo "<script>
alert('Required quantity not available');
window.location='incoming_requests.php';
</script>";

exit();

}


/* INSERT DISTRIBUTION RECORD */

mysqli_query($conn,
"INSERT INTO distributions
(request_id, ngo_id, donation_id, quantity_provided)
VALUES
('$request_id','$ngo_id','$donation_id','$required_qty')");


/* UPDATE DONATION QUANTITY */

$new_qty = $available_qty - $required_qty;

if($new_qty == 0){

mysqli_query($conn,
"UPDATE donations SET quantity=0, status='Completed'
WHERE id='$donation_id'");

}else{

mysqli_query($conn,
"UPDATE donations SET quantity='$new_qty', status='Assigned'
WHERE id='$donation_id'");

}


/* UPDATE REQUEST STATUS */

mysqli_query($conn,
"UPDATE requests SET status='Completed'
WHERE id='$request_id'");


echo "<script>
alert('Medicine Distributed Successfully');
window.location='incoming_requests.php';
</script>";

}



/* ===== FETCH APPROVED REQUESTS ===== */

$requests = mysqli_query($conn,
"SELECT requests.*, users.name 
FROM requests
JOIN users ON requests.requester_id = users.id
WHERE requests.status='Approved'
ORDER BY requests.created_at DESC");

?>

<!DOCTYPE html>
<html>
<head>

<title>Incoming Requests</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

.dashboard{
display:flex;
}

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

.main{
flex:1;
padding:30px;
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

.btn{
padding:6px 12px;
background:#1cc88a;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
}

.btn:hover{
background:#17a673;
}

</style>

</head>

<body>

<?php include("header.php"); ?>

<div class="dashboard">

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

<h2>Incoming Requests</h2>

<table>

<tr>
<th>User</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Prescription</th>
<th>Status</th>
<th>Action</th>
</tr>

<?php

if(mysqli_num_rows($requests)>0){

while($row=mysqli_fetch_assoc($requests)){

?>

<tr>

<td><?php echo $row['name']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td>
<a href="<?php echo $row['prescription_path']; ?>" target="_blank">
View
</a>
</td>

<td><?php echo $row['status']; ?></td>

<td>

<a href="incoming_requests.php?fulfill=<?php echo $row['id']; ?>">

<button class="btn">Fulfill</button>

</a>

</td>

</tr>

<?php
}

}else{
?>

<tr>
<td colspan="6">No Requests Found</td>
</tr>

<?php } ?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>