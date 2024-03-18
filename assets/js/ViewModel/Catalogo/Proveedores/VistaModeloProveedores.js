VistaModeloProveedores = function (data) {

    var self = this;
    var objeto_seleccionado = null;
    ko.mapping.fromJS(data, MappingCatalogo, self);
    ModeloProveedores.call(this,self);

    self.Inicializar = function () {
      if(self.data.Proveedores().length > 0)
      {
        var objeto = self.data.Proveedores()[0];
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
          ko.mapping.fromJS(objeto, {}, self.data.Proveedor);

          objeto_seleccionado = Knockout.CopiarObjeto(objeto);

          $("#img_FileFotoPreview").attr("src",self.data.Proveedor.ObtenerRutaFoto());
        }
      }
    }

    self.OnClickBtnEditar  = function(data, event) {
      if(event)
      {
        self.data.Proveedor.OnEditar(data,event,self.PostGuardar);
        $("#modalProveedor").modal("show");
      }
    }

    self.OnClickBtnEliminar = function (data, event) {
      if(event) {
        var titulo = "Eliminación de Proveedor";
        alertify.confirm(titulo ,"¿Desea borrar realmente?", function(){

          var objeto_data = ko.mapping.toJS(data);
          data = {"data" : objeto_data, "filtro" : self.copiatextofiltro()};
          data = Knockout.CopiarObjeto(data);

          self.data.Proveedor.OnEliminar(data,event,self.PostEliminar);
        },function(){});
      }
    }

    self.PostEliminar = function (data,event) {
      if(event)
      {
        var titulo = "Eliminación de Proveedor";

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
          var objeto = ko.utils.arrayFirst(self.data.Proveedores(),function (item) {return item.IdPersona() == objeto_seleccionado.IdPersona();});
          self.data.Proveedores.remove(objeto);

          var filas = self.data.Proveedores().length;
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
        if (self.data.Proveedor.EstaProcesado()==true) {
          if(self.data.Proveedor.opcionProceso() == opcionProceso.Nuevo) {
            //var copia_objeto = Knockout.CopiarObjeto(self.data.Proveedor);

            alertify.confirm("REGISTRO DE PROVEEDOR","Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function(){
              self.data.Proveedor.OnNuevo(self.data.Proveedor.ProveedorNuevo,event,self.PostGuardar);
            }, function(){
              $("#modalProveedor").modal("hide");

              var filas = self.data.Proveedores().length;
              self.data.Filtros.totalfilas($data.Filtros.totalfilas);
              if(filas >= 10) {
                $("#Paginador").paginador($data.Filtros,self.ConsultarPorPagina);
                var ultimo = $("#Paginador ul li:last").prev();
                ultimo.children("a").click();
              }
              else {
                var copia =  ko.mapping.toJS(self.data.Proveedor,mappingIgnore);
                self.data.Proveedores.push(new VistaModeloProveedor(copia));
                self.Seleccionar(self.data.Proveedor, event);
              }
            });
          }
          else {
            var copia = ko.mapping.toJS(self.data.Proveedor,mappingIgnore);
            var resultado = new VistaModeloProveedor(copia);

            var objeto = ko.utils.arrayFirst(self.data.Proveedores(),function (item) {return item.IdPersona() == data.IdPersona();});
            self.data.Proveedores.replace(objeto ,resultado);
            self.Seleccionar(resultado,event);
            $("#modalProveedor").modal("hide");
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
        self.data.Proveedor.Show(event);
        self.data.Proveedor.OnNuevo(self.data.Proveedor.ProveedorNuevo,event,self.PostGuardar);
        self.data.Proveedor.copiatextofiltroguardar(self.copiatextofiltro());

      }
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarProveedoresPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.Proveedores()[0];
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
          self.ListarProveedores(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.Proveedores()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }

  }
