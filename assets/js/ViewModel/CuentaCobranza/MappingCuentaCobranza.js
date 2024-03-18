var configCobranzaCliente = {
  IDForm : "#formCobranzaCliente",
  IDModalForm : "#modalCobranzaCliente",
};

var configCanjeLetraCobrar = {
  IDForm : "#formCanjeLetraCobrar",
  IDModalForm : "#modalCanjeLetraCobrar",
};

var MappingCuentaCobranza = {
  'CuentasCobranza': {
    create: function (options) {
      if (options)
      return new VistaModeloCuentaCobranza(options.data);
    }
  },
  'CobranzaCliente': {
    create: function (options) {
      if (options)
      return new VistaModeloCobranzaCliente(options.data,configCobranzaCliente);
    }
  },
  'CanjeLetraCobrar': {
    create: function (options) {
      if (options)
      return new VistaModeloCanjeLetraCobrar(options.data,configCanjeLetraCobrar);
    }
  },
  'CanjesLetraCobrar': {
    create: function (options) {
      if (options)
      return new VistaModeloCanjeLetraCobrar(options.data,configCanjeLetraCobrar);
    }
  },
  'PendientesLetraCobrar': {
    create: function (options) {
      if (options)
      return new VistaModeloDetalleCanjeLetraCobrar(options.data);
    }
  }
}
