VistaModeloBoletaViajeVenta = function(data) {
  var options = {
    IDForm : "#formBoletaViajeVenta",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderBoletaViajeVenta",
    IDModalComprobanteVenta : "#modalBoletaViajeVenta"  
  };
  return new VistaModeloComprobanteVenta(data,options);
}
