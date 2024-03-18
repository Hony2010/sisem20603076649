VistaModeloReporteListaPrecios = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteListaPrecios, self);

  self.IdForm = "#FormReporteListaPrecios";
  self.form = $(self.IdForm);

  self.DataSubFamilias = ko.observable([]);
  self.DataModelos = ko.observable([]);

  self.InicializarVistaModelo = function (event) {
    self.ObtenerSubFamiliasDesdeFamilia(self, event);
    self.ObtenerModelosDesdeMarca(self, event);
  }

  self.ValidarFecha = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ParseDataReporte = function (data, event) {
    if (event) {
      var datajs = {
        TextoFiltro: self.TextoFiltro() == '' ? '%' : self.TextoFiltro(),
        IdSede: self.IdSede(),
        IdFamilia: self.IdFamilia() == undefined ? '%' : self.IdFamilia(),
        IdSubFamilia: self.IdSubFamilia() == undefined ? '%' : self.IdSubFamilia(),
        IdMarca: self.IdMarca() == undefined ? '%' : self.IdSubFamilia(),
        IdModelo: self.IdModelo() == undefined ? '%' : self.IdModelo(),
        IdLineaProducto: self.IdLineaProducto() == undefined ? '%' : self.IdLineaProducto(),
        NombreArchivoJasper: self.NombreArchivoJasper() == undefined ? '%' : self.NombreArchivoJasper(),
        NombreArchivoReporte: self.NombreArchivoReporte() == undefined ? '%' : self.NombreArchivoReporte(),
        IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      };
      return { Data: datajs };
    }
  }

  self.DescargarReporteExcel = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/Venta/cReporteListaPrecios/GenerarReporteEXCEL';

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
      var url = SITE_URL + '/Reporte/Venta/cReporteListaPrecios/GenerarReportePDF';

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
      var url = SITE_URL + '/Reporte/Venta/cReporteListaPrecios/GenerarReportePANTALLA';

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

  self.ObtenerSubFamiliasDesdeFamilia = function (data, event) {
    if (event) {
      var subFamilias = ko.utils.arrayFilter(self.SubFamilias(), function (item) {
        return item.IdFamiliaProducto() == self.IdFamilia();
      });
      self.DataSubFamilias(subFamilias);
    }
  }

  self.ObtenerModelosDesdeMarca = function (data, event) {
    if (event) {
      var modelos = ko.utils.arrayFilter(self.Modelos(), function (item) {
        return item.IdMarca() == self.IdMarca();
      });
      self.DataModelos(modelos);
    }
  }

}

var MappingReporteListaPrecios = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new VistaModeloReporteListaPrecios(options.data);
    }
  }
}

IndexReporteListaPrecios = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteListaPrecios, self);

  self.Inicializar = function () {
    vistaModeloReporte.vmgReporteListaPrecios.Buscador.InicializarVistaModelo(window);
  }
}
