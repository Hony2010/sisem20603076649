VistaModeloBusquedaProforma = function (data) {
  var self = this;

  ko.mapping.fromJS(data, {}, self);

  ModeloBusquedaProforma.call(this, self);

  self.Inicializar = function (data, event, parent) {
    $("#PaginadorBusquedaProformas").paginador(ko.mapping.toJS(self.FiltrosBusquedaVentas), self.BuscarProformaPorPagina)
    self.Comprobante = parent;
    self.ComprobantesVentaProforma([]);
    $(".fecha").inputmask({ "mask": "99/99/9999", positionCaretOnTab: false });

    $("#ClienteProforma").autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, "#ClienteProforma");
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {
      var $evento = { target: `#ClienteProforma` };
      var $inputCliente = $($evento.target);

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda de cliente";
        var razonSocialCliente = "";
        var $data = { IdCliente: '' };
      } else {
        var memsajeError = "";
        var razonSocialCliente = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : `${data.NumeroDocumentoIdentidad} - ${data.RazonSocial}`;
        var $data = { RazonSocial: data.RazonSocial, NumeroDocumentoIdentidad: data.NumeroDocumentoIdentidad, IdCliente: data.IdPersona };
      }

      $inputCliente.attr("data-validation-error-msg", memsajeError);
      $inputCliente.attr("data-validation-text-found", razonSocialCliente);

      self.FiltrosBusquedaVentas.IdCliente($data.IdCliente)
      self.FiltrosBusquedaVentas.ClienteProforma(razonSocialCliente)

      self.ValidarClienteProforma(data, $evento);
    }
  }
  
  self.ValidarClienteProforma = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.FiltrosBusquedaVentas.IdCliente('');
        }
      });
    }
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }
  }

  self.OnKeyEnter = function (data, event) {
    if (event) {
      var resultado = $(event.target).enterToTab(event);
      return resultado;
    }
  }

  self.ObtenerFiltro = function (data, event) {
    if (event) {
      var filtro = ko.mapping.toJS(self.FiltrosBusquedaVentas);
      filtro.TextoFiltro = filtro.TextoFiltro == '' ? '%' : filtro.TextoFiltro;
      filtro.IdCliente = filtro.IdCliente == '' ? '%' : filtro.IdCliente;
      filtro.IdUsuarioVendedor = filtro.IdUsuarioVendedor || '%';
      return filtro;
    }
  }

  self.OnClickBtnBuscarProformas = function (data, event) {
    if (event) {
      var datajs = { Data: self.ObtenerFiltro(data, event) };
      $("#loader").show();
      self.ConsultarVentasProformas(datajs, event, self.PostBuscarProformaPorPagina)
    }
  }

  self.BuscarProformaPorPagina = function (data, event) {
    if (event) {
      var datajs = { Data: self.ObtenerFiltro(data, event) };
      $("#loader").show();
      self.ConsultarVentasProformasPorPagina(datajs, event, self.PostBuscarProformaPorPagina)
    }
  }

  self.PostBuscarProformaPorPagina = function (data, event) {
    if (event) {
      $("#loader").hide();
      if (!data.error) {
        self.ComprobantesVentaProforma([])
        ko.utils.arrayForEach(data.resultado, function (item) {
          item.CheckProformaSeleccionado = false;
          self.ComprobantesVentaProforma.push(Knockout.CopiarObjeto(item));
        });
        $("#PaginadorBusquedaProformas").paginador(data.Filtros, self.BuscarProformaPorPagina)
      } else {
        alertify.alert('ha ocurrido un error', data.error.msg, function () { });
      }
    }
  }

  self.OnClickAgregarProformas = function (data, event) {
    if (event) {
      var comprobantesSeleccionados = ko.utils.arrayFilter(self.ComprobantesVentaProforma(), item => item.CheckProformaSeleccionado())
      var detallesSeleccionados = [];
      var profomas = []
      var contador = 0
      ko.utils.arrayForEach(comprobantesSeleccionados, function (item) {
        $("#loader").show()
        self.Comprobante.ConsultarDetallesComprobante(item, event, function ($data) {
          contador++
          var obj = {
            IdComprobanteVenta: self.Comprobante.IdComprobanteVenta(),
            IdProforma: item.IdComprobanteVenta,
            Documento: item.Documento,
          }
          profomas.push(Knockout.CopiarObjeto(obj))
          detallesSeleccionados = [...detallesSeleccionados, ...$data];
          if (comprobantesSeleccionados.length == contador) {
            $("#loader").hide();
            self.AgregarProformasAComprobanteVenta(detallesSeleccionados, event);
            self.Comprobante.ProformasComprobanteVenta(profomas);
            $("#modalBuscadorProforma").modal("hide");
          }
        });
      })
    }
  }

  self.AgregarProformasAComprobanteVenta = function (data, event) {
    if (event) {
      self.Comprobante.DetallesComprobanteVenta([]);
      ko.utils.arrayForEach(data, function (item) {
        self.Comprobante.OnClickAgregarMercaderiaImagen(item, event);
      });
    }
  }
}
