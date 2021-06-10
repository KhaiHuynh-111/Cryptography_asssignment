

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">

    <style>
        nav {
          height: 8vh;
        }
  
        .container {
          width: 30%;
          height: 92vh;
        }
  
        button {
          width: 100%;
        }
      </style>

    <title>Edit</title>
</head>
<body>
<?php
    session_start();
    include 'config.php';
    if(isset($_SESSION['role'])){
        $role = $_SESSION['role'];
        if ($role == 'user'){
            echo "<script> alert(\"You dont have pemission to do this task\")</script>";
            header("Refresh:0,url=detailInformation.php");
        }
        else if ($role == 'manager'){
            echo "<script> alert(\"You dont have pemission to do this task\")</script>";
            header("Refresh:0,url=home.php");
        }

    }
    if(isset($_GET['uid'])){
        $username = $_GET['uid'];
        // $role_arr = array("user", "manager", "admin");
        $role = $_GET['role'];   
        
        $search_dn = "uid=".$username.",ou=".$role.",ou=system";    
        $filter = "(cn=*)";
        $sr = @ldap_search($ldap_con, $search_dn, $filter);                        
        if($sr){
            $res = ldap_get_entries($ldap_con, $sr);
        }
    }

    if(isset($_POST['edit-user'])){
        // $entry['uid'] = $_POST['edit-uid'];
        // $entry['use'] = $_POST['edit-password'];
        $entry['cn'] = $_POST['edit-fname'];
        $entry['sn'] = $_POST['edit-lname'];
        $entry['mail'] = $_POST['edit-mail'];
        $entry['mobile'] = $_POST['edit-phone'];
        
        // add attribute when its not exist, replace with new value
        $ldap_dn = "uid=".$_POST['edit-uid'].",ou=".$_POST['edit-role'].",ou=system";
        $res = ldap_mod_replace($ldap_con, $ldap_dn, $entry);
        if($res)
            echo "Replace successful!";
        else
            echo "Replace Failed!";
    }
?>
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

    <div class="container">
        <h1 class="new-user-title">Edit user</h1>
    
        <form method="POST" class="new-user">
            <div class="mb-3">
                <label for="user-id" class="form-label">User ID</label>
                <input name="edit-uid" type="text" class="form-control" id="user-id" value="<?php echo $res[0]['uid'][0];?>" readonly>
            </div>
    
            <div class="mb-3">
                <label for="role" class="form-label">Role</label>
                <input name="edit-role" type="text" class="form-control" id="role" value="<?php echo $role;?>" readonly>
            </div>
    
            <div class="mb-3 row">
                <div class="col">
                    <label for="first-name" class="form-label">First name</label>
                    <input name="edit-fname" type="text" class="form-control" id="first-name" aria-label="First name" value="<?php echo @$res[0]['cn'][0];?>">
                </div>
    
                <div class="col">
                    <label for="last-name" class="form-label">Last name</label>
                    <input name="edit-lname" type="text" class="form-control" id="last-name" aria-label="Last name" value="<?php echo @$res[0]['sn'][0];?>">
                </div>
            </div>
    
            <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input name="edit-mail" type="email" class="form-control" id="email" value="<?php echo @$res[0]['mail'][0];?>">
            </div>
    
            <div class="mb-3">
                <label for="phone" class="form-label">Phone no.</label>
                <input name="edit-phone" type="text" class="form-control" id="phone"  value="<?php echo @$res[0]['mobile'][0];?>">
            </div>
    
            <button type="submit" class="btn btn-primary" style="width: 100%" name="edit-user">Submit</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>