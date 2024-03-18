BuscadorModel_MovimientoAlmacenDocumentoIngreso = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_MovimientoAlmacenDocumentoIngreso()=='1')
        {
          $('#DivBuscar_MovimientoAlmacenDocumentoIngreso').hide();
          $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').show();
          $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').focus();

        }
        else
        {
          $('#DivBuscar_MovimientoAlmacenDocumentoIngreso').show();
          $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').hide();
          $('#TextoBuscar_MovimientoAlmacenDocumentoIngreso').val('');

        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event)
      {

        var $data = {};

        $data.Saldos = 0;
        $data.FechaInicial = self.FechaInicio_MovimientoAlmacenDocumentoIngreso();
        $data.FechaFinal = self.FechaFin_MovimientoAlmacenDocumentoIngreso();
        $data.IdAsignacionSede = $("#Alamacen_MovimientoAlmacenDocumentoIngreso").val() == "" ? '%' : $("#Alamacen_MovimientoAlmacenDocumentoIngreso").val();
        $data.IdProducto = data.Producto_MovimientoAlmacenDocumentoIngreso() == "0" ? '%' : $("#IdProducto_MovimientoAlmacenDocumentoIngreso").val();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_MovimientoAlmacenDocumentoIngreso();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_MovimientoAlmacenDocumentoIngreso();

        alertify.confirm("Reporte de Kardex","Para este reporte es recomendable que estén los saldos actualizados, <br> Este proceso puede demorar varios minutos dependiendo de la información que tenga registrada. <br> ¿Desea actualizar los saldos?",function() {
          $data.Saldos = 1;
          var datajs = {"Data":$data};

          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(datajs, event);
          else  self.DescargarReporte(datajs, event);
        },function() {
          $data.Saldos = 0;
          var datajs = {"Data":$data};

          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(datajs, event);
          else  self.DescargarReporte(datajs, event);
        });
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
          url: SITE_URL+'/Reporte/Inventario/cMovimientoAlmacenDocumentoIngreso/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cMovimientoAlmacenDocumentoIngreso/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_MovimientoAlmacenDocumentoIngreso()+'.pdf"></iframe>';
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

var MappingMovimientoAlmacenDocumentoIngreso = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_MovimientoAlmacenDocumentoIngreso(options.data);
            }
    }
}
