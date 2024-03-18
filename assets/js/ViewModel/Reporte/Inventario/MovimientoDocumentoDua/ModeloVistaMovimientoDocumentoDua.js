BuscadorModel_MovimientoDocumentoDua = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_MovimientoDocumentoDua()=='1')
        {
          $('#DivBuscar_MovimientoDocumentoDua').hide();
          $('#TextoBuscar_MovimientoDocumentoDua').show();
          $('#TextoBuscar_MovimientoDocumentoDua').focus();

        }
        else
        {
          $('#DivBuscar_MovimientoDocumentoDua').show();
          $('#TextoBuscar_MovimientoDocumentoDua').hide();
          $('#TextoBuscar_MovimientoDocumentoDua').val('');

        }
      }
    }
    self.GrupoDua = function(data,event){
      if (event)
      {
        if (data.Dua_MovimientoDocumentoDua()=='1')
        {
          self.GenerarJsonDua(data, event);
        }
        else
        {
          $('#DivDua_MovimientoDocumentoDua').show();
          $('#Item_MovimientoDocumentoDua').hide();
          $('#Item_MovimientoDocumentoDua').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_MovimientoDocumentoDua').resetearValidaciones();
        if (data.Fecha_MovimientoDocumentoDua()=='1')
        {
          $('#FechaDeterminada_MovimientoDocumentoDua').attr('disabled',false)
          $('#FechaHoy_MovimientoDocumentoDua').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_MovimientoDocumentoDua("");
          $('#FechaDeterminada_MovimientoDocumentoDua').attr('disabled',true)
        }
      }
    }

    self.OnChangeFiltroDua = function(data,event) {
      if (event) {
        self.Dua_MovimientoDocumentoDua('0');
        self.GrupoDua(data, event);
      }
    }

    self.GenerarJsonDua = function(data, event) {
      if (event) {
        var $data = {};
        $data.FechaInicio = self.FechaInicioDua_MovimientoDocumentoDua();
        $data.FechaFinal = self.FechaFinDua_MovimientoDocumentoDua();
        var datajs = {'Data':$data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cReporteMovimientoDocumentoDua/GenerarJsonDuaProductoPorFiltros',
          success: function (data) {
            $("#loader").hide();
            $('#DivDua_MovimientoDocumentoDua').hide();
            $('#Item_MovimientoDocumentoDua').show();
            $('#Item_MovimientoDocumentoDua').focus();
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
        $data.IdAsignacionSede = $('#Almacen_MovimientoDocumentoDua').val() == "" ? "%" : $('#Almacen_MovimientoDocumentoDua').val();
        $data.IdProducto = self.Producto_MovimientoDocumentoDua() == '0' ? "%" : $('#IdProducto_MovimientoDocumentoDua').val();
        $data.IdDua = self.Dua_MovimientoDocumentoDua() == '0' ? "%" : $('#IdDua_MovimientoDocumentoDua').val();
        $data.FechaMovimientoInicial = self.FechaInicio_MovimientoDocumentoDua();
        $data.FechaMovimientoFinal = self.FechaFin_MovimientoDocumentoDua();
        $data.FechaInicial = self.FechaInicioDua_MovimientoDocumentoDua();
        $data.FechaFinal = self.FechaFinDua_MovimientoDocumentoDua();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_MovimientoDocumentoDua();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_MovimientoDocumentoDua();
        $data.Saldos = 0;
        alertify.confirm("Reporte Movimiento Dua","Para este reporte es recomendable que estén los saldos actualizados, <br> Este proceso puede demorar varios minutos dependiendo de la información que tenga registrada. <br> ¿Desea actualizar los saldos?",function () {
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
          url: SITE_URL+'/Reporte/Inventario/cReporteMovimientoDocumentoDua/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cReporteMovimientoDocumentoDua/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_MovimientoDocumentoDua()+'.pdf"></iframe>';
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

var MappingMovimientoDocumentoDua = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_MovimientoDocumentoDua(options.data);
            }
    }
}
