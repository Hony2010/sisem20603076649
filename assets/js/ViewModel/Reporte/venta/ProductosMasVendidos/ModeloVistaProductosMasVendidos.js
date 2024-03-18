BuscadorModel_MAS = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.ParseData = function (data, event) {
    if (event) {
      var data = {
        FechaInicio_MAS : self.FechaInicio_MAS(),
        FechaFinal_MAS : self.FechaFinal_MAS(),
        CantidadFilas_MAS: self.CantidadFilas_MAS(),
        NombreArchivoReporte_MAS: self.NombreArchivoReporte_MAS(),
        NombreArchivoJasper_MAS: self.NombreArchivoJasper_MAS(),
        OrdenadoPor: self.OrdenadoPor() == 1 ? 4 : 5, // MayorCantidad : MayorMonto
        IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      }

      return data;
    }
  }

  self.DescargarReporte_MAS = function (data, event) {
    if (event) {
      $("#loader").show();
      var btn = $(event.target).attr("name");
      var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
      var datajs = ko.toJS({ "Data": self.ParseData(data, event) });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cProductosMasVendidos/' + nombre,
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

  self.Pantalla_MAS = function (data, event) {
    if (event) {
      $("#loader").show();
      var objeto = data;
      var datajs = ko.toJS({ "Data": self.ParseData(data, event) });
      document.getElementById("contenedorpdf").innerHTML = "";
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cProductosMasVendidos/GenerarReportePANTALLA',
        success: function (data) {
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_MAS() + '.pdf"></iframe>';
            $('#modalReporteVistaPrevia').modal('show');
          }
          $("#loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          alertify.alert("Ha ocurrido un error", jqXHR.responseText);
          $("#loader").hide();
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

var MappingProductoMasVendido = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_MAS(options.data);
    }
  }
}
