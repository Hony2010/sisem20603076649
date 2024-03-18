VistaModeloConsultaCanjeLetraCobrar = function (data) {

  var self = this;
  ko.mapping.fromJS(data, MappingCuentaCobranza, self);
  ModeloConsultaCanjeLetraCobrar.call(this, self);

  self.Inicializar = function () {
    if (self.data.CanjesLetraCobrar().length > 0) {
      var objeto = self.data.CanjesLetraCobrar()[0];
      self.Seleccionar(objeto, window);
      var input = ko.toJS(self.data.Filtros);
      $("#Paginador").paginador(input, self.ConsultarPorPagina);
    }
    $("#FechaInicio").inputmask({ "mask": "99/99/9999" });
    $("#FechaFin").inputmask({ "mask": "99/99/9999" });
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      if (data != undefined) {
        var id = "#" + data.IdCanjeLetraCobrar();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.OnClickBtnVer = function (data, event) {
    if (event) {
      var options = {};

      self.data.CanjeLetraCobrar.OnVer(data, event, self.PostGuardar);
      options = self.data.CanjeLetraCobrar.Options;

      $(options.IDModalForm).modal("show");
    }
  }

  self.OnClickBtnEditar = function (data, event) {
    if (event) {
      var options = {};

      self.data.CanjeLetraCobrar.OnEditar(data, event, self.PostGuardar);
      options = self.data.CanjeLetraCobrar.Options;

      $(options.IDModalForm).modal("show");
    }
  }

  self.OnClickBtnAnular = function (data, event) {
    if (event) {
      alertify.confirm("Anulación de Comprobante de Caja", "¿Desea anular realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        $("#loader").show();
        self.data.CanjeLetraCobrar.AnularCanjeLetraCobrar(data, event, self.PostAnular);
      }, function () { });
    }
  }

  self.OnClickBtnEliminar = function (data, event) {
    if (event) {
      alertify.confirm("Eliminación de Comprobante de Caja", "¿Desea borrar realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        var objeto = ko.mapping.toJS(data);
        self.data.CanjeLetraCobrar.EliminarCanjeLetraCobrar(objeto, event, self.PostEliminar);

      }, function () { });
    }
  }

  self.OnClickBtnImprimir = function (data, event) {
    if (event) {
      $("#loader").show();
      self.data.ComprobanteCaja.Imprimir(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          alertify.alert("Impresión de Comprobante de Caja", "El Comprobante se imprimió correctamente.", function () { })
        } else {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { });
        }
      });
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        var objeto = ko.utils.arrayFirst(self.data.CanjesLetraCobrar(), function (item) { return item.IdCanjeLetraCobrar() == data.IdCanjeLetraCobrar; });
        objeto.IndicadorEstado(data.IndicadorEstado);
        self.Seleccionar(objeto, event);
        $("#loader").hide();
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.PostEliminar = function (data, event) {
    if (event) {
      if (!data.error) {
        var id = "#" + data.IdCanjeLetraCobrar;
        var siguienteObjeto = $(id).next();
        if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
        siguienteObjeto.addClass('active').siblings().removeClass('active');
        var objeto = ko.utils.arrayFirst(self.data.CanjesLetraCobrar(), function (item) { return item.IdCanjeLetraCobrar() == data.IdCanjeLetraCobrar; });
        self.data.CanjesLetraCobrar.remove(objeto);

        // var filas = self.data.CanjesLetraCobrar().length;
        // self.data.Filtros.totalfilas(data.Filtros.totalfilas);
        // if (filas == 0) {
        //   $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        //   var ultimo = $("#Paginador ul li:last").prev();
        //   ultimo.children("a").click();
        // }
      } else {
        $("#loader").hide();
        alertify.alert("CONSULTA COMPROBANTE CAJA", data.error.msg, function () {
        });
      }
    }
  }

  self.PostGuardar = function (data, event) {
    if (event) {
      if (data) {
        var objeto = ko.utils.arrayFirst(self.data.CanjesLetraCobrar(), function (item) { return item.IdCanjeLetraCobrar() == data.IdCanjeLetraCobrar(); });
        var options = {}, item = {};

        var copia = ko.mapping.toJS(self.data.CanjeLetraCobrar, mappingIgnore);
        options = self.data.CanjeLetraCobrar.Options;
        self.data.CanjeLetraCobrar.PendientesLetraCobrar([]);
        self.data.BusquedaPendientesCobranzaCliente([]);

        item = new VistaModeloCanjeLetraCobrar(copia, options)

        self.data.CanjesLetraCobrar.replace(item, objeto);
        self.Seleccionar(item, event);
        $(options.IDModalForm).modal("hide");
      }
    }
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ListarCanjesLetraCobrarPorPagina(data, event, self.PostConsultarPorPagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      var objeto = self.data.CanjesLetraCobrar()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").pagination("drawPage", data.pagina);
    }
  }

  self.Consultar = function (data, event) {
    if (event) {
      var tecla = event.keyCode ? event.keyCode : event.which;
      if (tecla == TECLA_ENTER) {
        var inputs = $(event.target).closest('form').find(':input:visible');
        inputs.eq(inputs.index(event.target) + 1).focus();
        self.ListarCanjesLetraCobrar(data, event, self.PostConsultar);
      }
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      var objeto = self.data.CanjesLetraCobrar()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);
    }
  }
}
