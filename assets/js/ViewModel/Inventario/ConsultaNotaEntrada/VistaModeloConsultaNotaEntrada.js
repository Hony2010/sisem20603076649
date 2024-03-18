VistaModeloConsultaNotaEntrada = function (data) {

    var self = this;
    ko.mapping.fromJS(data, MappingInventario, self);
    ModeloConsultaNotaEntrada.call(this,self);

    self.Inicializar = function ()  {
      if(self.data.NotasEntrada().length > 0)
      {
        var objeto = self.data.NotasEntrada()[0];
        self.Seleccionar(objeto,window);
        var input = ko.toJS(self.data.Filtros);
        $("#Paginador").paginador(input,self.ConsultarPorPagina);
      }
      $("#FechaInicio").inputmask({"mask": "99/99/9999"});
      $("#FechaFin").inputmask({"mask": "99/99/9999"});
    }

    self.Seleccionar = function (data,event)  {
      if (event)
      {
        if (data != undefined) {
          var id = "#"+ data.IdNotaEntrada();
          $(id).addClass('active').siblings().removeClass('active');
        }
      }
    }

    self.Anular = function(data,event) {
      if (event)  {
        var titulo = "Anulación de Comprobante de Compra";
        var mensaje ="¿Desea anular realmente el comprobante "+data.SerieDocumento()+" - " + data.NumeroDocumento()+"?";

        setTimeout(function() {
          alertify.confirm(titulo,mensaje,
            function(){
              $("#loader").show();
              self.data.NotaEntrada.Anular(data,event,self.PostAnular);
            },
            function(){
              $("#loader").hide();
            }
          );
        }, 100);
      }
    }

    self.OnClickBtnVer = function(data,event) {
      if(event) {
        // $("#modalNotaEntrada").modal("show");
        // var options = self.data.NotaEntrada.Options;
        self.data.NotaEntrada.OnVer(data,event,self.PostGuardar);

        setTimeout( function()  {
          $("#modalNotaEntrada").modal("show");
        },500);
      }
    }

    self.OnClickBtnEditar = function(data,event) {
      if(event) {
        self.data.NotaEntrada.Editar(data,event,self.PostGuardar);

        setTimeout( function()  {
          $("#modalNotaEntrada").modal("show");
        },250);
      }
    }

    self.Eliminar = function (data,event) {
      if(event)
      {
        alertify.confirm("Eliminar Comprobante de Compra","¿Desea borrar realmente?",function () {
          var objeto = ko.mapping.toJS(data);
          objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
          self.data.NotaEntrada.Eliminar(objeto,event,self.PostEliminar);
        },function () {

        });
      }
    }

    self.PostAnular = function (data,event) {
      if(event)
      {
        if(data)
        {
          debugger;
          var objeto = ko.utils.arrayFirst(self.data.NotasEntrada(),function (item) {return item.IdNotaEntrada() == data.IdNotaEntrada;});
          //var copia = ko.mapping.toJS(data,mappingIgnore);//self.data.NotaEntrada
          //var resultado = new VistaModeloNotaEntrada(copia);
          //self.data.NotasEntrada.replace(objeto ,resultado);
          //ko.mapping.fromJS(resultado,{}, objeto);
          objeto.IndicadorEstado(data.IndicadorEstado);
          self.Seleccionar(objeto,event);
          $("#loader").hide();
        }
      }
    }

    self.PostEliminar = function (data,event) {
      if(event)
      {
        if(data.error) {
          $("#loader").hide();
          alertify.alert("Error en "+ self.titulo,data.error.msg,function(){
          });
        }
        else {
          // var id =  "#"+data.IdNotaEntrada();
          var id =  "#"+data.IdNotaEntrada;
          var siguienteObjeto = $(id).next();
          if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
          siguienteObjeto.addClass('active').siblings().removeClass('active');
          var objeto = ko.utils.arrayFirst(self.data.NotasEntrada(),function (item) {return item.IdNotaEntrada() == data.IdNotaEntrada;});
          self.data.NotasEntrada.remove(objeto);

          var filas = self.data.NotasEntrada().length;
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

    self.PostGuardar = function(data,event) {
      if(event) {
        //if (data.EstaProcesado()== true) {
          if(data) {
            var objeto = ko.utils.arrayFirst(self.data.NotasEntrada(),function (item) {return item.IdNotaEntrada() == data.IdNotaEntrada;});

            var copia = ko.mapping.toJS(self.data.NotaEntrada,mappingIgnore);
            copia = ko.mapping.toJS(copia, {ignore:["DetallesNotaEntrada", "MiniComprobantesVenta"]});
            var resultado = new VistaModeloNotaEntrada(copia);
            self.data.NotasEntrada.replace(objeto ,resultado);
            self.Seleccionar(resultado,event);
            $("#modalNotaEntrada").modal("hide");
            $("#loader").hide();
          }
        }
      //}
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ListarNotasEntradaPorPagina(data,event,self.PostConsultarPorPagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        var objeto = self.data.NotasEntrada()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").pagination("drawPage", data.pagina);
      }
    }

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();
          self.ListarNotasEntrada(data,event,self.PostConsultar);
        }
      }
    }

    self.PostConsultar = function (data,event) {
      if(event) {
        var objeto = self.data.NotasEntrada()[0];
        self.Seleccionar(objeto,event);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }
}
