var configNC = {
  IDForm: "#formNotaCredito",
  IDPanelHeader: "#panelheaderNotaCredito",
  IDModalComprobanteVenta: "#modalNotaCredito",
  IDModalBusqueda: "#BusquedaComprobantesVentaModelNC"
};

var configNV = {
  IDForm: "#formNotaCreditoDevolucion",
  IDPanelHeader: "#panelheaderNotaCreditoDevolucion",
  IDModalComprobanteVenta: "#modalNotaCreditoDevolucion",
  IDModalBusqueda: "#BusquedaComprobantesVentaModelNC"
};


var _MappingVenta = {
  'FiltrosNC': {
    create: function (options) {
      if (options)
        return new FiltrosNCModel(options.data);
    }
  },
  'MiniComprobantesVentaNC': {
    create: function (options) {
      if (options)
        return new MiniComprobantesVentaNCModel(options.data);
      // return new MiniComprobantesVentaNCModel(options.parent);
    }
  },
  'BusquedaComprobantesVentaNC': {
    create: function (options) {
      if (options)
        return new BusquedaComprobantesVentaNCModel(options.data);
    }
  },
  'BusquedaComprobanteVentaNC': {
    create: function (options) {
      if (options)
        return new BusquedaComprobanteVentaNCModel(options.data);
    }
  },
  'NotaCredito': {
    create: function (options) {
      var config = { NV: configNV, NC: configNC };
      if (options)
      return new VistaModeloNotaCredito(options.data, config);
    }
  },
  'DetallesNotaCredito': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleNotaCredito(options.data);
        }
      }
    }
  },
  'DetalleNotaCredito': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleNotaCredito(options.data);
        }
      }
    }
  }
}
//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingVenta = Object.assign(_MappingVenta, MappingCatalogo, MappingConfiguracionCatalogo, MappingConfiguracionGeneral);
