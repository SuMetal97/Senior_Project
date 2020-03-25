<?php
    session_start();
    $ID = strval($_SESSION['UID']);
    header("refresh: 120");
?>

<html>
    <head>
        <title>Inner Nourish Counseling | Patient Page</title>
            <link href="session.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <header>
	        <div class="row">
                <div class="logo">
                    <img src="logo_white.png">
                </div>
                <ul class="main-nav">
                    <li><a href="#">	PROFILE </a></li>
                    <li><a href="includes/logout.inc.php"> LOGOUT </a></li>
                    <li><a href="#">	ABOUT </a></li>
                    <li><a href="#">	CONTACT </a></li>
                    <li><a href="#">	FAQ </a></li>
                </ul>
            </div>

            <h1 id="curr_status"></h1>
	    </header>

        <!--OPEN THE CONNECTION BETWEEN WEBSITE AND HARDWARE AND READ/SEND DATA-->
        <script type = "text/javascript" src ='browserSocket.js'></script>
        <script>
            setInterval(() => {
                SocketConnection();
                var ajax = new XMLHttpRequest();
                var method = "POST";
                var url = "fetchStatus.php";
                var asynchronous = true;
                
                var fd = new FormData();
                fd.append("ID", "<?php echo $ID ?>");

                ajax.open(method, url, asynchronous);
                ajax.send(fd); 
                ajax.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        var data = JSON.parse(this.responseText);
                        
                        if(data[0].motorStatus == 0)
                            sendMessage('f');
                        else{
                            if(data[0].motorSpeed == 0)
                                sendMessage('0');
                            else if(data[0].motorSpeed == 1)
                                sendMessage('1');
                            else if(data[0].motorSpeed == 2)
                                sendMessage('2');
                            else if(data[0].motorSpeed == 3)
                                sendMessage(2);
                            else if(data[0].motorSpeed == 4)
                                sendMessage(2);
                        }
                    }
                }
            }, 1000);
        </script>

        <!--DISPLAY CURRENT SESSION STATUS-->
        <script>
            setInterval(() => {
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

                        if(sessionStatus == "1")
                            html = "You are connected to your therapist!";
                        else if(sessionStatus == "0")
                            html = "You are in! Waiting for the therapist to connect to you.";
                        else
                            html = "This line should never print";
                        document.getElementById("curr_status").innerHTML = html;
                    }
                }
            }, 500);
        </script>

        <style>
            h1{
                color: rgb(0, 89, 255);
                position: fixed;
                top: 40%;
                left: 30%;
            }
        </style>

    </body>
</html>
