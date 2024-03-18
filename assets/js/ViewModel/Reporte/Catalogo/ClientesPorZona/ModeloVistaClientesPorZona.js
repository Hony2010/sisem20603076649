BuscadorModel_Z = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.DescargarReporte_Z = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        var datajs = ko.toJS({"Data": self.ParseData(data, event)});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Catalogo/cClientesPorZona/'+nombre,
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

    self.ParseData = function (data, event) {
      if (event) {
        var $data = {
          NombreArchivoReporte_Z:  self.NombreArchivoReporte_Z(),
          NombreArchivoJasper_Z: self.NombreArchivoJasper_Z(),
          NombreZona_Z: self.NombreZona_Z() == "" ? "%" : self.NombreZona_Z() 
        }
        return $data;
      }
    }


    self.Pantalla_Z = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var datajs = ko.toJS({"Data": self.ParseData(data, event)});
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Catalogo/cClientesPorZona/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Z()+'.pdf"></iframe>';
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

var MappingClientesPorZona = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Z(options.data);
            }
    }
}

