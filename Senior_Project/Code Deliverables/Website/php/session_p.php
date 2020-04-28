<?php
    session_start();
    $ID = strval($_SESSION['UID']);
    header("refresh: 120"); //Refresh the webpage every 1 second to ensure consistent connectivity
?>

<html>
    <head>
        <title>Inner Nourish Counseling | Patient Page</title>
            <link href="../session.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <header>
	        <div class="row">
                <div class="logo">
                    <img src="../img/logo_white.png">
                </div>
                <ul class="main-nav">
                    <li><a href="#"> PROFILE </a></li>
                    <li><a href="../includes/logout.inc.php"> LOGOUT </a></li>
                    <li><a href="https://www.innernourish.com/aboutisabel">	ABOUT </a></li>
                    <li><a href="https://www.innernourish.com/contactform">	CONTACT </a></li>
                    <li><a href="../faqs.html">	FAQ </a></li>
                </ul>
            </div>

            <h1 id="curr_status"></h1>
	</header>

        <!--OPEN THE CONNECTION BETWEEN WEBSITE AND HARDWARE AND READ/SEND DATA-->
        <script type = "text/javascript" src ='../scripts/browserSocket.js'></script>
        <script>
            setInterval(() => {
                //Call socket connection to initialize a direct connection 
                //between the webpage and the Serial Port Server executable 
                SocketConnection();

                //Information used to specify description for sending data over to query file
                var ajax = new XMLHttpRequest();
                var method = "POST";
                var url = "fetchStatus.php";
                var asynchronous = true;
                
                var fd = new FormData();
                fd.append("ID", "<?php echo $ID ?>");

                //Set values and send input data to database handler file (aka updateStatus.php)
                ajax.open(method, url, asynchronous);
                ajax.send(fd); 
                ajax.onreadystatechange = function(){
                    //Once the query returns successfully do the following...
                    if(this.readyState == 4 && this.status == 200){
                        //Extract the results from the query into variable
                        var data = JSON.parse(this.responseText);
                        
                        //If status of motors is "0" or OFF transmit char 'f'
                        if(data[0].motorStatus == 0)
                            sendMessage('f');
                        
                        //Otherwise the motor must be ON, so verify the current 
                        //value for the speed and send appropriate char
                        else
                        {
                                //NOTE: The characters being sent are understood and translated by the 
                                //Arduino Mkr 1000, which generates an appropriate signal according to these
                                //values being sent to it
                                if(data[0].motorSpeed == 0) 
                                        sendMessage('0');        
                                else if(data[0].motorSpeed == 5)
                                        sendMessage('a');
                                else if(data[0].motorSpeed == 10)
                                        sendMessage('b');
                                else if(data[0].motorSpeed == 15)
                                        sendMessage('c');
                                else if(data[0].motorSpeed == 20)
                                        sendMessage('d');
                                else if(data[0].motorSpeed == 25)
                                        sendMessage('1');
                                else if(data[0].motorSpeed == 30)
                                        sendMessage('e');
                                else if(data[0].motorSpeed == 35)
                                        sendMessage('g');
                                else if(data[0].motorSpeed == 40)
                                        sendMessage('h');
                                else if(data[0].motorSpeed == 45)
                                        sendMessage('i');
                                else if(data[0].motorSpeed == 50)
                                        sendMessage('2');
                                else if(data[0].motorSpeed == 55)
                                        sendMessage('j');
                                else if(data[0].motorSpeed == 60)
                                        sendMessage('k');
                                else if(data[0].motorSpeed == 65)
                                        sendMessage('l');
                                else if(data[0].motorSpeed == 70)
                                        sendMessage('m');
                                else if(data[0].motorSpeed == 75)
                                        sendMessage('3');
                                else if(data[0].motorSpeed == 80)
                                        sendMessage('n');
                                else if(data[0].motorSpeed == 85)
                                        sendMessage('p');
                                else if(data[0].motorSpeed == 90)
                                        sendMessage('q');
                                else if(data[0].motorSpeed == 95)
                                        sendMessage('r');
                                else if(data[0].motorSpeed == 100)
                                        sendMessage('4');
                        }
                    }
                }
            }, 1000); //Repeat every second
        </script>

        <!--DISPLAY CURRENT SESSION STATUS-->
        <script>
            setInterval(() => {
                //Information used to specify description for sending data over to query file
                var ajax = new XMLHttpRequest();
                var method = "POST";
                var url = "sessionStatus.php";
                var asynchronous = true;
                
                var fd = new FormData();
                fd.append("ID", "<?php echo $ID ?>");

                ajax.open(method, url, asynchronous);
                ajax.send(fd); 
                ajax.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        var sessionStatus = this.responseText;
                        var html;

                        //If therapist selected the patient, display this message
                        if(sessionStatus == "1")
                            html = "You are connected to your therapist!";
                        //Else display this message
                        else if(sessionStatus == "0")
                            html = "You are in! Waiting for the therapist to connect to you.";
                    }
                }
            }, 500); //Repeat every 0.5 seconds
        </script>

        <!--STYLES USED ON THE PAGE-->
        <style>
            h1{
                color: yellowgreen;
                position: fixed;
                top: 40%;
                left: 30%;
            }
        </style>
    </body>
</html>
