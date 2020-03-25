<?php
    if(isset($_POST['Disconnect'])){

        $servername = "localhost";
        $dBUsername = "root";
        $dBPassword = "";
        $dBName = "phplogintest";

        $conn = mysqli_connect($servername, $dBUsername, $dBPassword, $dBName);

        if(!$conn){
            die("Connection failed: ".mysqli_connect_error());
        }

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