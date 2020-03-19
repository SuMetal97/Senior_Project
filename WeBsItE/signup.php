<?php

if(isset($_POST['signup-submit'])){
    require 'dbh.php';

    $fullname = $_POST['new_names'];
    $username = $_POST['new_uname'];
    $password = $_POST['new_psw'];

     //This is all security checks for info received from the form
    if(empty($username) || empty($password) || empty($fullname)){ //Empty fields
        header("Location: signup.html?error=emptyfields&fullname=".$fullname);
        exit();
    } 
    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ //Username uses only the following
        header("Location: signup.html?error=invaliduname".$username);
        exit();
    }
    else{ //Check if username already exists
        $sql = "SELECT uidUsers FROM users WHERE uidUsers=?"; 
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: signup.html?error=sqlerror");
            exit();
        }
        else{   //Finally get connection from sqli database and do all the mombo jombo that goes here and insert the information  
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $result = mysqli_stmt_num_rows($stmt);

            if($result > 0){
                header("Location: signup.html?error=usertaken");
                exit();
            }
            else{
                $sql = "INSERT INTO users (uidUsers, pwdtext, uname) VALUES (?, ?, ?)"; 
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: signup.html?error=sqlerror");
                    exit();
                }
                else{
                    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "sss", $username, $hashedpassword, $fullname);
                    mysqli_stmt_execute($stmt);
                    header("Location: session.html?signup=success");
                    exit();
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
else{
    header("Location: signup.html");
    exit();
}