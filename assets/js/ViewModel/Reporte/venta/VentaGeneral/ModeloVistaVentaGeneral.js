BuscadorModel_R = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.GrupoCliente_R = function (data, event) {
    if (event) {
      if (data.NumeroDocumentoIdentidad_R() == '1') {
        $('#DivBuscar_R').hide();
        $('#TextoBuscar_R').attr('type', 'text');
        $('#TextoBuscar_R').focus();

      }
      else {
        $('#DivBuscar_R').show();
        $('#TextoBuscar_R').attr('type', 'hidden');
        $('#TextoBuscar_R').val('');

      }
    }
  }

  self.OnClickBtnReportes = function (data, event) {
    if (event) {
      var $data = {
        FechaInicial: self.FechaInicio_R(),
        FechaFinal: self.FechaFinal_R(),
        HoraInicio: self.HoraInicio_R(),
        HoraFinal: self.HoraFinal_R(),
        IdPersona: self.NumeroDocumentoIdentidad_R() == "0" ? "%" : self.IdPersona(),
        NombreArchivoReporte: self.NombreArchivoReporte_R(),
        NombreArchivoJasper: self.NombreArchivoJasper_R(),
        UsuarioRegistro: self.OpcionVistaVenta_R() == "0" ? self.IdUsuario_R() : "%",
        TiposDocumento: self.TiposDocumento(),
        Placa: self.Placa ? self.Placa() : '',
        RadioTaxi: self.RadioTaxi ? self.RadioTaxi() : '',
        SerieDocumento: self.SerieDocumento() == '' ? '%' : self.SerieDocumento(),
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
        $('#' + value.IdTipoDocumento + '_TipoDocumento_R').prop('checked', selectorTodos);
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
        $('#SelectorTipoDocumentos_R').prop('checked', true);
      } else {
        $('#SelectorTipoDocumentos_R').prop('checked', false);
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

  self.SeleccionarTodosComprobantesLubricante = function (data, event) {
    if (event) {
      var selectorTodos = $(event.target).prop('checked');
      var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
      var tipoDocumentos = [];
      tipoDocumentosVenta.forEach(function (value, key) {
        $('#' + value.IdTipoDocumento + '_TipoDocumento_RL').prop('checked', selectorTodos);
        tipoDocumentos.push(value.IdTipoDocumento);
      })
      var total = tipoDocumentosVenta.length;
      self.NumeroDocumentosSeleccionados(selectorTodos ? total : 0);
      self.TiposDocumento(selectorTodos ? tipoDocumentos : []);
    }
  }

  self.SeleccionarComprobanteLubricante = function (data, event) {
    if (event) {
      var selectorIndividual = $(event.target).prop('checked');
      var tipoDocumentos = ko.mapping.toJS(self.TiposDocumento);
      self.NumeroDocumentosSeleccionados(selectorIndividual ? self.NumeroDocumentosSeleccionados() + 1 : self.NumeroDocumentosSeleccionados() - 1);

      if (self.NumeroDocumentosSeleccionados() == self.TotalTipoDocumentos()) {
        $('#SelectorTipoDocumentos_RL').prop('checked', true);
      } else {
        $('#SelectorTipoDocumentos_RL').prop('checked', false);
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
        url: SITE_URL + '/Reporte/Venta/cVentaGeneral/' + nombre,
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
        url: SITE_URL + '/Reporte/Venta/cVentaGeneral/GenerarReportePANTALLA',
        success: function (data) {
          if (data != "") {
            document.getElementById("contenedorpdf").innerHTML = '<iframe class="embed-responsive-item" src="' + SERVER_URL + 'assets/reportes/' + self.NombreArchivoReporte_R() + '.pdf"></iframe>';
            $('#modalReporteVistaPrevia').modal('show');
          }
          $("#loader").hide();
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

var MappingVentaGeneral = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new BuscadorModel_R(options.data);
    }
  }
}
