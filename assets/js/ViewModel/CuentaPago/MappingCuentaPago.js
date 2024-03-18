var configPagoProveedor = {
  IDForm : "#formPagoProveedor",
  IDModalForm : "#modalPagoProveedor",
};

var MappingCuentaPago = {
  'CuentasPago': {
    create: function (options) {
      if (options)
      return new VistaModeloCuentaPago(options.data);
    }
  },
  'PagoProveedor': {
    create: function (options) {
      if (options)
      return new VistaModeloPagoProveedor(options.data,configPagoProveedor);
    }
  }
}
