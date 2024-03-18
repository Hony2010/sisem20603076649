VistaModeloCobranzaRapida = function (data, cabecera) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
  self.Cabecera = cabecera;
  self.MontoACobrar = ko.observable(data.MontoACobrar ? data.MontoACobrar : "");
  self.UltimoItem = ko.observable(false);
  ModeloCobranzaRapida.call(this, self);

  self.Usuarios = ko.observable(ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_USUARIOS));

  self.titulo = "Cobranza Rápida";

  self.InicializarCobranza = function (data, event) {
    if (event) {
      $(".fecha").inputmask({ "mask": "99/99/9999" });

      var target = { id: self.InputDocumentoReferencia() };
      $(target.id).autoCompletadoPendientesCobranzaCliente(target, event, self.ValidarAutoCompletadoPendientesCobranzaCliente);
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      self.OnClickNuevaCobranza(data, event);
      self.Cabecera.OnFocus(data, event);
    }
  }

  self.OnClickNuevaCobranza = function (data, event) {
    if (event) {
      if (data.UltimoItem()) {
        self.Cabecera.OnAgregarNuevaCobranza(data, event);
      }
    }
  }

  self.InputDocumentoReferencia = ko.computed(function () {
    return self.IdComprobanteCaja ? "#inputDocumentoReferencia_" + self.IdComprobanteCaja() : "";
  }, this);

  self.DocumentoReferencia = ko.computed(function () {
    return self.Documento ? self.Documento() : "";
  }, this);

  self.OnChangeInputMontoACobrar = function (data, event) {
    if (event) {

      var $data = {
        TipoCambioVenta: self.ValorTipoCambio(),
        MontoCobrado: self.MontoACobrar(),
        MontoSoles: self.MontoACobrar(),
        MontoDolares: parseFloatAvanzado(self.MontoACobrar()) * parseFloatAvanzado(self.ValorTipoCambio()),
        NuevoSaldo: parseFloatAvanzado(self.SaldoPendiente()) - parseFloatAvanzado(self.MontoACobrar()),
        Importe: self.MontoACobrar()
      }

      var result = ko.mapping.fromJS($data, {}, self.DetallesCobranzaCliente()[0]);

      self.Importe(result.MontoSoles());
      self.MontoComprobante(result.MontoSoles());

      console.log(ko.mapping.toJS(result));
    }
  }

  self.ValidarDocumentoReferencia = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) { self.IdComprobanteVenta(""); }
      });
    }
  }

  self.ValidarAutoCompletadoPendientesCobranzaCliente = function (data, event) {
    if (event) {
      var $inputDocumentoReferencia = $(self.InputDocumentoReferencia());
      $inputDocumentoReferencia[0].value = $inputDocumentoReferencia.val().trim();
      if ($inputDocumentoReferencia.val().length != 11) {
        $inputDocumentoReferencia.attr("data-validation-error-msg", "Ingrese el documento correcto");
      } else {
        $inputDocumentoReferencia.attr("data-validation-error-msg", "No se han encontrado resultados para tu búsqueda");
      }

      var $evento = { target: self.InputDocumentoReferencia() };

      if (data === -1) {
        if ($inputDocumentoReferencia.attr("data-validation-text-found") === $inputDocumentoReferencia.val()) {
          self.ValidarDocumentoReferencia(data, $evento);
        } else {
          $inputDocumentoReferencia.attr("data-validation-text-found", "");
          self.ValidarDocumentoReferencia(data, $evento);
        }
      } else {
        if (($inputDocumentoReferencia.attr("data-validation-text-found") !== $inputDocumentoReferencia.val()) || ($inputDocumentoReferencia.val() == "")) {
          $inputDocumentoReferencia.attr("data-validation-text-found", data.Documento);
        }

        $("#loader").show();
        self.ConsultarCuentaCobranzaPorIdComprobanteVenta(data, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            $data[0].IdPersona = $data[0].IdCliente;
            ko.mapping.fromJS($data[0], {}, self);
            ko.mapping.fromJS($data[0], {}, self.DetallesCobranzaCliente()[0]);
            self.ValidarDocumentoReferencia(data, $evento);
            self.NextFocusAutoComplete(data, $evento);
          } else {
            alertify.alert(self.titulo, $data.error, function () { });
          }
        });
      }
    }
  }

  self.NextFocusAutoComplete = function (data, event) {
    if (event) {
      var $input = $(event.target);
      var pos = $input.closest("Form").find("input, select").not(':disabled').index($input);
      $input.closest("Form").find("input, select").not(':disabled').eq(pos + 1).focus();
    }
  }


}
