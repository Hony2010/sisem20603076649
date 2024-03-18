VistaModeloComanda = function(data) {
  var options = {
    IDForm : "#formComanda",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderComanda",
    IDModalComprobanteVenta : "#modalComanda"
  };
  return new VistaModeloComprobanteVenta(data,options);
}
