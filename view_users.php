<?php
session_start();
include("db.php");

// Admin session check
if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Delete user
if(isset($_GET['delete'])){
    $id = $_GET['delete'];
    mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='User'");
    header("Location: view_users.php");
    exit();
}

$result = mysqli_query($conn, "SELECT * FROM users WHERE role='User'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>View Users - MedBridge</title>

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

.delete-btn{
    background:#e74a3b;
    color:white;
    padding:6px 12px;
    text-decoration:none;
    border-radius:5px;
}

.delete-btn:hover{
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
    <h2>All Registered Users</h2>
</div>

<div class="container">

<a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Status</th>
    <th>Created At</th>
    <th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($result)) { ?>

<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['verification_status']; ?></td>
    <td><?php echo $row['created_at']; ?></td>
    <td>
        <a class="delete-btn"
           href="view_users.php?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure to delete this user?')">
           Delete
        </a>
    </td>
</tr>

<?php } ?>

</table>

</div>

</body>
</html>