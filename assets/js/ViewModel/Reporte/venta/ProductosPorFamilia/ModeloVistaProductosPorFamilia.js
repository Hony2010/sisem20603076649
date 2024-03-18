BuscadorModel_PF = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.ParseDataReporte = function (data, event) {
    if (event) {
      var $data = {
        FechaInicial: self.FechaInicio_PF(),
        FechaFinal: self.FechaFinal_PF(),
        HoraInicial: self.HoraInicio_PF(),
        HoraFinal: self.HoraFinal_PF(),
        NombreArchivoReporte: self.NombreArchivoReporte_PF(),
        NombreArchivoJasper: self.NombreArchivoJasper_PF(),
        IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede(),
        IdFamiliaProducto: self.IdFamiliaProducto() == undefined ? '%' : self.IdFamiliaProducto(),
      }
      return $data;
    }
  }

  self.DescargarReporte_PF = function (data, event) {
    if (event) {
      $("#loader").show();
      var btn = $(event.target).attr("name");
      var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
      var datajs = { "Data": self.ParseDataReporte(data, event) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cProductosPorFamilia/' + nombre,
        success: function (data) {
          $("#loader").hide();
          if (data.error == "") {
            window.location = data.url;
          }
          else {
            alertify.alert("Error en reporte detallado", data.error)
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("Ha ocurrido un error", jqXHR.responseText);
        }
      });
    }
  }

  self.Pantalla_PF = function (data, event) {
    if (event) {
      $("#loader").show();
      var datajs = { "Data": self.ParseDataReporte(data, event) };
      document.getElementById("contenedorpdf").innerHTML = "";
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cProductosPorFamilia/GenerarReportePANTALLA',
        success: function (data) {
          $("#loader").hide();
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_PF() + '.pdf"></iframe>';
            $('#modalReporteVistaPrevia').modal('show');
          }
        },
        error: function (jqXHR, textStatus, errorThrown) {
          $("#loader").hide();
          alertify.alert("Ha ocurrido un error", jqXHR.responseText);
        }
      });
    }
  }

  self.ValidarFecha = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }
}

var MappingProductosPorFamilia = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_PF(options.data);
    }
  }
}
