<?php
    session_start();
    include 'config.php';
    // check role
    if(isset($_SESSION['role'])){
        $role = $_SESSION['role'];
        if ($role == 'user'){
            echo "<script> alert(\"You dont have pemission to do this task\")</script>";
            header("Refresh:0,url=detailInformation.php");
            exit();
        }
        else if ($role == 'manager'){
            echo "<script> alert(\"You dont have pemission to do this task\")</script>";
            header("Refresh:0,url=home.php");
            exit();
        }
    }

    // find user then delete
    if(isset($_GET['uid'])){
        $del_uid = $_GET['uid'];
        $del_role = $_GET['role'];
    }
    // ldap_delete ( resource $ldap , string $dn , array|null $controls = null ) : bool
    $ldap_dn = "uid=".$del_uid.",ou=".$del_role.",ou=system";
    if(ldap_delete($ldap_con, $ldap_dn)){
        $extra = "home.php";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
        header("location:http://$host$uri/$extra");
        exit(); 
    }
    else{}
?>