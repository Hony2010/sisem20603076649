BuscadorModel_ResumenVentas = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_ResumenVentas = function(data,event){
      if (event)
      {
        if (data.NumeroDocumentoIdentidad_ResumenVentas()=='1') {
          $('#DivBuscar_ResumenVentas').hide();
          $('#TextoBuscar_ResumenVentas').attr('type','text');
          $('#TextoBuscar_ResumenVentas').focus();

        } else {
          $('#DivBuscar_ResumenVentas').show();
          $('#TextoBuscar_ResumenVentas').attr('type','hidden');
          $('#TextoBuscar_ResumenVentas').val('');

        }
      }
    }

    self.DescargarReporte_ResumenVentas = function (data,event) {
      if (event)
      {
        var $data = {};
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";

        $data.AliasUsuarioVenta = $('#Vendedor_ResumenVentas').val() == "" ? '%' : $('#Vendedor_ResumenVentas').val();
        $data.FechaInicial = self.FechaInicio_ResumenVentas();
        $data.FechaFinal = self.FechaFinal_ResumenVentas();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_ResumenVentas();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_ResumenVentas();
        $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
        var datajs ={"Data":$data};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cResumenVentas/'+nombre,
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

    self.Pantalla_ResumenVentas = function (data,event) {
      if (event)
      {
        var $data = {};
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        $data.AliasUsuarioVenta = $('#Vendedor_ResumenVentas').val() == "" ? '%' : $('#Vendedor_ResumenVentas').val();
        $data.FechaInicial = self.FechaInicio_ResumenVentas();
        $data.FechaFinal = self.FechaFinal_ResumenVentas();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_ResumenVentas();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_ResumenVentas();
        var datajs ={"Data":$data};

        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cResumenVentas/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_ResumenVentas()+'.pdf"></iframe>';
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

var MappingResumenVentas = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_ResumenVentas(options.data);
            }
    }
}
