var _MappingInventario = {
    'InventariosInicial': {
        create: function (options) {
            if (options)
              return new VistaModeloInventarioInicial(options.data);
            }
    },
    'InventarioInicial': {
        create: function (options) {
            if (options)
              return new VistaModeloInventarioInicial(options.data);
            }
    },
    'DetallesInventarioInicial': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleInventarioInicial(options.data);
              }
            }
      }
    },
    'DetalleInventarioInicial': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleInventarioInicial(options.data);
              }
            }
      }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingInventario = Object.assign(_MappingInventario,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
