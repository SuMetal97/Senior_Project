<?php
    if(isset($_POST['runStatus']) || isset($_POST['speedStatus'])){
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
        $sql;
        //If ON/OFF Button was pressed
        if(isset($_POST['runStatus'])){
            $newMotorStatus = (int)$_POST['runStatus'];
            $sql = "UPDATE motorattributes SET motorStatus=$newMotorStatus WHERE userID=$uid";
        }
        //If slider was updated
        else if(isset($_POST['speedStatus'])){
            $newSpeedStatus = (int)$_POST['speedStatus'];
            $sql = "UPDATE motorattributes SET motorSpeed=$newSpeedStatus WHERE userID=$uid";
        }
        else{
            echo "Something went wrong!!!";
        }
        $result = $conn->query($sql);

        $sql = "UPDATE clients SET sessionStatus=1 WHERE clientUID=$uid";
        $result = $conn->query($sql);

        mysqli_close($conn);
    }
    else{
        header("Location: unkown.html");
        exit();
    }
?>