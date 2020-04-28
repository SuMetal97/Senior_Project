COURSE: CIS4910 (SENIOR PROJECT)
PROJECT NAME: REMOTE EMDR OVER THE WEB

About this installer:
The purpose of this installer is to provide a patient who is going to participate on an EMDR therapy 
session over the internet with the rest of the necessary tools to effectively carry out this therapy.
The contents of this installer are listed and described below:

	- node_modules: Directory containing predefined source code values for use with the
	  SerialPortServer.js script

	- SerialPortServer.js - Javascript file which uses the "serialport" library (defined in 
	  node_modules) to read the data sent from the website to the computer. It then takes this data
	  and sends it over to the USB port so that the "Control Box" can update the pulsers.

	- connect.exe - Main program for the user to execute. This program offers the user a friendly
	  interface asking if they which to turn on the Serial Port Server to begin receiving data from
	  the website

	- disconnect.exe - Triggers immedietely after the user has decided to activate the Serial Port
	  Server. This will allow users to close the Serial Port Server.

	- Serial Port Server.lnk - a shortcut for the connect.exe executable. This shortcut should be 
	  available to the user on the Desktop for easy access.

	- arrange.cmd, clean.cmd, openConnect.cmd, openDisconnect.cmd, rebuild.cmd, 
	  runNode.cmd, and stopNode.cmd: All of these command files are used to aid the installer to 
	  successfully place files on the system. Additionally, some are used to help the connect.exe
	  and disconnect.exe executables to run properly.

	- package.json, package-lock.json - These files hold the attributes and file description for 
	  the SerialPortServer.js file

	- README.txt - This file you are currently reading.

The only program you as a user will need to execute is "Serial Port Server.lnk" or connect.exe, which 
are virtually the same executable. There is no need to execute any other program by itself. Further information
for each individual file can be found inside each of these.