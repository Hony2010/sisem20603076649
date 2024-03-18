BuscadorModel_D = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.GrupoCliente_D = function (data, event) {
    if (event) {
      if (data.NumeroDocumentoIdentidad_D() == '1') {
        $('#DivBuscar_D').hide();
        $('#TextoBuscar_D').attr('type', 'text');
        $('#TextoBuscar_D').focus();

      }
      else {
        $('#DivBuscar_D').show();
        $('#TextoBuscar_D').attr('type', 'hidden');
        $('#TextoBuscar_D').val('');

      }
    }
  }
  self.OrdenReporte = function () {
    if (self.Orden_D() == "0") {
      return "FechaEmision";
    } else if (self.Orden_D() == "1") {
      return "RazonSocial";
    } else {
      return "Documento";
    }
  };

  self.FormaPagoReporte = function () {
    if (self.FormaPago_D() == "0") {
      return "%";
    } else if (self.FormaPago_D() == "1") {
      return "CONTADO";
    } else if (self.FormaPago_D() == "2") {
      return "CREDITO";
    } else {
      return "%";
    }
  };

  self.OnClickBtnReportes = function (data, event) {
    if (event) {
      var $data = {
        FechaInicio: self.FechaInicio_D(),
        FechaFinal: self.FechaFinal_D(),
        IdPersona: self.NumeroDocumentoIdentidad_D() == "0" ? "%" : self.IdPersona(),
        Orden: self.OrdenReporte(),
        FormaPago: self.FormaPagoReporte(),
        UsuarioRegistro: self.OpcionVistaVenta_D() == "0" ? self.IdUsuario_D() : "%",
        NombreArchivoReporte: self.NombreArchivoReporte_D(),
        NombreArchivoJasper: self.NombreArchivoJasper_D(),
        TiposDocumento: self.TiposDocumento(),
        IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      };

      var datajs = { "Data": $data };

      if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(datajs, event);
      else self.DescargarReporte(datajs, event);
    }
  }

  self.SeleccionarTodosComprobantes = function (data, event) {
    if (event) {
      var selectorTodos = $(event.target).prop('checked');
      var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
      var tipoDocumentos = [];
      tipoDocumentosVenta.forEach(function (value, key) {
        $('#' + value.IdTipoDocumento + '_TipoDocumento_D').prop('checked', selectorTodos);
        tipoDocumentos.push(value.IdTipoDocumento);
      })
      var total = tipoDocumentosVenta.length;
      self.NumeroDocumentosSeleccionados(selectorTodos ? total : 0);
      self.TiposDocumento(selectorTodos ? tipoDocumentos : []);
    }
  }

  self.SeleccionarComprobante = function (data, event) {
    if (event) {
      var selectorIndividual = $(event.target).prop('checked');
      var tipoDocumentos = ko.mapping.toJS(self.TiposDocumento);
      self.NumeroDocumentosSeleccionados(selectorIndividual ? self.NumeroDocumentosSeleccionados() + 1 : self.NumeroDocumentosSeleccionados() - 1);

      if (self.NumeroDocumentosSeleccionados() == self.TotalTipoDocumentos()) {
        $('#SelectorTipoDocumentos_D').prop('checked', true);
      } else {
        $('#SelectorTipoDocumentos_D').prop('checked', false);
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

  self.DescargarReporte = function (datajs, event) {
    if (event) {
      $("#loader").show();
      var btn = $(event.target).attr("name");
      var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cVentaDetallado/' + nombre,
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


  self.MostrarReporte = function (datajs, event) {
    if (event) {
      $("#loader").show();
      document.getElementById("contenedorpdf").innerHTML = "";
      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Reporte/Venta/cVentaDetallado/GenerarReportePANTALLA',
        success: function (data) {
          $("#loader").hide();
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_D() + '.pdf"></iframe>';
            $('#modalReporteVistaPrevia').modal('show');
          }
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

var MappingVentaDetallado = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_D(options.data);
    }
  }
}
