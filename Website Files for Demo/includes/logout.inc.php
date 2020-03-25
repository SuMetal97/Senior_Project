<?php
    require 'dbh.inc.php';
    
    session_start();

    $sql = "UPDATE clients SET onlineStatus=FALSE WHERE clientUsername = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../session.php?sqlerror");
        exit();
    }
    else{
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
        mysqli_stmt_execute($stmt);
    }

    session_unset();
    session_destroy();
    header("Location: ../home.html");
?>