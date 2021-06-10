<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://unpkg.com/tailwindcss@^1.0/dist/tailwind.min.css" rel="stylesheet">
    <title>Validation</title>
</head>
<body>
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
                if (@ldap_bind($ldap_con, $ldap_dn, $ldap_password)){
                    $role = $role_arr[$i];                
                    break;
                }
            }

            //admin login
            // if($role ==""){
            //     $ldap_dn = "uid=".$username.",ou=system";
            //     if (ldap_bind($ldap_con, $ldap_dn, $ldap_password)){
            //         echo "Inside";
            //         $role = "admin";
            //         echo "<br>abc".$role;
            //     }
            // }           
        
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
                

                //send email and save to database

                //get mail
                $search_dn = "uid=".$username.",ou=".$role.",ou=system";    
                $filter = "(cn=*)";
                $sr = @ldap_search($ldap_con, $search_dn, $filter);                        
                if($sr){
                    $res = ldap_get_entries($ldap_con, $sr);
                }
                
                // $email_send = @$res[0]['mail'][0];
                $email_send = 'khai.huynhmt1805@hcmut.edu.vn';
                $_SESSION['email'] = $email_send;
                date_default_timezone_set("Asia/Ho_Chi_Minh");
                $time_send =  date("Y-m-d H:i:s");
                

                $otp = rand(100000,999999);
                $to      = $email_send;
                $subject = 'Test Crypto OTP to login';
                $message = 'this message otp '.$otp;
                $headers = 'From: testcrypt70@gmail.com'       . "\r\n" .
                            'Reply-To: testcrypt70@gmail.com' . "\r\n" .
                            'X-Mailer: PHP/' . phpversion();
                // ini_set('smtp_port',587);
                // ini_set('SMTP','smtp.gmail.com');
                // ini_set("mail.smtp.starttls.enable", "true");
                if(!mail($to, $subject, $message, $headers)){
                    echo "<script> alert(\"Failed to send mail, please login again!\")</script>";
                    header("Refresh:0,url=logout.php");
                    exit();
                }
                    
                //save to db
                $otp_insert = "INSERT into otp_expired(email,expire,otp,is_expired) values('$email_send','$time_send','$otp',0)";
                if(!mysqli_query($db_con, $otp_insert))
                    echo "Cant Insert OTP!";  
                // $num['id']; // hold the user id in session
                
                $userId_db = $_SESSION['id'];
                $username_db = $_SESSION['login'];
                $uip = $_SERVER['REMOTE_ADDR']; // get the user ip
                $action = "Login"; // query for inser user log in to data base
                $log_query = "INSERT into userlog(userId,username,role,userIp,action) values('$userId_db','$username_db','$role','$uip','$action')";
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
    else if(!isset($_SESSION['id'])){
        $extra = "login.php";
        $host = $_SERVER['HTTP_HOST'];
        $uri = rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
        header("location:http://$host$uri/$extra");
        exit();        
    }
?>
    
<div class="min-h-screen flex flex-col justify-center text-center">
      <!-- Make sure the following div id (OTPInput) is the same in the Javascipt -->
      <form action="checkOTP.php" method="post">
        <!-- This is the div where the otp fields are generated by Javascript -->
        <div class="flex justify-center " id="OTPInput">
        </div>
        <!-- Change this name attribute to mach your form submission parameters. Make sure you update the id in the javascript code if any changes are made to the id attribute -->
        <input hidden id="otp" name="otp" value="">
        <button class="mt-10 button button-primary font-bold text-lg px-6 pt-3 pb-3 rounded bg-black text-white" id="otpSubmit" name="otpSubmit">Submit
        </button>
      </form>
    </div>
    <script>
    /* This creates all the OTP input fields dynamically. Change the otp_length variable  to change the OTP Length */
    const $otp_length = 6;
    const element = document.getElementById('OTPInput');

    for (let i = 0; i < $otp_length; i++) {
        let inputField = document.createElement('input'); // Creates a new input element
        inputField.className = "w-12 h-12 bg-green-400 border-gray-100 outline-none focus:bg-gray-200 m-2 text-center rounded focus:border-blue-400 focus:shadow-outline";
        // Do individual OTP input styling here;

        inputField.style.cssText = "color: transparent; text-shadow: 0 0 0 black;"; // Input field text styling. This css hides the text caret
        inputField.id = 'otp-field' + i; // Don't remove
        inputField.maxLength = 1; // Sets individual field length to 1 char
        element.appendChild(inputField); // Adds the input field to the parent div (OTPInput)
    }

    /*  This is for switching back and forth the input box for user experience */
    const inputs = document.querySelectorAll('#OTPInput > *[id]');
    for (let i = 0; i < inputs.length; i++) {

        inputs[i].addEventListener('keydown', function(event) {
        if (event.key === "Backspace") {
            if (inputs[i].value == '') {
                if (i != 0) {
                inputs[i - 1].focus();
                }
            } 
            else {
                inputs[i].value = '';
            }
        } 

        else if (event.key === "ArrowLeft" && i !== 0) {
            inputs[i - 1].focus();
        } 
        else if (event.key === "ArrowRight" && i !== inputs.length - 1) {
            inputs[i + 1].focus();
        } 
        else if (event.key != "ArrowLeft" && event.key != "ArrowRight") {
            inputs[i].setAttribute("type", "text");
            inputs[i].value = ''; // Bug Fix: allow user to change a random otp digit after pressing it
            setTimeout(function() {
                inputs[i].setAttribute("type", "password");
            }, 1000); // Hides the text after 1 sec
        }
        });

        inputs[i].addEventListener('input', function() {
            inputs[i].value = inputs[i].value.toUpperCase(); // Converts to Upper case. Remove .toUpperCase() if conversion isnt required.
            if (i === inputs.length - 1 && inputs[i].value !== '') {
                return true;
            } 
            else if (inputs[i].value !== '') {
                inputs[i + 1].focus();
            }
        });

    }
    /*  This is to get the value on pressing the submit button
    *   In this example, I used a hidden input box to store the otp after compiling data from each input fields
    *   This hidden input will have a name attribute and all other single character fields won't have a name attribute
    *   This is to ensure that only this hidden input field will be submitted when you submit the form */

    document.getElementById('otpSubmit').addEventListener("click", function() {
        const inputs = document.querySelectorAll('#OTPInput > *[id]');
        let compiledOtp = '';
        for (let i = 0; i < inputs.length; i++) {
            compiledOtp += inputs[i].value;
        }
        document.getElementById('otp').value = compiledOtp;
        return true;
    });

    </script>
</body>
</html>