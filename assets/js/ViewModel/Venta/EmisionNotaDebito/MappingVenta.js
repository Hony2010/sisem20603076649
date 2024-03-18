var configCV = {
  IDForm : "#formNotaDebito",
  IDPanelHeader : "#panelheaderNotaDebito",
  IDModalComprobanteVenta : "#modalNotaDebito",
  IDModalBusqueda : "#BusquedaComprobantesVentaModelND"
};

var _MappingVenta = {
    'FiltrosND': {
        create: function (options) {
            if (options)
              return new FiltrosNDModel(options.data);
            }
    },
    'MiniComprobantesVentaND': {
        create: function (options) {
            if (options)
              return new MiniComprobantesVentaNDModel(options.data);
              // return new MiniComprobantesVentaNDModel(options.parent);
            }
    },
    'BusquedaComprobantesVentaND': {
        create: function (options) {
            if (options)
              return new BusquedaComprobantesVentaNDModel(options.data);
            }
    },
    'BusquedaComprobanteVentaND': {
        create: function (options) {
            if (options)
              return new BusquedaComprobanteVentaNDModel(options.data);
            }
    },
    'NotaDebito': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaDebito(options.data, configCV);
            }
    },
    'DetallesNotaDebito': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaDebito(options.data);
              }
            }
      }
    },
    'DetalleNotaDebito': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaDebito(options.data);
              }
            }
      }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingVenta = Object.assign(_MappingVenta,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
