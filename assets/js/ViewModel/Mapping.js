//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var Mapping = Object.assign(MappingCatalogo,MappingVenta);
