<?php
    if(isset($_POST['runStatus']) || isset($_POST['speedStatus'])){
        $servername = "localhost";
        $dBUsername = "root";
        $dBPassword = "";
        $dBName = "phplogintest";

        $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

        if(!$conn){
            die("Connection failed: ".mysqli_connect_error());
        }

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