window.Motivo = {"Data":[], "Reglas":[]};
window.Motivo.CambiarFiltro = function(item, callback, event)
{
  window.DataMotivosNotaDebito.forEach(function(elemento) {
    if(item == elemento.Data.IdMotivoNotaDebito)
    {
      window.Motivo.Data = elemento.Data;
      window.Motivo.Reglas = elemento.Reglas;
    }
  });
  console.log(this);
  callback(this, event);
};

var ignoreNotaDebito = {
  "ignore": [
    "DetallesComprobanteVenta",
    "DetallesNotaDebito",
    "__ko_mapping__"
  ]};

var mapeadoNotaDebito = {
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

var mapeoPropiedadesNotaDebito = {
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
