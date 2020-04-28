The contents included on this file are three main directories:
	- Serial Port Server
	- Website
	- Windows User Interface

Serial Port Server: This directory contains all of the files which were included and/or used to create the installer
that to provides users with all the required files and executables needed to run the Serial Port Server which is the 
javascript program that listens to the data sent from the website and then redirects this data over to the USB port.

Website: The contents of this directory are exactly what we placed on our "/www" directory on the web server on 
Digital Ocean. These files include the html and php files used for both the front and back-end. Additionally, there
are the javascript file "browserSocket.js" which creates a websocket to send the data over to the Serial Port Server
script and the installer. You can also find the installer on the "attachments" directory. Furthermore, I have included
a text file called "database.txt" which contains the MySQLi code used to create the two tables we used on the website.

Windows User Interface: This directory contains the files which were created and used to build the executables for 
the Serial Port Server. This executables include both the connect.exe and disconnect.exe. 