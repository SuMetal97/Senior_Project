; This script is perhaps one of the simplest NSIs you can make. All of the
; optional settings are left to their default settings. The installer simply 
; prompts the user asking them where to install, and drops a copy of example1.nsi
; there. 

;--------------------------------

; The name of the installer
Name "SerialPortServerInstaller"

; The file to write
OutFile "spsinstaller.exe"

; Request application privileges for Windows Vista
RequestExecutionLevel admin

; Build Unicode installer
Unicode False

; The default installation directory
InstallDir "C:\SerialPortServer"

;--------------------------------

; Pages

Page directory
Page instfiles

;--------------------------------

; The stuff to install
Section "Serial Port Server" ;No components page, name is not important

  ; Set output path to the installation directory.
  SetOutPath $INSTDIR
  
  ; Put file there
  File /r node_modules
  File connect.exe
  File disconnect.exe
  File openConnect.cmd
  File openDisconnect.cmd
  File rebuild.cmd
  File runNode.cmd
  File stopNode.cmd
  File SerialPortServer.js
  File package-lock.json
  File package.json
  File README.txt

;  Call Rebuild
;  Return

  SetOutPath $DESKTOP
  File "Serial Port Server.lnk"  
  File arrange.cmd
  File clean.cmd

SectionEnd ; end the section

;FUNCTION CALLS
Section "FinishBuildPart1"
  Call Relocate
  Return
SectionEnd

Section "FinishBuildPart2"
  Call Update
  Return
SectionEnd

;-----------------------------------

;FUNCTIONS
Function Relocate
Exec '"$DESKTOP\arrange.cmd"'
FunctionEnd

Function Update
Exec '"C:\Program Files (x86)\SerialPortServer\rebuild.cmd"'
FunctionEnd