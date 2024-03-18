VistaModeloClientes = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingCatalogo, self);
    ModeloClientes.call(this,self);

    self.Inicializar = function () {
      if(self.data.Clientes().length > 0)
      {
        var objeto = self.data.Clientes()[0];
        self.Seleccionar(objeto,window);
        var input = ko.toJS(self.data.Filtros);
        $("#Paginador").paginador(input,self.ConsultarPorPagina);
      }
    }

    self.Seleccionar = function (data,event){
      if (event) {
        if (data != undefined) {
          var id = "#"+ data.IdPersona();
          $(id).addClass('active').siblings().removeClass('active');

          var objeto = Knockout.CopiarObjeto(data);
          ko.mapping.fromJS(objeto, {}, self.data.Cliente);

          $("#img_FileFotoPreview").attr("src",data.ObtenerRutaFoto());
          $("#TituloNombrePadreAlumno").text(data.RazonSocial());
        }
      }
    }

    self.OnClickBtnEditar  = function(data, event) {
      if(event) {
        // var $data = ko.mapping.fromJS(data,{},self.data.Cliente);
        self.data.Cliente.OnEditar(data,event,self.PostGuardar);
        // $("#modalCliente").modal("show");
        self.data.Cliente.Show(event);
      }
    }

    self.OnClickBtnEliminar = function (data, event) {
      if(event) {
        var titulo ="Eliminación de Cliente";
        alertify.confirm(titulo,"¿Desea borrar realmente?", function() {

          var objeto_data = ko.mapping.toJS(data);
          data = {"data" : objeto_data, "filtro" : self.copiatextofiltro()};
          data = Knockout.CopiarObjeto(data);

          self.data.Cliente.OnEliminar(data,event,function($data,$event) {
            if($data.error) {
              $("#loader").hide();
              alertify.alert("Error en "+ titulo,$data.error.msg,function() {
              });
            }
            else {
              var id = "#"+data.data.IdPersona();
              var siguienteObjeto = $(id).next();
              if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
              siguienteObjeto.addClass('active').siblings().removeClass('active');

              var objeto = ko.utils.arrayFirst(self.data.Clientes(),function (item) {return item.IdPersona() == data.data.IdPersona();});
              self.data.Clientes.remove(objeto);

              var filas = self.data.Clientes().length;
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
        if (self.data.Cliente.EstaProcesado()== true) {
          if(self.data.Cliente.opcionProceso() == opcionProceso.Nuevo) {

            alertify.confirm("REGISTRO DE CLIENTE","Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function() {
              self.data.Cliente.OnNuevo(self.data.Cliente.ClienteNuevo,event,self.PostGuardar);
            }, function(){

              $("#modalCliente").modal("hide");

              var filas = self.data.Clientes().length;
              self.data.Filtros.totalfilas($data.Filtros.totalfilas);
              if(filas >= 10) {
                $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
                var ultimo = $("#Paginador ul li:last").prev();
                ultimo.children("a").click();
              }
              else {
                var copia =  ko.mapping.toJS(self.data.Cliente,mappingIgnore);
                self.data.Clientes.push(new VistaModeloCliente(copia));
                self.Seleccionar(self.data.Cliente, event);
              }
            });

          }
          else {
            var copia = ko.mapping.toJS(self.data.Cliente,mappingIgnore);
            var resultado = new VistaModeloCliente(copia);

            var objeto = ko.utils.arrayFirst(self.data.Clientes(),function (item) {return item.IdPersona() == data.IdPersona();});
            self.data.Clientes.replace(objeto ,resultado);
            self.Seleccionar(resultado,event);
            $("#modalCliente").modal("hide");
            $("#loader").hide();
          }
        }

      }
    }

    self.OnClickBtnNuevo = function(data,event) {
      if (event){
        self.data.Cliente.Show(event);
        self.data.Cliente.OnNuevo(self.data.Cliente.ClienteNuevo,event,self.PostGuardar);
        self.data.Cliente.copiatextofiltroguardar(self.copiatextofiltro());
      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarClientesPorPagina(data,event,function($data,$event) {
            var objeto = self.data.Clientes()[0];
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
          self.ListarClientes(data,event,function ($data,$event) {
              var objeto = self.data.Clientes()[0];
              self.Seleccionar(objeto, event);
              $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
              self.data.Filtros.totalfilas($data.Filtros.totalfilas);
          });
        }
      }
    }

    self.AgregarAlumno =function(data,event) {
      if (event) {
        self.data.Alumno.AgregarAlumno(data,event);
      }
    }

  }
