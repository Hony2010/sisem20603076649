var configCV = {
  IDForm : "formComprobanteVenta",
  IDModalCliente :"modalCliente" ,
  IDModalBusquedaProducto :"modalBusquedaAvanzadaProducto" ,
  IDPanelHeader : "panelHeaderComprobanteVenta",
  IDModalComprobanteVenta : "modalComprobanteVenta"
};

var MappingVenta = {
    'ComprobantesVenta': {
        create: function (options) {
            if (options)
              return new VistaModeloValidacionComprobantes(options.data,configCV);
            }
    }
}
