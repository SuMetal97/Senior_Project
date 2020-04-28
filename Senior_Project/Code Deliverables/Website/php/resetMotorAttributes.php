<?php
    if(isset($_POST['Disconnect'])){
        
        //Database connection info
        $servername = "localhost";
        $dBUsername = "accessor";
        $dBPassword = "gdEPL53kQC7A3zV";
        $dBName = "RemoteEMDRINC";

        $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);
        if(!$conn){
            die("Connection failed: ".mysqli_connect_error());
        }

        //Make query: All motor attributes are reset back to 0 for the specified patient
        $uid = (int)$_POST['Disconnect'];

        $sql = "UPDATE motorattributes SET motorStatus=0, motorSpeed=0 WHERE userID=$uid";
        $result = $conn->query($sql);

        $sql = "UPDATE clients SET sessionStatus=0 WHERE clientUID=$uid";
        $result = $conn->query($sql);

        header("Location: session_t.php");
        exit();
        mysqli_close($conn);
    }
    else{
        header("Location: unkown.html");
        exit();
    }
?>