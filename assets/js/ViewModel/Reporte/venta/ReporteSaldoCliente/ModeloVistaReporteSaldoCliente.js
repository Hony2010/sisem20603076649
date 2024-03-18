BuscadorModel_SaldoCliente = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_SaldoCliente = function(data,event){
      if (event)
      {
        if (data.IdPersona_SaldoCliente()=='1')
        {
          $('#DivBuscar_SaldoCliente').hide();
          $('#TextoBuscar_SaldoCliente').attr('type','text');
          $('#TextoBuscar_SaldoCliente').focus();

        }
        else
        {
          $('#DivBuscar_SaldoCliente').show();
          $('#TextoBuscar_SaldoCliente').attr('type','hidden');
          $('#TextoBuscar_SaldoCliente').val('');

        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event)
      {
        var $data = {} ;
        $data.FechaInicio = self.FechaInicio_SaldoCliente();
        $data.FechaFinal = self.FechaFinal_SaldoCliente();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_SaldoCliente();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_SaldoCliente();
        $data.IdPersona = self.IdPersona_SaldoCliente() == 0 ? '%' : $('#TextoBuscarOculto_SaldoCliente').val();
        $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()

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
          url: SITE_URL+'/Reporte/Venta/cReporteSaldoCliente/'+nombre,
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
          url: SITE_URL+'/Reporte/Venta/cReporteSaldoCliente/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_SaldoCliente()+'.pdf"></iframe>';
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

var MappingReporteSaldoCliente = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_SaldoCliente(options.data);
            }
    }
}
