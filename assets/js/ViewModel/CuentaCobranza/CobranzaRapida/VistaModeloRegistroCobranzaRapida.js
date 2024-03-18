VistaModeloRegistroCobranzaRapida = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingCuentaCobranza, self);
  ModeloCobranzaRapida.call(this, self);

  self.NumeroDocumentoIdentidad = ko.observable('')
  self.RazonSocial = ko.observable('');
  self.IdPersona = ko.observable('');

  self.titulo = "Cobranza rapida"

  self.Inicializar = function () {
    $(".fecha").inputmask({ "mask": "99/99/9999" });

    var target = "#RazonSocialVendedor";
    $(target).autoCompletadoVendedor(window, self.ValidarAutoCompletadoVendedor, target);

    var targetCliente = "#RazonSocialCliente";
    $(targetCliente).autoCompletadoCliente(window, self.ValidarAutoCompletadoVendedor, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, targetCliente);

    self.InicializarValidator();

    self.OnAgregarNuevaCobranza(self, window);
  }

  self.InicializarValidator = function () {

    $.formUtils.addValidator({
      name: 'autocompletado_vendedor',
      validatorFunction: function (value, $el, config, language, $form) {
        var texto = $el.attr("data-validation-text-found");
        var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
        return resultado;
      },
      errorMessageKey: 'badautocompletado_vendedor'
    });

    $.formUtils.addValidator({
      name: 'autocompletado_documento',
      validatorFunction: function (value, $el, config, language, $form) {
        var texto = $el.attr("data-validation-text-found");
        var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
        return resultado;
      },
      errorMessageKey: 'badautocompletado_documento'
    });
  }


  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.ValidarAutoCompletadoVendedor = function (data, event) {
    if (event) {
      var $inputVendedor = $("#RazonSocialVendedor").is(':visible') ? $("#RazonSocialVendedor") : $("#RazonSocialCliente");
      $inputVendedor[0].value = $inputVendedor.val().trim();
      if ($inputVendedor.val().length != 11) {
        $inputVendedor.attr("data-validation-error-msg", "Ingrese el numero de documento correcto");
      } else {
        $inputVendedor.attr("data-validation-error-msg", "No se han encontrado resultados para tu búsqueda");
      }

      var $evento = { target: $("#RazonSocialVendedor").is(':visible') ? "#RazonSocialVendedor" : "#RazonSocialCliente" };

      if (data === -1) {
        if ($inputVendedor.attr("data-validation-text-found") === $inputVendedor.val()) {
          self.ValidarVendedor(data, $evento);
        } else {
          $inputVendedor.attr("data-validation-text-found", "");
          self.ValidarVendedor(data, $evento);
        }
      } else {
        data.RazonSocial = data.RazonSocial ? data.RazonSocial : data.NombrePersona;
        if (($inputVendedor.attr("data-validation-text-found") !== $inputVendedor.val()) || ($inputVendedor.val() == "")) {
          if (data.NumeroDocumentoIdentidad == "") {
            $inputVendedor.attr("data-validation-text-found", data.RazonSocial);
          } else {
            $inputVendedor.attr("data-validation-text-found", data.NumeroDocumentoIdentidad + " - " + data.RazonSocial);
          }
        }

        ko.mapping.fromJS(data, MappingCuentaCobranza, self);
        self.ValidarVendedor(data, $evento);

        $("#FechaInicio").focus();
      }
    }
  }

  self.ValidarVendedor = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdPersona("");
        }
      });
    }
  }

  self.RazonSocialVendedor = ko.computed(function () {
    var resultado = "";
    if (self.RazonSocial() == "") {
      resultado = "";
    } else if (self.NumeroDocumentoIdentidad() == "") {
      resultado = self.RazonSocial();
    } else {
      resultado = self.NumeroDocumentoIdentidad() + ' - ' + self.RazonSocial();
    }

    return resultado;
  }, this);

  self.OnAgregarNuevaCobranza = function (data, event, nuevo = true) {
    if (event) {
      self.data.CobranzasCliente().map(item => item.UltimoItem(false))

      var obj = nuevo ? ko.mapping.toJS(self.data.CobranzaRapida) : data;
      var nuevaCobranza = new VistaModeloCobranzaRapida(obj, self);
      nuevaCobranza.DetallesCobranzaCliente.push(nuevaCobranza.NuevoDetalleCobranzaCliente);
      if (nuevo == false) ko.mapping.fromJS(nuevaCobranza.NuevoDetalleCobranzaCliente, {}, nuevaCobranza.DetallesCobranzaCliente()[0]);
      self.data.CobranzasCliente.push(nuevaCobranza);

      var idMaximo = Math.max.apply(null, ko.utils.arrayMap(self.data.CobranzasCliente(), function (e) { return e.IdComprobanteCaja(); }));
      nuevaCobranza.IdComprobanteCaja(idMaximo == '-Infinity' ? 1 : idMaximo + 1);
      nuevaCobranza.InicializarCobranza(data, event);
      nuevaCobranza.UltimoItem(true);
    }
  }

  self.OnClickBtnRemoveCobranza = function (data, event) {
    if (event) {
      self.data.CobranzasCliente.remove(data);
    }
  }

  self.OnClickBtnGuardarCobranza = function (data, event) {
    if (event) {
      var obj = [];
      ko.utils.arrayForEach(ViewModels.data.CobranzasCliente(), function (item) {
        if (item.Importe() != "") {
          var result = ko.mapping.toJS(item, mappingIgnore);
          result.IdComprobanteCaja = "";
          obj.push(result);
        }
      })

      $("#loader").show();
      self.InsertarCobranzaRapida({ CobranzasCliente: obj }, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          alertify.alert(self.titulo, "se guardo correctamente las cobranzas", function () {
            self.data.CobranzasCliente([]);
            self.OnAgregarNuevaCobranza(data, event);
          });
        } else {
          if ($data.error.msg) mensaje = $data.error.msg;
          else mensaje = $data.error;
          alertify.alert(self.titulo, mensaje, function () { });
        }
      })
    }
  }

  self.OnClickBtnCargarCobranzas = function (data, event, filtros) {
    if (event) {
      var filtros = ko.mapping.toJS(filtros);
      filtros.IdPersona = self.IdPersona()

      if (filtros.IdPersona != '') {
        $("#loader").show();
        self.ConsultarComprobantesVentaClientesConDeudaPorVendedor(filtros, event, self.PostCargarCobranzas);
      } else {
        alertify.alert(self.titulo, "Debe seleccionar un vendedor.", function () { })
      }

    }
  }

  self.PostCargarCobranzas = function (data, event) {
    $("#loader").hide();
    if (!data.error) {
      self.data.CobranzasCliente([]);
      ko.utils.arrayForEach(data, function (item) {
        item.UsuarioCobrador = item.AliasUsuarioVenta
        self.OnAgregarNuevaCobranza(item, event, false);
      });

      self.OnAgregarNuevaCobranza(data, event);

    } else {
      alertify.alert(self.titulo, data.error, function () { });
    }
  }

  self.TotalCobranzaRapida = ko.computed(function (data, event) {
    var cobranzas = self.data.CobranzasCliente();
    var total = 0;

    ko.utils.arrayForEach(cobranzas, function (item) {
      if (item.IdCliente) {
        total = total + parseFloatAvanzado(item.MontoACobrar());
      }
    });

    return total.toFixed(NUMERO_DECIMALES_VENTA);
  }, this)

  self.OnChangeRol = function (data, event) {
    if (event) {
      $("#RazonSocialVendedor").val('');
      $("#RazonSocialVendedor").attr("data-validation-text-found", "");

      $("#RazonSocialCliente").val('');
      $("#RazonSocialCliente").attr("data-validation-text-found", "");
    }
  }

  self.OnClickBtnVerComprobante = function (data, event) {
    if (event) {
      var datajs = ko.mapping.toJS({ "Data": { "IdComprobanteVenta": data.IdComprobanteVenta() } });

      $("#loader").show();
      self.ObtenerComprobanteVentaPorId(datajs, event, function ($data) {
        if (!$data.error) {

          datajs.Data.IdTipoVenta = $data[0].IdTipoVenta;
          datajs.Data.IdAsignacionSede = $data[0].IdAsignacionSede;
          self.ConsultarDetallesComprobanteVenta(datajs, event, function ($dataDetalle) {
            $("#loader").hide();
            if (!$dataDetalle.error) {
              $data[0].DetallesComprobanteVenta = $dataDetalle;
              ko.mapping.fromJS($data[0], {}, self.data.ComprovanteVenta);
              $("#modalVistaComprobanteVenta").modal("show");
            } else {
              alertify.alert(self.titulo, $dataDetalle.error.msg, function () { });
            }
          })
        } else {
          alertify.alert(self.titulo, $data.error.msg, function () { });
        }
      })
    }
  }

  
  self.OnDisableBtnGrabar = ko.computed(function () {

    if (self.data.Filtros.IndicadorPermisoCobranzaRapida() == 0 ) {
      return true;
    }
    
    return false;
  }, this)
}
