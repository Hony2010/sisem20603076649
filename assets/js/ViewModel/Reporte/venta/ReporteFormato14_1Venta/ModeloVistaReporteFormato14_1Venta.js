BuscadorModel_Formato14 = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.GrupoCliente_Formato14 = function (data, event) {
    if (event) {
      if (data.IdPersona_Formato14() == '1') {
        $('#DivBuscar_Formato14').hide();
        $('#TextoBuscar_Formato14').attr('type', 'text');
        $('#TextoBuscar_Formato14').focus();

      }
      else {
        $('#DivBuscar_Formato14').show();
        $('#TextoBuscar_Formato14').attr('type', 'hidden');
        $('#TextoBuscar_Formato14').val('');

      }
    }
  }

  self.OnClickBtnReportes = function (data, event) {
    if (event) {
      var $data = {};
      $data.FechaInicial = self.FechaInicio_Formato14();
      $data.FechaFinal = self.FechaFinal_Formato14();
      $data.NombreArchivoJasper = self.NombreArchivoJasper_Formato14();
      $data.NombreArchivoReporte = self.NombreArchivoReporte_Formato14();
      $data.TiposDocumento = self.TiposDocumento();
      $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()

      if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
      else self.DescargarReporte($data, event);
    }
  }

  self.DescargarReporte = function (data, event) {
    if (event) {
      $("#loader").show();
      var btn = $(event.target).attr("name");
      var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
      var datajs = ko.toJS({ "Data": data });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cReporteFormato14_1Venta/' + nombre,
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

  self.MostrarReporte = function (data, event) {
    if (event) {
      $("#loader").show();
      var datajs = ko.toJS({ "Data": data });
      document.getElementById("contenedorpdf").innerHTML = "";
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cReporteFormato14_1Venta/GenerarReportePANTALLA',
        success: function (data) {
          $("#loader").hide();
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_Formato14() + '.pdf"></iframe>';
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

  self.SeleccionarTodosComprobantes = function (data, event) {
    if (event) {
      var selectorTodos = $(event.target).prop('checked');
      var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
      var tipoDocumentos = [];
      var total = tipoDocumentosVenta.length;
      tipoDocumentosVenta.forEach(function (value, key) {
        $('#' + value.IdTipoDocumento + '_TipoDocumento_14').prop('checked', selectorTodos);
        tipoDocumentos.push(value.IdTipoDocumento);
      })
      self.NumeroDocumentosSeleccionados(selectorTodos ?  total : 0);
      self.TiposDocumento(selectorTodos ? tipoDocumentos : []);
    }
  }

  self.SeleccionarComprobante = function (data, event) {
    if (event) {
      var selectorIndividual = $(event.target).prop('checked');
      var tipoDocumentos = ko.mapping.toJS(self.TiposDocumento);
      self.NumeroDocumentosSeleccionados(selectorIndividual ? self.NumeroDocumentosSeleccionados() + 1 : self.NumeroDocumentosSeleccionados() - 1);

      if (self.NumeroDocumentosSeleccionados() == self.TotalTipoDocumentos()) {
        $('#SelectorTipoDocumentos_14').prop('checked', true);
      } else {
        $('#SelectorTipoDocumentos_14').prop('checked', false);
      }

      if (selectorIndividual) {
        tipoDocumentos.push(data.IdTipoDocumento());
      } else {
        tipoDocumentos = tipoDocumentos.filter(function (value) {
          return value != data.IdTipoDocumento();
        });
      }
      self.TiposDocumento(tipoDocumentos);
    }
  }
}

var MappingReporteFormato14_1Venta = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_Formato14(options.data);
    }
  }
}
