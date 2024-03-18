window.Motivo = {"Data":[], "Reglas":[]};
window.Motivo.CambiarFiltro = function(item, callback, event)
{
  window.DataMotivosNotaCredito.forEach(function(elemento) {
    if(item == elemento.Data.IdMotivoNotaCredito)
    {
      window.Motivo.Data = elemento.Data;
      window.Motivo.Reglas = elemento.Reglas;
    }
  });
  console.log(this);
  callback(this, event);
};

var ignoreNotaCredito = {
  "ignore": [
    "DetallesComprobanteVenta",
    "__ko_mapping__"
  ]};

var mapeadoNotaCredito = {
  "Total": "",
  "IGV": "",
  "DescuentoGlobal": "",
  "ValorVentaGravado": "",
  "ValorVentaNoGravado": "",
  "ValorVentaInafecto": "",
  "IdMoneda": "",
  "MontoLetra": "",
  "IdFormaPago": ""
};

var mapeoPropiedadesNotaCredito = {
  "IdMoneda": "",
  "IdFormaPago": ""
};


var ignore_array_data = {
  "ignore": [
    "__ko_mapping__",
    "NuevoDetalleComprobanteVenta",
    "SeriesDocumento",
    "FormasPago",
    "Monedas",
    "Cliente",
    "TiposTarjeta",
    "MotivosNotaCredito",
    "ConceptosNotaCredito",
    "Sedes"
  ]};
