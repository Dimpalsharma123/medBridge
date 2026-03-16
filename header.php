<!DOCTYPE html>
<html>
<head>
<title>MedBridge</title>

<style>

body{
margin:0;
font-family:Arial, sans-serif;
background:#f4f6f9;
}

/* HEADER */
.header{
display:flex;
background:#f4f6f9;
justify-content:space-between;
align-items:center;
padding:15px 40px;
background:white;
box-shadow:0 2px 8px rgba(0,0,0,0.1);
}

/* LOGO */
.logo{
font-size:22px;
font-weight:bold;
color:#1cc88a;
}

/* NAVIGATION */
.nav{
display:flex;
align-items:center;
}

.nav a{
margin-left:25px;
text-decoration:none;
color:#333;
font-weight:bold;
position:relative;
}

.nav a:hover{
color:#1cc88a;
}

/* DROPDOWN CONTAINER */
.dropdown{
position:relative;
display:inline-block;
}

/* DROPDOWN MENU */
.dropdown-content{
display:none;
position:absolute;
top:30px;
left:0;
background:white;
min-width:320px;
padding:20px;
box-shadow:0 6px 20px rgba(0,0,0,0.15);
border-radius:6px;
z-index:100;
}

/* SHOW DROPDOWN */
.dropdown:hover .dropdown-content{
display:block;
}

/* DROPDOWN TEXT */
.dropdown-content p{
margin:8px 0;
font-size:14px;
color:#444;
}

.ngo-item{
padding:6px 0;
border-bottom:1px solid #eee;
}

.ngo-item:last-child{
border-bottom:none;
}

</style>

</head>

<body>

<div class="header">

<div class="logo">
💊 MedBridge
</div>

<div class="nav">

<a href="index.php">Home</a>


<!-- HOW IT WORKS DROPDOWN -->

<div class="dropdown">

<a href="#">How it Works </a>

<div class="dropdown-content">

<h3>How MedBridge Creates Impact</h3>

<p><b>💊 Turn Unused Medicines Into Hope</b><br>
Many households have medicines that go unused. MedBridge allows you to donate them so they can help someone in need instead of being wasted.</p>

<p><b>🤝 Connect Donors With People in Need</b><br>
Our platform connects donors, NGOs, and beneficiaries to create a transparent medicine-sharing network.</p>

<p><b>🔍 Verified & Safe Process</b><br>
All NGOs and medicine requests are verified by the admin to ensure safety and authenticity.</p>

<p><b>🚚 Responsible Distribution</b><br>
Trusted NGOs collect donated medicines and distribute them to beneficiaries who need them the most.</p>

<p><b>❤️ Every Donation Makes a Difference</b><br>
A small contribution from you can help save lives and support communities.</p>

</div>

</div>


<!-- NGO DROPDOWN -->

<div class="dropdown">

<a href="#">NGOs</a>

<div class="dropdown-content">

<?php
include("db.php");

$sql="SELECT name FROM users WHERE role='NGO' AND verification_status='Approved'";
$result=mysqli_query($conn,$sql);

if(mysqli_num_rows($result)>0){

while($row=mysqli_fetch_assoc($result)){

echo "<div class='ngo-item'>".$row['name']."</div>";

}

}else{

echo "No NGOs Available";

}
?>

</div>

</div>


<a href="login.php">Login</a>

</div>

</div>