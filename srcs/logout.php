<?php
session_start();
include('config.php');

// Getting logout time in db
$username=$_SESSION['login']; // hold the user name in session
$uid=$_SESSION['id']; // hold the user id in session
$uip=$_SERVER['REMOTE_ADDR']; // get the user ip
$role=$_SESSION['role'];
$action="Logout";
// query for inser user log in to data base
$query=mysqli_query($db_con,"insert into userlog(userId,username,role,userIp,action) values('$uid','$username','$role','$uip','$action')");

if($query){
    // session_unset();
    session_destroy();
    ldap_close($ldap_con);
}

$_SESSION['msg']="You have logged out successfully..!";
?>


<script language="javascript">
    document.location="login.php";
</script>