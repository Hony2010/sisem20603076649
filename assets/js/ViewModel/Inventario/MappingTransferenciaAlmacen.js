var _MappingTransferenciaAlmacen = {
    'TransferenciasAlmacen': {
        create: function (options) {
            if (options)
              return new VistaModeloTransferenciaAlmacen(options.data);
            }
    },
    'TransferenciaAlmacen': {
        create: function (options) {
            if (options)
              return new VistaModeloTransferenciaAlmacen(options.data);
            }
    },
    'DetallesTransferenciaAlmacen': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleTransferenciaAlmacen(options.data);
              }
            }
      }
    },
    'DetalleTransferenciaAlmacen': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleTransferenciaAlmacen(options.data);
              }
            }
      }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingTransferenciaAlmacen = Object.assign(_MappingTransferenciaAlmacen,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
