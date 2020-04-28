<?php

if(isset($_POST['submit-request'])){
    //Get the connection to the database
    require 'dbh.inc.php';

    session_start();

    //Obtain all information provided by the user
    $fullname = $_POST['uName'];
    $username = $_POST['uid'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwdCheck'];
    $phoneNumber = $_POST['phoneNumber'];
    $type = 'THERAPIST';
    $status = TRUE;
    
    //This is all security checks for info received from the form
    if(empty($fullname) || empty($username) || empty($password)){ //If empty fields then return
        header("Location: ../php/session_t.php?error=emptyfields&fullname=".$fullname."&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(empty($email) && empty($phoneNumber)){
        header("Location: ../php/session_t.php?error=emptyfields&fullname=".$fullname."&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z ]*$/", $fullname)){ //If the characters used on the Full Name field do not match A-Z or a-z return
        header("Location: ../php/session_t.php?error=invalidname&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ //Username uses only the following
        header("Location: ../php/session_t.php?error=invaliduid");
        exit();
    }
    else if($password !== $passwordRepeat){ //If the password and password check do not match return
        header("Location: ../php/session_t.php?error=pwdmismatch");
        exit();
    }
    else{
        //If phone field is empty, there must be an email, so test for validity of email next
        if(empty($phoneNumber))
        {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                header("Location: ../php/session_t.php?error=invalidemail&fullname=".$fullname."&uid=".$username."&phonenumber=".$phoneNumber);
                exit();
            }
        }

        //Check if username already exists
        $sql = "SELECT clientUsername FROM clients WHERE clientUsername = ?";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../php/session_t.php?sqlerror");
            exit();
        }
        else{   //Finally get connection from sqli database and do all the mombo jombo that goes here and insert the information  
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $result = mysqli_stmt_num_rows($stmt);

            //If the username does not exist then return
            if($result > 0){
                header("Location: ../php/session_t.php?error=usernametaken");
                exit();
            }
            else{
                //Prepare statement to query database
                $sql = "INSERT INTO clients (clientFullName, clientUsername, clientEmail, clientPhoneNumber, clientPWD, clientType, onlineStatus, sessionStatus) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
                $stmt = mysqli_stmt_init($conn);

                //If statement fails return
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../php/session_t.php?sqlerror");
                    exit();
                }
                else{
                    //Hash the password provided by user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    //Bind information provided by user to statement
                    mysqli_stmt_bind_param($stmt, "sssissi", $fullname, $username, $email, $phoneNumber, $hashedPassword, $type, $status);
                    mysqli_stmt_execute($stmt);

                    $sql = "SELECT * FROM clients WHERE clientUsername = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../php/session_t.php?error=sql");
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        //Update mottorattribute table and add new row
                        if($row = mysqli_fetch_assoc($result)){      
                            $sql = "INSERT INTO motorattributes (motorStatus, motorSpeed) VALUES (0, 0)";
                            $result = $conn->query($sql);

                            header("Location: ../php/t_signup.php?signup=success");
                            exit();
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