window.Motivo = {"Data":[], "Reglas":[]};
window.Motivo.CambiarFiltro = function(item, callback, event)
{
  window.DataMotivosNotaCreditoCompra.forEach(function(elemento) {
    if(item == elemento.Data.IdMotivoNotaCredito)
    {
      window.Motivo.Data = elemento.Data;
      window.Motivo.Reglas = elemento.Reglas;
    }
  });
  console.log(this);
  callback(this, event);
};

var ignoreNotaCreditoCompra = {
  "ignore": [
    "DetallesComprobanteCompra",
    "__ko_mapping__"
  ]};

var mapeadoNotaCreditoCompra = {
  "Total": "",
  "IGV": "",
  "DescuentoGlobal": "",
  "ValorCompraGravado": "",
  "ValorCompraNoGravado": "",
  "IdMoneda": "",
  "MontoLetra": "",
  "IdFormaPago": ""
};

var mapeoPropiedadesNotaCreditoCompra = {
  "IdMoneda": "",
  "IdFormaPago": ""
};


var ignore_array_data = {
  "ignore": [
    "__ko_mapping__",
    "NuevoDetalleComprobanteCompra",
    "SeriesDocumento",
    "FormasPago",
    "Monedas",
    "Cliente",
    "TiposTarjeta",
    "MotivosNotaCreditoCompra",
    "ConceptosNotaCreditoCompra",
    "Sedes"
  ]};
