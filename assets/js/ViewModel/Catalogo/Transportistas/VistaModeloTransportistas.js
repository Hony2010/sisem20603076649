VistaModeloTransportistas = function (data) {

    var self = this;
    var objeto_seleccionado = null;
    ko.mapping.fromJS(data, MappingCatalogo, self);
    ModeloTransportistas.call(this,self);

    self.Inicializar = function () {
      if(self.data.Transportistas().length > 0)
      {
        var objeto = self.data.Transportistas()[0];
        self.Seleccionar(objeto,window);
        var input = ko.toJS(self.data.Filtros);
        $("#Paginador").paginador(input,self.ConsultarPorPagina);
      }
    }

    self.Seleccionar = function (data,event){
      if (event) {
        if(data != undefined) {
          var id = "#"+ data.IdPersona();
          $(id).addClass('active').siblings().removeClass('active');

          var objeto = Knockout.CopiarObjeto(data);
          ko.mapping.fromJS(objeto, {}, self.data.Transportista);

          objeto_seleccionado = Knockout.CopiarObjeto(objeto);

          $("#img_FileFotoPreview").attr("src",self.data.Transportista.ObtenerRutaFoto());
        }
      }
    }

    self.OnClickBtnEditar  = function(data, event) {
      if(event)
      {
        self.data.Transportista.OnEditar(data,event,self.PostGuardar);
        $("#modalTransportista").modal("show");
      }
    }

    self.OnClickBtnEliminar = function (data, event) {
      if(event) {
        var titulo = "Eliminación de Transportista";
        alertify.confirm(titulo ,"¿Desea borrar realmente?", function(){

          var objeto_data = ko.mapping.toJS(data);
          data = {"data" : objeto_data, "filtro" : self.copiatextofiltro()};
          data = Knockout.CopiarObjeto(data);

          self.data.Transportista.OnEliminar(data,event,self.PostEliminar);
        },function(){});
      }
    }

    self.PostEliminar = function (data,event) {
      if(event)
      {
        var titulo = "Eliminación de Transportista";

        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en "+ titulo,data.error.msg,function(){
          });
        }
        else {
          var id =  "#"+objeto_seleccionado.IdPersona();
          var siguienteObjeto = $(id).next();
          if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
          siguienteObjeto.addClass('active').siblings().removeClass('active');
          var objeto = ko.utils.arrayFirst(self.data.Transportistas(),function (item) {return item.IdPersona() == objeto_seleccionado.IdPersona();});
          self.data.Transportistas.remove(objeto);

          var filas = self.data.Transportistas().length;
          self.data.Filtros.totalfilas(data.Filtros.totalfilas);
          if(filas == 0)
          {
            $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
            var ultimo = $("#Paginador ul li:last").prev();
            ultimo.children("a").click();
          }
        }
      }
    }

    self.PostGuardar = function(data,$data,event) {
      if(event)
      {
        if (self.data.Transportista.EstaProcesado()==true) {
          if(self.data.Transportista.opcionProceso() == opcionProceso.Nuevo) {
            //var copia_objeto = Knockout.CopiarObjeto(self.data.Transportista);

            alertify.confirm("REGISTRO DE Transportista","Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function(){
              self.data.Transportista.OnNuevo(self.data.Transportista.TransportistaNuevo,event,self.PostGuardar);
            }, function(){
              $("#modalTransportista").modal("hide");

              var filas = self.data.Transportistas().length;
              self.data.Filtros.totalfilas($data.Filtros.totalfilas);
              if(filas >= 10) {
                $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
                var ultimo = $("#Paginador ul li:last").prev();
                ultimo.children("a").click();
              }
              else {
                var copia =  ko.mapping.toJS(self.data.Transportista,mappingIgnore);
                self.data.Transportistas.push(new VistaModeloTransportista(copia));
                self.Seleccionar(self.data.Transportista, event);
              }
            });
          }
          else {
            var copia = ko.mapping.toJS(self.data.Transportista,mappingIgnore);
            var resultado = new VistaModeloTransportista(copia);

            var objeto = ko.utils.arrayFirst(self.data.Transportistas(),function (item) {return item.IdPersona() == data.IdPersona();});
            self.data.Transportistas.replace(objeto ,resultado);
            self.Seleccionar(resultado,event);
            $("#modalTransportista").modal("hide");
            $("#loader").hide();
          }
        }
        else {
          self.Seleccionar(objeto_seleccionado,event);
        }
      }
    }

    self.OnClickBtnNuevo = function(data,event) {
      if (event){
        self.data.Transportista.Show(event);
        self.data.Transportista.OnNuevo(self.data.Transportista.TransportistaNuevo,event,self.PostGuardar);
        self.data.Transportista.copiatextofiltroguardar(self.copiatextofiltro());

      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarTransportistasPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.Transportistas()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").pagination("drawPage", data.pagina);
      }
    }

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          self.copiatextofiltro(data.textofiltro());
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();
          self.ListarTransportistas(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.Transportistas()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }

  }
