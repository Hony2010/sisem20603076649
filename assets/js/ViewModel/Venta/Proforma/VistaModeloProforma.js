VistaModeloProforma = function(data) {
  var options = {
    IDForm : "#formProforma",
    IDModalCliente :"#modalCliente" ,
    IDPanelHeader : "#panelHeaderProforma",
    IDModalComprobanteVenta : "#modalProforma"  
  };
  return new VistaModeloComprobanteVenta(data,options);
}
