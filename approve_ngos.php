<?php
session_start();
include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

if(isset($_GET['approve'])){
    $id = $_GET['approve'];
    mysqli_query($conn, "UPDATE users SET verification_status='Approved' WHERE id='$id'");
    header("Location: approve_ngos.php");
    exit();
}

if(isset($_GET['reject'])){
    $id = $_GET['reject'];
    mysqli_query($conn, "UPDATE users SET verification_status='Rejected' WHERE id='$id'");
    header("Location: approve_ngos.php");
    exit();
}

$result = mysqli_query($conn,
"SELECT * FROM users 
 WHERE role='NGO' 
 AND verification_status='Pending'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Approve NGO Requests</title>

<style>
body{
    margin:0;
    font-family: Arial;
    background:#f4f6f9;
}

.header{
    background:#4e73df;
    color:white;
    padding:15px;
    text-align:center;
}

.container{
    padding:30px;
}

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
    <h2>Pending NGO Requests</h2>
</div>

<div class="container">

<a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Registration No.</th>
    <th>Certificate</th>
    <th>Action</th>
</tr>

<?php if(mysqli_num_rows($result)>0){ ?>
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['registration_number']; ?></td>
    <td>
        <a href="<?php echo $row['certificate_path']; ?>" target="_blank">View</a>
    </td>
    <td>
        <a class="approve-btn"
           href="?approve=<?php echo $row['id']; ?>"
           onclick="return confirm('Approve this NGO?')">
           Approve
        </a>

        <a class="reject-btn"
           href="?reject=<?php echo $row['id']; ?>"
           onclick="return confirm('Reject this NGO?')">
           Reject
        </a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="6">No Pending Requests</td></tr>
<?php } ?>

</table>

</div>
</body>
</html>