var configCV = {
  IDForm : "#formNotaDebitoCompra",
  IDPanelHeader : "#panelheaderNotaDebitoCompra",
  IDModalComprobanteCompra : "#modalNotaDebitoCompra",
  IDModalBusqueda : "#BusquedaComprobantesCompraModelND"
};

var _MappingCompra = {
    'FiltrosND': {
        create: function (options) {
            if (options)
              return new FiltrosNDModel(options.data);
            }
    },
    'MiniComprobantesCompraND': {
        create: function (options) {
            if (options)
              return new MiniComprobantesCompraNDModel(options.data);
              // return new MiniComprobantesCompraNDModel(options.parent);
            }
    },
    'BusquedaComprobantesCompraND': {
        create: function (options) {
            if (options)
              return new BusquedaComprobantesCompraNDModel(options.data);
            }
    },
    'BusquedaComprobanteCompraND': {
        create: function (options) {
            if (options)
              return new BusquedaComprobanteCompraNDModel(options.data);
            }
    },
    'NotaDebitoCompra': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaDebitoCompra(options.data, configCV);
            }
    },
    'DetallesNotaDebitoCompra': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaDebitoCompra(options.data);
              }
            }
      }
    },
    'DetalleNotaDebitoCompra': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaDebitoCompra(options.data);
              }
            }
      }
    }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingCompra = Object.assign(_MappingCompra,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
