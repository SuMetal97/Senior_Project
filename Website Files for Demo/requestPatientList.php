<?php
    $servername = "localhost";
    $dBUsername = "root";
    $dBPassword = "";
    $dBName = "phplogintest";

    $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

    if(!$conn){
        die("Connection failed: ".mysqli_connect_error());
    }

    $sql = "SELECT * FROM clients WHERE clientType=\"PATIENT\"";
    $result = $conn->query($sql);
    
    $patients = array();
    while($row = mysqli_fetch_assoc($result)){
        $patients[] = $row;
    }
    
    echo json_encode($patients);
    mysqli_close($conn);
?>