VistaModeloTransferenciaCaja = function (data,options) {
    var self = this;
    self.Options = options;
    ko.mapping.fromJS(data, MappingCaja, self);
    self.IndicadorReseteoFormulario = true;
    ModeloTransferenciaCaja.call(this,self);

    var $form = $(self.Options.IDForm);
    var idform = self.Options.IDForm;

    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarModelo();
        self.InicializarValidator(event);
        self.OnChangeComboOrigen(data, event);
        $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        AccesoKey.AgregarKeyOption(idform, "#BtnGrabar", TECLA_G);
        setTimeout(function(){
          $('#ComboCaja').focus();
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
        self.NuevoTransferenciaCaja(data,event);
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
        self.EditarTransferenciaCaja(data,event);
        self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarTransferenciaCaja(data,event,function($data,$event) {
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
            self.GuardarTransferenciaCaja(event,function($data,$event){
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
        self.OnEditar(self.TransferenciaCajaInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        self.OnNuevo(self.TransferenciaCajaInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalTransferenciaCaja").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showTransferenciaCaja(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showTransferenciaCaja(false);//$("#modalTransferenciaCaja").modal("hide");
        self.EstaProcesado(false);
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.MostrarTitulo = ko.computed( function () {
      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.titulo = "REGISTRO DE APERTURA CAJA";
      }
      else {
        self.titulo = "EDICIÓN DE APERTURA CAJA";
      }
      return self.titulo;
    },this);

    self.ValidarCombo = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.OnChangeComboOrigen = function (data, event, callback) {
      if (event) {
        var caja =  ko.mapping.toJS(self.CajasOrigen).filter(function(item) {
          return item.IdCaja == self.IdCajaOrigen();
        });

        var idMoneda = caja.length > 0 ? caja[0].IdMoneda : '';
        self.IdMoneda(idMoneda);
        self.CajasDestino(self.DataCajasDestino(idMoneda));

        if (callback) callback(data, event);
        if (caja.length > 0) self.ObtenerSaldoCajaTurnoOrigen(data, event, caja);
      }
    }

    self.ObtenerSaldoCajaTurnoOrigen = function (data, event, caja) {
      if (event) {
        var cajaOrigen = { "IdCajaOrigen": caja[0].IdCaja, "IdUsuarioOrigen": caja[0].IdUsuario };
        ko.mapping.fromJS(cajaOrigen, self);

        var datajs = {"Data" : JSON.stringify(ko.mapping.toJS(self, mappingIgnore))};

        $("#loader").show()
        self.BuscarSaldoCajaTurnoOrigen(datajs, event, function ($data, $event) {
          $("#loader").hide()
          if (!$data.error) {
            var cajaOrigen = {
                "IdCajaOrigen": $data[0].IdCaja,
                "IdTurnoOrigen": $data[0].IdTurno,
                "SaldoActual": $data[0].SaldoActual,
                "IdUsuarioOrigen": $data[0].IdUsuario,
              };
              ko.mapping.fromJS(cajaOrigen, self);

            } else {
              alertify.alert(self.titulo,$data.error.msg, function functionName() {
                var cajaOrigen = {"IdTurnoOrigen": "", "SaldoActual": "", "IdUsuarioOrigen": "", "AliasUsuarioVenta":"" };
                ko.mapping.fromJS(cajaOrigen, self);
              });
            }
          });
        }
      }

      self.OnChangeComboDestino = function (data, event, callback) {
        if (event) {
          var caja =  ko.mapping.toJS(self.CajasDestino).filter(function(item) {
            return item.IdCaja == self.IdCajaDestino();
          });

          if (callback) callback(data,event);
          if(caja.length > 0)self.ObtenerSaldoCajaTurnoDestino(data, event, caja)
        }
      }

      self.ObtenerSaldoCajaTurnoDestino = function (data, event, caja) {
        if (event) {
          var cajaDestino = { "IdCajaDestino": caja[0].IdCaja, "IdUsuarioDestino": caja[0].IdUsuario };
          ko.mapping.fromJS(cajaDestino, self);

          var datajs = {"Data" : JSON.stringify(ko.mapping.toJS(self, mappingIgnore))};

          $("#loader").show()
          self.BuscarSaldoCajaTurnoDestino(datajs, event, function($data, $event){
            $("#loader").hide()
            if(!$data.error) {
              var cajaDestino = {
                "IdCajaDestino": $data[0].IdCaja,
                "IdTurnoDestino": $data[0].IdTurno,
                "IdUsuarioDestino": $data[0].IdUsuario,
                "AliasUsuarioVenta": $data[0].AliasUsuarioVenta
              };
              ko.mapping.fromJS(cajaDestino, self);

            } else {
              console.log($data.error);
              var cajaDestino = { "IdTurnoDestino": "", "IdUsuarioDestino": "", "AliasUsuarioVenta": "" };
              ko.mapping.fromJS(cajaDestino, self);

            }
          });
        }
      }

}
