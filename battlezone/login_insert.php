
<?php
session_start();
include("connection.php");
$error = "";

if(isset($_POST['login'])){
    $email=$_POST["email"];
    $password=$_POST["password"];
 $sql="SELECT * FROM users WHERE email='$email'";
$result=mysqli_query($conn,$sql);
if(mysqli_num_rows($result)>0){
    $user =mysqli_fetch_assoc($result);
    if(password_verify($password,$user['password'])){
$_SESSION['user_id']=$user['id'];
$_SESSION['email']=$user['email'];
$_SESSION['fullname']=$user['Fullname'];
$_SESSION['logged_in']=true;
header("Location:home.php");
exit();
    }
    else {
        $_SESSION['error'] = "Incorrect password.";
        header("location:login.php");
        exit();
    }
}else{
    $_SESSION['error'] = "No user found with that email.";
    header("location:login.php");
    exit();

}

}
?>
