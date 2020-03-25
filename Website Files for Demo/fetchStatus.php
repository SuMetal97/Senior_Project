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
    $sql = "SELECT * FROM motorattributes WHERE userID=$uid";
    $result = $conn->query($sql);
    
    $motorAttributes = array();
    while($row = mysqli_fetch_assoc($result)){
        $motorAttributes[] = $row;
    }

    echo json_encode($motorAttributes);
    mysqli_close($conn);
?>