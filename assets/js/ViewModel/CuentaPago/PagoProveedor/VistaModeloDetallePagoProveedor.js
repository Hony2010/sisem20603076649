VistaModeloDetallePagoProveedor = function (data, $parent) {
    var self = this;
    self.Cabecera = $parent;
    ko.mapping.fromJS(data, MappingCuentaPago, self);

    var $form = $(self.Cabecera.Options.IDForm);

    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.OnChangeMontoPago(self, event);
        self.InicializarValidator(event);
      }
    }

    self.OnFocus = function (data, event) {
      if (event) {
          self.Cabecera.OnFocus(data, event)
      }
    }

    self.OnKeyEnter = function (data, event) {
      if (event) {
          self.Cabecera.OnKeyEnter(data, event)
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {
      }
    }

    self.ValidarMontoSoles =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarMontoDolares =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }
    self.OnEnableMontoSoles = ko.computed(function () {
      return self.Cabecera.IdMoneda() == ID_MONEDA_SOLES ? true : false;
    }, this)

    self.OnEnableMontoDolares = ko.computed(function () {
      return self.Cabecera.IdMoneda() == ID_MONEDA_DOLARES ? true : false;
    }, this)

    self.OnChangeMontoPago = function (data, event) {
      if (event) {
        var valorTipoCambio = parseFloatAvanzado(self.Cabecera.ValorTipoCambio());
        var montoSoles = parseFloatAvanzado(self.MontoSoles());
        var montoDolares = parseFloatAvanzado(self.MontoDolares());

        switch (self.Cabecera.IdMoneda()) {
          case String(ID_MONEDA_SOLES):
            self.Importe(self.MontoSoles());
            self.MontoDolares(valorTipoCambio > 0 ? montoSoles / valorTipoCambio : 0);
          break;
          case String(ID_MONEDA_DOLARES):
            self.Importe(self.MontoDolares());
            self.MontoSoles(valorTipoCambio > 0 ? montoDolares * valorTipoCambio : 0);
          break;
          default:
            self.Importe(0);
            self.MontoSoles(0);
        }
        self.Cabecera.CalcularTotalComprobante(data, event);
      }
    }

    self.NuevoSaldo = ko.computed(function () {
      var saldoPendiente = parseFloatAvanzado(self.SaldoPendiente())
      var montoSoles = parseFloatAvanzado(self.MontoSoles());
      var montoDolares = parseFloatAvanzado(self.MontoDolares());

      switch (self.IdMoneda()) {
        case String(ID_MONEDA_SOLES):
          var nuevoSaldo = saldoPendiente - montoSoles;
        break;
        case String(ID_MONEDA_DOLARES):
          var nuevoSaldo = saldoPendiente - montoDolares;
        break;
        default:
          var nuevoSaldo = 0;
      }

      return nuevoSaldo.toFixed(2);
    }, this)
}
