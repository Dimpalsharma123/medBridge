<?php
error_reporting(E_ALL);
ini_set('display_errors',1);

session_start();
include("db.php");

/* ADMIN CHECK */

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}


/* APPROVE REQUEST */

if(isset($_GET['approve'])){

$id = $_GET['approve'];

mysqli_query($conn,
"UPDATE requests SET status='Approved' WHERE id='$id'");

header("Location: view_requests.php");
exit();

}


/* REJECT REQUEST */

if(isset($_GET['reject'])){

$id = $_GET['reject'];

mysqli_query($conn,
"UPDATE requests SET status='Rejected' WHERE id='$id'");

header("Location: view_requests.php");
exit();

}


/* FETCH REQUESTS */

$result = mysqli_query($conn,

"SELECT requests.*, users.name

FROM requests

JOIN users ON requests.requester_id = users.id

ORDER BY requests.created_at DESC"

);

?>


<!DOCTYPE html>
<html>
<head>

<title>Admin - Medicine Requests</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4f6f9;
}


/* HEADER */

.header{
background:#4e73df;
color:white;
padding:15px;
text-align:center;
}


/* PAGE CONTAINER */

.container{
padding:30px;
}


/* TABLE */

table{
width:100%;
border-collapse:collapse;
background:white;
box-shadow:0 5px 15px rgba(0,0,0,0.1);
}

th, td{
padding:12px;
text-align:center;
border-bottom:1px solid #ddd;
}

th{
background:#1cc88a;
color:white;
}

tr:hover{
background:#f1f1f1;
}


/* BUTTONS */

.approve-btn{
background:#1cc88a;
color:white;
padding:6px 12px;
text-decoration:none;
border-radius:5px;
}

.approve-btn:hover{
background:#17a673;
}

.reject-btn{
background:#e74a3b;
color:white;
padding:6px 12px;
text-decoration:none;
border-radius:5px;
}

.reject-btn:hover{
background:#c0392b;
}

.back-btn{
display:inline-block;
margin-bottom:15px;
background:#858796;
color:white;
padding:8px 15px;
text-decoration:none;
border-radius:5px;
}

.back-btn:hover{
background:#5a5c69;
}

</style>

</head>

<body>

<div class="header">
<h2>Medicine Requests</h2>
</div>


<div class="container">

<a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>


<table>

<tr>
<th>ID</th>
<th>User</th>
<th>Medicine</th>
<th>Quantity</th>
<th>Prescription</th>
<th>Status</th>
<th>Action</th>
</tr>


<?php

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

?>

<tr>

<td><?php echo $row['id']; ?></td>

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

<?php if($row['status']=="Pending"){ ?>

<a class="approve-btn"
href="view_requests.php?approve=<?php echo $row['id']; ?>"
onclick="return confirm('Approve this request?')">
Approve
</a>

<a class="reject-btn"
href="view_requests.php?reject=<?php echo $row['id']; ?>"
onclick="return confirm('Reject this request?')">
Reject
</a>

<?php } else {

echo $row['status'];

} ?>

</td>

</tr>

<?php
}

}else{
?>

<tr>
<td colspan="7">No Requests Found</td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>