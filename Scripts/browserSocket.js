var socket;

function SocketConnection(){
    socket = new WebSocket("ws://localhost:8081");
}

function LEDStatus(){
    var status = prompt("ON or OFF?");
    socket.send(status);
}

function alertME(){
    alert("Its is printing.");
}