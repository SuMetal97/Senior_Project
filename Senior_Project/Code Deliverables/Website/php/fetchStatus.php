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

    //Make the query: Here the current status of the motors for 
    //the specified patient is fetched from the database 
    $uid = (int)$_POST['ID'];
    $sql = "SELECT * FROM motorattributes WHERE userID=$uid";
    $result = $conn->query($sql);
    
    $motorAttributes = array();
    while($row = mysqli_fetch_assoc($result)){
        $motorAttributes[] = $row;
    }

    echo json_encode($motorAttributes);
    mysqli_close($conn);
?>