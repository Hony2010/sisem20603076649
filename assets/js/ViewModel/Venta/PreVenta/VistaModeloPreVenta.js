VistaModeloPreVenta = function(data) {
  var options = {
    IDForm : "#formPreVenta",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderPreVenta",
    IDModalComprobanteVenta : "#modalPreVenta"
  };
  return new VistaModeloComprobanteVenta(data,options);
}
