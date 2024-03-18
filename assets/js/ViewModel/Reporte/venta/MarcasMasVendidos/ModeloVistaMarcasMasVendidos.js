BuscadorModel_Marca = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.DescargarReporte_Marca = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        data.IdAsignacionSede(data.IdAsignacionSede() || '%');
        var datajs = ko.toJS({"Data":data});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cMarcasMasVendidos/'+nombre,
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

    self.Pantalla_Marca = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var objeto = data;
        data.IdAsignacionSede(data.IdAsignacionSede() || '%');
        var datajs = ko.toJS({"Data":data});
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cMarcasMasVendidos/GenerarReportePANTALLA',
          success: function (data) {
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Marca()+'.pdf"></iframe>';
              $('#modalReporteVistaPrevia').modal('show');
            }
            $("#loader").hide();
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

var MappingMarcaMasVendido = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Marca(options.data);
            }
    }
}
