VistaModeloBoletaVenta = function(data) {
  var options = {
    IDForm : "#formBoletaVenta",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderBoletaVenta",
    IDModalComprobanteVenta : "#modalBoletaVenta"  
  };
  return new VistaModeloComprobanteVenta(data,options);
}
