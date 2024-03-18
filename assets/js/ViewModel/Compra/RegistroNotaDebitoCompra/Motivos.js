window.Motivo = {"Data":[], "Reglas":[]};
window.Motivo.CambiarFiltro = function(item, callback, event)
{
  window.DataMotivosNotaDebitoCompra.forEach(function(elemento) {
    if(item == elemento.Data.IdMotivoNotaDebito)
    {
      window.Motivo.Data = elemento.Data;
      window.Motivo.Reglas = elemento.Reglas;
    }
  });
  console.log(this);
  callback(this, event);
};

var ignoreNotaDebitoCompra = {
  "ignore": [
    "DetallesComprobanteCompra",
    "__ko_mapping__"
  ]};

var mapeadoNotaDebitoCompra = {
  "Total": "",
  "IGV": "",
  "DescuentoGlobal": "",
  "ValorCompraGravado": "",
  "ValorCompraNoGravado": "",
  "IdMoneda": "",
  "MontoLetra": "",
  "IdFormaPago": ""
};

var mapeoPropiedadesNotaDebitoCompra = {
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
    "MotivosNotaDebitoCompra",
    "ConceptosNotaDebitoCompra",
    "Sedes"
  ]};
