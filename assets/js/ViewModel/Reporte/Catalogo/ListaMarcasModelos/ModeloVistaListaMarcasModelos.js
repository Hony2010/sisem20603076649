BuscadorModel_MarcasModelos = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.OnClickBtnReportes = function (data,event) {
      var $data = {};
      $data.NombreArchivoJasper = self.NombreArchivoJasper_MarcasModelos();
      $data.NombreArchivoReporte = self.NombreArchivoReporte_MarcasModelos();
      var datajs = {"Data" : $data};

      if ($(event.target).attr("name") == "pantalla") {
        self.MostrarReporte(datajs, event)
      } else {
        self.DescargarReporte(datajs, event)
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
          url: SITE_URL+'/Reporte/Catalogo/cListaMarcasModelos/'+nombre,
          success: function (data) {
            $("#loader").hide();
            if (data.error == "") {
              window.location = data.url;
            }
            else {
              alertify.alert("Error en reporte listado clientes", data.error)
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
          url: SITE_URL+'/Reporte/Catalogo/cListaMarcasModelos/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_MarcasModelos()+'.pdf"></iframe>';
              $('#modalReporteVistaPrevia').modal('show');
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            alertify.alert("Ha ocurrido un error",jqXHR.responseText);
            $("#loader").hide();
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

var MappingMarcasModelos = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_MarcasModelos(options.data);
            }
    }
}
