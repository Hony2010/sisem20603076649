@echo off
echo INICIO BACKUP DE BASE DE DATOS SISEM

set unidad=%cd:~0,2%
set basedatos=%1
set usuario=%2
set pass=%3
set port=%4
set destino=%5
set zip=%unidad%\xampp\recursos\7-Zip\7z.exe

%unidad%\xampp\mysql\bin\mysqldump -u%usuario% -p%pass% --port=%port% --databases %basedatos% > %destino%
%zip% a %destino%.zip %destino%
del %destino%

echo FIN BACKUP BASE DE DATOS SISEM