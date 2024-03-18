BuscadorModel_MovimientoDocumentoZofra = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_MovimientoDocumentoZofra()=='1')
        {
          $('#DivBuscar_MovimientoDocumentoZofra').hide();
          $('#TextoBuscar_MovimientoDocumentoZofra').show();
          $('#TextoBuscar_MovimientoDocumentoZofra').focus();

        }
        else
        {
          $('#DivBuscar_MovimientoDocumentoZofra').show();
          $('#TextoBuscar_MovimientoDocumentoZofra').hide();
          $('#TextoBuscar_MovimientoDocumentoZofra').val('');

        }
      }
    }
    self.GrupoDocumentoSalidaZofra = function(data,event){
      if (event)
      {
        if (data.DocumentoSalidaZofra_MovimientoDocumentoZofra()=='1')
        {
          self.GenerarJsonDocumentoSalidaZofra(data, event);
        }
        else
        {
          $('#DivDocumentoSalidaZofra_MovimientoDocumentoZofra').show();
          $('#Item_MovimientoDocumentoZofra').hide();
          $('#Item_MovimientoDocumentoZofra').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_MovimientoDocumentoZofra').resetearValidaciones();
        if (data.Fecha_MovimientoDocumentoZofra()=='1')
        {
          $('#FechaDeterminada_MovimientoDocumentoZofra').attr('disabled',false)
          $('#FechaHoy_MovimientoDocumentoZofra').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_MovimientoDocumentoZofra("");
          $('#FechaDeterminada_MovimientoDocumentoZofra').attr('disabled',true)
        }
      }
    }

    self.OnChangeFiltroDocumentoSalidaZofra = function(data,event) {
      if (event) {
        self.DocumentoSalidaZofra_MovimientoDocumentoZofra('0');
        self.GrupoDocumentoSalidaZofra(data, event);
      }
    }

    self.GenerarJsonDocumentoSalidaZofra = function(data, event) {
      if (event) {
        var $data = {};
        $data.FechaInicio = self.FechaInicioDocumentoSalidaZofra_MovimientoDocumentoZofra();
        $data.FechaFinal = self.FechaFinDocumentoSalidaZofra_MovimientoDocumentoZofra();
        var datajs = {'Data':$data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDocumentoZofra/GenerarJsonDocumentoZofraPorFiltros',
          success: function (data) {
            $("#loader").hide();
            $('#DivDocumentoSalidaZofra_MovimientoDocumentoZofra').hide();
            $('#Item_MovimientoDocumentoZofra').show();
            $('#Item_MovimientoDocumentoZofra').focus();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert("Ha ocurrido un error",jqXHR.responseText);
          }
        });
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = {};
        $data.IdAsignacionSede = $('#Almacen_MovimientoDocumentoZofra').val() == "" ? "%" : $('#Almacen_MovimientoDocumentoZofra').val();
        $data.IdProducto = self.Producto_MovimientoDocumentoZofra() == '0' ? "%" : $('#IdProducto_MovimientoDocumentoZofra').val();
        $data.IdDocumentoSalidaZofra = self.DocumentoSalidaZofra_MovimientoDocumentoZofra() == '0' ? "%" : $('#IdDocumentoSalidaZofra_MovimientoDocumentoZofra').val();
        $data.FechaMovimientoInicial = self.FechaInicio_MovimientoDocumentoZofra();
        $data.FechaMovimientoFinal = self.FechaFin_MovimientoDocumentoZofra();
        $data.FechaInicial = self.FechaInicioDocumentoSalidaZofra_MovimientoDocumentoZofra();
        $data.FechaFinal = self.FechaFinDocumentoSalidaZofra_MovimientoDocumentoZofra();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_MovimientoDocumentoZofra();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_MovimientoDocumentoZofra();
        $data.Saldos = 0;
        alertify.confirm("Reporte Movimiento DocumentoSalidaZofra","Para este reporte es recomendable que estén los saldos actualizados, <br> Este proceso puede demorar varios minutos dependiendo de la información que tenga registrada. <br> ¿Desea actualizar los saldos?",function () {
          $data.Saldos = 1;
          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
          else  self.DescargarReporte($data, event);
        },function () {
          $data.Saldos = 0;
          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
          else  self.DescargarReporte($data, event);
        })

      }
    }

    self.DescargarReporte = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        var datajs = {"Data" : data};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cMovimientoDocumentoZofra/'+nombre,
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

    self.MostrarReporte = function (data,event) {
      if (event)
      {
        $("#loader").show();

        var datajs = ko.toJS({"Data":data});
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cMovimientoDocumentoZofra/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_MovimientoDocumentoZofra()+'.pdf"></iframe>';
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

var MappingMovimientoDocumentoZofra = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_MovimientoDocumentoZofra(options.data);
            }
    }
}
