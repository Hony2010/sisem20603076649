window.Motivo = {"Data":[], "Reglas":[]};
window.Motivo.CambiarFiltro = function(item, callback, event)
{
  window.DataMotivos.forEach(function(elemento) {
    if(item == elemento.Data.IdMotivoNotaEntrada)
    {
      window.Motivo.Data = elemento.Data;
      window.Motivo.Reglas = elemento.Reglas;
    }
  });
  console.log(this);
  callback(this, event);
};

var ignoreNotaEntrada = {
  "ignore": [
    "DetallesNotaEntrada",
    "__ko_mapping__"
  ]};

var mapeadoNotaEntrada = {
  "Total": "",
  "IGV": "",
  "DescuentoGlobal": "",
  "ValorVentaGravado": "",
  "ValorVentaNoGravado": "",
  "IdMoneda": "",
  "MontoLetra": "",
  "IdFormaPago": ""
};

var mapeoPropiedadesNotaEntrada = {
  "IdMoneda": "",
  "IdFormaPago": ""
};

var ignore_array_data = {
  "ignore": [
    "__ko_mapping__",
    "NuevoDetalleNotaEntrada",
    "SeriesDocumento",
    "FormasPago",
    "Monedas",
    "Cliente",
    "TiposTarjeta",
    "MotivosNotaCredito",
    "ConceptosNotaCredito",
    "Sedes"
  ]};
// var FiltrarDataNotaEntrada = {
//   "ignore": [
//       "Sedes",
//       "MotivosNotaEntrada",
//       "NuevoCliente",
//       "Cliente"
//   ]};
