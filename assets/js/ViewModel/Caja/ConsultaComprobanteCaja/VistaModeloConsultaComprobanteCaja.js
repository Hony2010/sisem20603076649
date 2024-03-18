VistaModeloConsultaComprobanteCaja = function (data) {

  var self = this;
  ko.mapping.fromJS(data, MappingCaja, self);
  ModeloConsultaComprobanteCaja.call(this, self);

  self.Inicializar = function () {
    if (self.data.ComprobantesCaja().length > 0) {
      var objeto = self.data.ComprobantesCaja()[0];
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
        var id = "#" + data.IdComprobanteCaja();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.OnClickBtnVer = function (data, event) {
    if (event) {
      var options = {};

      switch (data.IndicadorTipoComprobante()) {
        case INDICADOR_TIPO_COMPROBANTE.INGRESO:
          switch (data.IdTipoOperacionCaja()) {
            case ID_TIPO_OPERACION_SALDO_INICIAL:
              self.data.AperturaCaja.OnVer(data, event, self.PostGuardar);
              options = self.data.AperturaCaja.Options;
              break;
            default:
              self.data.OtroDocumentoIngreso.OnVer(data, event, self.PostGuardar);
              options = self.data.OtroDocumentoIngreso.Options;
          }
          break;
        case INDICADOR_TIPO_COMPROBANTE.SALIDA:
          self.data.OtroDocumentoEgreso.OnVer(data, event, self.PostGuardar);
          options = self.data.OtroDocumentoEgreso.Options;
          break;
        case INDICADOR_TIPO_COMPROBANTE.TRANFERENCIA:
          self.data.TransferenciaCaja.OnVer(data, event, self.PostGuardar);
          options = self.data.TransferenciaCaja.Options;
          break;
        default:
          options.IDModalForm = "";
      }
      $(options.IDModalForm).modal("show");
    }
  }

  self.OnClickBtnEditar = function (data, event) {
    if (event) {
      var options = {};

      switch (data.IndicadorTipoComprobante()) {
        case INDICADOR_TIPO_COMPROBANTE.INGRESO:
          switch (data.IdTipoOperacionCaja()) {
            case ID_TIPO_OPERACION_SALDO_INICIAL:
              self.data.AperturaCaja.OnEditar(data, event, self.PostGuardar);
              options = self.data.AperturaCaja.Options;
              break;
            default:
              self.data.OtroDocumentoIngreso.OnEditar(data, event, self.PostGuardar);
              options = self.data.OtroDocumentoIngreso.Options;
          }
          break;
        case INDICADOR_TIPO_COMPROBANTE.SALIDA:
          self.data.OtroDocumentoEgreso.OnEditar(data, event, self.PostGuardar);
          options = self.data.OtroDocumentoEgreso.Options;
          break;
        case INDICADOR_TIPO_COMPROBANTE.TRANFERENCIA:
          self.data.TransferenciaCaja.OnEditar(data, event, self.PostGuardar);
          options = self.data.TransferenciaCaja.Options;
          break;
        default:
          options.IDModalForm = "";
      }

      $(options.IDModalForm).modal("show");
    }
  }

  self.OnClickBtnAnular = function (data, event) {
    if (event) {
      alertify.confirm("Anulación de Comprobante de Caja", "¿Desea anular realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        $("#loader").show();

        switch (data.IndicadorTipoComprobante()) {
          case INDICADOR_TIPO_COMPROBANTE.INGRESO:
            switch (data.IdTipoOperacionCaja()) {
              case ID_TIPO_OPERACION_SALDO_INICIAL:
                self.data.AperturaCaja.AnularAperturaCaja(data, event, self.PostAnular);
                break;
              default:
                self.data.OtroDocumentoIngreso.AnularDocumentoIngreso(data, event, self.PostAnular);
            }
            break;
          case INDICADOR_TIPO_COMPROBANTE.SALIDA:
            self.data.OtroDocumentoEgreso.AnularDocumentoEgreso(data, event, self.PostAnular);
            break;
          case INDICADOR_TIPO_COMPROBANTE.TRANFERENCIA:
            self.data.TransferenciaCaja.AnularTransferenciaCaja(data, event, self.PostAnular);
            break;
          default:
        }

      }, function () { });
    }
  }

  self.OnClickBtnEliminar = function (data, event) {
    if (event) {
      alertify.confirm("Eliminación de Comprobante de Caja", "¿Desea borrar realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        var objeto = ko.mapping.toJS(data);
        objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
        objeto.Filtros.IdCaja = (objeto.Filtros.IdCaja == undefined) ? "%" : objeto.Filtros.IdCaja;
        objeto.Filtros.IdTipoOperacionCaja = (objeto.Filtros.IdTipoOperacionCaja == undefined) ? "%" : objeto.Filtros.IdTipoOperacionCaja;

        switch (data.IndicadorTipoComprobante()) {
          case INDICADOR_TIPO_COMPROBANTE.INGRESO:
            switch (data.IdTipoOperacionCaja()) {
              case ID_TIPO_OPERACION_SALDO_INICIAL:
                self.data.AperturaCaja.EliminarAperturaCaja(objeto, event, self.PostEliminar);
                break;
              default:
                self.data.OtroDocumentoIngreso.EliminarDocumentoIngreso(objeto, event, self.PostEliminar);
            }
            break;
          case INDICADOR_TIPO_COMPROBANTE.SALIDA:
            self.data.OtroDocumentoEgreso.EliminarDocumentoEgreso(objeto, event, self.PostEliminar);
            break;
          case INDICADOR_TIPO_COMPROBANTE.TRANFERENCIA:
            self.data.TransferenciaCaja.EliminarTransferenciaCaja(objeto, event, self.PostEliminar);
            break;
          default:
        }
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
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesCaja(), function (item) { return item.IdComprobanteCaja() == data.IdComprobanteCaja; });
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
        var id = "#" + data.IdComprobanteCaja;
        var siguienteObjeto = $(id).next();
        if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
        siguienteObjeto.addClass('active').siblings().removeClass('active');
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesCaja(), function (item) { return item.IdComprobanteCaja() == data.IdComprobanteCaja; });
        self.data.ComprobantesCaja.remove(objeto);

        var filas = self.data.ComprobantesCaja().length;
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
        if (filas == 0) {
          $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
          var ultimo = $("#Paginador ul li:last").prev();
          ultimo.children("a").click();
        }
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
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesCaja(), function (item) { return item.IdComprobanteCaja() == data.IdComprobanteCaja(); });
        var options = {}, item = {};

        switch (data.IndicadorTipoComprobante()) {
          case INDICADOR_TIPO_COMPROBANTE.INGRESO:
            switch (data.IdTipoOperacionCaja()) {
              case ID_TIPO_OPERACION_SALDO_INICIAL:
                var copia = ko.mapping.toJS(self.data.AperturaCaja, mappingIgnore);
                options = self.data.AperturaCaja.Options;
                item = new VistaModeloAperturaCaja(copia, options)
                break;
              default:
                var copia = ko.mapping.toJS(self.data.OtroDocumentoIngreso, mappingIgnore);
                options = self.data.OtroDocumentoIngreso.Options;
                item = new VistaModeloOtroDocumentoIngreso(copia, options);
            }
            break;
          case INDICADOR_TIPO_COMPROBANTE.SALIDA:
            var copia = ko.mapping.toJS(self.data.OtroDocumentoEgreso, mappingIgnore);
            options = self.data.OtroDocumentoEgreso.Options;
            item = new VistaModeloOtroDocumentoEgreso(copia, options)
            break;
          case INDICADOR_TIPO_COMPROBANTE.TRANFERENCIA:
            var copia = ko.mapping.toJS(self.data.TransferenciaCaja, mappingIgnore);
            options = self.data.TransferenciaCaja.Options;
            item = new VistaModeloTransferenciaCaja(copia, options)
            break;
          default:
            options.IDModalForm = "";
        }

        self.data.ComprobantesCaja.replace(item, objeto);
        self.Seleccionar(item, event);
        $(options.IDModalForm).modal("hide");
      }
    }
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ListarComprobantesCajaPorPagina(data, event, self.PostConsultarPorPagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      var objeto = self.data.ComprobantesCaja()[0];
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
        self.ListarComprobantesCaja(data, event, self.PostConsultar);
      }
    }
  }

  self.ConsultarDesdeButton = function (data, event) {
    if (event) {
      var inputs = $(event.target).closest('form').find(':input:visible');
      inputs.eq(inputs.index(event.target) + 1).focus();
      self.ListarComprobantesCaja(data, event, self.PostConsultar);
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      var objeto = self.data.ComprobantesCaja()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);

    }
  }

}
