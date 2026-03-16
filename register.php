<?php
session_start();
include("db.php");

if(isset($_POST['register'])){

    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $role = $_POST['role'];

    if($password !== $confirm_password){
        echo "<script>alert('Passwords do not match');</script>";
    } else {

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $registration_number = NULL;
        $certificate_path = NULL;

        if($role == "NGO"){

            $registration_number = $_POST['registration_number'];

            $targetDir = "uploads/";
            if(!is_dir($targetDir)){
                mkdir($targetDir);
            }

            $fileName = time() . "_" . $_FILES["certificate"]["name"];
            $targetFile = $targetDir . $fileName;

            move_uploaded_file($_FILES["certificate"]["tmp_name"], $targetFile);

            $certificate_path = $targetFile;
            $verification_status = "Pending";

        } else {
            $verification_status = "Approved";
        }

        $sql = "INSERT INTO users 
        (name,email,phone,password,role,verification_status,registration_number,certificate_path)
        VALUES 
        ('$name','$email','$phone','$hashed_password','$role','$verification_status','$registration_number','$certificate_path')";

        if(mysqli_query($conn,$sql)){
            echo "<script>alert('Registration Successful'); window.location='login.php';</script>";
        } else {
            echo "<script>alert('Email already exists');</script>";
        }
    }
}
?>

<?php include("header.php"); ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

.register-section{
display:flex;
justify-content:center;
align-items:center;
min-height:80vh;
background: linear-gradient(135deg,#1cc88a,#4e73df);
}

.register-box{
background:white;
padding:35px;
width:380px;
border-radius:10px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
text-align:center;
}

.register-box h2{
margin-bottom:15px;
}

.register-box input,
.register-box select{
width:100%;
padding:10px;
margin:8px 0;
border:1px solid #ccc;
border-radius:5px;
}

.password-container{
position:relative;
}

.password-container input{
padding-right:40px;
}

.toggle-password{
position:absolute;
right:12px;
top:50%;
transform:translateY(-50%);
cursor:pointer;
color:#666;
}

.register-box button{
width:100%;
padding:10px;
background:#4e73df;
color:white;
border:none;
border-radius:5px;
margin-top:10px;
font-weight:bold;
cursor:pointer;
}

.register-box button:hover{
background:#2e59d9;
}

.login-link{
margin-top:12px;
}

.login-link a{
color:#1cc88a;
font-weight:bold;
text-decoration:none;
}

.login-link a:hover{
text-decoration:underline;
}

</style>


<div class="register-section">

<div class="register-box">

<h2>MedBridge Registration</h2>

<form method="POST" enctype="multipart/form-data">

<input type="text" name="name" placeholder="Full Name / NGO Name" required>

<input type="email" name="email" placeholder="User Email / NGO Email" required>

<input type="text" name="phone" placeholder="Phone number" required>

<select name="role" id="role" required onchange="toggleNGOFields()">
<option value="">Select Role</option>
<option value="User">User</option>
<option value="NGO">NGO</option>
</select>

<input type="text" name="registration_number" id="registration_number"
placeholder="Registration Number (for NGO)" disabled>

<input type="file" name="certificate" id="certificate" disabled>

<div class="password-container">
<input type="password" name="password" id="password" placeholder="Password" required>

<span class="toggle-password" onclick="togglePassword('password','eye1')">
<i class="fa-solid fa-eye-slash" id="eye1"></i>
</span>
</div>

<div class="password-container">
<input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm Password" required>

<span class="toggle-password" onclick="togglePassword('confirm_password','eye2')">
<i class="fa-solid fa-eye-slash" id="eye2"></i>
</span>
</div>

<button type="submit" name="register">Register</button>

</form>

<div class="login-link">
Already have an account? <a href="login.php">Login Here</a>
</div>

</div>

</div>


<script>

function togglePassword(fieldId, iconId){

var field=document.getElementById(fieldId);
var icon=document.getElementById(iconId);

if(field.type==="password"){
field.type="text";
icon.classList.remove("fa-eye-slash");
icon.classList.add("fa-eye");
}else{
field.type="password";
icon.classList.remove("fa-eye");
icon.classList.add("fa-eye-slash");
}

}

function toggleNGOFields(){

var role=document.getElementById("role").value;
var regField=document.getElementById("registration_number");
var certField=document.getElementById("certificate");

if(role==="NGO"){

regField.disabled=false;
certField.disabled=false;
regField.required=true;
certField.required=true;

}else{

regField.disabled=true;
certField.disabled=true;
regField.required=false;
certField.required=false;

}

}

</script>

<?php include("footer.php"); ?><?php

