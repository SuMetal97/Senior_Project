<?php

if(isset($_POST['login-request'])){
    //Get the connection to the database
    require 'dbh.inc.php';

    //Get the username and password entered by user
    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    //Check if any empty fields are empty
    if(empty($username) || empty($password)){
        header("Location: ../login.html?error=emptyfield");
        exit();
    }
    else{
        //Set up prepared statement to query the database
        $sql = "SELECT * FROM clients WHERE clientUsername = ?";
        $stmt = mysqli_stmt_init($conn);

        //If the statement fails return with error
        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../login.html?error=sql");
            exit();
        }
        else{
            //Bind user input to prepare statement
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            //If the query is successful do the following
            if($row = mysqli_fetch_assoc($result)){
                //Authenticate the provided password with the one stored on database for the user
                $pwdCheck = password_verify($password, $row['clientPWD']);
                
                //If the passwords do not match, return and specify error
                if($pwdCheck == false){
                    header("Location: ../login.html?error=wrongpassword");
                    exit();
                }
                else if($pwdCheck == true){
                    //Update the user's current status
                    $sql = "UPDATE clients SET onlineStatus=TRUE WHERE clientUsername = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../login.html?sqlerror");
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);

                        //Begin session and get all essential user data
                        session_start();
                        $_SESSION['UID'] = $row['clientUID'];
                        $_SESSION['username'] = $row['clientUsername'];
                        $_SESSION['type'] = $row['clientType'];

                        //If the user is a patient, send to patient page
                        if($_SESSION['type'] == "PATIENT"){
                            header("Location: ../php/session_p.php?login=success");
                            exit();
                        }
                        //If user is therapist or admin send to therapist page
                        else if($_SESSION['type'] == "THERAPIST"){
                            header("Location: ../php/session_t.php?login=success");
                            exit();
                        }
                        else if($_SESSION['type'] == "ADMIN"){
                            header("Location: ../php/session_t.php?login=success");
                            exit();
                        }
                    }
                }
                else{
                    header("Location: ../login.html?error=wrongpwd");
                    exit();
                }
            }
            else{
                header("Location: ../login.html?error=nouser");
                exit();
            }
        }
    }

}

else{
    header("Location: ../login.html");
    exit();
}