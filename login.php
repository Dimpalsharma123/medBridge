<?php
session_start();
include("db.php");

if(isset($_POST['login'])){

    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Static Admin
    if(strtolower($email) === "dimpal123@gmail.com" && $password === "dimpal123"){
        $_SESSION['admin'] = "Admin";
        header("Location: admin_dashboard.php");
        exit();
    }

    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn,$sql);

    if(mysqli_num_rows($result) > 0){

        $row = mysqli_fetch_assoc($result);

        if(password_verify($password, $row['password'])){

            if($row['role'] == "NGO" && $row['verification_status'] != "Approved"){
                echo "<script>alert('Wait for Admin Approval');</script>";
                exit();
            }

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['role'] = $row['role'];

            if($row['role'] == "User"){
                header("Location: user_dashboard.php");
            } elseif($row['role'] == "NGO"){
                header("Location: ngo_dashboard.php");
            }

            exit();

        } else {
            echo "<script>alert('Invalid Password');</script>";
        }

    } else {
        echo "<script>alert('User Not Found');</script>";
    }
}
?>

<?php include("header.php"); ?>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>

.login-section{
display:flex;
justify-content:center;
align-items:center;
min-height:80vh;
background: linear-gradient(135deg, #4e73df, #1cc88a);
}

.login-box{
background:white;
padding:40px;
width:350px;
border-radius:10px;
box-shadow:0 10px 25px rgba(0,0,0,0.2);
text-align:center;
}

.login-box h2{
margin-bottom:20px;
color:#333;
}

.login-box input{
width:100%;
padding:10px;
margin:10px 0;
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

.toggle-password:hover{
color:#000;
}

.login-box button{
width:100%;
padding:10px;
background:#4e73df;
color:white;
border:none;
border-radius:5px;
cursor:pointer;
font-weight:bold;
margin-top:10px;
}

.login-box button:hover{
background:#2e59d9;
}

.register-link{
margin-top:15px;
}

.register-link a{
text-decoration:none;
color:#1cc88a;
font-weight:bold;
}

.register-link a:hover{
text-decoration:underline;
}

</style>

<div class="login-section">

<div class="login-box">

<h2>MedBridge Login</h2>

<form method="POST">

<input type="email" name="email" placeholder="Email" required>

<div class="password-container">

<input type="password" name="password" id="password" placeholder="Password" required>

<span class="toggle-password" onclick="togglePassword()">
<i class="fa-solid fa-eye-slash" id="eyeIcon"></i>
</span>

</div>

<button type="submit" name="login">Login</button>

</form>

<div class="register-link">
Don't have an account? <a href="register.php">Register Here</a>
</div>

</div>

</div>

<script>

function togglePassword() {

var passwordField = document.getElementById("password");
var eyeIcon = document.getElementById("eyeIcon");

if (passwordField.type === "password") {

passwordField.type = "text";
eyeIcon.classList.remove("fa-eye-slash");
eyeIcon.classList.add("fa-eye");

} else {

passwordField.type = "password";
eyeIcon.classList.remove("fa-eye");
eyeIcon.classList.add("fa-eye-slash");

}

}

</script>

<?php include("footer.php"); ?>