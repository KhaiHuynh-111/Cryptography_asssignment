<?php
      session_start();
      if(isset($_SESSION["id"])) {
          header("Location:validation.php");
      }
?> 
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

    <title>Login</title>
</head>
<body>
  
  <nav class="navbar navbar-light bg-primary">
    <div class="container-fluid">
      <a class="navbar-brand text-white" href="#">IAM - Identity and Access Management</a>
    </div>
  </nav>

  <div class="container">
    <h1 class="new-user-title">Login</h1>

    <form action="validation.php" method="POST">
      <div class="mb-3">
        <label for="username" class="form-label">Username</label>
        <input name="username" class="form-control" id="email" aria-describedby="emailHelp">
        <div id="emailHelp" class="form-text">We'll never share your username with anyone else.</div>
      </div>

      <div class="mb-3">
        <label for="password" class="form-label">Password</label>
        <input name="password" type="password" class="form-control" id="password">
      </div>

      <div class="mb-3 form-check">
        <input name="check" type="checkbox" class="form-check-input" id="check">
        <label class="form-check-label" for="check">Check me out</label>
      </div>
      
      <!-- <input type="submit" class="btn btn-primary" name="login" value="Submit"> -->
      <button name="login" type="submit" class="btn btn-primary">Submit</button>
    </form>
  </div>
  

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>
</html>