//Import the required libraries
var serialport = require('serialport');
var WebSocketServer = require('ws').Server;
var fs = require('fs');

//Read from portID.txt the name of the port
var portName = "\0";
fs.readFile('.\\PortID.txt', (err, data) => { 
    if (err) 
        throw err; 
  
    //Set portName to the port name found in portID.txt
    portName = data.toString();
})

//Start the server
setTimeout(() => {

    //Initialize and open the port
    var myPort = new serialport(portName, {
        baudRate: 9600,
        parser: new serialport.parsers.Readline('\r\n')
    });
    myPort.on('open', onOpen);

    var SERVER_PORT = 8081;               // Port number for the webSocket server, this number must match that one specified inside browserSocket.js
    var wss = new WebSocketServer({port: SERVER_PORT}); // The webSocket server
    var connections = new Array;          // List of connections to the server

    wss.on('connection', handleConnection);
    
    //Handle the connection to the websocket on the website
    function handleConnection(client) {
        console.log("New Connection"); // You have a new client
        connections.push(client); // Add this client to the connections array
        
        client.on('message', sendToSerial); // When a client sends a message,
        
        client.on('close', function() { // When a client closes its connection
            console.log("connection closed"); // Print it out
            var position = connections.indexOf(client); // Get the client's position in the array
            connections.splice(position, 1); // and delete it from the array
        });
    }

    //Send received data to serial port (USB Port)
    function sendToSerial(data) {
        console.log("sending to serial: " + data);
        myPort.write(data);
    }

    //Show there is a connection
    function onOpen(){
        console.log("Connection Successful!");
    }
}, 100);