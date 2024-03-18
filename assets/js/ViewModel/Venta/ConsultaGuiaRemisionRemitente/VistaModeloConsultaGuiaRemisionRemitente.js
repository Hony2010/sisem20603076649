VistaModeloConsultaGuiaRemisionRemitente = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingGuiaRemisionRemitente, self);
  ModeloConsultaGuiaRemisionRemitente.call(this, self);

  self.Inicializar = function () {
    if (self.data.GuiasRemisionRemitente().length > 0) {
      self.Seleccionar(self.data.GuiasRemisionRemitente()[0], window);
      $("#Paginador").paginador(ko.toJS(self.data.Filtros), self.ConsultarPorPagina);
    }

    $(".fecha").inputmask({ "mask": "99/99/9999" });
  }

  self.Seleccionar = function (data, event) {
    if (event) {
      if (data != undefined) {
        var id = "#" + data.IdGuiaRemisionRemitente();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.OnClickBtnVer = function (data, event) {
    if (event) {
      self.data.GuiaRemisionRemitente.OnVer(data, event, self.PostGuardar);
      var options = self.data.GuiaRemisionRemitente.Options;

      setTimeout(function () {
        $(options.IdModal).modal("show");
      }, 250);
    }
  }

  self.OnClickBtnImprimir = function (data, event) {
    if (event) {
      $("#loader").show();
      self.data.GuiaRemisionRemitente.Imprimir(data, event, self.PostImprimir);
    }
  }

  self.PostImprimir = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (data == "") {
        alertify.alert("IMPRIMIR REGISTRO", "Se imprimió correctamente.", function () { })
      }
      else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.OnClickBtnEditar = function (data, event) {
    if (event) {
      self.data.GuiaRemisionRemitente.OnEditar(data, event, self.PostGuardar);
      var options = self.data.GuiaRemisionRemitente.Options;

      setTimeout(function () {
        $(options.IdModal).modal("show");
      }, 250);
    }
  }

  self.Eliminar = function (data, event) {
    if (event) {
      alertify.confirm("Eliminación de Guia Remisión Remitente", "¿Desea borrar realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        $("#loader").show();
        var objeto = ko.mapping.toJS(data);
        objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
        self.data.GuiaRemisionRemitente.Eliminar(objeto, event, self.PostEliminar);
      }, function () { });
    }
  }

  self.PostEliminar = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        var id = "#" + data.IdGuiaRemisionRemitente;
        var siguienteObjeto = $(id).next();
        if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
        siguienteObjeto.addClass('active').siblings().removeClass('active');
        var objeto = ko.utils.arrayFirst(self.data.GuiasRemisionRemitente(), function (item) { return item.IdGuiaRemisionRemitente() == data.IdGuiaRemisionRemitente; });
        self.data.GuiasRemisionRemitente.remove(objeto);

        var filas = self.data.GuiasRemisionRemitente().length;
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
        if (filas == 0) {
          $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
          var ultimo = $("#Paginador ul li:last").prev();
          ultimo.children("a").click();
        }
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.Anular = function (data, event) {
    if (event) {
      alertify.confirm("Anulación de Guia Remisión Remitente", "¿Desea anular realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        $("#loader").show();
        self.data.GuiaRemisionRemitente.Anular(data, event, self.PostAnular);
      }, function () { });
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        var objeto = ko.utils.arrayFirst(self.data.GuiasRemisionRemitente(), function (item) { return item.IdGuiaRemisionRemitente() == data.IdGuiaRemisionRemitente; });
        objeto.IndicadorEstado(data.IndicadorEstado);
        objeto.AbreviaturaSituacionCPE(data.AbreviaturaSituacionCPE);
        self.Seleccionar(objeto, event);
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }


  self.PostGuardar = function (data, event) {
    if (event) {
      //if (data.EstaProcesado()== true) {
      if (data) {
        data = ko.mapping.toJS(data);
        var objeto = ko.utils.arrayFirst(self.data.GuiasRemisionRemitente(), function (item) { return item.IdGuiaRemisionRemitente() == data.IdGuiaRemisionRemitente; });

        self.data.GuiaRemisionRemitente.DetallesGuiaRemisionRemitente([])
        var copia = ko.mapping.toJS(self.data.GuiaRemisionRemitente, mappingIgnore);
        var options = self.data.GuiaRemisionRemitente.Options;

        var resultado = new VistaModeloGuiaRemisionRemitente(copia, options);
        self.data.GuiasRemisionRemitente.replace(objeto, resultado);
        self.Seleccionar(resultado, event);

        $(options.IdModal).modal("hide");
        $("#loader").hide();
      }
    }
    //}
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ListarGuiasRemisionRemitentePorPagina(data, event, self.PostConsultarPorPagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      var objeto = self.data.GuiasRemisionRemitente()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").pagination("drawPage", data.pagina);
    }
  }

  self.Consultar = function (data, event) {
    if (event) {
      var inputs = $(event.target).closest('form').find(':input:visible');
      inputs.eq(inputs.index(event.target) + 1).focus();
      self.ListarGuiasRemisionRemitente(data, event, self.PostConsultar);
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      var objeto = self.data.GuiasRemisionRemitente()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);
    }
  }
}
