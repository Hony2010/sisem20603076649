VistaModeloConsultaTransferenciaAlmacen = function (data) {

  var self = this;
  ko.mapping.fromJS(data, MappingTransferenciaAlmacen, self);
  ModeloConsultaTransferenciaAlmacen.call(this, self);

  self.Inicializar = function () {
    if (self.data.TransferenciasAlmacen().length > 0) {
      var objeto = self.data.TransferenciasAlmacen()[0];
      self.Seleccionar(objeto, window);
      var input = ko.toJS(self.data.Filtros);
      $("#Paginador").paginador(input, self.ConsultarPorPagina);
    }
    $(".fecha").inputmask({ "mask": "99/99/9999" });
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      if (data != undefined) {
        var id = "#" + data.IdTransferenciaAlmacen();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.OnClickBtnVer = function (data, event) {
    if (event) {

      self.data.TransferenciaAlmacen.Ver(data, event, self.PostGuardar);
      var modal = self.data.TransferenciaAlmacen.idModal;

      $(modal).modal("show");
    }
  }

  self.OnClickBtnEditar = function (data, event) {
    if (event) {
      self.data.TransferenciaAlmacen.Editar(data, event, self.PostGuardar);
      var modal = self.data.TransferenciaAlmacen.idModal;

      $(modal).modal("show");
    }
  }

  self.OnClickBtnAnular = function (data, event) {
    if (event) {
      alertify.confirm("Anulación de Transferencia Almacen", "¿Desea anular realmente el comprobante " + data.SerieTransferencia() + " - " + data.NumeroTransferencia() + "?", function () {
        $("#loader").show();
        self.data.TransferenciaAlmacen.Anular(data, event, self.PostAnular);
      }, function () { });
    }
  }

  self.OnClickBtnEliminar = function (data, event) {
    if (event) {
      alertify.confirm("Eliminación de Transferencia Almacen", "¿Desea borrar realmente el comprobante " + data.SerieTransferencia() + " - " + data.NumeroTransferencia() + "?", function () {
        var objeto = ko.mapping.toJS(data);
        objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
        $("#loader").show();
        self.data.TransferenciaAlmacen.Eliminar(objeto, event, self.PostEliminar);
      }, function () { });
    }
  }

  self.OnClickBtnImprimir = function (data, event) {
    if (event) {
      $("#loader").show();
      self.data.TransferenciaAlmacen.Imprimir(data, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error) {
          alertify.alert("Impresión de Transferencia Almacen", "El Comprobante se imprimió correctamente.", function () { })
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
        var objeto = ko.utils.arrayFirst(self.data.TransferenciasAlmacen(), function (item) { return item.IdTransferenciaAlmacen() == data.IdTransferenciaAlmacen; });
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
      $("#loader").hide();
      if (!data.error) {
        alertify.alert("Consulta Transferencia Almacen", "Se eliminó correctamente.", function () {
          var id = "#" + data.IdTransferenciaAlmacen;
          var siguienteObjeto = $(id).next();
          if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
          siguienteObjeto.addClass('active').siblings().removeClass('active');
          var objeto = ko.utils.arrayFirst(self.data.TransferenciasAlmacen(), function (item) { return item.IdTransferenciaAlmacen() == data.IdTransferenciaAlmacen; });
          self.data.TransferenciasAlmacen.remove(objeto);

          var filas = self.data.TransferenciasAlmacen().length;
          self.data.Filtros.totalfilas(data.Filtros.totalfilas);
          if (filas == 0) {
            $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
            var ultimo = $("#Paginador ul li:last").prev();
            ultimo.children("a").click();
          }
        });
      } else {
        alertify.alert("Consulta Transferencia Almacen", data.error.msg, function () {
        });
      }
    }
  }

  self.PostGuardar = function (data, event) {
    if (event) {
      if (data) {
        var objeto = ko.utils.arrayFirst(self.data.TransferenciasAlmacen(), function (item) { return item.IdTransferenciaAlmacen() == self.data.TransferenciaAlmacen.IdTransferenciaAlmacen(); });

        var copia = ko.mapping.toJS(self.data.TransferenciaAlmacen, mappingIgnore);
        var modal = self.data.TransferenciaAlmacen.idModal;
        var item = new VistaModeloTransferenciaAlmacen(copia)

        self.data.TransferenciasAlmacen.replace(item, objeto);
        self.Seleccionar(item, event);

        $(modal).modal("hide");
      }
    }
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ListarTransferenciasAlmacenPorPagina(data, event, self.PostConsultarPorPagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      var objeto = self.data.TransferenciasAlmacen()[0];
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
        self.ListarTransferenciasAlmacen(data, event, self.PostConsultar);
      }
    }
  }

  self.ConsultarDesdeButton = function (data, event) {
    if (event) {
      var inputs = $(event.target).closest('form').find(':input:visible');
      inputs.eq(inputs.index(event.target) + 1).focus();
      self.ListarTransferenciasAlmacen(data, event, self.PostConsultar);
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      var objeto = self.data.TransferenciasAlmacen()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);

    }
  }

}
