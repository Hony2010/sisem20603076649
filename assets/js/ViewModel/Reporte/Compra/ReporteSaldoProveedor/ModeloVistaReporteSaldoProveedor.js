BuscadorModel_SaldoProveedor = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_SaldoProveedor = function(data,event){
      if (event)
      {
        if (data.IdPersona_SaldoProveedor()=='1')
        {
          $('#DivBuscar_SaldoProveedor').hide();
          $('#TextoBuscar_SaldoProveedor').show();
          $('#TextoBuscar_SaldoProveedor').focus();

        }
        else
        {
          $('#DivBuscar_SaldoProveedor').show();
          $('#TextoBuscar_SaldoProveedor').hide();
          $('#TextoBuscar_SaldoProveedor').val('');

        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event)
      {
        var $data = {} ;
        $data.FechaInicio = self.FechaInicio_SaldoProveedor();
        $data.FechaFinal = self.FechaFinal_SaldoProveedor();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_SaldoProveedor();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_SaldoProveedor();
        $data.IdPersona = self.IdPersona_SaldoProveedor() == 0 ? '%' : $('#TextoBuscarOculto_SaldoProveedor').val();

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
        var datajs = ko.toJS({"Data":data});

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cReporteSaldoProveedor/'+nombre,
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
          url: SITE_URL+'/Reporte/Compra/cReporteSaldoProveedor/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_SaldoProveedor()+'.pdf"></iframe>';
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

var MappingReporteSaldoProveedor = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_SaldoProveedor(options.data);
            }
    }
}
