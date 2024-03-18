BuscadorModel_StockProductoDua = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_StockProductoDua()=='1')
        {
          $('#DivBuscar_StockProductoDua').hide();
          $('#TextoBuscar_StockProductoDua').show();
          $('#TextoBuscar_StockProductoDua').focus();

        }
        else
        {
          $('#DivBuscar_StockProductoDua').show();
          $('#TextoBuscar_StockProductoDua').hide();
          $('#TextoBuscar_StockProductoDua').val('');

        }
      }
    }
    self.GrupoDua = function(data,event){
      if (event)
      {
        if (data.Dua_StockProductoDua()=='1')
        {
          self.GenerarJsonDua(data, event);
        }
        else
        {
          $('#DivDua_StockProductoDua').show();
          $('#Item_StockProductoDua').hide();
          $('#Item_StockProductoDua').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_StockProductoDua').resetearValidaciones();
        if (data.Fecha_StockProductoDua()=='1')
        {
          $('#FechaDeterminada_StockProductoDua').attr('disabled',false)
          $('#FechaHoy_StockProductoDua').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_StockProductoDua("");
          $('#FechaDeterminada_StockProductoDua').attr('disabled',true)
        }
      }
    }

    self.OnChangeFiltroDua = function(data,event) {
      if (event) {
        self.Dua_StockProductoDua('0');
        self.GrupoDua(data, event);
      }
    }

    self.GenerarJsonDua = function(data, event) {
      if (event) {
        var $data = {};
        $data.FechaInicio = self.FechaInicio_StockProductoDua();
        $data.FechaFinal = self.FechaFin_StockProductoDua();
        var datajs = {'Data':$data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDua/GenerarJsonDuaProductoPorFiltros',
          success: function (data) {
            $("#loader").hide();
            $('#DivDua_StockProductoDua').hide();
            $('#Item_StockProductoDua').show();
            $('#Item_StockProductoDua').focus();
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
        $data.IdAsignacionSede = $('#Almacen_StockProductoDua').val() == "" ? "%" : $('#Almacen_StockProductoDua').val();
        $data.Orden = self.Orden_StockProductoDua() == '0' ? "SaldoFisico desc" : "NombreProducto asc";
        $data.IdProducto = self.Producto_StockProductoDua() == '0' ? "%" : $('#IdProducto_StockProductoDua').val();
        $data.IdDua = self.Dua_StockProductoDua() == '0' ? "%" : $('#IdDua_StockProductoDua').val();
        $data.FechaInicial = self.FechaInicio_StockProductoDua();
        $data.FechaFinal = self.FechaFin_StockProductoDua();
        $data.Fecha = self.Fecha_StockProductoDua();
        $data.FechaActual = self.FechaHoy_StockProductoDua();
        $data.FechaDeterminada = self.FechaDeterminada_StockProductoDua();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_StockProductoDua();

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
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDua/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cStockProductoDua/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_StockProductoDua()+'.pdf"></iframe>';
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

var MappingStockProductoDua = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_StockProductoDua(options.data);
            }
    }
}
