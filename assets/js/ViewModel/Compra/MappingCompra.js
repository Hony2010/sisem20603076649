var configCC = {
  IDForm: "#formComprobanteCompra",
  IDModalProveedor: "#modalProveedor",
  IDPanelHeader: "#panelheaderComprobanteCompra",
  IDModalComprobanteCompra: "#modalComprobanteCompra",
  IDModalBusquedaDocumentoIngreso: "#modalBusquedaDocumentoIngreso",
  IDModalComprobanteCompraAlternativo: "#modalComprobanteCompraAlternativo",
  IDFormAlternativo: "#formComprobanteCompraAlternativo"
};
var configG = {
  IDForm: "#formCompraGasto",
  IDModalProveedor: "#modalProveedor",
  IDPanelHeader: "#panelHeaderCompraGasto",
  IDModalComprobanteCompra: "#modalCompraGasto"
};
var configCA = {
  IDForm: "#formCompraCostoAgregado",
  IDModalProveedor: "#modalProveedor",
  IDPanelHeader: "#panelHeaderCompraCostoAgregado",
  IDModalComprobanteCompra: "#modalCompraCostoAgregado"
};

var configNCC = {
  IDForm: "#formNotaCreditoCompra",
  IDPanelHeader: "#panelheaderNotaCreditoCompra",
  IDModalComprobanteCompra: "#modalNotaCreditoCompra",
  IDModalBusqueda: "#BusquedaComprobantesCompraModelNC"
};

var configNDC = {
  IDForm: "#formNotaDebitoCompra",
  IDPanelHeader: "#panelheaderNotaDebitoCompra",
  IDModalComprobanteCompra: "#modalNotaDebitoCompra",
  IDModalBusqueda: "#BusquedaComprobantesCompraModelND"
};

var _MappingCompra = {
  'ComprobantesCompra': {
    create: function (options) {
      if (options)
        return new VistaModeloComprobanteCompra(options.data, configCC);
    }
  },
  'ComprobanteCompra': {
    create: function (options) {
      if (options)
        return new VistaModeloComprobanteCompra(options.data, configCC);
    }
  },
  'DetallesComprobanteCompra': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleComprobanteCompra(options.data);
        }
      }
    }
  },
  'DetalleComprobanteCompra': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleComprobanteCompra(options.data);
        }
      }
    }
  },
  'CompraGastos': {
    create: function (options) {
      if (options)
        return new VistaModeloCompraGasto(options.data, configG);
    }
  },
  'CompraGasto': {
    create: function (options) {
      if (options)
        return new VistaModeloCompraGasto(options.data, configG);
    }
  },
  'DetallesCompraGasto': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleCompraGasto(options.data);
        }
      }
    }
  },
  'DetalleCompraGasto': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleCompraGasto(options.data);
        }
      }
    }
  },
  'CompraCostosAgregado': {
    create: function (options) {
      if (options)
        return new VistaModeloCompraCostoAgregado(options.data, configCA);
    }
  },
  'CompraCostoAgregado': {
    create: function (options) {
      if (options)
        return new VistaModeloCompraCostoAgregado(options.data, configCA);
    }
  },
  'DetallesCompraCostoAgregado': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleCompraCostoAgregado(options.data);
        }
      }
    }
  },
  'DetalleCompraCostoAgregado': {
    create: function (options) {
      if (options) {
        if (options.skip != undefined) {
          return new VistaModeloDetalleCompraCostoAgregado(options.data);
        }
      }
    }
  },
  'FiltrosCostoAgregado': {
    create: function (options) {
      if (options)
        return new VistaModeloBusquedaDocumentoCompra(options.data);
    }
  },
  'FiltrosIngreso': {
    create: function (options) {
      if (options)
        return new VistaModeloFiltroDocumentoIngreso(options.data);
    }
  },
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
        return new VistaModeloNotaCreditoCompra(options.data, configNCC);
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
  },
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
        return new VistaModeloNotaDebitoCompra(options.data, configNDC);
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
  },
  'BusquedaAvanzadaProducto': {
    create: function (options) {
      if (options)
        return new VistaModeloBusquedaAvanzadaProducto(options.data);
    }
  },
}

//Eliminamos los modelos : Alumnos, Alumno
delete MappingCatalogo.Alumno;
delete MappingCatalogo.Alumnos;
var MappingCompra = Object.assign(_MappingCompra, MappingCatalogo, MappingConfiguracionCatalogo, MappingConfiguracionGeneral);
