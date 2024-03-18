VistaModeloProveedor = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingCatalogo, self);
  //self.procesado = false;
  self.IndicadorReseteoFormulario = true;
  ModeloProveedor.call(this, self);

  self.copiatextofiltroguardar = ko.observable("");


  self.InicializarVistaModelo = function (data, event) {
    if (event) {
      self.InicializarModelo();
      var src = self.ObtenerRutaFoto();
      $('#img_FileFoto').attr('src', src);
      $('#foto_previa').attr('src', src);
      AccesoKey.AgregarKeyOption("#formproveedor", "#BtnGrabar", TECLA_G);

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
        var numero = data.NumeroDocumentoIdentidad();
        var idtipo = data.IdTipoDocumentoIdentidad();

        if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_DNI) {
          if ($.isNumeric(numero) === true && numero.length == MAXIMO_DIGITOS_DNI) data.IdTipoPersona(ID_TIPO_PERSONA_NATURAL);
        }
        else if (idtipo == ID_TIPO_DOCUMENTO_IDENTIDAD_RUC) {
          if ($.isNumeric(numero) === true && numero.length == MAXIMO_DIGITOS_RUC) {
            if (numero.substring(0, 2) == 20) data.IdTipoPersona(ID_TIPO_PERSONA_JURIDICA);
            if (numero.substring(0, 1) == 1) data.IdTipoPersona(ID_TIPO_PERSONA_NATURAL);
          }
        }

        self.OnBuscarPersona(data, event, function ($data, $event) {
          //self.procesado = true;
          callback(data, event);
          //self.procesado = false;
        });
      }

      return true;
    }
  }

  self.OnNuevo = function (data, event, callback) {
    if (event) {
      $('#formproveedor').resetearValidaciones();
      if (callback) self.callback = callback;
      self.NuevoProveedor(data, event);
      self.InicializarVistaModelo(data, event);
      self.LimpiarImagen(event);
      self.OnChangeTipoDocumentoIdentidad(self, event);
      self.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_RUC);
    }
  }

  self.OnEditar = function (data, event, callback) {
    if (event) {
      if (self.IndicadorReseteoFormulario === true) $('#formproveedor').resetearValidaciones();
      if (callback) self.callback = callback;
      self.EditarProveedor(data, event);
      self.InicializarVistaModelo(data, event);
      self.OnChangeTipoPersona(data, event);
      self.RazonSocial(data, event);
      self.OnChangeTipoDocumentoIdentidad(data, event);
    }
  }

  self.OnEliminar = function (data, event, callback) {
    if (event) {
      self.EliminarProveedor(data, event, callback);
    }
  }

  self.OnChangeTipoPersona = function (data, event) {
    if (event) {
      var texto = $("#combo-tipopersona option:selected").text();
      var IdTipoPersona = data.IdTipoPersona();

      data.NombreTipoPersona(texto);
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
          data.Foto($data.filename);
        }
      });
    }
  }

  self.OnClickBtnGrabar = function (data, event) {
    if (event) {
      if ($("#formproveedor").isValid() === false) {
        alertify.alert("Error en Validación de " + self.titulo, "Existe aun datos inválidos , por favor de corregirlo.");
      }
      else {
        //alertify.confirm(self.titulo,"¿Desea guardar los cambios?",function() {
        $("#loader").show();
        self.GuardarProveedor(event, function ($data, $event) {
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
            //setTimeout(function() {

            //}, 100);
          }
        });
        //},function(){

        //});
      }
    }
  }

  self.OnClickBtnDeshacer = function (data, event) {
    if (event) {
      self.OnEditar(self.ProveedorInicial, event, self.callback);
    }
  }

  self.OnClickBtnLimpiar = function (data, event) {
    if (event) {
      self.OnNuevo(self.ProveedorInicial, event, self.callback);
    }
  }

  self.OnClickBtnCerrar = function (data, event) {
    if (event) {
      $("#modalProveedor").modal("hide");
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
      data.NombreAbreviado(texto);
      var idtipo = data.IdTipoDocumentoIdentidad();

      ko.utils.arrayForEach(self.TiposDocumentoIdentidad(), function (item) {
        if (item.IdTipoDocumentoIdentidad() == idtipo) {
          data.CodigoDocumentoIdentidad(item.CodigoDocumentoIdentidad());
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
          self.ObtenerProveedorPorServicioExterno(data, event, function ($data, $event) {
            if ($data.success == false) {
              $("#loader").hide();
              alertify.alert("Error en " + self.titulo, $data.message, function () { });
            }
            else {
              $("#loader").hide();
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
      var IdTipoPersona = data.IdTipoPersona();

      if ((IdTipoPersona == ID_TIPO_PERSONA_NATURAL) || (IdTipoPersona == ID_TIPO_PERSONA_NO_DOMICILIADO)) {
        data.RazonSocial(data.ApellidoCompleto() + ' ' + data.NombreCompleto());
      }
    }
  }

  self.OnChangeNombreCompleto = function (data, event) {
    if (event) {
      var IdTipoPersona = data.IdTipoPersona();
      if ((IdTipoPersona == ID_TIPO_PERSONA_NATURAL) || (IdTipoPersona == ID_TIPO_PERSONA_NO_DOMICILIADO)) {
        data.RazonSocial(data.ApellidoCompleto() + ' ' + data.NombreCompleto());
      }
    }
  }

  self.Show = function (event) {
    if (event) {
      self.showProveedor(true);
    }
  }

  self.Hide = function (event) {
    if (event) {
      self.showProveedor(false);//$("#modalProveedor").modal("hide");
      self.EstaProcesado(false);
      self.OnClickBtnCerrar(self, event);
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
      self.titulo = "REGISTRO DE PROVEEDOR";
    }
    else {
      self.titulo = "EDICIÓN DE PROVEEDOR";
    }

    return self.titulo;
  }, this);

  self.CambiarEstadoProveedor = ko.computed(function () {
    var estado = self.IndicadorEstadoProveedor() ? '1' : '0';
    self.EstadoProveedor(estado);
  }, this)

}
