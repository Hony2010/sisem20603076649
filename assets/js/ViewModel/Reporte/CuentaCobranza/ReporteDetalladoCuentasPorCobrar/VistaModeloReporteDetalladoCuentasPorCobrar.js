VistaModeloReporteDetalladoCuentasPorCobrar = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.RazonSocial = ko.observable('');
  self.NumeroDocumentoIdentidad = ko.observable('');
  self.IdForm = "#FormReporteDetalladoCuentasPorCobrar";
  self.form = $(self.IdForm);

  self.InicializarVistaModelo = function (event) {
    var target = `${self.IdForm} #RazonSocialCliente_1`;
    $(target).autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target)
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {
      var $inputCliente = self.form.find("#RazonSocialCliente_1");
      var $evento = { target: `${self.IdForm} #RazonSocialCliente_1` };

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

      ko.mapping.fromJS($data, {}, self);
      self.ValidarCliente(data, $evento);
    }
  }

  self.ValidarCliente = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdCliente('');
        }
      });
    }
  }

  self.RazonSocialCliente = ko.computed(function () {
    var nombre = self.NumeroDocumentoIdentidad() == '' ? self.RazonSocial() : `${self.NumeroDocumentoIdentidad()} - ${self.RazonSocial()}`;
    return nombre;
  }, this)

  self.ValidarFecha = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ParseDataReporte = function (data, event) {
    if (event) {
      var datajs = {
        FechaInicio: self.FechaInicio(),
        FechaFinal: self.FechaFinal(),
        NombreArchivoJasper: self.NombreArchivoJasper(),
        NombreArchivoReporte: self.NombreArchivoReporte(),
        IdCliente: self.IndicadorRadioBtnCliente() == 0 ? '%' : self.IdCliente(),
        VendedoresSeleccionados: self.VendedoresSeleccionados(),
      };
      return { Data: datajs };
    }
  }

  self.DescargarReporteExcel = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/CuentaCobranza/cReporteDetalladoCuentasPorCobrar/GenerarReporteEXCEL';

      $("#loader").show();
      self.GenerarReporte(datajs, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error.msg) {
          window.location = $data.url;
        } else {
          alertify.alert("Ha ocurrido un error", $data.error.msg, function () { });
        }
      }, url);
    }
  }

  self.DescargarReportePdf = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/CuentaCobranza/cReporteDetalladoCuentasPorCobrar/GenerarReportePDF';

      $("#loader").show();
      self.GenerarReporte(datajs, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data.error.msg) {
          window.location = $data.url;
        } else {
          alertify.alert("Ha ocurrido un error", $data.error.msg, function () { });
        }
      }, url);
    }
  }

  self.MostrarReportePantalla = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/CuentaCobranza/cReporteDetalladoCuentasPorCobrar/GenerarReportePANTALLA';

      $("#loader").show();
      self.GenerarReporte(datajs, event, function ($data, $event) {
        $("#loader").hide();
        if (!$data) {
          document.getElementById("contenedorpdf").innerHTML = "";
          document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte() + '.pdf"></iframe>';
          $('#modalReporteVistaPrevia').modal('show');
        } else {
          alertify.alert("Ha ocurrido un error", $data.error.msg, function () { });
        }
      }, url);
    }
  }

  self.GenerarReporte = function (data, event, callback, url) {
    if (event) {
      $.ajax({
        type: 'POST',
        data: data,
        dataType: "json",
        url: url,
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var data = { error: { msg: jqXHR.responseText } };
          callback(data, event);
        }
      });
    }
  }

  self.SeleccionarTodosVendedores = function (data, event) {
    if (event) {
      var selectorTodos = $(event.target).prop('checked');
      var usuarios = ko.mapping.toJS(self.Vendedores);
      var usuariosSeleccionados = [];
      usuarios.forEach(function (value, key) {
        $('#' + value.IdUsuario + '_Vendedor_dcpc').prop('checked', selectorTodos);
        usuariosSeleccionados.push(value.AliasUsuarioVenta);
      })
      self.NumeroVendedoresSeleccionados(selectorTodos ? usuarios.length : 0);
      self.VendedoresSeleccionados(selectorTodos ? usuariosSeleccionados : []);
    }
  }

  self.SeleccionarVendedor = function (data, event) {
    if (event) {
      var selectorIndividual = $(event.target).prop('checked');
      var usuariosSeleccionados = ko.mapping.toJS(self.VendedoresSeleccionados);
      self.NumeroVendedoresSeleccionados(selectorIndividual ? self.NumeroVendedoresSeleccionados() + 1 : self.NumeroVendedoresSeleccionados() - 1);

      if (self.NumeroVendedoresSeleccionados() == self.TotalVendedores()) {
        $('#SelectorVendedores_dcpc').prop('checked', true);
      } else {
        $('#SelectorVendedores_dcpc').prop('checked', false);
      }

      if (selectorIndividual) {
        usuariosSeleccionados.push(data.AliasUsuarioVenta());
      } else {
        usuariosSeleccionados = usuariosSeleccionados.filter(function (value) {
          return value != data.AliasUsuarioVenta();
        });
      }
      self.VendedoresSeleccionados(usuariosSeleccionados);
    }
  }

}

var MappingReporteDetalladoCuentasPorCobrar = {
  'Filtro': {
    create: function (options) {
      if (options)
        return new VistaModeloReporteDetalladoCuentasPorCobrar(options.data);
    }
  }
}

IndexReporteDetalladoCuentasPorCobrar = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteDetalladoCuentasPorCobrar, self);

  self.Inicializar = function () {
    vistaModeloReporte.ReporteDetalladoCuentasPorCobrar.Filtro.InicializarVistaModelo(window);
  }
}
