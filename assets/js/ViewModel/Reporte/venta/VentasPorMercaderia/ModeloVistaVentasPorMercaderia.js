BuscadorModel_Mercaderia = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_Mercaderia = function(data,event){
      if (event)
      {
        if (data.IdProducto_Mercaderia()=='1')
        {
          $('#DivBuscar_Mercaderia').hide();
          $('#TextoBuscar_Mercaderia').attr('type','text');
          $('#TextoBuscar_Mercaderia').focus();

        }
        else
        {
          $('#DivBuscar_Mercaderia').show();
          $('#TextoBuscar_Mercaderia').attr('type','hidden');
          $('#TextoBuscar_Mercaderia').val('');

        }
      }
    }

    self.DescargarReporte_Mercaderia = function (data,event) {
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
          url: SITE_URL+'/Reporte/Venta/cVentasPorMercaderia/'+nombre,
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

    self.Pantalla_Mercaderia = function (data,event) {
      if (event)
      {
        $("#loader").show();
        data.IdAsignacionSede(data.IdAsignacionSede() || '%');
        var datajs = ko.toJS({"Data":data});
        
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cVentasPorMercaderia/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Mercaderia()+'.pdf"></iframe>';
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

var MappingVentasPorMercaderia = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Mercaderia(options.data);
            }
    }
}
