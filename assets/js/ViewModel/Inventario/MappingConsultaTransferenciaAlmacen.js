// var configCobranzaCliente = {
//   IDForm : "#formCobranzaCliente",
//   IDModalForm : "#modalCobranzaCliente",
// };

var MappingConsultaTransferenciaAlmacen = {
  'TransferenciasAlmacen': {
    create: function (options) {
        if (options)
          return new VistaModeloTransferenciaAlmacen(options.data);
        }
},
}
