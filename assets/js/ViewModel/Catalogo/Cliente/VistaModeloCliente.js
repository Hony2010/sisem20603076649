VistaModeloCliente = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  self.IndicadorReseteoFormulario = true;
  ModeloCliente.call(this, self);

  self.IndicadorGuardarVehiculoCliente = ko.observable(false);
  self.IdClienteVenta = ko.observable("");

  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo();
      var src = self.ObtenerRutaFoto();
      $('#img_FileFoto').attr('src', src);
      $('#foto_previa').attr('src', src);
      $(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });
      AccesoKey.AgregarKeyOption("#formcliente", "#BtnGrabar", TECLA_G);
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

  self.OnKeyEnterNumeroDocumentoIdentidad = function (data, event, callback) {
    if (event) {
      if (event.keyCode == TECLA_ENTER) {
        var numero = self.NumeroDocumentoIdentidad();
        var idtipo = self.IdTipoDocumentoIdentidad();

        if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
          if ($.isNumeric(numero) === true && numero.length == MAXIMO_DIGITOS_DNI) self.IdTipoPersona(ID_TIPO_PERSONA_NATURAL);
        }
        else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {
          if ($.isNumeric(numero) === true && numero.length == MAXIMO_DIGITOS_RUC) {
            if (numero.substring(0, 2) == 20) self.IdTipoPersona(ID_TIPO_PERSONA_JURIDICA);
            if (numero.substring(0, 1) == 1) self.IdTipoPersona(ID_TIPO_PERSONA_NATURAL);
          }
        }

        self.OnBuscarPersona(data, event, function ($data, $event) {
          callback(data, event);
        });
      }

      return true;
    }
  }

  self.VistaOpciones = ko.pureComputed(function () {
    return self.IdPersona() != ID_CLIENTES_VARIOS ? "visible" : "hidden";
  }, this);

  self.OnNuevo = function (data, event, callback) {
    if (event) {
      $('#formcliente').resetearValidaciones();
      if (callback) self.callback = callback;
      self.NuevoCliente(data, event);
      self.InicializarVistaModelo(data, event);
      self.LimpiarImagen(event);
      self.OnChangeTipoDocumentoIdentidad(self, event);
      self.OnChangeGradoAlumno(data, event);
    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $('#formcliente').resetearValidaciones();
      if (callback) self.callback = callback;
      self.EditarCliente(data, event);
      self.InicializarVistaModelo(data, event);
      self.OnChangeTipoPersona(data, event);
      self.OnChangeTipoDocumentoIdentidad(data, event);
      self.OnChangeApellidoCompleto(data, event);
      //debugger;

      $('#loader').show();
      self.ObtenerDetalleCliente(data, event, function ($data, $event) {

        if (self.DireccionesCliente().length > 0) {
          //console.log(self.DireccionesClienteBorrado().length);
          //self.DireccionesClienteBorrado().pop();       
          ko.utils.arrayForEach(self.DireccionesCliente(), function (el) {
            el.InicializarVistaModelo(event);
          });
        }


        if (self.VehiculosCliente().length > 0) {
          ko.utils.arrayForEach(self.VehiculosCliente(), function (el) {
            el.InicializarVistaModelo(event);
          });
          //console.log(self.DireccionesClienteBorrado().length);
          //self.DireccionesClienteBorrado().pop();
        }

        $('#loader').hide();
      });

    }
  }

  self.OnEliminar = function (data, event, callback) {
    if (event) {
      self.EliminarCliente(data, event, function ($data, $event) {
        callback($data, $event);
      });
    }
  }

  self.OnChangeTipoPersona = function (data, event) {
    if (event) {
      var texto = $("#combo-tipopersona option:selected").text();
      var IdTipoPersona = self.IdTipoPersona();

      self.NombreTipoPersona(texto);
      if (IdTipoPersona == ID_TIPO_PERSONA_JURIDICA) {
        $("#NombreCompleto").prop("disabled", true);
        $("#NombreCompleto").prop("tabIndex", "-1");
        $("#NombreCompleto").addClass("no-tab");
        $("#ApellidoCompleto").prop("disabled", true);
        $("#ApellidoCompleto").prop("tabIndex", "-1");
        $("#ApellidoCompleto").addClass("no-tab");
        $("#RazonSocial").prop("disabled", false);
        $("#RazonSocial").removeAttr("tabIndex");
        $("#RazonSocial").removeClass("no-tab");
      }
      else if ((IdTipoPersona == ID_TIPO_PERSONA_NATURAL) || (IdTipoPersona == ID_TIPO_PERSONA_NO_DOMICILIADO)) {
        $("#NombreCompleto").prop("disabled", false);
        $("#NombreCompleto").removeAttr("tabIndex");
        $("#NombreCompleto").removeClass("no-tab");
        $("#ApellidoCompleto").prop("disabled", false);
        $("#ApellidoCompleto").removeAttr("tabIndex");
        $("#ApellidoCompleto").removeClass("no-tab");
        $("#RazonSocial").prop("disabled", true);
        $("#RazonSocial").prop("tabIndex", "-1");
        $("#RazonSocial").addClass("no-tab");
      }
    }
  }

  self.OnChangeFileFoto = function (data, event) {
    if (event) {
      $("#FileFoto").readAsImage(event, function ($data) {
        if ($data) {
          $('#img_FileFoto').attr('src', $data.source);
          $('#foto_previa').attr('src', $data.source);
          self.Foto($data.filename);
        }
      });
    }
  }

  self.OnClickBtnGrabar = function (data, event) {
    if (event) {
      if ($("#formcliente").isValid() === false) {
        alertify.alert("Error en Validación de " + self.titulo, "Existe aun datos inválidos , por favor de corregirlo.", function () {
          alertify.alert().destroy()
        });
      }
      else {
        $("#loader").show();
        self.GuardarCliente(event, function ($data, $event) {
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
      self.OnEditar(self.ClienteInicial, event, self.callback);
    }
  }

  self.OnClickBtnLimpiar = function (data, event) {
    if (event) {
      self.OnNuevo(self.ClienteInicial, event, self.callback);
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalCliente").modal("hide");
      if (self.callback) self.callback(self, event);
    }
  }

  self.OnClickLnkFileFoto = function (data, event) {
    if (event) {
      if ($("#foto_previa").attr('src') != '')
        $("#modalPreview").modal("show");
    }
  }

  self.OnClickBtnCierrePreview = function (data, event) {
    if (event) {
      $("#modalPreview").modal("hide");
    }
  }

  self.OnChangeTipoDocumentoIdentidad = function (data, event) {
    if (event) {
      var texto = $("#combo-tipodocumentoIdentidad option:selected").text();
      self.NombreAbreviado(texto);
      var idtipo = self.IdTipoDocumentoIdentidad();

      ko.utils.arrayForEach(self.TiposDocumentoIdentidad(), function (item) {
        if (item.IdTipoDocumentoIdentidad() == idtipo) {
          self.CodigoDocumentoIdentidad(item.CodigoDocumentoIdentidad());
        }
      });

      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI || idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {
        $('#BtnBusqueda').show();
        if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
          $('#BtnBusqueda').empty();
          $('#BtnBusqueda').append('<img width="25px" height="20px" src="' + BASE_URL + 'assets/js/iconos/logoRENIEC.svg" alt="" title="RENIEC">');
        }
        else {
          $('#BtnBusqueda').empty();
          $('#BtnBusqueda').append('<img width="22px" height="20px" src="' + BASE_URL + 'assets/js/iconos/logoSUNAT.svg" alt="" title="SUNAT">');
        }
      }
      else {
        $('#BtnBusqueda').hide();
      }

      if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
        self.NumeroDocumentoIdentidad('-');
      }
    }
  }

  self.OnFocusOutNumeroDocumentoIdentidad = function (data, event) {
    if (event) {
      //self.OnBuscarPersona(data,event);
    }
  }

  self.OnClickBtnBusqueda = function (data, event) {
    if (event) {
      self.OnBuscarPersona(data, event);
    }
  }

  self.OnBuscarPersona = function (data, event, callback) {
    if (event) {
      //if(!self.procesado) {

      $("#NumeroDocumentoIdentidad").validate(function (valid, elem) {

        if (valid === true) {
          $("#loader").show();
          self.ObtenerClientePorServicioExterno(data, event, function ($data, $event) {
            $("#loader").hide();
            if ($data.success == false) {
              alertify.alert("Error en " + self.titulo, $data.message, function () { });
            }
            if (callback) callback(data, event);
          });
        }
        else {
          if (callback) callback(data, event);
        }
      });
    }
    else {
      if (callback) callback(data, event);
    }
    //}
  }

  self.OnChangeApellidoCompleto = function (data, event) {
    if (event) {
      var IdTipoPersona = self.IdTipoPersona();

      if ((IdTipoPersona == ID_TIPO_PERSONA_NATURAL) || (IdTipoPersona == ID_TIPO_PERSONA_NO_DOMICILIADO)) {
        self.RazonSocial(self.ApellidoCompleto() + ' ' + self.NombreCompleto());
      }
    }
  }

  self.OnChangeNombreCompleto = function (data, event) {
    if (event) {
      var IdTipoPersona = self.IdTipoPersona();
      if ((IdTipoPersona == ID_TIPO_PERSONA_NATURAL) || (IdTipoPersona == ID_TIPO_PERSONA_NO_DOMICILIADO)) {
        self.RazonSocial(self.ApellidoCompleto() + ' ' + self.NombreCompleto());
      }
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showCliente(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showCliente(false);//$("#modalCliente").modal("hide");
      self.EstaProcesado(false);
      self.OnClickBtnCerrar(self, event);
    }
  }

  self.HideDirecciones = function (event) {
    if (event) {
      self.showDirecciones(false);
      var direcciones = ko.utils.arrayFilter(self.DireccionesCliente(), function (item) {
        return item.Direccion() != "" && item.Direccion() != null;
      });
      self.DireccionesCliente(direcciones);
    }
  }

  self.HideVehiculo = function (event) {
    if (event) {
      self.showVehiculos(false);
      var vehiculos = ko.utils.arrayFilter(self.VehiculosCliente(), function (item) {
        return item.NumeroPlaca() != "" && item.NumeroPlaca() != null;
      });
      self.VehiculosCliente(vehiculos);
    }
  }

  self.LimpiarImagen = function (event) {
    if (event) {
      var src = self.ObtenerRutaFoto();
      $('#img_FileFotoPreview').attr('src', src);
    }
  }

  self.MostrarTitulo = ko.computed(function () {
    if (self.opcionProceso() == opcionProceso.Nuevo) {
      self.titulo = "REGISTRO DE CLIENTE";
    }
    else {
      self.titulo = "EDICIÓN DE CLIENTE";
    }

    return self.titulo;
  }, this);

  self.OnChangeGradoAlumno = function (data, event) {
    if (event) {
      if (self.Parametros.ParametroAlumno() == 1) {
        var $data = ko.mapping.toJS(self.GradosAlumno());
        var busqueda = JSPath.apply('.{.IdGradoAlumno *= $Texto}', $data, { Texto: self.IdGradoAlumno() });
        self.NombreGradoAlumno(busqueda[0].NombreGradoAlumno);
      }
    }
  }

  self.OnClickBtnAgregarDireccionesCliente = function (data, event) {
    if (event) {
      self.showDirecciones(true);
    }
  }

  self.OnClickBtnNuevaDireccionCliente = function (data, event) {
    if (event) {
      var nuevaDireccion = new VistaModeloDireccionCliente(ko.mapping.toJS(self.NuevaDireccionCliente), self);
      nuevaDireccion.EstadoDireccion(ESTADO_DIRECCION_CLIENTE.INSERTADO);
      self.DireccionesCliente.push(nuevaDireccion);
      $("#modalDireccionesCliente").find("input").last().focus();
    }
  }

  self.OnChangedDireccionCliente = function (data, event) {
    if (event) {

      if (data.EstadoDireccion() != ESTADO_DIRECCION_CLIENTE.INSERTADO) {
        data.EstadoDireccion(ESTADO_DIRECCION_CLIENTE.ACTUALIZADO);
      }

      self.Direccion(data.Direccion());
    }
  }

  self.OnClickBtnRemoverDireccionCliente = function (data, event) {
    if (event) {

      if (data.EstadoDireccion() != ESTADO_DIRECCION_CLIENTE.INSERTADO) {
        data.EstadoDireccion(ESTADO_DIRECCION_CLIENTE.BORRADO);
        self.DireccionesClienteBorrado.push(data);
      }

      self.DireccionesCliente.remove(data);
    }
  };

  //Vehiculo 
  self.OnClickBtnNuevoVehiculoCliente = function (data, event) {
    if (event) {
      var nuevoVehiculoCliente = new VistaModeloVehiculoCliente(ko.mapping.toJS(self.NuevoVehiculoCliente), self);
      self.VehiculosCliente.push(nuevoVehiculoCliente);
      $("#modalVehiculosCliente").find("input").last().focus();
    }
  }

  self.OnClickGuardarVehiculosCliente = function (data, event) {
    if (event) {
      if (self.VehiculosCliente().length == 0) {
        alertify.alert(self.titulo, "debe crear una nueva placa para registrar", function () { })
        return false;
      }

      var vehiculoCliente = ko.mapping.toJS(self.VehiculosCliente()[0])
      vehiculoCliente.IdPersona = self.IdClienteVenta();
      var datajs = { Data: vehiculoCliente };

      $("#loader").show();
      self.GuardarVehiculosCliente(datajs, event, function ($data) {
        $("#loader").hide();
        if (!$data.error) {
          alertify.alert(self.titulo, "Se guardó correctamente la placa.", function () {
            self.VehiculosCliente([]);
            self.showVehiculos(false);
          })
        } else {
          alertify.alert("Error en " + self.titulo, $data.error.msg, function () {
            alertify.alert().destroy();
          });
        }
      })
    }
  }

  self.OnClickBtnAgregarVehiculoCliente = function (data, event) {
    if (event) {
      self.showVehiculos(true);
    }
  }

  self.OnClickBtnRemoverVehiculoCliente = function (data, event) {
    if (event) {
      self.VehiculosCliente.remove(data);
    }
  };

  self.CambiarEstadoCliente = ko.computed(function () {
    var estado = self.IndicadorEstadoCliente() ? '1' : '0';
    self.EstadoCliente(estado);
  }, this)

}
