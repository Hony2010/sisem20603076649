VistaModeloTurnos = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingTurno, self);
    ModeloTurnos.call(this,self);

    self.Inicializar = function () {
      if(self.data.Turnos().length > 0)
      {
        var objeto = self.data.Turnos()[0];
        self.Seleccionar(objeto,window);
        var input = ko.toJS(self.data.Filtros);
        $("#Paginador").paginador(input,self.ConsultarPorPagina);
      }
    }

    self.Seleccionar = function (data,event){
      if (event) {
        if (data != undefined) {
          var id = "#"+ data.IdTurno();
          $(id).addClass('active').siblings().removeClass('active');

          var objeto = Knockout.CopiarObjeto(data);
          ko.mapping.fromJS(objeto, {}, self.data.Turno);

        }
      }
    }

    self.OnClickBtnEditar  = function(data, event) {
      if(event) {
        // var $data = ko.mapping.fromJS(data,{},self.data.Turno);
        self.data.Turno.OnEditar(data,event,self.PostGuardar);
        $("#modalTurno").modal("show");
      }
    }

    self.OnClickBtnEliminar = function (data, event) {
      if(event) {
        var titulo ="Eliminación de Turno";
        alertify.confirm(titulo,"¿Desea borrar realmente?", function() {

          var objeto_data = ko.mapping.toJS(data);
          data = {"data" : objeto_data, "filtro" : self.copiatextofiltro()};
          data = Knockout.CopiarObjeto(data);

          self.data.Turno.OnEliminar(data,event,function($data,$event) {
            if($data.error) {
              $("#loader").hide();
              alertify.alert("Error en "+ titulo,$data.error.msg,function() {
              });
            }
            else {
              var id = "#"+data.data.IdTurno();
              var siguienteObjeto = $(id).next();
              if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
              siguienteObjeto.addClass('active').siblings().removeClass('active');

              var objeto = ko.utils.arrayFirst(self.data.Turnos(),function (item) {return item.IdTurno() == data.data.IdTurno();});
              self.data.Turnos.remove(objeto);

              var filas = self.data.Turnos().length;
              self.data.Filtros.totalfilas($data.Filtros.totalfilas);
              if(filas == 0) {
                $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
                var ultimo = $("#Paginador ul li:last").prev();
                ultimo.children("a").click();
              }
            }
          });
        },function(){});
      }
    }

    self.PostGuardar = function(data,$data,event) {
      if(event) {
        if (self.data.Turno.EstaProcesado() == true) {
          if(self.data.Turno.opcionProceso() == opcionProceso.Nuevo) {

            alertify.confirm("REGISTRO DE TURNO","Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function() {
              self.data.Turno.OnNuevo(self.data.Turno.TurnoNuevo,event,self.PostGuardar);
            }, function(){

              $("#modalTurno").modal("hide");

              // var filas = self.data.Turnos().length;
              // self.data.Filtros.totalfilas($data.Filtros.totalfilas);
              // if(filas >= 10) {
              //   $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
              //   var ultimo = $("#Paginador ul li:last").prev();
              //   ultimo.children("a").click();
              // }
              // else {
                var copia =  ko.mapping.toJS(self.data.Turno,mappingIgnore);
                self.data.Turnos.push(new VistaModeloTurno(copia));
                self.Seleccionar(self.data.Turno, event);
              // }
            });

          }
          else {
            var copia = ko.mapping.toJS(self.data.Turno,mappingIgnore);
            var resultado = new VistaModeloTurno(copia);

            var objeto = ko.utils.arrayFirst(self.data.Turnos(),function (item) {return item.IdTurno() == data.IdTurno();});
            self.data.Turnos.replace(objeto ,resultado);
            self.Seleccionar(resultado,event);
            $("#modalTurno").modal("hide");
            $("#loader").hide();
          }
        }

      }
    }

    self.OnClickBtnNuevo = function(data,event) {
      if (event){
        self.data.Turno.Show(event);
        self.data.Turno.OnNuevo(self.data.Turno.TurnoNuevo,event,self.PostGuardar);
        self.data.Turno.copiatextofiltroguardar(self.copiatextofiltro());
      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarTurnosPorPagina(data,event,function($data,$event) {
            var objeto = self.data.Turnos()[0];
            self.Seleccionar(objeto, event);
            $("#Paginador").pagination("drawPage", $data.pagina);
        });
      }
    }

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER) {
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();

          self.copiatextofiltro(data.textofiltro());
          self.ListarTurnos(data,event,function ($data,$event) {
              var objeto = self.data.Turnos()[0];
              self.Seleccionar(objeto, event);
              $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
              self.data.Filtros.totalfilas($data.Filtros.totalfilas);
          });
        }
      }
    }
  }
