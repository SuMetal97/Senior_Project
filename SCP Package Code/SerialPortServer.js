var serialport = require('serialport');
var WebSocketServer = require('ws').Server;

var portName = "COM4";

var myPort = new serialport(portName, {
    baudRate: 9600,
    parser: new serialport.parsers.Readline('\r\n')
});

myPort.on('open', onOpen);

var SERVER_PORT = 8081;               // port number for the webSocket server
var wss = new WebSocketServer({port: SERVER_PORT}); // the webSocket server
var connections = new Array;          // list of connections to the server

wss.on('connection', handleConnection);
 
function handleConnection(client) {
    console.log("New Connection"); // you have a new client
    connections.push(client); // add this client to the connections array
    
    client.on('message', sendToSerial); // when a client sends a message,
    
    client.on('close', function() { // when a client closes its connection
    console.log("connection closed"); // print it out
    var position = connections.indexOf(client); // get the client's position in the array
    connections.splice(position, 1); // and delete it from the array
    });
}

function sendToSerial(data) {
    console.log("sending to serial: " + data);
    myPort.write(data);
}

function onOpen(){
    console.log("Connection Successful!");
}