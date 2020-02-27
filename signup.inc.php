<?php

if(isset($_POST['submit-request'])){
    require 'dbh.inc.php';

//  $fullname = $_POST['???'];
    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    //This is all security checks for info received from the form
    if(empty($username) || empty($password)){ //Empty fields
        header("Location: ../signup.php?error=emptyfields&uid=".$username);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ //Username uses only the following
        header("Location: ../signup.php?error=invaliduid");
        exit();
    }
    else{ //Check if username already exists
        $sql = "SELECT userName FROM users WHERE userName = ?";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../signup.php?sqlerror");
            exit();
        }
        else{   //Finally get connection from sqli database and do all the mombo jombo that goes here and insert the information  
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $result = mysqli_stmt_num_rows($stmt);

            if($result > 0){
                header("Location: ../signup.php?error=usertaken");
                exit();
            }
            else{
                $sql = "INSERT INTO users (userName, userPWD) VALUES (?, ?)";
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../signup.php?sqlerror");
                    exit();
                }
                else{
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
                    mysqli_stmt_execute($stmt);
                    header("Location: ../signup.php?signup=success");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
else{
    header("Location: ../signup.php");
    exit();
}