VistaModeloReporteDocumentosPorCobrar = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.RazonSocial = ko.observable('');
  self.NumeroDocumentoIdentidad = ko.observable('');
  self.IdForm = "#FormReporteDocumentosPorCobrar";
  self.form = $(self.IdForm);

  self.InicializarVistaModelo = function (event) {
    var target = `${self.IdForm} #RazonSocialCliente_3`;
    $(target).autoCompletadoCliente(event, self.ValidarAutoCompletadoCliente, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target)
  }

  self.ValidarAutoCompletadoCliente = function (data, event) {
    if (event) {
      var $inputCliente = self.form.find("#RazonSocialCliente_3");
      var $evento = { target: `${self.IdForm} #RazonSocialCliente_3` };

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
        IdCliente: self.IndicadorRadioBtnCliente() == 0 ? '%' : self.IdCliente()
      };
      return { Data: datajs };
    }
  }

  self.DescargarReporteExcel = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/CuentaCobranza/cReporteDocumentosPorCobrar/GenerarReporteEXCEL';

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
      var url = SITE_URL + '/Reporte/CuentaCobranza/cReporteDocumentosPorCobrar/GenerarReportePDF';

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
      var url = SITE_URL + '/Reporte/CuentaCobranza/cReporteDocumentosPorCobrar/GenerarReportePANTALLA';

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
}

var MappingReporteDocumentosPorCobrar = {
  'Filtro': {
    create: function (options) {
      if (options)
        return new VistaModeloReporteDocumentosPorCobrar(options.data);
    }
  }
}

IndexReporteDocumentosPorCobrar = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteDocumentosPorCobrar, self);

  self.Inicializar = function () {
    vistaModeloReporte.ReporteDocumentosPorCobrar.Filtro.InicializarVistaModelo(window);
  }
}
