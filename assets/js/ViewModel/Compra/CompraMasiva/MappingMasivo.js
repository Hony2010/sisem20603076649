var options_CV = {
  IDForm : "#formCompraMasiva",
  IDModalCliente :"#modalCliente" ,
  IDPanelHeader : "#panelHeaderCompraMasiva",
  IDModalCompraMasiva : "#modalCompraMasiva"
};

var _MappingMasivo = {
    'CompraMasiva': {
        create: function (options) {
            if (options)
              return new VistaModeloCompraMasiva(options.data, options_CV);
            }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingMasivo = Object.assign(_MappingMasivo,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
