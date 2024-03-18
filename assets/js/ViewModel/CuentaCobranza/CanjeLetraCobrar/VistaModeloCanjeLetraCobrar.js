VistaModeloCanjeLetraCobrar = function (data, options) {
  var self = this;
  self.Options = options;
  ko.mapping.fromJS(data, MappingCuentaCobranza, self);
  self.IndicadorReseteoFormulario = true;

  ModeloCanjeLetraCobrar.call(this, self);

  var $form = $(self.Options.IDForm);
  var idform = self.Options.IDForm;

  self.TotalPendientesCobranzaCliente = ko.observable("0.00");

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo();
      self.InicializarValidator(event);
      $(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

      var target = idform + " #RazonSocialCliente";
      $(target).autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target);

      AccesoKey.AgregarKeyOption(idform, "#BtnGrabar", TECLA_G);
      setTimeout(function () {
        $('#FechaComprobante').focus();
      }, 500);
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {
      $.formUtils.addValidator({
        name: "select",
        validatorFunction: function (value) {
          return (value == "" || value == null || value == 0 ? false : true)
        },
        errorMessage: "",
        errorMessageKey: "Selecciona un Item"
      });

      $.formUtils.addValidator({
        name: 'autocompletado',
        validatorFunction: function (value, $el) {
          var texto = $el.attr("data-validation-text-found");
          var resultado = (value.toUpperCase() === texto.toUpperCase() && value.toUpperCase() !== "") ? true : false;
          return resultado;
        },
        errorMessageKey: 'badautocompletado'
      });
    }
  }

  self.OnFocus = function (data, event, callback) {
    if (event) {
      $(event.target).select();
      if (callback) callback(data, event);
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.OnNuevo = function (data, event, callback) {
    if (event) {
      $form.resetearValidaciones();
      if (callback) self.callback = callback;
      self.NuevoCanjeLetraCobrar(data, event);
      self.InicializarVistaModelo(data, event);
    }
  }

  self.OnVer = function (data, event, callback) {
    if (event) {
      $form.disabledElments($form, true);
      self.OnEditar(data, event, callback, true);
    }
  }

  self.OnEditar = function (data, event, callback, ver = false) {
    if (event) {
      if (!ver) { $form.disabledElments($form, false); }
      if (self.IndicadorReseteoFormulario === true) $form.resetearValidaciones();
      if (callback) self.callback = callback;
      self.ConsultarCobranzasClientePorCanje(data, event, ver);
      self.ConsultarDetallesCanjeLetraCobrar(data, event, ver);
      self.EditarCanjeLetraCobrar(data, event);
      self.InicializarVistaModelo(data, event);

      var razonSocialCliente = self.NumeroDocumentoIdentidad() == "" ? self.RazonSocial() : `${self.NumeroDocumentoIdentidad()}  -  ${self.RazonSocial()}`;
      $form.find("#RazonSocialCliente").attr("data-validation-text-found", razonSocialCliente);
      self.Filtro.RazonSocialCliente(razonSocialCliente);
      self.TotalPendientesCobranzaCliente(self.ImporteTotalCanje());
    }
  }

  self.OnEliminar = function (data, event, callback) {
    if (event) {
      self.EliminarCanjeLetraCobrar(data, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.OnAnular = function (data, event, callback) {
    if (event) {
      self.AnularCanjeLetraCobrar(data, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.OnClickBtnGrabar = function (data, event) {
    if (event) {
      if ($form.isValid() === false) {
        alertify.alert("Error en Validación de " + self.titulo, "Existe aun datos inválidos , por favor de corregirlo.", function () {
          setTimeout(function () {
            $form.find('.has-error').find('input, select').first().focus();
          }, 300);
        });
      }
      else {
        if (self.PendientesLetraCobrar().length <= 0) {
          alertify.alert(self.titulo, "No hay ninguna cobranza para el cliente seleccionado.", function () { });
          return false;
        }
        if (self.TotalPendientesCobranzaCliente() != self.ImporteTotalCanje()) {
          alertify.alert(self.titulo, "Los montos deben ser iguales", function () { });
          return false;
        }
        $("#loader").show();
        self.GuardarCanjeLetraCobrar(event, function ($data, $event) {
          if ($data.error) {
            $("#loader").hide();
            alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
          }
          else {
            $("#loader").hide();
            if (self.opcionProceso() == opcionProceso.Nuevo) {
              if (self.callback) self.callback(self, $data, event);
            }
            else {
              alertify.alert(self.titulo, self.mensaje, function () {
                if (self.callback) self.callback(self, $data, event);
              });
            }
          }
        });
      }
    }
  }

  self.OnClickBtnDeshacer = function (data, event) {
    if (event) {
      self.OnEditar(self.CanjeLetraCobrarInicial, event, self.callback);
    }
  }

  self.OnClickBtnLimpiar = function (data, event) {
    if (event) {
      $("#RazonSocialCliente").val("");
      self.OnNuevo(self.CanjeLetraCobrarInicial, event, self.callback);
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalCanjeLetraCobrar").modal("hide");
      if (self.callback) self.callback(self, event);
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showCanjeLetraCobrar(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showCanjeLetraCobrar(false);
      self.EstaProcesado(false);
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.MostrarTitulo = ko.computed(function () {
    if (self.opcionProceso() == opcionProceso.Nuevo) {
      self.titulo = "REGISTRO DE COBRANZA CLIENTE";
    }
    else {
      self.titulo = "EDICIÓN DE COBRANZA CLIENTE";
    }
    return self.titulo;
  }, this);


  self.ValidarCliente = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.Filtro.IdCliente("");
        }
      });
    }
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {
      var $inputCliente = $form.find("#RazonSocialCliente");
      var $evento = { target: `${idform} #RazonSocialCliente` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda de cliente";
        var razonSocialCliente = "";
        var idCliente = "";
      } else {
        var memsajeError = "";
        var razonSocialCliente = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : `${data.NumeroDocumentoIdentidad}  -  ${data.RazonSocial}`;
        var idCliente = data.IdPersona;
      }

      $inputCliente.attr("data-validation-error-msg", memsajeError);
      $inputCliente.attr("data-validation-text-found", razonSocialCliente);

      self.Filtro.IdCliente(idCliente);
      self.Filtro.RazonSocialCliente(razonSocialCliente);
      self.IdCliente(idCliente)
      self.IdMoneda(self.Filtro.IdMoneda())

      self.ValidarCliente(data, $evento);
    }
  }

  self.ObtenerPendientesCobranzaCliente = function (data, event) {
    if (event) {
      if (self.IdCliente() == "" || self.IdCliente() == null) {
        alertify.alert(self.titulo, "Debe seleccionar un cliente para la busqueda.", function () { });
        return false
      }
      $("#loader").show();
      var obj = ko.mapping.toJS(self.Filtro);
      self.ConsultarPendientesCobranzaCliente(obj, event, function ($data) {
        $("#loader").hide();
        if (!$data.error) {
          if ($data.length > 0) {
            var resultado = Knockout.CopiarObjeto($data);
            self.PendientesLetraCobrar([]);
            ViewModels.data.BusquedaPendientesCobranzaCliente([]);
            ViewModels.data.BusquedaPendientesCobranzaCliente(resultado());
            self.CalcularImporteTotalCanje(data, event);
            self.CalcularTotalPendientesCobranzaCliente(data, event);
          } else {
            alertify.alert(self.titulo, "No se encontro comprobantes pendientes de cobranza", function () { });
          }
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      });
    }
  }

  self.OnClickBtnRemoverDetalle = function (data, event) {
    if (event) {
      self.DetallesCanjeLetraCobrar.remove(data);
      self.CalcularTotalesDetalle(data, event, self.DetallesCanjeLetraCobrar);
    }
  }

  self.CalcularTotalPendientesCobranzaCliente = function (data, event) {
    if (event) {
      var total = 0;
      ko.utils.arrayForEach(ViewModels.data.BusquedaPendientesCobranzaCliente(), function (item) {
        if (item.SaldoPendiente && item.ComprobanteSeleccionado() == 1) {
          total = total + parseFloatAvanzado(item.SaldoPendiente());
        }
      });
      self.TotalPendientesCobranzaCliente(total);
    }
  }

  self.CalcularImporteTotalCanje = function (data, event) {
    if (event) {
      var total = 0;
      ko.utils.arrayForEach(self.PendientesLetraCobrar(), function (item) {
        if (item.ImporteLetra) {
          total = total + parseFloatAvanzado(item.ImporteLetra());
        }
      });
      self.ImporteTotalCanje(total);
    }
  }

  self.OnChangeCheckedComprobantePendienteCobranza = function (data, event) {
    if (event) {
      self.CalcularTotalPendientesCobranzaCliente(data, event);

      if (self.NumeroLetra() > 0) {
        var nuevoPendiente = ko.mapping.toJS(self.NuevoPendienteLetraCobrar);
        var numeroLetra = parseFloatAvanzado(self.NumeroLetra());
        var total = parseFloatAvanzado(self.TotalPendientesCobranzaCliente()) / numeroLetra;
        var diasPlazo = 0;
        self.PendientesLetraCobrar([]);
        for (let i = 0; i < numeroLetra; i++) {
          diasPlazo = diasPlazo + 30

          nuevoPendiente.ImporteLetra = total;
          nuevoPendiente.DiasPlazo = diasPlazo;
          nuevoPendiente.Item = i + 1;

          var objeto = new VistaModeloDetalleCanjeLetraCobrar(nuevoPendiente, self);
          self.PendientesLetraCobrar.push(objeto);

          objeto.ObtenerFechaVencimiento(data, event);
        }
        $(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
        self.CalcularImporteTotalCanje(data, event);
        var pendientesCobranzaCliente = ViewModels.data.BusquedaPendientesCobranzaCliente().filter(item => item.ComprobanteSeleccionado());
        self.PendientesCobranzaCliente(pendientesCobranzaCliente);

      }
    }
  }

  self.OnDisableBtnEditar = ko.computed(function () {
    if (self.IndicadorEstado() == ESTADO.ANULADO) {
      var disable = true;
    } else {
      var disable = false;
    }
    return disable;
  }, this);

  self.OnDisableBtnAnular = ko.computed(function () {
    if (self.IndicadorEstado() == ESTADO.ANULADO) {
      var disable = true;
    } else {
      var disable = false;
    }
    return disable;
  }, this);

  self.OnDisableBtnBorrar = ko.computed(function () {
    return false;
  }, this);

  self.ConsultarDetallesCanjeLetraCobrar = function (data, event, ver = false) {
    if (event) {
      var objeto = ko.mapping.toJS(data, mappingIgnore);
      $("#show").show();
      self.ConsultarPendientesLetraCobrarPorCanje(objeto, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          
          self.PendientesLetraCobrar([]);
          $data.forEach(function (item) {
            var objetodetalle = new VistaModeloDetalleCanjeLetraCobrar(Object.assign(ko.mapping.toJS(self.NuevoPendienteLetraCobrar), item), self);
            self.PendientesLetraCobrar.push(objetodetalle);
          });
          $form.disabledElments($form, !ver ? false : true);
          
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      })
    }
  }
  
  
  self.ConsultarCobranzasClientePorCanje = function (data, event, ver = false) {
    if (event) {
      var objeto = ko.mapping.toJS(data, mappingIgnore);
      $("#show").show();
      self.ConsultarPendientesCobranzaClientePorCanje(objeto, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          var resultado = Knockout.CopiarObjeto($data);
          ViewModels.data.BusquedaPendientesCobranzaCliente([]);
          ViewModels.data.BusquedaPendientesCobranzaCliente(resultado());
          self.CalcularTotalPendientesCobranzaCliente(data, event);

          $form.disabledElments($form, !ver ? false : true);
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      })
    }
  }
}
