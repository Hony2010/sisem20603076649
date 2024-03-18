var configCV = {
  IDForm : "#formNotaCreditoCompra",
  IDPanelHeader : "#panelheaderNotaCreditoCompra",
  IDModalComprobanteCompra : "#modalNotaCreditoCompra",
  IDModalBusqueda : "#BusquedaComprobantesCompraModelNC"
};

var _MappingCompra = {
    'FiltrosNC': {
        create: function (options) {
            if (options)
              return new FiltrosNCModel(options.data);
            }
    },
    'MiniComprobantesCompraNC': {
        create: function (options) {
            if (options)
              return new MiniComprobantesCompraNCModel(options.data);
              // return new MiniComprobantesCompraNCModel(options.parent);
            }
    },
    'BusquedaComprobantesCompraNC': {
        create: function (options) {
            if (options)
              return new BusquedaComprobantesCompraNCModel(options.data);
            }
    },
    'BusquedaComprobanteCompraNC': {
        create: function (options) {
            if (options)
              return new BusquedaComprobanteCompraNCModel(options.data);
            }
    },
    'NotaCreditoCompra': {
        create: function (options) {
            if (options)
              return new VistaModeloNotaCreditoCompra(options.data, configCV);
            }
    },
    'DetallesNotaCreditoCompra': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaCreditoCompra(options.data);
              }
            }
      }
    },
    'DetalleNotaCreditoCompra': {
        create: function (options) {
            if (options) {
              if (options.skip != undefined) {
                return new VistaModeloDetalleNotaCreditoCompra(options.data);
              }
            }
      }
    }
}

//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingCompra = Object.assign(_MappingCompra,MappingCatalogo, MappingConfiguracionCatalogo,MappingConfiguracionGeneral);
