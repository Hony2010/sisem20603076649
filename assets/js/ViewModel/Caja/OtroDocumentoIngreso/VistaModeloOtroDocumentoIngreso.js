VistaModeloOtroDocumentoIngreso = function (data,options) {
    var self = this;
    self.Options = options;
    ko.mapping.fromJS(data, MappingCaja, self);
    self.IndicadorReseteoFormulario = true;
    ModeloOtroDocumentoIngreso.call(this,self);

    var $form = $(self.Options.IDForm);
    var idform = self.Options.IDForm;

    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarModelo();
        self.InicializarValidator(event);
        self.ObtenerMoneda(data, event);

        $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        AccesoKey.AgregarKeyOption(idform, "#BtnGrabar", TECLA_G);
        setTimeout(function(){
          $('#ComboTipoOperacion').focus();
        }, 500);
      }
    }

    self.InicializarValidator = function(event) {
      if(event) {

        $.formUtils.addValidator({
        	name: "select",
        	validatorFunction: function(value) {
            return (value == "" || value == null ? false : true)
        	},
        	errorMessage: "",
        	errorMessageKey: "Selecciona un Item"
        });

      }
    }

    self.OnFocus = function(data,event,callback) {
      if(event)  {
          $(event.target).select();
          if(callback) callback(data,event);
      }
    }

    self.OnKeyEnter = function(data,event) {
      if(event) {
        var resultado = $(event.target).enterToTab(event);
        return resultado;
      }
    }

    self.OnNuevo = function (data,event,callback) {
      if (event) {
        $form.resetearValidaciones();
        if(callback) self.callback = callback;
        self.NuevoOtroDocumentoIngreso(data,event);
        self.InicializarVistaModelo(data,event);
      }
    }

    self.OnVer = function (data,event,callback) {
      if(event) {
        $form.disabledElments($form, true);
        self.OnEditar(data, event, callback, true);
      }
    }

    self.OnEditar = function (data,event,callback, ver = false) {
      if(event) {
        if (!ver) {$form.disabledElments($form, false);}
          if (self.IndicadorReseteoFormulario === true)  $form.resetearValidaciones();
          if(callback) self.callback = callback;
          self.EditarOtroDocumentoIngreso(data,event);
          self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarOtroDocumentoIngreso(data,event,function($data,$event) {
            callback($data,$event);
          });
      }
    }

    self.OnClickBtnGrabar = function(data, event)  {
      if(event) {
        if($form.isValid() === false) {
          alertify.alert("Error en Validación de "+self.titulo,"Existe aun datos inválidos , por favor de corregirlo.",function() {
            setTimeout(function(){
              $form.find('.has-error').find('input, select').first().focus();
            }, 300);
          });
        }
        else {
            $("#loader").show();
            self.GuardarOtroDocumentoIngreso(event,function($data,$event){
              if($data.error) {
                $("#loader").hide();
                alertify.alert("Error en "+self.titulo,$data.error.msg,function() {});
              }
              else {
                $("#loader").hide();
                if (self.opcionProceso() == opcionProceso.Nuevo) {
                  if (self.callback) self.callback(self,$data,event);
                }
                else {
                  alertify.alert(self.titulo,self.mensaje, function() {
                      if (self.callback) self.callback(self,$data,event);
                    });
                }
              }
            });
        }
      }
    }

    self.OnClickBtnDeshacer = function(data,event)  {
      if(event) {
        self.OnEditar(self.OtroDocumentoIngresoInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        self.OnNuevo(self.OtroDocumentoIngresoInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalOtroDocumentoIngreso").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showOtroDocumentoIngreso(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showOtroDocumentoIngreso(false);//$("#modalOtroDocumentoIngreso").modal("hide");
        self.EstaProcesado(false);
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.MostrarTitulo = ko.computed( function () {
      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.titulo = "REGISTRO DE DOCUMENTO DE INGRESO";
      }
      else {
        self.titulo = "EDICIÓN DE DOCUMENTO DE INGRESO";
      }
      return self.titulo;
    },this);

    self.ValidarCombo = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ObtenerMoneda = function (data, event) {
      var caja =  ko.mapping.toJS(self.Cajas).filter(function(item) {
        return item.IdCaja == self.IdCaja();
      });

      var idMoneda = caja.length > 0 ? caja[0].IdMoneda : '';
      self.IdMoneda(idMoneda);
    }

    self.OnChangeComboCaja = function (data, event, callback) {
      if (event) {
        self.ObtenerMoneda(data, event);
        if (callback) callback(data,event);
      }
    }

    self.OnChangeComboSeriesDocumento = function (data, event) {
      if (event) {
        var serieDocumento = $("#ComboSeriesDocumento option:selected").text();
        self.SerieDocumento(serieDocumento);
      }
    }

    self.ValidarEdicionComprobanteSegunMotivo = function (data, event) {
      if (event) {

      }
    }

    self.OnDisableBtnEditar = ko.computed( function () {
      if ((self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_VENTA_CONTADO) || (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_VOUCHER_EGRESO || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_VOUCHER_INGRESO)) {
        var disable = true;
      } else if (self.IndicadorEstado() == ESTADO.ANULADO) {
        var disable = true;
      } else {
        var disable = false
      }
      return disable;
    }, this);

    self.OnDisableBtnAnular = ko.computed( function () {
      if ((self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_VENTA_CONTADO) || (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_VOUCHER_EGRESO || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_VOUCHER_INGRESO)) {
        var disable = true;
      } else if (self.IndicadorEstado() == ESTADO.ANULADO) {
        var disable = true;
      } else {
        var disable = false
      }
      return disable;
    }, this);

    self.OnDisableBtnBorrar = ko.computed( function () {
      if ((self.IdTipoOperacionCaja() == ID_TIPO_OPERACION_VENTA_CONTADO) || (self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_VOUCHER_EGRESO || self.IdTipoDocumento() == ID_TIPO_DOCUMENTO_VOUCHER_INGRESO)) {
        var disable = true;
      } else {
        var disable = false
      }
      return disable;
    }, this);

}
