window.Motivo = {"Data":[], "Reglas":[]};
window.Motivo.CambiarFiltro = function(item, callback, event)
{
  window.DataMotivos.forEach(function(elemento) {
    if(item == elemento.Data.IdMotivoNotaSalida)
    {
      window.Motivo.Data = elemento.Data;
      window.Motivo.Reglas = elemento.Reglas;
    }
  });
  console.log(this);
  callback(this, event);
};

var ignoreNotaSalida = {
  "ignore": [
    "DetallesNotaSalida",
    "__ko_mapping__"
  ]};

var mapeadoNotaSalida = {
  "Total": "",
  "IGV": "",
  "DescuentoGlobal": "",
  "ValorVentaGravado": "",
  "ValorVentaNoGravado": "",
  "IdMoneda": "",
  "MontoLetra": "",
  "IdFormaPago": ""
};

var mapeoPropiedadesNotaSalida = {
  "IdMoneda": "",
  "IdFormaPago": ""
};

var ignore_array_data = {
  "ignore": [
    "__ko_mapping__",
    "NuevoDetalleNotaSalida",
    "SeriesDocumento",
    "FormasPago",
    "Monedas",
    "Cliente",
    "TiposTarjeta",
    "MotivosNotaCredito",
    "ConceptosNotaCredito",
    "Sedes"
  ]};
// var FiltrarDataNotaSalida = {
//   "ignore": [
//       "Sedes",
//       "MotivosNotaSalida",
//       "NuevoCliente",
//       "Cliente"
//   ]};
