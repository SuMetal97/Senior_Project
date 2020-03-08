<?php

if(isset($_POST['login-request'])){
    require 'dbh.inc.php';

    $username = $_POST['uid'];
    $password = $_POST['pwd'];

    if(empty($username) || empty($password)){
        header("Location: ../login.php?error=emptyfield");
        exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE userName = ?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: ../header.php?error=sql");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){
                $pwdCheck = password_verify($password, $row['userPWD']);
                
                if($pwdCheck == false){
                    header("Location: ../login.php?error=wrongpassword");
                    exit();
                }
                else if($pwdCheck == true){
                    session_start();
                    $_SESSION['userID'] = $row['userUID'];
                    $_SESSION['userId'] = $row['userName'];

                    header("Location: ../session_header.php?login=success");
                    exit();
                }
                else{
                    header("Location: ../login.php?error=wrongpwd");
                    exit();
                }
            }
            else{
                header("Location: ../login.php?error=nouser");
                exit();
            }
        }
    }

}

else{
    header("Location: ../login.php");
    exit();
}