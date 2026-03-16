<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

include("db.php");

if(!isset($_SESSION['admin'])){
    header("Location: login.php");
    exit();
}

// Delete NGO
if(isset($_GET['delete'])){
    $id = $_GET['delete'];

    // Optional: Delete certificate file also
    $getFile = mysqli_query($conn, "SELECT certificate_path FROM users WHERE id='$id'");
    $fileRow = mysqli_fetch_assoc($getFile);

    if($fileRow && file_exists($fileRow['certificate_path'])){
        unlink($fileRow['certificate_path']);
    }

    mysqli_query($conn, "DELETE FROM users WHERE id='$id' AND role='NGO'");
    header("Location: view_ngos.php");
    exit();
}

// Fetch only Approved NGOs
$result = mysqli_query($conn,
"SELECT * FROM users 
 WHERE role='NGO' 
 AND verification_status='Approved'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Approved NGOs - MedBridge</title>

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

.status-approved{
    color:green;
    font-weight:bold;
}

.view-btn{
    background:#36b9cc;
    color:white;
    padding:6px 12px;
    text-decoration:none;
    border-radius:5px;
}

.view-btn:hover{
    background:#2c9faf;
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
    <h2>Approved NGOs</h2>
</div>

<div class="container">

<a href="admin_dashboard.php" class="back-btn">⬅ Back to Dashboard</a>

<table>
<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Registration No.</th>
    <th>Certificate</th>
    <th>Status</th>
    <th>Action</th>
</tr>

<?php if(mysqli_num_rows($result)>0){ ?>
<?php while($row=mysqli_fetch_assoc($result)){ ?>
<tr>
    <td><?php echo $row['id']; ?></td>
    <td><?php echo $row['name']; ?></td>
    <td><?php echo $row['email']; ?></td>
    <td><?php echo $row['phone']; ?></td>
    <td><?php echo $row['registration_number']; ?></td>

    <td>
        <?php if($row['certificate_path']){ ?>
            <a class="view-btn" href="<?php echo $row['certificate_path']; ?>" target="_blank">View</a>
        <?php } else { echo "No File"; } ?>
    </td>

    <td class="status-approved">Approved</td>

    <td>
        <a class="delete-btn"
           href="view_ngos.php?delete=<?php echo $row['id']; ?>"
           onclick="return confirm('Are you sure to delete this NGO?')">
           Delete
        </a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr><td colspan="8">No Approved NGOs Found</td></tr>
<?php } ?>

</table>

</div>
</body>
</html>