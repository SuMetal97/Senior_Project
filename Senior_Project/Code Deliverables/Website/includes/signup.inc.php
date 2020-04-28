<?php

if(isset($_POST['submit-request'])){
    //Get the connection to the database
    require 'dbh.inc.php';

    //Obtain all information provided by the user
    $fullname = $_POST['uName'];
    $username = $_POST['uid'];
    $email = $_POST['email'];
    $password = $_POST['pwd'];
    $passwordRepeat = $_POST['pwdCheck'];
    $phoneNumber = $_POST['phoneNumber'];
    $type = 'PATIENT';
    $status = TRUE;
    
    //This is all security checks for info received from the form
    if(empty($fullname) || empty($username) || empty($password)){ //If there are empty fields return
        header("Location: ../signup.html?error=emptyfields&fullname=".$fullname."&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(empty($email) && empty($phoneNumber)){
        header("Location: ../signup.html?error=emptyfields&fullname=".$fullname."&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z ]*$/", $fullname)){ //If the characters used on the Full Name field do not match A-Z or a-z return
        header("Location: ../signup.html?error=invalidname&uid=".$username."&email=".$email."&phonenumber=".$phoneNumber);
        exit();
    }
    else if(!preg_match("/^[a-zA-Z0-9]*$/", $username)){ //If the username does not contain the specified characters return
        header("Location: ../signup.html?error=invaliduid");
        exit();
    }
    else if($password !== $passwordRepeat){ //If the password and password check do not match return
        header("Location: ../signup.html?error=pwdmismatch");
        exit();
    }
    else{
        if(empty($phoneNumber)) //If phone field is empty, there must be an email, so test for validity of email next
        {
            if(!filter_var($email, FILTER_VALIDATE_EMAIL))
            {
                header("Location: ../signup.html?error=invalidemail&fullname=".$fullname."&uid=".$username."&phonenumber=".$phoneNumber);
                exit();
            }
        }

        //Check if username already exists
        $sql = "SELECT clientUsername FROM clients WHERE clientUsername = ?";
        $stmt = mysqli_stmt_init($conn);
        
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../signup.html?sqlerror");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            $result = mysqli_stmt_num_rows($stmt);

            //If username exists, return
            if($result > 0){
                header("Location: ../signup.html?error=usernametaken");
                exit();
            }
            else{
                //Prepare statement to query the database
                $sql = "INSERT INTO clients (clientFullName, clientUsername, clientEmail, clientPhoneNumber, clientPWD, clientType, onlineStatus, sessionStatus) VALUES (?, ?, ?, ?, ?, ?, ?, 0)";
                $stmt = mysqli_stmt_init($conn);
        
                if(!mysqli_stmt_prepare($stmt, $sql)){
                    header("Location: ../signup.html?sqlerror");
                    exit();
                }
                else{
                    //Hash the password provided by user
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    //Bind all information provided by the user to query statement
                    mysqli_stmt_bind_param($stmt, "sssissi", $fullname, $username, $email, $phoneNumber, $hashedPassword, $type, $status);
                    mysqli_stmt_execute($stmt);

                    $sql = "SELECT * FROM clients WHERE clientUsername = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../signup.html?error=sql");
                        exit();
                    }
                    else{
                        //Fetch the just created username
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if($row = mysqli_fetch_assoc($result)){
                            //Initialize a session with that user and get all of the needed info
                            session_start();
                            $_SESSION['UID'] = $row['clientUID'];
                            $_SESSION['username'] = $row['clientUsername'];
                            $_SESSION['type'] = $row['clientType'];
        
                            //Update motorattributes table and add new row to it for this user
                            $sql = "INSERT INTO motorattributes (motorStatus, motorSpeed) VALUES (0, 0)";
                            $result = $conn->query($sql);

                            //If the user is a patient, send them to the patient webpage
                            if($_SESSION['type'] == "PATIENT"){
                                header("Location: ../php/session_p.php?signup=success");
                                exit();
                            }
                            //If the user is a therapist, send them to the therapist webpage
                            else if($_SESSION['type'] == "THERAPIST"){
                                header("Location: ../php/session_t.php?signup=success");
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