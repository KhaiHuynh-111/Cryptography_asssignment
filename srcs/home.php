<?php
    session_start();
    include 'config.php';
    if(isset($_POST['login']))
    {    
        if(!isset($_SESSION['id'])){        
            $username = $_POST['username']; // Get username
            $password = $_POST['password']; // get password       
            //dn: distinguish name
            //uid: user id
            //ou: organization unit
        
            // role 
            // admin: lam gi cung duoc
            // manager: chi coi duoc user detail vaf user log, them bot user k dc
            // user: chi coi duoc thong tin cua chinh user
            $role_arr = array("user", "manager", "admin");
            $role = "";
                
            for($i = 0; $i < count($role_arr); $i++){
                $ldap_dn = "uid=".$username.",ou=".$role_arr[$i].",ou=system";            
                $ldap_password = $password;    

                if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)){
                    $role = $role_arr[$i];                
                    break;
                }
            }

            //admin login
            if($role ==""){
                $ldap_dn = "uid=".$username.",ou=system";
                if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)){
                    echo "Inside";
                    $role = "admin";
                    echo "<br>abc".$role;
                }
            }           
        
            //     echo "Bind Successful!" . "<br>";
            // }
            // else {
            //     echo "Invalid user/pass or other error!";
            // }     
            
            // if user inputs match if condition will runn
            if ($role != ""){
                $_SESSION['login'] = $username; // hold the user name in session
                $_SESSION['userDn'] = $ldap_dn; // hold the user dn
                $_SESSION['role'] = $role;            
                $_SESSION['id'] = session_id(); // get the current session_id

                
                // $num['id']; // hold the user id in session
                
                $userId_db = $_SESSION['id'];
                $username_db = $_SESSION['login'];
                $uip = $_SERVER['REMOTE_ADDR']; // get the user ip
                $action = "Login"; // query for inser user log in to data base
                $log_query = "INSERT into userlog(userId,username,userIp,action) values('$userId_db','$username_db','$uip','$action')";
                if(!mysqli_query($db_con, $log_query))
                    echo "Cant Insert user log!";         
            }
            // If the userinput no matched with database else condition will run
            else
            {
                $_SESSION['msg'] = "Invalid username or password";
                $extra = "login.php";
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
                header("location:http://$host$uri/$extra");
                exit();
            }
        }
    }
    else if(isset($_POST['add-user'])){
        if(isset($_SESSION['id'])){
            $uid_add = $_POST['user-id'];
            $userPassword_add = $_POST['password'];
            $cn_add = $_POST['fname'];
            $sn_add = $_POST['lname'];
            $mail_add = $_POST['email'];
            $mobile_add = $_POST['phone'];

            //prepare data
            $info["objectClass"] = "inetOrgPerson";
            $info["cn"] = $cn_add;
            $info["sn"] = $sn_add;
            $info["mail"] = $mail_add;
            $info["mobile"] = $mobile_add;
            $info["userPassword"] = $userPassword_add;
            $info["uid"] = $uid_add;        
        
            // add data to directory     
            $new_userdn = "uid=".$uid_add.",ou=user,ou=system";       
            $ldap_res = ldap_add($ldap_con, $new_userdn, $info);       
        }
        
    }
    else{
        $extra = "login.php";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
        header("location:http://$host$uri/$extra");
        exit();        
    }
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
        </div>
    </nav>

    <div class="container m-0">
        <div class="row p-0">
            <!-- Left -->
            <div class="col-3 bg-light p-0">
                <!-- ---------- Analysis ---------- -->

                <h1>Analysis</h1>

                <h6 class="users m-0 p-3 q">
                    <i class="fas fa-users"></i>
                    <spap>Users</span>
                </h6>

                <h6 class="logs m-0 p-3">
                    <i class="fas fa-pen-square"></i>
                    <span>Logs</span>
                </h6>

                <!-- ---------- Manage ---------- -->

                <h1>Manage</h1>

                <h6 class="new-user m-0 p-3 q">
                    <i class="fas fa-user-plus"></i>
                    <spap>Add new user</span>
                </h6>
            </div>

            <!-- Right -->
            <div class="col-9">
                <!-- ---------- Users table ---------- -->
                <h1 class="users-title">Users</h1>

                <table class="users table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th></th>
                        <th></th>
                    </tr>

                    <!-- query user detail - ldap -->
                    <?php
                
                    $role_arr = array("user", "manager", "admin");                    
                    $search_dn = "";
                    $filter = "(cn=*)";
                    for($i = 0; $i < count($role_arr); $i++){                        
                        $search_dn = "ou=".$role_arr[$i].",ou=system";
                        // $justthese = array("username", "cn", "mail");
                        // ldap_bind($ldap_con);
                        $sr = ldap_search($ldap_con, $search_dn, $filter);                        
                        $res = ldap_get_entries($ldap_con, $sr);
                        
                        for($j = 0; $j < $res['count']; $j++){
                            $id = $res[$j]['uid'][0];
                            $name = $res[$j]['sn'][0];
                            $email = @$res[$j]['mail'][0] or "";
                            echo "<tr><td>".$id."</td>";
                            echo "<td>".$name."</td>";
                            echo "<td>".$email."</td>";
                            echo "<td><a href=\"edit.php\" class=\"btn btn-primary\">Edit</a></td>";
                            echo "<td><a href=\"#\" class=\"btn btn-danger\">Delete</a></td></tr>";
                        }
                    }
                    ?>
                    
                </table>

                <!-- ---------- Logs table ---------- -->

                <h1 class="logs-title">Logs</h1>

                <table class="logs table table-hover">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>

                    <!-- query userlog - mysql-->
                    <?php
                        $query = "SELECT * from userlog order by loginTime desc";
                        $res = mysqli_query($db_con, $query);
                        if(!$res)
                            echo "Cant query";
                        else{
                            $row = mysqli_fetch_assoc($res);                            
                            while($row){
                                echo "<tr><td>{$row['userId']}</td>";
                                echo "<td>{$row['username']}</td>";
                                echo "<td>{$row['loginTime']}</td>";
                                echo "<td>{$row['action']}</td></tr>";
                                $row = mysqli_fetch_assoc($res);
                            }                           
                        }                                            
                    ?>
                </table>

                <!-- ---------- Add user form ---------- -->

                <h1 class="new-user-title">Add new user</h1>

                <form action="" method="POST" class="new-user">
                    <div class="mb-3">
                        <label for="user-id" class="form-label">User ID</label>
                        <input  name="user-id" type="text" class="form-control" id="user-id">
                    </div>

                    <div class="mb-3">
                      <label for="password" class="form-label">Password</label>
                      <input name="password" type="password" class="form-control" id="password">
                    </div>

                    <div class="mb-3 row">
                        <div class="col">
                          <label for="first-name" class="form-label">First name</label>
                          <input name="fname" type="text" class="form-control" id="first-name" aria-label="First name">
                        </div>

                        <div class="col">
                          <label for="last-name" class="form-label">Last name</label>
                          <input name="lname" type="text" class="form-control" id="last-name" aria-label="Last name">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email address</label>
                        <input name="email" type="email" class="form-control" id="email">
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">Phone no.</label>
                        <input name="phone" type="text" class="form-control" id="phone">
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%" name="add-user">Submit</button>
                </form>
            </div>
        </div>
    </div>

    <script src="home.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>