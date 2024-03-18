BuscadorModel_StockProductoDocumentoZofra = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_StockProductoDocumentoZofra()=='1')
        {
          $('#DivBuscar_StockProductoDocumentoZofra').hide();
          $('#TextoBuscar_StockProductoDocumentoZofra').show();
          $('#TextoBuscar_StockProductoDocumentoZofra').focus();

        }
        else
        {
          $('#DivBuscar_StockProductoDocumentoZofra').show();
          $('#TextoBuscar_StockProductoDocumentoZofra').hide();
          $('#TextoBuscar_StockProductoDocumentoZofra').val('');

        }
      }
    }
    self.GrupoDocumentoSalidaZofra = function(data,event){
      if (event)
      {
        if (data.DocumentoSalidaZofra_StockProductoDocumentoZofra()=='1')
        {
          self.GenerarJsonDocumentoSalidaZofra(data, event);
        }
        else
        {
          $('#DivDocumentoSalidaZofra_StockProductoDocumentoZofra').show();
          $('#Item_StockProductoDocumentoZofra').hide();
          $('#Item_StockProductoDocumentoZofra').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_StockProductoDocumentoZofra').resetearValidaciones();
        if (data.Fecha_StockProductoDocumentoZofra()=='1')
        {
          $('#FechaDeterminada_StockProductoDocumentoZofra').attr('disabled',false)
          $('#FechaHoy_StockProductoDocumentoZofra').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_StockProductoDocumentoZofra("");
          $('#FechaDeterminada_StockProductoDocumentoZofra').attr('disabled',true)
        }
      }
    }

    self.OnChangeFiltroDocumentoSalidaZofra = function(data,event) {
      if (event) {
        self.DocumentoSalidaZofra_StockProductoDocumentoZofra('0');
        self.GrupoDocumentoSalidaZofra(data, event);
      }
    }

    self.GenerarJsonDocumentoSalidaZofra = function(data, event) {
      if (event) {
        var $data = {};
        $data.IdAsignacionSede = $('#Almacen_StockProductoDocumentoZofra').val() == "" ? '%': $('#Almacen_StockProductoDocumentoZofra').val();
        $data.FechaInicio = self.FechaInicio_StockProductoDocumentoZofra();
        $data.FechaFinal = self.FechaFin_StockProductoDocumentoZofra();
        var datajs = {'Data':$data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDocumentoZofra/GenerarJsonDocumentoZofraPorFiltros',
          success: function (data) {
            $("#loader").hide();
            $('#DivDocumentoSalidaZofra_StockProductoDocumentoZofra').hide();
            $('#Item_StockProductoDocumentoZofra').show();
            $('#Item_StockProductoDocumentoZofra').focus();
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
        $data.IdAsignacionSede = $('#Almacen_StockProductoDocumentoZofra').val() == "" ? "%" : $('#Almacen_StockProductoDocumentoZofra').val();
        $data.Orden = self.Orden_StockProductoDocumentoZofra() == '0' ? "SaldoFisico desc" : "NombreProducto asc";
        $data.IdProducto = self.Producto_StockProductoDocumentoZofra() == '0' ? "%" : $('#IdProducto_StockProductoDocumentoZofra').val();
        $data.IdDocumentoSalidaZofra = self.DocumentoSalidaZofra_StockProductoDocumentoZofra() == '0' ? "%" : $('#IdDocumentoSalidaZofra_StockProductoDocumentoZofra').val();
        $data.FechaInicial = self.FechaInicio_StockProductoDocumentoZofra();
        $data.FechaFinal = self.FechaFin_StockProductoDocumentoZofra();
        $data.Fecha = self.Fecha_StockProductoDocumentoZofra();
        $data.FechaActual = self.FechaHoy_StockProductoDocumentoZofra();
        $data.FechaDeterminada = self.FechaDeterminada_StockProductoDocumentoZofra();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_StockProductoDocumentoZofra();

        if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
        else  self.DescargarReporte($data, event);
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
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDocumentoZofra/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDocumentoZofra/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_StockProductoDocumentoZofra()+'.pdf"></iframe>';
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

var MappingStockProductoDocumentoZofra = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_StockProductoDocumentoZofra(options.data);
            }
    }
}
