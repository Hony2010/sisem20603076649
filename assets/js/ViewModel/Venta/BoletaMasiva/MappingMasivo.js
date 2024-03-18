var options_CV = {
  IDForm : "#formBoletaMasiva",
  IDModalCliente :"#modalCliente" ,
  IDPanelHeader : "#panelHeaderBoletaMasiva",
  IDModalBoletaMasiva : "#modalBoletaMasiva"
};

var _MappingMasivo = {
    'BoletaMasiva': {
        create: function (options) {
            if (options)
              return new VistaModeloBoletaMasiva(options.data, options_CV);
            }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingMasivo = Object.assign(_MappingMasivo,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
