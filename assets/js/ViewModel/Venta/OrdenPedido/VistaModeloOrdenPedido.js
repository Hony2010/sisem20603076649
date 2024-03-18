VistaModeloOrdenPedido = function(data) {
  var options = {
    IDForm : "#formOrdenPedido",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderOrdenPedido",
    IDModalComprobanteVenta : "#modalOrdenPedido"
  };
  return new VistaModeloComprobanteVenta(data,options);
}
