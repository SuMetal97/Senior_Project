<?php

if(isset($_POST['login-submit'])){
    require 'dbh.php';

    $username = $_POST['uname'];
    $password = $_POST['psw'];
	$fullname = $_POST['names'];

    if(empty($username) || empty($password) || empty($fullname)){
        header("Location: login.html?error=emptyfield".$fullname);
        exit();
    }
    else{
        $sql = "SELECT * FROM users WHERE uidUsers=?";
        $stmt = mysqli_stmt_init($conn);

        if(!mysqli_stmt_prepare($stmt, $sql)){
            header("Location: login.html?error=sql");
            exit();
        }
        else{
            mysqli_stmt_bind_param($stmt, "s", $username); 
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if($row = mysqli_fetch_assoc($result)){ 
                $pwdCheck = password_verify($password, $row['pwdtext']);
                
                if($pwdCheck == false){
                    header("Location: login.html?error=wrongpassword".$fullname);
                    exit();
                }
                else if($pwdCheck == true){
                    session_start();
                    $_SESSION['uid_Users'] = $row['uidUsers'];
                    $_SESSION['u_name'] = $row['uname'];

                    header("Location: ../session.html?login=success");
                    exit();
                }
                else{
                    header("Location: login.html?error=wrongpwd.$fullname");
                    exit();
                }
            }
            else{
                header("Location: login.html?error=nouser");
                exit();
            }
        }
    }

}

else{
    header("Location: ../login.php");
    exit();
}