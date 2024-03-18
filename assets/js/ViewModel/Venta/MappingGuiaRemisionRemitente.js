var configGRR = {
  IdForm: "#formGuiaRemisionRemitente",
  IdModal: "#modalGuiaRemisionRemitente"
};

var MappingGuiaRemisionRemitente = {
  'GuiaRemisionRemitente': {
    create: function (options) {
      if (options)
        return new VistaModeloGuiaRemisionRemitente(options.data, configGRR);
    }
  },

  'GuiasRemisionRemitente': {
    create: function (options) {
      if (options)
        return new VistaModeloGuiaRemisionRemitente(options.data, configGRR);
    }
  },

  'DetalleGuiaRemisionRemitente': {
    create: function (options) {
      if (options)
        return new VistaModeloDetalleGuiaRemisionRemitente(options.data, configGRR);
    }
  },
  'BuscadorFacturasGuia': {  
    create: function (options) {
      if (options) {
        return new BusquedaFacturasGuiaViewModel(options.data);
      }
    }
  }

}