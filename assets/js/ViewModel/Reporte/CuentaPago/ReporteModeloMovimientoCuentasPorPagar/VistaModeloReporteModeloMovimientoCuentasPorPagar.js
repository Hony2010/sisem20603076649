VistaModeloReporteModeloMovimientoCuentasPorPagar = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.RazonSocial = ko.observable('');
  self.NumeroDocumentoIdentidad = ko.observable('');
  self.IdForm = "#FormReporteModeloMovimientoCuentasPorPagar";
  self.form = $(self.IdForm);

  self.InicializarVistaModelo = function (event) {
    var target = `${self.IdForm} #RazonSocialProveedor_4`;
    $(target).autoCompletadoProveedor(event, self.ValidarAutoCompletadoProveedor, CODIGO_TIPO_DOCUMENTO_IDENTIDAD.TODOS, target)
  }

  self.ValidarAutoCompletadoProveedor = function (data, event) {
    if (event) {
      var $inputProveedor = self.form.find("#RazonSocialProveedor_4");
      var $evento = { target: `${self.IdForm} #RazonSocialProveedor_4` };

      if (data === -1) {
        var memsajeError = "No se han encontrado resultados para tu búsqueda de cliente";
        var razonSocialProveedor = "";
        var $data = { IdProveedor: '' };
      } else {
        var memsajeError = "";
        var razonSocialProveedor = data.NumeroDocumentoIdentidad == "" ? data.RazonSocial : `${data.NumeroDocumentoIdentidad} - ${data.RazonSocial}`;
        var $data = { RazonSocial: data.RazonSocial, NumeroDocumentoIdentidad: data.NumeroDocumentoIdentidad, IdProveedor: data.IdPersona };
      }

      $inputProveedor.attr("data-validation-error-msg", memsajeError);
      $inputProveedor.attr("data-validation-text-found", razonSocialProveedor);

      ko.mapping.fromJS($data, {}, self);
      self.ValidarProveedor(data, $evento);
    }
  }

  self.ValidarProveedor = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
        if (!valid) {
          self.IdProveedor('');
        }
      });
    }
  }

  self.RazonSocialProveedor = ko.computed(function () {
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
        IdProveedor: self.IndicadorRadioBtnProveedor() == 0 ? '%' : self.IdProveedor()
      };
      return { Data: datajs };
    }
  }

  self.DescargarReporteExcel = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/CuentaPago/cReporteModeloMovimientoCuentasPorPagar/GenerarReporteEXCEL';

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
      var url = SITE_URL + '/Reporte/CuentaPago/cReporteModeloMovimientoCuentasPorPagar/GenerarReportePDF';

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
      var url = SITE_URL + '/Reporte/CuentaPago/cReporteModeloMovimientoCuentasPorPagar/GenerarReportePANTALLA';

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

var MappingReporteModeloMovimientoCuentasPorPagar = {
  'Filtro': {
    create: function (options) {
      if (options)
        return new VistaModeloReporteModeloMovimientoCuentasPorPagar(options.data);
    }
  }
}

IndexReporteModeloMovimientoCuentasPorPagar = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteModeloMovimientoCuentasPorPagar, self);

  self.Inicializar = function () {
    vistaModeloReporte.ReporteModeloMovimientoCuentasPorPagar.Filtro.InicializarVistaModelo(window);
  }
}
