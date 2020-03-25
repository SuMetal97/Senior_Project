<?php
    $servername = "localhost";
    $dBUsername = "root";
    $dBPassword = "";
    $dBName = "phplogintest";

    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

    $uid = (int)$_POST['ID'];
    $sql = "SELECT * FROM clients WHERE clientUID=$uid";
    $result = $conn->query($sql);
    $stats = $result->fetch_assoc();
    $arg;
    if($stats['sessionStatus'] == TRUE){
        $arg = 1;
    }
    else{
        $arg = 0;
    }
    echo "$arg";
    
    mysqli_close($conn);
?>