VistaModeloReporteRemuneracionEmpleadosMetaMensual = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteRemuneracionEmpleadosMetaMensual, self);

  self.IdForm = "#FormReporteRemuneracionEmpleadosMetaMensual";
  self.form = $(self.IdForm);

  self.ValidarFecha = function (data, event) {
    if (event) {
      $(event.target).validate(function (valid, elem) {
      });
    }
  }

  self.ParseDataReporte = function (data, event) {
    if (event) {
      var datajs = {
        Mes: self.form.find("#Mes :selected").val(),
        Anio: self.Anio(),
        NombreArchivoJasper: self.NombreArchivoJasper(),
        NombreArchivoReporte: self.NombreArchivoReporte(),
        VendedoresSeleccionados: self.VendedoresSeleccionados(),
        IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      };
      return { Data: datajs };
    }
  }

  self.DescargarReporteExcel = function (data, event) {
    if (event) {
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/Venta/cReporteRemuneracionEmpleadosMetaMensual/GenerarReporteEXCEL';

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
      var url = SITE_URL + '/Reporte/Venta/cReporteRemuneracionEmpleadosMetaMensual/GenerarReportePDF';

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
      var url = SITE_URL + '/Reporte/Venta/cReporteRemuneracionEmpleadosMetaMensual/GenerarReportePANTALLA';

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

  self.SeleccionarTodosVendedores = function (data, event) {
    if (event) {
      var selectorTodos = $(event.target).prop('checked');
      var usuarios = ko.mapping.toJS(self.Vendedores);
      var usuariosSeleccionados = [];
      usuarios.forEach(function (value, key) {
        self.form.find('#' + value.IdUsuario + '_VendedorMetaMesual').prop('checked', selectorTodos);
        usuariosSeleccionados.push(value.AliasUsuarioVenta);
      })
      self.NumeroVendedoresSeleccionados(selectorTodos ? usuarios.length : 0);
      self.VendedoresSeleccionados(selectorTodos ? usuariosSeleccionados : []);
    }
  }

  self.SeleccionarVendedor = function (data, event) {
    if (event) {
      var selectorIndividual = $(event.target).prop('checked');
      var usuariosSeleccionados = ko.mapping.toJS(self.VendedoresSeleccionados);
      self.NumeroVendedoresSeleccionados(selectorIndividual ? self.NumeroVendedoresSeleccionados() + 1 : self.NumeroVendedoresSeleccionados() - 1);

      if (self.NumeroVendedoresSeleccionados() == self.TotalVendedores()) {
        self.form.find('#SelectorVendedoresMetaMesual').prop('checked', true);
      } else {
        self.form.find('#SelectorVendedoresMetaMesual').prop('checked', false);
      }

      if (selectorIndividual) {
        usuariosSeleccionados.push(data.AliasUsuarioVenta());
      } else {
        usuariosSeleccionados = usuariosSeleccionados.filter(function (value) {
          return value != data.AliasUsuarioVenta();
        });
      }
      self.VendedoresSeleccionados(usuariosSeleccionados);
    }
  }


}

var MappingReporteRemuneracionEmpleadosMetaMensual = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new VistaModeloReporteRemuneracionEmpleadosMetaMensual(options.data);
    }
  }
}

IndexReporteRemuneracionEmpleadosMetaMensual = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingReporteRemuneracionEmpleadosMetaMensual, self);

  self.Inicializar = function () {
    vistaModeloReporte.ReporteRemuneracionEmpleadosMetaMensual.Filtro.InicializarVistaModelo(window);
  }
}
