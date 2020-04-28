<?php
    //Database connection info
    $servername = "localhost";
    $dBUsername = "accessor";
    $dBPassword = "gdEPL53kQC7A3zV";
    $dBName = "RemoteEMDRINC";

    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

    //Make the query: Get current status of patient and therapist
    //If therapist has selected the patient, then the value returned should be 1, and otherwise it should be 0
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