//This function is used to create a connection between the website and the 
//"Serial Port Server" executable that patients must enable for pulser to work
//NOTE: The 8081 is arbitrary and therefore could be change, however this "server number"
//must match the "server number" the SerialPortServer.js file is calling
function SocketConnection(){
    socket = new WebSocket("ws://localhost:8081");
}

//This function is responsible for transmitting the message from the website to the 
//local "Serial Port Server" executable
function sendMessage(msg){
    // Wait until the state of the socket is not ready and send the message when it is
    waitForSocketConnection(socket, function(){
        console.log("message sent!!!");
        socket.send(msg);
    });
}

// Make the function wait until the connection is made
function waitForSocketConnection(socket, callback){
    setTimeout(
        function () {
            if (socket.readyState === 1) {
                console.log("Connection is made")
                if (callback != null){
                    callback();
                }
            } else {
                console.log("wait for connection...")
                waitForSocketConnection(socket, callback);
            }

        }, 5); // Wait 5 milisecond for the connection
}