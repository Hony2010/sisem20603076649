VistaModeloVehiculos = function (data) {

  var self = this;
  ko.mapping.fromJS(data, MappingVehiculo, self);
  ModeloVehiculos.call(this, self);

  self.Inicializar = function () {
    if (self.data.Vehiculos().length > 0) {
      var objeto = self.data.Vehiculos()[0];
      self.Seleccionar(objeto, window);
      var input = ko.toJS(self.data.Filtros);
      $("#Paginador").paginador(input, self.ConsultarPorPagina);
    }
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      if (data != undefined) {
        var id = "#" + data.IdVehiculo();
        $(id).addClass('active').siblings().removeClass('active');

        var objeto = Knockout.CopiarObjeto(data);
        ko.mapping.fromJS(objeto, {}, self.data.Vehiculo);
      }
    }
  }

  self.OnClickBtnEditar = function (data, event) {
    if (event) {
      // var $data = ko.mapping.fromJS(data,{},self.data.Vehiculo);
      self.data.Vehiculo.OnEditar(data, event, self.PostGuardar);
      // $("#modalVehiculo").modal("show");
      self.data.Vehiculo.Show(event);
    }
  }

  self.OnClickBtnEliminar = function (data, event) {
    if (event) {
      var titulo = "Eliminación de Vehiculo";
      alertify.confirm(titulo, "¿Desea borrar realmente?", function () {

        var objeto_data = ko.mapping.toJS(data);
        data = { "data": objeto_data, "filtro": self.copiatextofiltro() };
        data = Knockout.CopiarObjeto(data);

        self.data.Vehiculo.OnEliminar(data, event, function ($data, $event) {
          if ($data.error) {
            $("#loader").hide();
            alertify.alert("Error en " + titulo, $data.error.msg, function () {
            });
          }
          else {
            var id = "#" + data.data.IdVehiculo();
            var siguienteObjeto = $(id).next();
            if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
            siguienteObjeto.addClass('active').siblings().removeClass('active');

            var objeto = ko.utils.arrayFirst(self.data.Vehiculos(), function (item) { return item.IdVehiculo() == data.data.IdVehiculo(); });
            self.data.Vehiculos.remove(objeto);

            var filas = self.data.Vehiculos().length;
            self.data.Filtros.totalfilas($data.Filtros.totalfilas);
            if (filas == 0) {
              $("#Paginador").paginador($data.Filtros, self.ConsultarPorPagina);
              var ultimo = $("#Paginador ul li:last").prev();
              ultimo.children("a").click();
            }
          }
        });
      }, function () { });
    }
  }

  self.PostGuardar = function (data, $data, event) {
    if (event) {
      if (self.data.Vehiculo.EstaProcesado() == true) {
        if (self.data.Vehiculo.opcionProceso() == opcionProceso.Nuevo) {

          alertify.confirm("REGISTRO DE VEHICULO", "Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function () {
            self.data.Vehiculo.OnNuevo(self.data.Vehiculo.VehiculoNuevo, event, self.PostGuardar);
          }, function () {

            $("#modalVehiculo").modal("hide");

            var filas = self.data.Vehiculos().length;
            self.data.Filtros.totalfilas($data.Filtros.totalfilas);
            if (filas >= 10) {
              $("#Paginador").paginador($data.Filtros, self.ConsultarPorPagina);
              var ultimo = $("#Paginador ul li:last").prev();
              ultimo.children("a").click();
            }
            else {
              var copia = ko.mapping.toJS(self.data.Vehiculo, mappingIgnore);
              self.data.Vehiculos.push(new VistaModeloVehiculo(copia));
              self.Seleccionar(self.data.Vehiculo, event);
            }
          });

        }
        else {
          var copia = ko.mapping.toJS(self.data.Vehiculo, mappingIgnore);
          var resultado = new VistaModeloVehiculo(copia);

          var objeto = ko.utils.arrayFirst(self.data.Vehiculos(), function (item) { return item.IdVehiculo() == data.IdVehiculo(); });
          self.data.Vehiculos.replace(objeto, resultado);
          self.Seleccionar(resultado, event);
          $("#modalVehiculo").modal("hide");
          $("#loader").hide();
        }
      }

    }
  }

  self.OnClickBtnNuevo = function (data, event) {
    if (event) {
      self.data.Vehiculo.Show(event);
      self.data.Vehiculo.OnNuevo(self.data.Vehiculo.VehiculoNuevo, event, self.PostGuardar);
      self.data.Vehiculo.copiatextofiltroguardar(self.copiatextofiltro());
    }
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ListarVehiculosPorPagina(data, event, function ($data, $event) {
        var objeto = self.data.Vehiculos()[0];
        self.Seleccionar(objeto, event);
        $("#Paginador").pagination("drawPage", $data.pagina);
      });
    }
  }

  self.Consultar = function (data, event) {
    if (event) {
      var tecla = event.keyCode ? event.keyCode : event.which;
      if (tecla == TECLA_ENTER) {
        var inputs = $(event.target).closest('form').find(':input:visible');
        inputs.eq(inputs.index(event.target) + 1).focus();

        self.copiatextofiltro(data.textofiltro());
        self.ListarVehiculos(data, event, function ($data, $event) {
          var objeto = self.data.Vehiculos()[0];
          self.Seleccionar(objeto, event);
          $("#Paginador").paginador($data.Filtros, self.ConsultarPorPagina);
          self.data.Filtros.totalfilas($data.Filtros.totalfilas);
        });
      }
    }
  }

}
