BuscadorModel_Diario = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = {};
        $data.FechaInicial = self.FechaInicio_Diario();
        $data.FechaFinal = self.FechaFinal_Diario();
        $data.UsuarioRegistro  = self.OpcionVistaVenta_Diario() == "0" ? self.IdUsuario_Diario() : "%";
        $data.NombreArchivoReporte = self.NombreArchivoReporte_Diario();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_Diario();
        $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
        var datajs = {"Data" : $data};

        if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(datajs, event);
        else  self.DescargarReporte(datajs, event);
      }
    }

    self.DescargarReporte = function (datajs,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cVentaDiaria/'+nombre,
          success: function (data) {
            $("#loader").hide();
            if (data.error == "") {
              window.location = data.url;
            }
            else {
              alertify.alert("Error en reporte detallado", data.error)
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert("Ha ocurrido un error",jqXHR.responseText);
          }
        });
      }
    }

    self.MostrarReporte = function (datajs,event) {
      if (event)
      {
        $("#loader").show();
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cVentaDiaria/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Diario()+'.pdf"></iframe>';
              $('#modalReporteVistaPrevia').modal('show');
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert("Ha ocurrido un error",jqXHR.responseText);
          }
        });
      }
    }

    self.ValidarFecha = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }
}

var MappingVentaDiaria = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Diario(options.data);
            }
    }
}
