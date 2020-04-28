<?php
    session_start();
    $id = (int)$_POST['patientID']; //Get patient ID by using POST method
?>

<html>
    <head>
        <title>Inner Nourish Counseling | Control Massagers</title>
        <link href="../session.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <header>
	        <div class="row">
                <div class="logo">
                    <img src="../img/logo_white.png">
                </div>
                <!--
                <ul class="main-nav">
                    <li><a href="session_t.php"> DASHBOARD </a></li>
                    <li><a href="#">	PROFILE </a></li>
                    <li><a href="includes/logout.inc.php"> LOGOUT </a></li>
                    <li><a href="#">	ABOUT </a></li>
                    <li><a href="#">	CONTACT </a></li>
                    <li><a href="#">	FAQ </a></li>
                </ul>
                -->
            </div>

            <!--MOTOR CONTROLS-->
            <div>
                <button id="on" onclick="update('FALSE')">ON</button>
                <button id="off" onclick="update('TRUE')">OFF</button>

                <form action="resetMotorAttributes.php" method="post">
                    <button class="disconnect" type="submit" name="Disconnect" value="<?php echo $id ?>">Disconnect</button>
                </form>
            </div>

            <!--SLIDER USED TO CONTROL THE SPEED OF THE MOTORS-->
            <div class="slidecontainer">
                <input type="range" min="0" max="100" value="0" class="slider" id="motorSpeed">
            </div>

            <!--MOTOR CONTROLS MENU TEXT AND INITIAL VIEW OF 0%-->
            <span class="zero" id="init_zero">The current motor speed is at: 0%</span>
            <span class="menu_text">PULSERS CONTROLS</span>

            <!--CONTINUAL VIEW OF SPEED PERCENTAGE-->
            <div id="sliderIndicator" class="zero"></div>
        </header>
        
        <!--HIDE ON BUTTON-->
        <script>document.getElementById("on").style.visibility = "hidden";</script>
        
        <!--INPUT UPDATER-->
        <script>
            function update(input){
                //Information used to specify description for sending data over to query file
                var ajax = new XMLHttpRequest();
                var method = "POST";
                var url = "updateStatus.php";
                var asynchronous = true;

                var fd = new FormData();
                fd.append("ID", "<?php echo $id ?>");

                //If the input is "TRUE", disable "OFF" button and enable "ON" button and update value in database
                if(input == 'TRUE'){
                    fd.append("runStatus", "1");
                    document.getElementById("on").style.visibility = "visible";
                    document.getElementById("off").style.visibility = "hidden";
                }
                //If the input is "FALSE", disable "ON" button and enable "OFF" button and update value in database
                else if(input == 'FALSE'){
                    fd.append("runStatus", "0");
                    document.getElementById("on").style.visibility = "hidden";
                    document.getElementById("off").style.visibility = "visible";
                }
                //Otherwise, the input is a speed change and therefore the query to update the database is made
                else
                    fd.append("speedStatus", input);

                //Set values and send input data to database handler file (aka updateStatus.php)
                ajax.open(method, url, asynchronous);
                ajax.send(fd);
                ajax.onreadystatechange = function(){
                    if(this.readyState == 4 && this.status == 200){
                        console.log(this.responseText);
                    }
                }
            }
        </script>

        <!--BUTTON/SLIDER CONTROLLER-->
        <script>
            //Get values from input elements on the website
            var button = document.getElementById("on");
            var slider = document.getElementById("motorSpeed");
            var html;

            //When one of the arrow keys is pressed, the value of the slider will be updated accordingly
            document.addEventListener('keydown', function(event) {
                //Get key pressed and execute update functions accordingly
                //DOWN arrow key reduces speed percent by 5
                if(event.keyCode == 40) {
                    slider.value -= 5;
                    update(slider.value);
                }
                //UP arrow key increases speed percent by 5
                else if(event.keyCode == 38) {
                    slider.value++;
                    slider.value++;
                    slider.value++;
                    slider.value++;
                    slider.value++;
                    update(slider.value);
                }
                //LEFT arrow key reduces speed percent by 25
                else if(event.keyCode == 37){
                    slider.value -= 25;
                    update(slider.value);
                }
                //RIGHT arrow key increases speed percent by 25
                else if(event.keyCode == 39){
                    slider.value++; slider.value++; slider.value++; slider.value++; slider.value++;
                    slider.value++; slider.value++; slider.value++; slider.value++; slider.value++;
                    slider.value++; slider.value++; slider.value++; slider.value++; slider.value++;
                    slider.value++; slider.value++; slider.value++; slider.value++; slider.value++;
                    slider.value++; slider.value++; slider.value++; slider.value++; slider.value++;
                    update(slider.value);
                }
                //SPACE bar triggers "ON" or "OFF" depending on current status
                else if(event.keyCode == 32){
                    if(window.getComputedStyle(button).visibility == "hidden")
                        document.getElementById("off").click();
                    else
                        document.getElementById("on").click();
                }

                //Print active percentage in slider
                document.getElementById("init_zero").style.visibility = "hidden";
                document.getElementById("sliderIndicator").innerHTML = "The current motor speed is at: " + slider.value + "%";
            });
        </script>

        <!--STYLES FOR BUTTONS AND SLIDER-->
        <style>
            .zero{
                position: absolute;
                color: white;
                font-size: 15px;
                background: rgb(9, 59, 109);
                left: 710px;
                top: 290px;
            }

            .slidecontainer {
                width: 20%;
            }

            .slider {
                position: absolute;
                -webkit-appearance: none;
                width: 20%;
                height: 25px;
                background: #d3d3d3;
                outline: none;
                opacity: 0.7;
                -webkit-transition: .2s;
                transition: opacity .2s;
                left: 700px;
                top: 250px;
            }

            .slider:hover {
                opacity: 1;
            }

            .slider::-webkit-slider-thumb {
                -webkit-appearance: none;
                appearance: none;
                width: 25px;
                height: 25px;
                background: rgb(9, 59, 109);
                cursor: pointer;
            }

            .slider::-moz-range-thumb {
                width: 25px;
                height: 25px;
                background: rgb(9, 59, 109);
                cursor: pointer;
            }

            .disconnect{
                position: absolute;
                background-color: rgb(9, 59, 109);
                color: white;
                border: none;
                width: 150px;
                height: 50px;
                text-align: center;
                display: inline-block;
                font-size: 20px;
                margin: 4px 4px;
                cursor: hand;
                left: 690px;
                top: 390px;
                border-radius: 0%;
            }

            button{
                position: absolute;
                background-color: rgb(9, 59, 109);
                color: white;
                border: none;
                width: 120px;
                height: 120px;
                text-align: center;
                display: inline-block;
                font-size: 20px;
                margin: 4px 4px;
                cursor: hand;
                left: 550px;
                top: 200px;
                border-radius: 100%;
            }

            .menu_text{
                color: rgb(9, 59, 109);
                font-family: "Roboto", sans-serif;
                font-size: 50px;
                position: absolute;
                left: 500px;
                top: 100px;
                background: rgb(127,127,127);
            }
        </style>

    </body>
</html>
