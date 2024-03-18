VistaModeloFacturaVenta = function(data) {
  var options = {
    IDForm : "#formFacturaVenta",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderFacturaVenta",
    IDModalComprobanteVenta : "#modalFacturaVenta",
    IDInputCliente : "#ClienteFV"
  };
  return new VistaModeloComprobanteVenta(data,options);
}
