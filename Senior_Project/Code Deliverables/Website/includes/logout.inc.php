<?php
    //Get the connection to the database
    require 'dbh.inc.php';
    
    //Begin session and prepare query statement
    session_start();

    $sql = "UPDATE clients SET onlineStatus=FALSE WHERE clientUsername = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)){
        header("Location: ../php/session.php?sqlerror");
        exit();
    }
    else{
        //If statement prepared correctly, execute the query to database
        mysqli_stmt_bind_param($stmt, "s", $_SESSION['username']);
        mysqli_stmt_execute($stmt);
    }

    //Free resources and return to homepage
    session_unset();
    session_destroy();
    header("Location: ../index.html");
?>