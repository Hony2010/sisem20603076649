VistaModeloConsultaComprobanteVenta = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingVenta, self);
  ModeloConsultaComprobanteVenta.call(this, self);

  self.Inicializar = function () {
    if (self.data.ComprobantesVenta().length > 0) {
      var objeto = self.data.ComprobantesVenta()[0];
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
        var id = "#" + data.IdComprobanteVenta();
        $(id).addClass('active').siblings().removeClass('active');
      }
    }
  }

  self.Anular = function (data, event) {
    if (event) {
      var titulo = "Anulación de Comprobante de Venta";
      var mensaje = "¿Desea anular realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?";
      setTimeout(function () {
        alertify.confirm(titulo, mensaje, function () {
          $("#loader").show();
          if (data.IdComprobantePreVenta() == "" || data.IdComprobantePreVenta() == null) {
            if (data.IdTipoDocumento() == ID_TIPO_DOCUMENTO_PROFORMA) {
              self.data.ComprobanteVenta.AnularProforma(data, event, self.PostAnular);
            } else {
              self.data.ComprobanteVenta.Anular(data, event, self.PostAnular);
            }
          } else {
            self.data.ComprobanteVenta.AnularComprobantePreVenta(data, event, self.PostAnular);
          }
        },
          function () {
            $("#loader").hide();
          }
        );
      }, 100);
      alertify.confirm("Anulación de Comprobante de Venta", "¿Desea anular realmente el comprobante " + data.SerieDocumento() + " - " + data.NumeroDocumento() + "?", function () {
        $("#loader").show();
        self.data.ComprobanteVenta.Anular(data, event, self.PostAnular);
      }, function () { });
    }
  }

  self.OnClickBtnVer = function (data, event) {
    if (event) {
      switch (data.IdTipoDocumento()) {
        case ID_TIPO_DOCUMENTO_FACTURA:
          self.data.FacturaVenta.OnVer(data, event, self.PostGuardar);
          var options = self.data.FacturaVenta.Options;
          break;
        case ID_TIPO_DOCUMENTO_BOLETA:
          if (data.IndicadorBoletaViaje() == 1) {
            self.data.BoletaViajeVenta.OnVer(data, event, self.PostGuardar);
            var options = self.data.BoletaViajeVenta.Options;
          } else {
            self.data.BoletaVenta.OnVer(data, event, self.PostGuardar);
            var options = self.data.BoletaVenta.Options;
          }
          break;
        case ID_TIPO_DOCUMENTO_ORDEN_PEDIDO:
          self.data.OrdenPedido.OnVer(data, event, self.PostGuardar);
          var options = self.data.OrdenPedido.Options;
          break;
        case ID_TIPO_DOCUMENTO_PROFORMA:
          self.data.Proforma.OnVer(data, event, self.PostGuardar);
          var options = self.data.Proforma.Options;
          break;
        case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
          self.data.NotaCredito.OnVer(data, event, self.PostGuardar);
          var options = self.data.NotaCredito.Options.NC;
          break;
        case String(ID_TIPO_DOCUMENTO_NOTADEVOLUCION):
          self.data.NotaCredito.OnVer(data, event, self.PostGuardar);
          var options = self.data.NotaCredito.Options.NV;
          break;
        case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
          self.data.NotaDebito.OnVer(data, event, self.PostGuardar);
          var options = self.data.NotaDebito.Options;
          break;
        default:
        //alertify.alert(self.titulo,"No se encontro pantalla para este tipo, consulte al administrador",function(){});
      }

      setTimeout(function () {
        $(options.IDModalComprobanteVenta).modal("show");
      }, 250);
    }
  }

  self.OnClickBtnImprimir = function (data, event) {
    if (event) {
      $("#loader").show();
      self.data.ComprobanteVenta.Imprimir(data, event, self.PostImprimir);
    }
  }
  self.PostImprimir = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        if (data.APP_RUTA) { printJS(data.APP_RUTA); }
      }
      else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.OnClickBtnEditar = function (data, event) {
    if (event) {
      switch (data.IdTipoDocumento()) {
        case ID_TIPO_DOCUMENTO_FACTURA:
          self.data.FacturaVenta.OnEditar(data, event, self.PostGuardar);
          var options = self.data.FacturaVenta.Options;
          break;
        case ID_TIPO_DOCUMENTO_BOLETA:
          if (data.IndicadorBoletaViaje() == 1) {
            self.data.BoletaViajeVenta.OnEditar(data, event, self.PostGuardar);
            var options = self.data.BoletaViajeVenta.Options;
          } else {
            self.data.BoletaVenta.OnEditar(data, event, self.PostGuardar);
            var options = self.data.BoletaVenta.Options;
          }
          break;
        case ID_TIPO_DOCUMENTO_ORDEN_PEDIDO:
          self.data.OrdenPedido.OnEditar(data, event, self.PostGuardar);
          var options = self.data.OrdenPedido.Options;
          break;
        case ID_TIPO_DOCUMENTO_PROFORMA:
          self.data.Proforma.OnEditar(data, event, self.PostGuardar);
          var options = self.data.Proforma.Options;
          break;
        case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
          self.data.NotaCredito.OnEditar(data, event, self.PostGuardar);
          var options = self.data.NotaCredito.Options.NC;
          break;
        case String(ID_TIPO_DOCUMENTO_NOTADEVOLUCION):
          self.data.NotaCredito.OnEditar(data, event, self.PostGuardar);
          var options = self.data.NotaCredito.Options.NV;
          break;
        case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
          self.data.NotaDebito.OnEditar(data, event, self.PostGuardar);
          var options = self.data.NotaDebito.Options;
          break;
        default:
        //alertify.alert(self.titulo,"No se encontro pantalla para este tipo, consulte al administrador",function(){});
      }

      setTimeout(function () {
        $(options.IDModalComprobanteVenta).modal("show");//"#modalComprobanteVenta"
      }, 250);
    }
  }

  self.Eliminar = function (data, event) {
    if (event) {
      alertify.confirm("ELIMINAR REGISTRO", "¿Desea borrar realmente?", function () {
        var objeto = ko.mapping.toJS(data);
        objeto.Filtros = ko.mapping.toJS(self.data.Filtros);
        objeto.Filtros.TipoVenta = (objeto.Filtros.IdTipoVenta == undefined) ? "%" : objeto.Filtros.IdTipoVenta;
        objeto.Filtros.TipoDocumento = (objeto.Filtros.IdTipoDocumento == undefined) ? "%" : objeto.Filtros.IdTipoDocumento;
        if (objeto.IdComprobantePreVenta == "" || objeto.IdComprobantePreVenta == null) {
          if (objeto.IdTipoDocumento == ID_TIPO_DOCUMENTO_PROFORMA) {
            self.data.ComprobanteVenta.EliminarProforma(objeto, event, self.PostEliminar);
          } else {
            self.data.ComprobanteVenta.Eliminar(objeto, event, self.PostEliminar);
          }
        } else {
          self.data.ComprobanteVenta.EliminarComprobantePreVenta(objeto, event, self.PostEliminar);
        }
      }, function () { });
    }
  }

  self.OnClickBtnLiberarCasilleroGenero = function (data, event) {
    if (event) {
      alertify.confirm("LIBERAR CASILLERO", `¿Desea liberar el casillero ${data.NombreCasillero()} del genero ${data.NombreGenero()}?`, function () {
        self.data.ComprobanteVenta.LiberarCasillero(data, event, self.PostLiberarCasilleroGenero);
      }, function () { });
    }
  }

  self.PostLiberarCasilleroGenero = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesVenta(), item => item.IdComprobanteVenta() == data.IdComprobanteVenta);
        self.Seleccionar(objeto, event);
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.PostAnular = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesVenta(), function (item) { return item.IdComprobanteVenta() == data.IdComprobanteVenta; });
        objeto.IndicadorEstado(data.IndicadorEstado);
        objeto.AbreviaturaSituacionCPE(data.AbreviaturaSituacionCPE);
        self.Seleccionar(objeto, event);
      } else {
        alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function () { });
      }
    }
  }

  self.PostEliminar = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        var id = "#" + data.IdComprobanteVenta;
        var siguienteObjeto = $(id).next();
        if (siguienteObjeto.length == 0) siguienteObjeto = $(id).prev();
        siguienteObjeto.addClass('active').siblings().removeClass('active');
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesVenta(), function (item) { return item.IdComprobanteVenta() == data.IdComprobanteVenta; });
        self.data.ComprobantesVenta.remove(objeto);

        var filas = self.data.ComprobantesVenta().length;
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

  self.PostGuardar = function (data, event) {
    if (event) {
      //if (data.EstaProcesado()== true) {
      if (data) {
        data = ko.mapping.toJS(data);
        var objeto = ko.utils.arrayFirst(self.data.ComprobantesVenta(), function (item) { return item.IdComprobanteVenta() == data.IdComprobanteVenta; });
        var idtipo = data.IdTipoDocumento;

        switch (idtipo) {
          case ID_TIPO_DOCUMENTO_FACTURA:
            self.data.FacturaVenta.DetallesComprobanteVenta([]);
            var copia = ko.mapping.toJS(self.data.FacturaVenta, mappingIgnore);
            var options = self.data.FacturaVenta.Options;
            break;
          case ID_TIPO_DOCUMENTO_BOLETA:
            if (data.IndicadorBoletaViaje == 1) {
              self.data.BoletaViajeVenta.DetallesComprobanteVenta([]);
              var copia = ko.mapping.toJS(self.data.BoletaViajeVenta, mappingIgnore);
              var options = self.data.BoletaViajeVenta.Options;
            } else {
              self.data.BoletaVenta.DetallesComprobanteVenta([]);
              var copia = ko.mapping.toJS(self.data.BoletaVenta, mappingIgnore);
              var options = self.data.BoletaVenta.Options;
            }
            break;
          case ID_TIPO_DOCUMENTO_ORDEN_PEDIDO:
            self.data.OrdenPedido.DetallesComprobanteVenta([]);
            var copia = ko.mapping.toJS(self.data.OrdenPedido, mappingIgnore);
            var options = self.data.OrdenPedido.Options;
            break;
          case ID_TIPO_DOCUMENTO_PROFORMA:
            self.data.Proforma.DetallesComprobanteVenta([]);
            var copia = ko.mapping.toJS(self.data.Proforma, mappingIgnore);
            var options = self.data.Proforma.Options;
            break;
          case ID_TIPO_DOCUMENTO_NOTA_CREDITO:
            self.data.NotaCredito.Editar(data, event, self.PostGuardar);
            // self.data.NotaCredito.Editar(self.data.NotaCredito,event,self.PostGuardar);
            var copiaData = ko.mapping.toJS(self.data.NotaCredito, mappingIgnore);
            var copia = Object.assign(copiaData, ko.mapping.toJS(data, mappingIgnore));
            var options = self.data.NotaCredito.Options.NC;
            break;
          case String(ID_TIPO_DOCUMENTO_NOTADEVOLUCION):
            self.data.NotaCredito.Editar(data, event, self.PostGuardar);
            // self.data.NotaCredito.Editar(self.data.NotaCredito,event,self.PostGuardar);
            var copiaData = ko.mapping.toJS(self.data.NotaCredito, mappingIgnore);
            var copia = Object.assign(copiaData, ko.mapping.toJS(data, mappingIgnore));
            var options = self.data.NotaCredito.Options.NV;
            break;
          case ID_TIPO_DOCUMENTO_NOTA_DEBITO:
            self.data.NotaDebito.Editar(data, event, self.PostGuardar);
            // self.data.NotaDebito.Editar(self.data.NotaDebito,event,self.PostGuardar);
            var copiaData = ko.mapping.toJS(self.data.NotaDebito, mappingIgnore);
            var copia = Object.assign(copiaData, ko.mapping.toJS(data, mappingIgnore));
            var options = self.data.NotaDebito.Options;
            break;
          default:
          //alertify.alert(self.titulo,"No se encontro pantalla para este tipo, consulte al administrador",function(){});
        }

        var resultado = new VistaModeloComprobanteVenta(copia, options);
        
        if (event.target.id == "btn_Grabar") {
          self.data.ComprobantesVenta.replace(objeto, resultado);
        }

        self.Seleccionar(resultado, event);

        self.data.CasillerosPorGenero.Masculino([]);
        self.data.CasillerosPorGenero.Femenino([]);

        $(options.IDModalComprobanteVenta).modal("hide");
        $("#loader").hide();
      }
    }
    //}
  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      self.ListarComprobantesVentaPorPagina(data, event, self.PostConsultarPorPagina);
    }
  }

  self.PostConsultarPorPagina = function (data, event) {
    if (event) {
      var objeto = self.data.ComprobantesVenta()[0];
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
        self.ListarComprobantesVenta(data, event, self.PostConsultar);
      }
    }
  }

  self.PostConsultar = function (data, event) {
    if (event) {
      var objeto = self.data.ComprobantesVenta()[0];
      self.Seleccionar(objeto, event);
      $("#Paginador").paginador(data.Filtros, self.ConsultarPorPagina);
      self.data.Filtros.totalfilas(data.Filtros.totalfilas);
    }
  }

  
}
