<?php
    session_start();
    if($_SESSION['type'] != 'THERAPIST')
        header("Location: unkown.html");
?>

<html>
    <head>
        <title>Inner Nourish Counseling | Therapist Page</title>
            <link href="session.css" rel="stylesheet" type="text/css">
    </head>

    <body>
	    <header>
	        <div class="row">
                <div class="logo">
                    <img src="logo_white.png">
                </div>

                <ul class="main-nav">
                    <li><a href="#"> DASHBOARD </a></li>
                    <li><a href="#">	PROFILE </a></li>
                    <li><a href="includes/logout.inc.php"> LOGOUT </a></li>
                    <li><a href="#">	ABOUT </a></li>
                    <li><a href="#">	CONTACT </a></li>
                    <li><a href="#">	FAQ </a></li>
                </ul>
            </div>

            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

            <div id="patientList"></div>
	    </header>
        
        <!--CREATE TABLE NOW-->
        <script>
            var ajax = new XMLHttpRequest();
            var method = "GET";
            var url = "requestPatientList.php";
            var asynchronous = true;

            ajax.open(method, url, asynchronous);
            ajax.onreadystatechange = function(){
                if(this.readyState == 4 && this.status == 200){
                    var patients = JSON.parse(this.responseText);

                    //I KNOW THIS LOOKS TERRIFYING, BUT THIS IS JUST CREATING THE TABLES
                    var html;
                    var onlineTable = "<h2>ONLINE</h2><table><tr><th>Session</th><th>Full Name</th><th>Email</th><th>Phone Number</th></tr>";
                    var offlineTable = "<h2>OFFLINE</h2><table><tr><th>Full Name</th><th>Email</th><th>Phone Number</th></tr>";

                    for(var i = 0; i < patients.length; i++){
                        var fullName = patients[i].clientFullName;
                        var email = patients[i].clientEmail;
                        var phoneNumber = patients[i].clientPhoneNumber;
                        var onlineStatus;

                        if(patients[i].onlineStatus == true){
                            onlineTable += "<tr><td><form action=\"session_control.php\" method=\"post\"><button type=\"submit\" name=\"patientID\" value=\"" + patients[i].clientUID + "\">Select</button></form></td>";
                            onlineTable += "<td>" + fullName + "</td><td>" + email + "</td><td>" + phoneNumber + "</td>";
                            onlineTable += "</tr>";
                        }
                        else{
                            offlineTable += "<tr><td>" + fullName + "</td><td>" + email + "</td><td>" + phoneNumber + "</td></tr>";
                        }
                    }
                    onlineTable += "</table><br/><br/><br/><br/>";
                    offlineTable += "</table>";
                    html = onlineTable + offlineTable;
                    document.getElementById("patientList").innerHTML = html;
                }
            }
            ajax.send();
        </script>

        <!--UPDATE TABLE EVERY 5 SECONDS-->
        <script>
            setInterval(() => {
                var ajax = new XMLHttpRequest();
                var method = "GET";
                var url = "requestPatientList.php";
                var asynchronous = true;

                ajax.open(method, url, asynchronous);
                ajax.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        var patients = JSON.parse(this.responseText);

                        //I KNOW THIS LOOKS TERRIFYING, BUT THIS IS JUST CREATING THE TABLES
                        var html;
                        var onlineTable = "<h2>ONLINE</h2><table style=\"width:50%\"><tr><th>Session</th><th>Full Name</th><th>Email</th><th>Phone Number</th></tr>";
                        var offlineTable = "<h2>OFFLINE</h2><table style=\"width:50%\"><tr><th>Full Name</th><th>Email</th><th>Phone Number</th></tr>";
                        for(var i = 0; i < patients.length; i++){
                            var fullName = patients[i].clientFullName;
                            var email = patients[i].clientEmail;
                            var phoneNumber = patients[i].clientPhoneNumber;
                            var onlineStatus;

                            if(patients[i].onlineStatus == true){
                                onlineTable += "<tr><td><form action=\"session_control.php\" method=\"post\"><button type=\"submit\" name=\"patientID\" value=\"" + patients[i].clientUID + "\">Select</button>";
                                onlineTable += "<td>" + fullName + "</td><td>" + email + "</td><td>" + phoneNumber + "</td>";
                                onlineTable += "</form></td>";
                            }
                            else{
                                offlineTable += "<tr><td>" + fullName + "</td><td>" + email + "</td><td>" + phoneNumber + "</td><tr>";
                            }
                        }

                        onlineTable += "</table><br/><br/><br/><br/>";
                        offlineTable += "</table>";
                        html = onlineTable + offlineTable;
                        document.getElementById("patientList").innerHTML = html;
                    }
                }
                ajax.send();

            }, 5000);
        </script>

        <!--THESE ARE STYLES TO FORMAT TABLES AND HEADERS AND SO ON...-->
        <style>
            h2{
                color: rgb(0, 97, 194);
                font-family: "Roboto", sans-serif;
            }
            table {
                color: rgb(9, 45, 80);
                font-family: "Roboto", sans-serif;
                border-collapse: collapse;
                width: 50%;
            }
            td, th {
                border: 2px solid #cccccc;
                text-align: left;
                padding: 8px;
                background-color: #eeeeee;
            }

            button{
                position: relative;
                background-color: rgb(9, 59, 109);
                color: white;
                border: none;
                width: 50px;
                height: 30px;
                text-align: center;
                display: inline-block;
                font-size: 16px;
                cursor: hand;
                left: 10%;
            }
        </style>
    </body>

</html>