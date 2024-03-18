VistaModeloVehiculo = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingVehiculo, self);
  self.IndicadorReseteoFormulario = true;
  ModeloVehiculo.call(this, self);

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo();
      $(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      AccesoKey.AgregarKeyOption("#formVehiculo", "#BtnGrabar", TECLA_G);
      setTimeout(function () {
        $('#combo-tipodocumentoIdentidad').focus();
      }, 850);

      self.InicializarValidator(event);
    }
  }

  self.InicializarValidator = function (event) {
    if (event) {
      $.formUtils.addValidator({
        name: 'validacion_numero_documento',
        validatorFunction: function (value, $el, config, language, $form) {
          return self.ValidarNumeroDocumentoIdentidad(value, event);
        }
      });
    }
  }

  // self.InputComboRadioTaxi = ko.computed(function () {
  //   if (self.IdDetalleComprobanteVenta != undefined) {
  //     return "#" + self.IdRadioTaxi() + "_input_ComboRadioTaxi";
  //   }
  //   else {
  //     return "";
  //   }
  // }, this);

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

  self.VistaOpciones = ko.pureComputed(function () {
    return self.IdVehiculo() != 0 ? "visible" : "hidden";
  }, this);

  self.OnNuevo = function (data, event, callback) {
    if (event) {
      $('#formVehiculo').resetearValidaciones();
      if (callback) self.callback = callback;
      self.NuevoVehiculo(data, event);
      self.InicializarVistaModelo(data, event);
    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $('#formVehiculo').resetearValidaciones();
      if (callback) self.callback = callback;
      self.EditarVehiculo(data, event);
      self.InicializarVistaModelo(data, event);
    }
  }

  self.OnEliminar = function (data, event, callback) {
    if (event) {
      self.EliminarVehiculo(data, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }


  self.OnClickBtnGrabar = function (data, event) {
    if (event) {
      if ($("#formVehiculo").isValid() === false) {
        alertify.alert("Error en Validación de " + self.titulo, "Existe aun datos inválidos , por favor de corregirlo.", function () {
          alertify.alert().destroy()
        });
      }
      else {
        $("#loader").show();
        self.GuardarVehiculo(event, function ($data, $event) {
          if ($data.error) {
            $("#loader").hide();
            alertify.alert("Error en " + self.titulo, $data.error.msg, function () { });
          }
          else {
            $("#loader").hide();
            self.EstaProcesado(true);
            self.NombreRadioTaxi($("#combo-radiotaxi :selected").text());
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
      self.OnEditar(self.VehiculoInicial, event, self.callback);
    }
  }

  self.OnClickBtnLimpiar = function (data, event) {
    if (event) {
      self.OnNuevo(self.VehiculoInicial, event, self.callback);
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalVehiculo").modal("hide");
      if (self.callback) self.callback(self, event);
    }
  }

  self.OnClickBtnCierrePreview = function (data, event) {
    if (event) {
      $("#modalPreview").modal("hide");
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showVehiculo(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showVehiculo(false);//$("#modalVehiculo").modal("hide");
      self.EstaProcesado(false);
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.MostrarTitulo = ko.computed(function () {
    if (self.opcionProceso() == opcionProceso.Nuevo) {
      self.titulo = "REGISTRO DE VEHÍCULO";
    }
    else {
      self.titulo = "EDICIÓN DE VEHÍCULO";
    }

    return self.titulo;
  }, this);

}
