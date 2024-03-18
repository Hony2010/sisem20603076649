VistaModeloTurno = function (data) {
    var self = this;
    ko.mapping.fromJS(data, MappingTurno, self);
    self.IndicadorReseteoFormulario = true;
    ModeloTurno.call(this,self);

    self.InicializarVistaModelo =function(data,event) {
      if(event) {
        self.InicializarModelo();
        $(".hora").inputmask({"mask":"99:99:99",positionCaretOnTab : false});
        AccesoKey.AgregarKeyOption("#formturno", "#BtnGrabar", TECLA_G);
        setTimeout(function(){
          $('#NombreTurno').focus();
        }, 500);
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
        $('#formturno').resetearValidaciones();
        if(callback) self.callback = callback;
        self.NuevoTurno(data,event);
        self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEditar = function (data,event,callback) {
      if(event) {
          if (self.IndicadorReseteoFormulario === true)  $('#formturno').resetearValidaciones();
          if(callback) self.callback = callback;
          self.EditarTurno(data,event);
          self.InicializarVistaModelo(data,event);
      }
    }

    self.OnEliminar = function(data,event,callback) {
      if(event) {
          self.EliminarTurno(data,event,function($data,$event) {
            callback($data,$event);
          });
      }
    }

    self.OnClickBtnGrabar = function(data, event)  {
      if(event) {
        if($("#formturno").isValid() === false) {
          alertify.alert("Error en Validación de "+self.titulo,"Existe aun datos inválidos , por favor de corregirlo.");
        }
        else {
            $("#loader").show();
            self.GuardarTurno(event,function($data,$event){
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
        self.OnEditar(self.TurnoInicial,event,self.callback);
      }
    }

    self.OnClickBtnLimpiar = function (data,event) {
      if(event) {
        self.OnNuevo(self.TurnoInicial,event,self.callback);
      }
    }

    self.OnClickBtnCerrar = function(data,event)  {
      if(event) {
        $("#modalTurno").modal("hide");
        if (self.callback) self.callback(self,event);
      }
    }

    self.Show = function(event) {
      if(event) {
        self.showTurno(true);
      }
    }

    self.Hide = function(event) {
      if(event) {
        self.showTurno(false);//$("#modalTurno").modal("hide");
        self.EstaProcesado(false);
        self.OnClickBtnCerrar(self,event);
      }
    }

    self.MostrarTitulo = ko.computed( function () {
      if (self.opcionProceso() == opcionProceso.Nuevo)  {
        self.titulo = "REGISTRO DE TURNO";
      }
      else {
        self.titulo = "EDICIÓN DE TURNO";
      }

      return self.titulo;
    },this);

    self.ValidarHoraInicio =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarHoraFinal =  function (data,event) {
      if (event) {

        $(event.target).validate(function(valid, elem) {
        });
      }
    }

    self.ValidarNombreTurno =  function (data,event) {
      if (event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

}
