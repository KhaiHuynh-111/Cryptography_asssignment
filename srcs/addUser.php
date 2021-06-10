<?php
    session_start();
    include 'config.php';
    
    //check role
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
    //check new user info

    if(isset($_POST['add-user'])){
        $entry['objectClass'] = "inetOrgPerson";
        $entry['uid'] = $_POST['user-id'];
        $entry['userpassword'] = $_POST['password'];
        $entry['cn'] = $_POST['fname'];
        $entry['sn'] = $_POST['lname'];
        $entry['mail'] = $_POST['email'];
        $entry['mobile'] = $_POST['phone'];
         
        //ldap_add ( resource $ldap , string $dn , array $entry , array|null $controls = null ) : bool
        $add_dn = "uid=".$_POST['user-id'].",ou=user,ou=system";
        if(ldap_add($ldap_con, $add_dn, $entry)){
            $extra = "home.php";
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
            header("location:http://$host$uri/$extra");
            exit(); 
        }
    }
?>