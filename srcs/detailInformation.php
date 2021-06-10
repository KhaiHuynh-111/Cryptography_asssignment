<?php
    session_start();
    include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        nav {
            height: 8vh;
        }

        .row:first-of-type {
            height: 92vh;
        }

        h6:hover {
            background-color: lightgray;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }
    </style>

    <title>Home</title>
</head>
<body>    
    <nav class="navbar navbar-light bg-primary">
        <div class="container-fluid">
          <a class="navbar-brand text-white" href="#">IAM - Identity and Access Management</a>
            <?php 
                if(isset($_SESSION['login'])){?>
                    <!-- <a class="nav-item nav-link text-white" href="#">Hello </a> -->
                    <a class="nav-item nav-link text-white" href="logout.php">Logout</a>
            <?php
                }
                else {
                    ?>
                    <a class="nav-item nav-link text-white" href="login.php">Login</a>
                    <?php                    
                }
            ?>
        </div>        
    </nav>

    <div class="container m-0">
        <div class="row p-0">
            <!-- Left -->
            <div class="col-3 bg-light p-0">
                <!-- ---------- Analysis ---------- -->

                <h1>Profile</h1>

                <h6 class="users m-0 p-3 q">
                    <i class="fas fa-users"></i>
                    <spap>Users</span>
                </h6>

            </div>

            <!-- Right -->
            <div class="col-9">
                <!-- ---------- Users table ---------- -->
                <h1 class="users-title">Users Information</h1>

                <table class="users table table-hover">

                    <!-- query user detail - ldap -->
                    <?php                       
                        
                        $username = $_SESSION['login'];
                       
                        $role = $_SESSION['role'];   
                        
                        $search_dn = "uid=".$username.",ou=".$role.",ou=system";    
                        $filter = "(cn=*)";
                        $sr = @ldap_search($ldap_con, $search_dn, $filter);                        
                        if($sr){
                            $res = ldap_get_entries($ldap_con, $sr);

                            echo 
                            "
                                <tr><td>First Name</td><td>".@$res[0]['cn'][0]."</td><tr>
                                <tr><td>Last Name</td><td>".@$res[0]['sn'][0]."</td><tr>
                                <tr><td>Email</td><td>".@$res[0]['mail'][0]."</td><tr>
                                <tr><td>Phone</td><td>".@$res[0]['mobile'][0]."</td><tr>
                            "
                                // <tr><a href=\"edit.php?uid=".$id."&role=".$role_arr[$i]."\" class=\"btn btn-primary\">Edit</a></tr>
                            ;
                        }
                    ?>
                    
                </table>
            </div>  
    </div>

    <script src="home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>