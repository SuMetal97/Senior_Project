<?php
    //Database connection info
    $servername = "localhost";
    $dBUsername = "accessor";
    $dBPassword = "gdEPL53kQC7A3zV";
    $dBName = "RemoteEMDRINC";

    //Make the connection and if connection is unsuccessful then kill the session
    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }
?>