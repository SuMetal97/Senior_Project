<?php

if(isset($_POST['login-request'])){
    require 'dbh.inc.php';

    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    if(empty($username) || empty($password)){
        header("Location: ../login.html?error=emptyfield");
        exit();
    }
    else{
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
                $pwdCheck = password_verify($password, $row['clientPWD']);
                
                if($pwdCheck == false){
                    header("Location: ../login.html?error=wrongpassword");
                    exit();
                }
                else if($pwdCheck == true){
                    $sql = "UPDATE clients SET onlineStatus=TRUE WHERE clientUsername = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)){
                        header("Location: ../signup.html?sqlerror");
                        exit();
                    }
                    else{
                        mysqli_stmt_bind_param($stmt, "s", $username);
                        mysqli_stmt_execute($stmt);

                        session_start();
                        $_SESSION['UID'] = $row['clientUID'];
                        $_SESSION['username'] = $row['clientUsername'];
                        $_SESSION['type'] = $row['clientType'];

                        if($_SESSION['type'] == "PATIENT"){
                            header("Location: ../session_p.php?login=success");
                            exit();
                        }
                        else if($_SESSION['type'] == "THERAPIST"){
                            header("Location: ../session_t.php?login=success");
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