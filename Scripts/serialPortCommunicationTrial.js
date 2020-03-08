var serialport = require('serialport');
var portName = "COM4";

var myPort = new serialport(portName, {
    baudRate: 9600,
    parser: new serialport.parsers.Readline('\r\n')
});

myPort.on('open', onOpen);

myPort.write("s");

//myPort.on('data', onData);

//if(myPort.write("s")){
//    console.log('Success!')
//}

function onOpen(){
    console.log('Open connections!');
}

//function onData(data){
//    console.log('on Data ' + data);
//}

function ledStatus(){
    var status = prompt("ON or OFF?");

    if(status == "r")
        myPort.write("r");
    else if(status == "s")
        myPort.write("s");
    else{}
}