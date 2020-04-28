<?php
    session_start();
    //If the user trying to access this page does not have 
    //the appropriate credentials send them to an error page
    if($_SESSION['type'] != 'ADMIN')
    {
        if($_SESSION['type'] != 'THERAPIST')
            header("Location: unkown.html");
    }
?>

<html>
    <head>
        <title>Inner Nourish Counseling | Therapist Page</title>
            <link href="../session.css" rel="stylesheet" type="text/css">
    </head>

    <body>
	    <header>
	        <div class="row">
                <div class="logo">
                    <img src="../img/logo_white.png">
                </div>

                <ul class="main-nav">
                    <li><a href="#"> DASHBOARD </a></li>
                    <li><a href="#"> PROFILE </a></li>
                    <?php
                        //If the user is an ADMIN, present them with the ability to add more therapists
                        if($_SESSION['type'] == 'ADMIN')
                            echo '<li><a href="t_signup.php"> ADD THERAPIST </a></li>';
                    ?>
                    <li><a href="../includes/logout.inc.php"> LOGOUT </a></li>
                </ul>
            </div>
            <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>

            <!--THIS DIV BLOCK IS USED TO DISPLAY THE TABLE CONTAINING THE PATIENTS AND THEIR RESPECTIVE STATUS-->
            <div id="patientList"></div>
	    </header>
        
        <!--CREATE TABLE RIGHT AFTER LOADING THIS PAGE-->
        <script>
            //Information used to specify description for sending data over to query file
            var ajax = new XMLHttpRequest();
            var method = "GET";
            var url = "requestPatientList.php";
            var asynchronous = true;

            ajax.open(method, url, asynchronous);
            ajax.onreadystatechange = function(){
                //After query is successfull
                if(this.readyState == 4 && this.status == 200){
                    //Get the results from the query
                    var patients = JSON.parse(this.responseText);

                    //Create the two tables
                    var html;
                    var onlineTable = "<h2>ONLINE</h2><table><tr><th>Session</th><th>Full Name</th><th>Email</th><th>Phone Number</th></tr>";
                    var offlineTable = "<h2>OFFLINE</h2><table><tr><th>Full Name</th><th>Email</th><th>Phone Number</th></tr>";

                    //For all of the patients on the database do the following...
                    for(var i = 0; i < patients.length; i++){
                        var fullName = patients[i].clientFullName;
                        var email = patients[i].clientEmail;
                        var phoneNumber = patients[i].clientPhoneNumber;
                        var onlineStatus;

                        //If the patient is logged in add them with their info onto the "ONLINE" table
                        if(patients[i].onlineStatus == true){
                            onlineTable += "<tr><td><form action=\"session_control.php\" method=\"post\"><button type=\"submit\" name=\"patientID\" value=\"" + patients[i].clientUID + "\">Select</button></form></td>";
                            onlineTable += "<td>" + fullName + "</td><td>" + email + "</td><td>" + phoneNumber + "</td>";
                            onlineTable += "</tr>";
                        }
                        //Otherwise add them with their info onto the "OFFLINE" table
                        else{
                            offlineTable += "<tr><td>" + fullName + "</td><td>" + email + "</td><td>" + phoneNumber + "</td></tr>";
                        }
                    }
                    
                    //Close the html text approprietely
                    onlineTable += "</table><br/><br/><br/><br/>";
                    offlineTable += "</table>";
                    
                    //Append both tables one after the other
                    html = onlineTable + offlineTable;
                    
                    //Display final HTML result of the tables in the div with id="patientList"
                    document.getElementById("patientList").innerHTML = html;
                }
            }
            ajax.send();
        </script>

        <!--UPDATE TABLE EVERY 5 SECONDS-->
        <script>
            //This is an exact copy of the script above, the only difference is that this is used to
            //dynamically update the table every 5 seconds
            setInterval(() => {
                var ajax = new XMLHttpRequest();
                var method = "GET";
                var url = "requestPatientList.php";
                var asynchronous = true;

                ajax.open(method, url, asynchronous);
                ajax.onreadystatechange = function(){

                    if(this.readyState == 4 && this.status == 200){
                        var patients = JSON.parse(this.responseText);

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

        <!--THESE ARE STYLES TO FORMAT TABLES AND HEADERS-->
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