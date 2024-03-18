
var _MappingInventario = {
    'Filtros': {
        create: function (options) {
            if (options)
              return new FiltrosModel(options.data);
            }
    },
    'MiniComprobantesVenta': {
        create: function (options) {
            if (options)
              return new MiniComprobantesVentaModel(options.parent);
            }
    },
    'BusquedaComprobantesVenta': {
        create: function (options) {
            if (options)
              return new BusquedaComprobantesVentaModel(options.data);
            }
    },
    'BusquedaComprobanteVenta': {
        create: function (options) {
            if (options)
              return new BusquedaComprobanteVentaModel(options.data);
            }
    },
    'NotasSalida': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaSalida(options.data);
            }
    },
    'NotaSalida': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaSalida(options.data);
            }
    },
    'DetallesNotaSalida': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaSalida(options.data);
              }
            }
      }
    },
    'DetalleNotaSalida': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaSalida(options.data);
              }
            }
      }
    },
    'BusquedaAvanzadaProducto': {
      create: function (options) {
        if (options)
          return new VistaModeloBusquedaAvanzadaProducto(options.data);
      }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingInventario = Object.assign(_MappingInventario,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
