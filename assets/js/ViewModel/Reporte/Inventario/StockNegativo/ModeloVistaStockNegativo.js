BuscadorModel_StockNegativo = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_StockNegativo()=='1')
        {
          $('#DivBuscar_StockNegativo').hide();
          $('#TextoBuscar_StockNegativo').show();
          $('#TextoBuscar_StockNegativo').focus();

        }
        else
        {
          $('#DivBuscar_StockNegativo').show();
          $('#TextoBuscar_StockNegativo').hide();
          $('#TextoBuscar_StockNegativo').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_StockNegativo').resetearValidaciones();
        if (data.Fecha_StockNegativo()=='1')
        {
          // data.FechaHoy_StockNegativo("");
          $('#FechaDeterminada_StockNegativo').prop('disabled',false)
          $('#FechaHoy_StockNegativo').prop('disabled',true)
        }
        else
        {
          data.FechaDeterminada_StockNegativo("");
          $('#FechaDeterminada_StockNegativo').prop('disabled',true)
          // $('#FechaHoy_StockNegativo').prop('disabled',false)
        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = ko.toJS(data);
        $data.IdAsignacionSede = $data.IdSede_StockNegativo == undefined ? "%" : $data.IdSede_StockNegativo;
        $data.Orden = $data.Orden_StockNegativo == '0' ? "SaldoFisico desc" : "NombreProducto asc";
        $data.IdProducto = $data.Producto_StockNegativo == '0' ? "%" : $('#IdProducto_StockNegativo').val();
        $data.Saldos  = 0 ;

        if ($data.Fecha_StockNegativo == 0) {
          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
          else  self.DescargarReporte($data, event);
        }
        else {
          alertify.confirm("Reporte de Stock de Producto", "Para este reporte es recomendable que estén los saldos actualizados, \n ¿Desea actualizar los saldos?",function () {
            $data.Saldos  = 1 ;
            if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
            else  self.DescargarReporte($data, event);
          },function () {
            $data.Saldos  = 0 ;
            if ($(event.target).attr("name") == "pantalla") self.MostrarReporte($data, event);
            else  self.DescargarReporte($data, event);
          });
        }
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
          url: SITE_URL+'/Reporte/Inventario/cStockNegativo/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cStockNegativo/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_StockNegativo()+'.pdf"></iframe>';
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

var MappingStockNegativo = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_StockNegativo(options.data);
            }
    }
}
