<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include("db.php");

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != "NGO"){
    header("Location: login.php");
    exit();
}

$ngo_id = $_SESSION['user_id'];

/* ===== FETCH NGO NAME ===== */
$userResult = mysqli_query($conn,"SELECT name FROM users WHERE id='$ngo_id'");
$user = mysqli_fetch_assoc($userResult);

/* ===== STATISTICS ===== */

$totalDonations = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM distributions WHERE ngo_id='$ngo_id'"));

$pendingRequests = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM requests WHERE status='Pending'"));

$approvedRequests = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM requests WHERE status='Approved'"));

$distributed = mysqli_num_rows(mysqli_query($conn,
"SELECT * FROM distributions WHERE ngo_id='$ngo_id'"));

$recentRequests = mysqli_query($conn,
"SELECT created_at, medicine_name, quantity, status
 FROM requests
 ORDER BY id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html>
<head>
<title>NGO Dashboard</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}

/* DASHBOARD LAYOUT */

.dashboard{
display:flex;
min-height:80vh;
}

/* SIDEBAR */

.sidebar{
width:250px;
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

.header-text{
font-size:22px;
margin-bottom:20px;
}

/* CARDS */

.cards{
display:grid;
grid-template-columns:repeat(4,1fr);
gap:20px;
margin-bottom:30px;
}

.card{
background:white;
padding:20px;
border-radius:10px;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
text-align:center;
}

.card h3{
margin:10px 0;
font-size:26px;
}

.card p{
margin:0;
font-weight:bold;
color:#555;
}

.card.donation{border-left:6px solid #4e73df;}
.card.pending{border-left:6px solid #e74a3b;}
.card.approved{border-left:6px solid #1cc88a;}
.card.distributed{border-left:6px solid #858796;}

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

<!-- MAIN CONTENT -->

<div class="main">

<div class="header-text">
Welcome, <?php echo $user['name']; ?> 👋
</div>

<!-- CARDS -->

<div class="cards">

<div class="card donation">
<h3><?php echo $totalDonations; ?></h3>
<p>Total Donations Received</p>
</div>

<div class="card pending">
<h3><?php echo $pendingRequests; ?></h3>
<p>Pending Requests</p>
</div>

<div class="card approved">
<h3><?php echo $approvedRequests; ?></h3>
<p>Approved Requests</p>
</div>

<div class="card distributed">
<h3><?php echo $distributed; ?></h3>
<p>Medicines Distributed</p>
</div>

</div>

<!-- RECENT REQUESTS -->

<h3>Recent Requests</h3>

<table>

<tr>
<th>Date</th>
<th>Medicine</th>
<th>Qty</th>
<th>Status</th>
</tr>

<?php
if(mysqli_num_rows($recentRequests)>0){
while($row=mysqli_fetch_assoc($recentRequests)){
?>

<tr>

<td><?php echo $row['created_at']; ?></td>

<td><?php echo $row['medicine_name']; ?></td>

<td><?php echo $row['quantity']; ?></td>

<td><?php echo $row['status']; ?></td>

</tr>

<?php
}
}else{
echo "<tr><td colspan='4'>No Requests Found</td></tr>";
}
?>

</table>

</div>

</div>

<?php include("footer.php"); ?>

</body>
</html>