var configCV = {
  IDForm: "formComprobanteVenta",
  IDModalCliente: "modalCliente",
  IDModalBusquedaProducto: "modalBusquedaAvanzadaProducto",
  IDPanelHeader: "panelHeaderComprobanteVenta",
  IDModalComprobanteVenta: "modalComprobanteVenta"
};
var configNC = {
  IDForm: "#formNotaCredito",
  IDPanelHeader: "#panelheaderNotaCredito",
  IDModalComprobanteVenta: "#modalNotaCredito",
  IDModalBusqueda: "#BusquedaComprobantesVentaModelNC"
};

var configND = {
  IDForm: "#formNotaDebito",
  IDPanelHeader: "#panelheaderNotaDebito",
  IDModalComprobanteVenta: "#modalNotaDebito",
  IDModalBusqueda: "#BusquedaComprobantesVentaModelND"
};

var configNV = {
  IDForm: "#formNotaCreditoDevolucion",
  IDPanelHeader: "#panelheaderNotaCreditoDevolucion",
  IDModalComprobanteVenta: "#modalNotaCreditoDevolucion",
  IDModalBusqueda: "#BusquedaComprobantesVentaModelNC"
};

var _MappingVenta = {
  'ComprobantesVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloComprobanteVenta(options.data, configCV);
    }
  },
  'ComprobanteVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloComprobanteVenta(options.data, configCV);
    }
  },
  'DetallesComprobanteVenta': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleComprobanteVenta(options.data);
        }
      }
    }
  },
  'DetalleComprobanteVenta': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleComprobanteVenta(options.data);
        }
      }
    }
  },
  'FacturaVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloFacturaVenta(options.data);
    }
  },
  'BoletaVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloBoletaVenta(options.data);
    }
  },
  'BoletaViajeVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloBoletaViajeVenta(options.data);
    }
  },
  'Proforma': {
    create: function (options) {
      if (options)
        return new VistaModeloProforma(options.data);
    }
  },
  'PuntoVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloPuntoVenta(options.data);
    }
  },
  'Comanda': {
    create: function (options) {
      if (options)
        return new VistaModeloComanda(options.data);
    }
  },
  'PreVenta': {
    create: function (options) {
      if (options)
        return new VistaModeloPreVenta(options.data);
    }
  },
  'ControlMesa': {
    create: function (options) {
      if (options)
        return new VistaModeloControlMesa(options.data);
    }
  },
  'OrdenPedido': {
    create: function (options) {
      if (options)
        return new VistaModeloOrdenPedido(options.data);
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
  'NotaDebito': {
    create: function (options) {
      if (options)
        return new VistaModeloNotaDebito(options.data, configND);
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
      // return new MiniComprobantesVentaNDModel(options.parent);
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
  'BoletaMasiva': {
    create: function (options) {
      if (options)
        return new VistaModeloBoletaMasiva(options.data);
    }
  },
  'BusquedaAvanzadaProducto': {
    create: function (options) {
      if (options)
        return new VistaModeloBusquedaAvanzadaProducto(options.data);
    }
  },
  'TecladoVirtual': {
    create: function (options) {
      if (options)
        return new VistaModeloTecladoVirtual(options.data);
    }
  },
  'VerificacionCorrelatividad': {
    create: function (options) {
      if (options)
        return new VistaModeloVerificacionCorrelatividad(options.data);
    }
  },
  'DetallesVerificacionCorrelatividad': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleVerificacionCorrelatividad(options.data);
        }
      }
    }
  },
  'BusquedaProformaVenta': {
    create: function (options) {
      if (options) {
        return new VistaModeloBusquedaProforma(options.data);
      }
    }
  },
  'CuotasPagoClienteComprobanteVenta': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloCuotaPagoClienteComprobanteVenta(options.data);
        }
      }
    }
  } ,
 'CuotaPagoClienteComprobanteVenta': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloCuotaPagoClienteComprobanteVenta(options.data);
        }
      }
    }
  }   

}

//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;

var MappingVenta = Object.assign(_MappingVenta, MappingCatalogo, MappingConfiguracionCatalogo, MappingConfiguracionGeneral);
