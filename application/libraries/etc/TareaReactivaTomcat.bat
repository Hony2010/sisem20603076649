@echo off
set carpeta=%1
set carpeta_actual=%cd%
set unidad=%cd:~0,2%
echo ....Eliminando Java....
taskkill /im java.exe /F
echo ....Inicio de catalina.... 
cd /D %unidad%\xampp\tomcat\bin\
start /Min catalina.bat start  
echo ....fin.... 
pause
