VistaModeloCierreTurnoCaja = function (data,options) {
    var self = this;
    self.Options = ko.observable(options)
    ko.mapping.fromJS(data, MappingCaja, self);
    self.IndicadorReseteoFormulario = true;
    ModeloCierreTurnoCaja.call(this,self);

    var $form = $(self.Options().IDForm);
    var idform = self.Options().IDForm;
    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarModelo();
        self.OnChangeCaja(data,event);
        $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $(".fechahora").inputmask({"mask":"99/99/9999 99:99:99",positionCaretOnTab : false});
        AccesoKey.AgregarKeyOption(idform, "#BtnGrabar", TECLA_G);
        setTimeout(function(){
          $('#combo-caja').focus();
        }, 500);
        if (self.Cajas().length == 0) {
          $("#BtnGrabar").prop("disabled",true);
        }
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
        self.NuevoCierreTurnoCaja(data,event);
        self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEditar = function (data,event,callback) {
      if(event) {
          if (self.IndicadorReseteoFormulario === true)  $form.resetearValidaciones();
          if(callback) self.callback = callback;
          self.EditarCierreTurnoCaja(data,event);
          self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarCierreTurnoCaja(data,event,function($data,$event) {
            callback($data,$event);
          });
      }
    }

    self.OnClickBtnGrabar = function(data, event)  {
      if(event) {
        if($form.isValid() === false) {
          alertify.alert("Error en Validación "+self.titulo,"Existe aun datos inválidos , por favor de corregirlo.",function() {
            setTimeout(function(){
              $form.find('.has-error').find('input, select').first().focus();
            }, 300);
          });
        }
        else {
            $("#loader").show();
            self.GuardarCierreTurnoCaja(event,function($data,$event){
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
        self.OnEditar(self.CierreTurnoCajaInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        self.OnNuevo(self.CierreTurnoCajaInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalCierreTurnoCaja").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showCierreTurnoCaja(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showCierreTurnoCaja(false);//$("#modalCierreTurnoCaja").modal("hide");
        self.EstaProcesado(false);
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.MostrarTitulo = ko.computed( function () {
      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.titulo = "REGISTRO DE CIERRE TURNO";
      }
      else {
        self.titulo = "EDICIÓN DE CIERRE TURNO";
      }
      return self.titulo;
    },this);

    self.ValidarFechaApertura =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarFechaCierre =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarMontoInicial =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarMontoComprobante =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.OnChangeCaja = function (data, event) {
      if (event) {
        // ko.mapping.fromJS(self.CierreTurnoCajaInicial.NuevoComprobanteCaja,{},self);
        self.FechaAperturaTurnoCaja("");
        self.MontoAperturaTurnoCaja("");
        self.MontoComprobante("");
        var $data = ko.mapping.toJS(self.Cajas());
        var busqueda = JSPath.apply('.{.IdCaja *= $Texto}', $data, {Texto: self.IdCaja()});
        self.IdMoneda(busqueda[0].IdMoneda);
      }
    }

    self.OnKeyEnterComboCajas = function(data, event, callback) {
      if (event) {
        if (event.keyCode == TECLA_ENTER) {
          self.OnClickBtnObtenerApertura(data,event, function ($data, $event) {
            if (callback) { callback(data,event); };
          });
        } else {
          return true;
        }
      }
    }

    self.OnClickBtnObtenerApertura = function (data, event, callback) {
      if (event) {
        var datajs = {"Data" : JSON.stringify(ko.mapping.toJS(data))};
        $("#loader").show();
        self.ObtenerApertura(datajs,event,function ($data, $event) {
          $("#loader").hide();
          if ($data.error) {
            alertify.alert("Error en "+self.titulo,$data.error.msg,function() {

            })
          }
          else {
            ko.mapping.fromJS($data, MappingCaja, self);
          }
        });

        if (callback) { callback(data,event); }
      }
    }
}
