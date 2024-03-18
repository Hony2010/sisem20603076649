VistaModeloPuntoVenta = function(data) {
  var options = {
    IDForm : "#formPuntoVenta",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderPuntoVenta",
    IDModalComprobanteVenta : "#modalPuntoVenta"
  };
  return new VistaModeloComprobanteVenta(data,options);
}
