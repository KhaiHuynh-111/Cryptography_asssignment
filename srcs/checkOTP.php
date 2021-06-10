<?php
    session_start();
    include 'config.php';
    var_dump($_POST);
    var_dump($_SESSION);
    if(isset($_POST["otpSubmit"])){
        $OTP_input = $_POST["otp"];
        $query = "SELECT * from otp_expired WHERE email = '".$_SESSION["email"]."' AND is_expired = 0";
        $res = mysqli_query($db_con, $query);
        if(!$res)
            echo "Cant query";
        else{
            
            $row = mysqli_fetch_assoc($res);
            var_dump($row);
            if($OTP_input == $row["otp"]){
                $query = "UPDATE `otp_expired` SET `is_expired`= 1  WHERE otp = '".$OTP_input."'";
                $res = mysqli_query($db_con, $query);
                if(!$res)
                echo "Cannot update expired";
                $_SESSION['validated'] = TRUE;                
            }                     
                
            
              
        }
    }
    if(isset($_SESSION['validated']) && $_SESSION['role'] == "user"){
        header("Location:detailInformation.php");
    }
    else if(isset($_SESSION['validated'])){
        header("Location:home.php");
    }
    echo "Outside";
?>