VistaModeloComprobanteCaja = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingCaja, self);


    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarValidator(event);
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {

      }
    }

    self.MontoIngreso = ko.computed( function () {

      if (self.IndicadorTipoComprobante() == INDICADOR_TIPO_COMPROBANTE.INGRESO) {
        var monto = accounting.formatNumber(parseFloatAvanzado(self.MontoComprobante()), NUMERO_DECIMALES_VENTA);

      } else {
        var monto = '0.00';
      }
      return self.SimboloMoneda() + ' ' + monto;
    }, this);

    self.MontoSalida = ko.computed( function () {
      if (self.IndicadorTipoComprobante() == INDICADOR_TIPO_COMPROBANTE.SALIDA || self.IndicadorTipoComprobante() == INDICADOR_TIPO_COMPROBANTE.TRANFERENCIA) {
        var monto = accounting.formatNumber(parseFloatAvanzado(self.MontoComprobante()), NUMERO_DECIMALES_VENTA);
      } else {
        var monto = '0.00';
      }
      return self.SimboloMoneda() + ' ' + monto;
    }, this);


    self.OnDisableBtnEditar = ko.computed( function () {
      if (self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_VENTA_CONTADO || self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_COMPRA_CONTADO || self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_SALDO_INICIAL || self.IndicadorEstado() == ESTADO.ANULADO) {
        var disable = true;
      } else {
        var disable = false;
      }
      return disable;
    }, this);

    self.OnDisableBtnAnular = ko.computed( function () {
      if (self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_VENTA_CONTADO || self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_COMPRA_CONTADO || self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_SALDO_INICIAL || self.IndicadorEstado() == ESTADO.ANULADO) {
        var disable = true;
      } else {
        var disable = false;
      }
      return disable;
    }, this);

    self.OnDisableBtnBorrar = ko.computed( function () {
      if (self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_VENTA_CONTADO || self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_COMPRA_CONTADO || self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_SALDO_INICIAL) {
        var disable = true;
      } else {
        var disable = false;
      }
      return disable;
    }, this);
}
