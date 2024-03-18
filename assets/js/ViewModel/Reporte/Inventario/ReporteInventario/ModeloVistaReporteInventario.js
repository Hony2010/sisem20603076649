BuscadorModel_Inventario = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_Inventario()=='1')
        {
          $('#DivBuscar_Inventario').hide();
          $('#TextoBuscar_Inventario').show();
          $('#TextoBuscar_Inventario').focus();

        }
        else
        {
          $('#DivBuscar_Inventario').show();
          $('#TextoBuscar_Inventario').hide();
          $('#TextoBuscar_Inventario').val('');

        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = {};

        $data.Saldos = 0;
        $data.IdAsignacionSede = $("#Alamacen_Inventario").val() == "" ? '%' : $("#Alamacen_Inventario").val();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_Inventario();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_Inventario();

        var datajs = {"Data":$data};
        if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(datajs, event);
        else  self.DescargarReporte(datajs, event);
      }
    }

    self.DescargarReporte = function (datajs,event) {
      if (event)
      {
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cReporteInventario/'+nombre,
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
        document.getElementById("contenedorpdf").innerHTML="";
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cReporteInventario/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Inventario()+'.pdf"></iframe>';
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

var MappingInventario = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Inventario(options.data);
            }
    }
}
