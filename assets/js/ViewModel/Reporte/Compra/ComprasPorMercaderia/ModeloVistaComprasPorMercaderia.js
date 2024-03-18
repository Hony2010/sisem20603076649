BuscadorModel_Mercaderia = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.GrupoCliente_Mercaderia = function (data, event) {
    if (event) {
      if (data.IdProducto_Mercaderia() == '1') {
        $('#DivBuscar_Mercaderia').hide();
        $('#TextoBuscar_Mercaderia').show();
        $('#TextoBuscar_Mercaderia').focus();

      }
      else {
        $('#DivBuscar_Mercaderia').show();
        $('#TextoBuscar_Mercaderia').hide();
        $('#TextoBuscar_Mercaderia').val('');

      }
    }
  }

  self.DescargarReporte_Mercaderia = function (data, event) {
    if (event) {
      $("#loader").show();
      var btn = $(event.target).attr("name");
      var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
      var datajs = { Data: ko.mapping.toJS(data) };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Compra/cComprasPorMercaderia/' + nombre,
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

  self.Pantalla_Mercaderia = function (data, event) {
    if (event) {
      $("#loader").show();
      var datajs = { Data: ko.mapping.toJS(data) };
      document.getElementById("contenedorpdf").innerHTML = "";
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Compra/cComprasPorMercaderia/GenerarReportePANTALLA',
        success: function (data) {
          $("#loader").hide();
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_Mercaderia() + '.pdf"></iframe>';
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

var MappingComprasPorMercaderia = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_Mercaderia(options.data);
    }
  }
}
