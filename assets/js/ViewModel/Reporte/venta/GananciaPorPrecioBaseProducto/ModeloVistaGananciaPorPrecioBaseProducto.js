BuscadorModel_GananciaPorPrecioBaseProducto = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.GrupoCliente_GananciaPorPrecioBaseProducto = function (data, event) {
    if (event) {
      if (data.IdProducto_GananciaPorPrecioBaseProducto() == '1') {
        $('#DivBuscar_GananciaPorPrecioBaseProducto').hide();
        $('#TextoBuscar_GananciaPorPrecioBaseProducto').attr('type', 'text');
        $('#TextoBuscar_GananciaPorPrecioBaseProducto').focus();

      }
      else {
        $('#DivBuscar_GananciaPorPrecioBaseProducto').show();
        $('#TextoBuscar_GananciaPorPrecioBaseProducto').attr('type', 'hidden');
        $('#TextoBuscar_GananciaPorPrecioBaseProducto').val('');

      }
    }
  }

  self.dataReporte = function (data, event) {
    if (event) {
      var $data = {};
      $data.IdAsignacionSede = $("#Alamacen_GananciaPorPrecioBaseProducto").val() == "" ? "%" : $("#Alamacen_GananciaPorPrecioBaseProducto").val();
      $data.IdMarca = $("#Marcas_Gananciaporproducto").val() == "" ? "%" : $("#Marcas_Gananciaporproducto").val();
      $data.FechaInicial = data.FechaInicio_GananciaPorPrecioBaseProducto();
      $data.FechaFinal = data.FechaFinal_GananciaPorPrecioBaseProducto();
      $data.IdProducto = data.IdProducto_GananciaPorPrecioBaseProducto() == 0 ? "%" : data.TextoMercaderia_GananciaPorPrecioBaseProducto();
      $data.NombreArchivoJasper = data.NombreArchivoJasper_GananciaPorPrecioBaseProducto();
      $data.NombreArchivoReporte = data.NombreArchivoReporte_GananciaPorPrecioBaseProducto();
      $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      if ($(event.target).attr("name") == 'pantalla') {
        self.Pantalla_GananciaPorPrecioBaseProducto($data, event);
      } else {
        self.DescargarReporte_GananciaPorPrecioBaseProducto($data, event);
      }
    }
  }

  self.DescargarReporte_GananciaPorPrecioBaseProducto = function (data, event) {
    if (event) {
      $("#loader").show();
      var btn = $(event.target).attr("name");
      var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
      var datajs = { "Data": data };

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cGananciaPorPrecioBaseProducto/' + nombre,
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

  self.Pantalla_GananciaPorPrecioBaseProducto = function (data, event) {
    if (event) {
      $("#loader").show();
      var objeto = data;
      var datajs = { "Data": data };
      document.getElementById("contenedorpdf").innerHTML = "";
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cGananciaPorPrecioBaseProducto/GenerarReportePANTALLA',
        success: function (data) {
          $("#loader").hide();
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_GananciaPorPrecioBaseProducto() + '.pdf"></iframe>';
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

var MappingGananciaPorPrecioBaseProducto = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_GananciaPorPrecioBaseProducto(options.data);
    }
  }
}
