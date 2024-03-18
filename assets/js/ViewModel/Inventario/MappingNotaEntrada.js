
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
              return new VistaModeloNotaEntrada(options.data);
            }
    },
    'NotaEntrada': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaEntrada(options.data);
            }
    },
    'NotasEntrada': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaEntrada(options.data);
            }
    },
    'DetallesNotaEntrada': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaEntrada(options.data);
              }
            }
      }
    },
    'DetalleNotaEntrada': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaEntrada(options.data);
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
