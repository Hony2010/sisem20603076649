var configAperturaCaja = {
  IDForm : "#formAperturaCaja",
  IDModalForm : "#modalAperturaCaja",
};
var configCierreCaja = {
  IDForm : "#formCierreCaja",
  IDModalForm : "#modalCierreCaja",
};

var configTransferenciaCaja = {
  IDForm : "#formTransferenciaCaja",
  IDModalForm : "#modalTransferenciaCaja",
};

var configOtroDocumentoIngreso = {
  IDForm : "#formOtroDocumentoIngreso",
  IDModalForm : "#modalOtroDocumentoIngreso",
};

var configOtroDocumentoEgreso = {
  IDForm : "#formOtroDocumentoEgreso",
  IDModalForm : "#modalOtroDocumentoEgreso",
};

var MappingCaja = {
  'ComprobantesCaja': {
    create: function (options) {
      if (options)
      return new VistaModeloComprobanteCaja(options.data);
    }
  },
  'AperturaCaja': {
    create: function (options) {
      if (options)
      return new VistaModeloAperturaCaja(options.data,configAperturaCaja);
    }
  },
  'CierreCaja': {
    create: function (options) {
      if (options)
      return new VistaModeloCierreCaja(options.data,configCierreCaja);
    }
  },
  'TransferenciaCaja': {
    create: function (options) {
      if (options)
      return new VistaModeloTransferenciaCaja(options.data,configTransferenciaCaja);
    }
  },
  'OtroDocumentoIngreso': {
    create: function (options) {
      if (options)
      return new VistaModeloOtroDocumentoIngreso(options.data,configOtroDocumentoIngreso);
    }
  },
  'OtroDocumentoEgreso': {
    create: function (options) {
      if (options)
      return new VistaModeloOtroDocumentoEgreso(options.data,configOtroDocumentoEgreso);
    }
  }
}
