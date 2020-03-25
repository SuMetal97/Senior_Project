<?php

if(isset($_POST['submit-request'])){
    require 'dbh.inc.php';

    $fullname = $_POST['uName'];
    $username = $_POST['uid'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwdCheck'];
    $phoneNumber = $_POST['phoneNumber'];
    $type = 'PATIENT';
    $status = TRUE;
    
    //This is all security checks for info received from the form
    if(empty($fullname) || empty($username) || empty($password)){ //Empty fields
        header("Location: ../signup.html?error=emptyfields&fullname=".$fullname."&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(empty($email) && empty($phoneNumber)){
        header("Location: ../signup.html?error=emptyfields&fullname=".$fullname."&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z ]*$/", $fullname)){
        header("Location: ../signup.html?error=invalidname&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ //Username uses only the following
        header("Location: ../signup.html?error=invaliduid");
        exit();
    }
    else if($password !== $passwordRepeat){
        header("Location: ../signup.html?error=pwdmismatch");
        exit();
    }
    else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        header("Location: ../signup.html?error=invalidemail&fullname=".$fullname."&uid=".$username."&phonenumber=".$phoneNumber);
        exit();
    }
    else{ //Check if username already exists
        $sql = "SELECT clientUsername FROM clients WHERE clientUsername = ?";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../signup.html?sqlerror");
            exit();
        }
        else{   //Finally get connection from sqli database and do all the mombo jombo that goes here and insert the information  
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $result = mysqli_stmt_num_rows($stmt);

            if($result > 0){
                header("Location: ../signup.html?error=usernametaken");
                exit();
            }
            else{
                $sql = "INSERT INTO clients (clientFullName, clientUsername, clientEmail, clientPhoneNumber, clientPWD, clientType, onlineStatus, sessionStatus) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../signup.html?sqlerror");
                    exit();
                }
                else{
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    mysqli_stmt_bind_param($stmt, "sssissi", $fullname, $username, $email, $phoneNumber, $hashedPassword, $type, $status);
                    mysqli_stmt_execute($stmt);

                    $sql = "SELECT * FROM clients WHERE clientUsername = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../header.html?error=sql");
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if($row = mysqli_fetch_assoc($result)){
                            session_start();
                            $_SESSION['UID'] = $row['clientUID'];
                            $_SESSION['username'] = $row['clientUsername'];
                            $_SESSION['type'] = $row['clientType'];
        
                            $sql = "INSERT INTO motorattributes (motorStatus, motorSpeed) VALUES (0, 0)";
                            $result = $conn->query($sql);

                            if($_SESSION['type'] == "PATIENT"){
                                header("Location: ../session_p.php?signup=success");
                                exit();
                            }
                            else if($_SESSION['type'] == "THERAPIST"){
                                header("Location: ../session_t.php?signup=success");
                                exit();
                            }
                        }
                        else{
                            header("Location: unkown.html");
                            exit();
                        }
                    }
                }
            }
        }
    }
    mysqli_stmt_close($stmt);
    mysqli_close($conn);

}
else{
    header("Location: ../signup.html");
    exit();
}