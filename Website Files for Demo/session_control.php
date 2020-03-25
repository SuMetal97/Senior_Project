<?php
    session_start();
    $id = (int)$_POST['patientID'];
?>

<html>
    <head>
        <title>Inner Nourish Counseling | Control Massagers</title>
        <link href="session.css" rel="stylesheet" type="text/css">
    </head>

    <body>
        <header>
	        <div class="row">
                <div class="logo">
                    <img src="logo_white.png">
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
            <div class="slidecontainer">
                <input type="range" min="0" max="4" value="0" class="slider" id="motorSpeed">
            </div>

            <!--MOTOR CONTROLS MENU TEXT AND INITIAL VIEW OF 25%-->
            <span class="zero" id="init_zero">0%</span>
            <span class="menu_text">HAND MASSAGERS CONTROLS</span>

            <!--CONTINUAL VIEW OF SPEED PERCENTAGE-->
            <div id="sliderIndicator"></div>
        </header>
        
        <!--HIDE ON BUTTON-->
        <script>document.getElementById("on").style.visibility = "hidden";</script>
        
        <!--INPUT UPDATER-->
        <script>
            function update(input){
                var ajax = new XMLHttpRequest();
                var method = "POST";
                var url = "updateStatus.php";
                var asynchronous = true;

                var fd = new FormData();
                fd.append("ID", "<?php echo $id ?>");

                if(input == 'TRUE'){
                    fd.append("runStatus", "1");
                    document.getElementById("on").style.visibility = "visible";
                    document.getElementById("off").style.visibility = "hidden";
                }
                else if(input == 'FALSE'){
                    fd.append("runStatus", "0");
                    document.getElementById("on").style.visibility = "hidden";
                    document.getElementById("off").style.visibility = "visible";
                }
                else
                    fd.append("speedStatus", input);

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
            var button = document.getElementById("on");
            var slider = document.getElementById("motorSpeed");
            var html;

            document.addEventListener('keydown', function(event) {
                //Get key pressed and execute update functions accordingly
                if(event.keyCode == 37 || event.keyCode == 40) {
                    slider.value--;
                    update(slider.value);
                }
                else if(event.keyCode == 39 || event.keyCode == 38) {
                    slider.value++;
                    update(slider.value);
                }
                else if(event.keyCode == 32){
                    if(window.getComputedStyle(button).visibility == "hidden")
                        document.getElementById("off").click();
                    else
                        document.getElementById("on").click();
                }

                //Print active percentage in slider
                if(slider.value == 0){
                    html = "<span class=\"zero\">0%</span>";
                    document.getElementById("init_zero").style.visibility = "hidden";
                }
                else if(slider.value == 1){
                    html = "<span class=\"quarter\">25%</span>";
                    document.getElementById("init_zero").style.visibility = "hidden";
                }
                else if(slider.value == 2){
                    html = "<span class=\"half\">50%</span>";
                }
                else if(slider.value == 3){
                    html = "<span class=\"threeFourths\">75%</span>";
                }
                else{
                    html = "<span class=\"full\">100%</span>";
                }
                document.getElementById("sliderIndicator").innerHTML = html;
            });
        </script>

        <!--STYLES FOR BUTTONS AND SLIDER-->
        <style>
            .zero{
                position: absolute;
                color: white;
                left: 705px;
                top: 255px;
            }
            .quarter{
                position: absolute;
                color: white;
                left: 773px;
                top: 255px;
            }
            .half{
                position: absolute;
                color: white;
                left: 843px;
                top: 255px;
            }
            .threeFourths{
                position: absolute;
                color: white;
                left: 913px;
                top: 255px;
            }
            .full{
                position: absolute;
                color: white;
                left: 982px;
                top: 255px;
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
                left: 400px;
                top: 100px;
                background: rgb(127,127,127);
            }
        </style>

    </body>
</html>
