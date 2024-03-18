VistaModeloAperturaCaja = function (data,options) {
    var self = this;
    self.Options = options;
    ko.mapping.fromJS(data, MappingCaja, self);
    self.IndicadorReseteoFormulario = true;
    ModeloAperturaCaja.call(this,self);

    var $form = $(self.Options.IDForm);
    var idform = self.Options.IDForm;

    self.InicializarVistaModelo = function(data, event) {
      if(event) {
        self.InicializarModelo();
        self.InicializarValidator(event);
        if (self.opcionProceso() == opcionProceso.Nuevo) {
          self.OnChangeCaja(data,event, undefined);
          self.CargarApertura(data,event, false);
        }

        $(".fecha").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
        $(".fechahora").inputmask({"mask":"99/99/9999 99:99:99",positionCaretOnTab : false});
        AccesoKey.AgregarKeyOption(idform, "#BtnGrabar", TECLA_G);
        setTimeout(function(){
          $('#ComboSeriesDocumento').focus();
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
        self.NuevoAperturaCaja(data,event);
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
        self.EditarAperturaCaja(data,event);
        self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarAperturaCaja(data,event,function($data,$event) {
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
            self.GuardarAperturaCaja(event,function($data,$event){
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
        self.OnEditar(self.AperturaCajaInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        self.OnNuevo(self.AperturaCajaInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalAperturaCaja").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showAperturaCaja(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showAperturaCaja(false);//$("#modalAperturaCaja").modal("hide");
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

    self.ValidarFechaApertura =  function (data,event) {
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

    self.OnChangeCaja = function (data, event, callback) {
      if (event) {
        self.MontoComprobante("");
        self.NumeroDocumento("");
        var $data = ko.mapping.toJS(self.Cajas());
        var busqueda = JSPath.apply('.{.IdCaja *= $Texto}', $data, {Texto: self.IdCaja()});
        self.IdMoneda(busqueda[0].IdMoneda);

        if (callback) {
          $("#loader").show();
          callback(data, event);
        }
      }
    }

    self.CargarApertura = function (data, event, alert = true) {
      if (event) {
        var datajs = {"Data" : JSON.stringify(ko.mapping.toJS(data))};

        self.ConsultarAperturaCaja(datajs, event, function ($data, $event) {
          $("#loader").hide();
          if (!$data.error) {
            ko.mapping.fromJS($data, MappingCaja, self);
          } else {
            if (alert) {
              alertify.alert(self.titulo, $data.error.msg, function functionName() {
              });
            }
          }
        });
      }
    }
}
