<?php
    //Database connection info
    $servername = "localhost";
    $dBUsername = "accessor";
    $dBPassword = "gdEPL53kQC7A3zV";
    $dBName = "RemoteEMDRINC";

    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

    //If connection is unsuccessful then kill the session
    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

    //Make the query: Here all information (except passwords) are
    //fetched from the database to display on the patient table for the therapist to select
    $sql = "SELECT * FROM clients WHERE clientType=\"PATIENT\"";
    $result = $conn->query($sql);
    
    $patients = array();
    while($row = mysqli_fetch_assoc($result)){
        $patients[] = $row;
    }
    
    echo json_encode($patients);
    mysqli_close($conn);
?>