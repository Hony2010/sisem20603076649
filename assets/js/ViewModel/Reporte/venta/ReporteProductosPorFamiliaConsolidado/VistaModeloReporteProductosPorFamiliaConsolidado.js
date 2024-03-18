VistaModeloReporteProductosPorFamiliaConsolidado = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.IdForm = "#FormReporteProductosPorFamiliaConsolidado";
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
        FechaFinal: self.FechaFinal(),
        FechaInicio: self.FechaInicio(),
        HoraFinal: self.HoraFinal(),
        HoraInicio: self.HoraInicio(),
        NombreArchivoJasper: self.NombreArchivoJasper(),
        NombreArchivoReporte: self.NombreArchivoReporte(),
        Vendedores: self.UsuariosSeleccionados(),
        IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      };
      return { Data: datajs };
    }
  }

  self.DescargarReporteExcel = function (data, event) {
    if (event) {
      if (self.UsuariosSeleccionados().length == 0) {
        alertify.alert("Productos por Familia", 'Debe de seleccionar uno o mas Usuarios', function () { });
        return false
      }

      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/Venta/cProductosPorFamiliaConsolidado/GenerarReporteEXCEL';

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

      if (self.UsuariosSeleccionados().length == 0) {
        alertify.alert("Productos por Familia", 'Debe de seleccionar uno o mas Usuarios', function () { });
        return false
      }

      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/Venta/cProductosPorFamiliaConsolidado/GenerarReportePDF';

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

      if (self.UsuariosSeleccionados().length == 0) {
        alertify.alert("Productos por Familia", 'Debe de seleccionar uno o mas Usuarios', function () { });
        return false
      }
      
      var datajs = self.ParseDataReporte(data, event);
      var url = SITE_URL + '/Reporte/Venta/cProductosPorFamiliaConsolidado/GenerarReportePANTALLA';

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

  self.SeleccionarTodosUsuarios = function (data, event) {
    if (event) {
      var selectorTodos = $(event.target).prop('checked');
      var vendedores = ko.mapping.toJS(self.Usuarios);
      var vendedoresSeleccionados = [];
      vendedores.forEach(function (value, key) {
        self.form.find('#' + value.IdUsuario + '_Usuario').prop('checked', selectorTodos);
        vendedoresSeleccionados.push(value.AliasUsuarioVenta);
      })
      self.NumeroUsuariosSeleccionados(selectorTodos ? vendedores.length : 0);
      self.UsuariosSeleccionados(selectorTodos ? vendedoresSeleccionados : []);
    }
  }

  self.SeleccionarUsuario = function (data, event) {
    if (event) {
      var selectorIndividual = $(event.target).prop('checked');
      var vendedoresSeleccionados = ko.mapping.toJS(self.UsuariosSeleccionados);
      self.NumeroUsuariosSeleccionados(selectorIndividual ? self.NumeroUsuariosSeleccionados() + 1 : self.NumeroUsuariosSeleccionados() - 1);

      if (self.NumeroUsuariosSeleccionados() == self.TotalUsuarios()) {
        self.form.find('#SelectorUsuarios').prop('checked', true);
      } else {
        self.form.find('#SelectorUsuarios').prop('checked', false);
      }

      if (selectorIndividual) {
        vendedoresSeleccionados.push(data.AliasUsuarioVenta());
      } else {
        vendedoresSeleccionados = vendedoresSeleccionados.filter(function (value) {
          return value != data.AliasUsuarioVenta();
        });
      }
      self.UsuariosSeleccionados(vendedoresSeleccionados);
    }
  }

}

var MappingProductosPorFamiliaConsolidado = {
  'Buscador': {
    create: function (options) {
      if (options)
        return new VistaModeloReporteProductosPorFamiliaConsolidado(options.data);
    }
  }
}

IndexReporteProductosPorFamiliaConsolidado = function (data) {
  var self = this;
  ko.mapping.fromJS(data, MappingProductosPorFamiliaConsolidado, self);
}