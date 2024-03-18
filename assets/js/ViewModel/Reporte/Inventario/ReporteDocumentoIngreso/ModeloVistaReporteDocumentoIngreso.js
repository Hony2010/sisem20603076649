BuscadorModel_DocumentoIngreso = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);


    self.GrupoDocumentoIngreso = function(data,event){
      if (event)
      {
        if (data.DI_DocumentoIngreso()=='1')
        {
          self.GenerarJsonDocumentoIngreso(data, event);
        }
        else
        {
          $('#DivDI_DocumentoIngreso').show();
          $('#Item_DocumentoIngreso').hide();
          $('#Item_DocumentoIngreso').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_DocumentoIngreso').resetearValidaciones();
        if (data.Fecha_DocumentoIngreso()=='1')
        {
          $('#FechaDeterminada_DocumentoIngreso').attr('disabled',false)
          $('#FechaHoy_DocumentoIngreso').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_DocumentoIngreso("");
          $('#FechaDeterminada_DocumentoIngreso').attr('disabled',true)
        }
      }
    }

    self.OnChangeFiltroDocumentoIngreso = function(data,event) {
      if (event) {
        self.DI_DocumentoIngreso('0');
        self.GrupoDocumentoIngreso(data, event);
      }
    }

    self.GenerarJsonDocumentoIngreso = function(data, event) {
      if (event) {
        var $data = {};
        $data.FechaInicio = self.FechaInicio_DocumentoIngreso();
        $data.FechaFinal = self.FechaFin_DocumentoIngreso();
        var datajs = {'Data':$data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cReporteDocumentoIngreso/GenerarJsonDocumentoIngresoPorFiltros',
          success: function (data) {
            $("#loader").hide();
            $('#DivDI_DocumentoIngreso').hide();
            $('#Item_DocumentoIngreso').show();
            $('#Item_DocumentoIngreso').focus();
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
        $data.IdDocumentoIngresoZofra = self.DI_DocumentoIngreso() == '0' ? "%" : $('#IdDI_DocumentoIngreso').val();
        $data.FechaInicial = self.FechaInicio_DocumentoIngreso();
        $data.FechaFinal = self.FechaFin_DocumentoIngreso();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_DocumentoIngreso();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_DocumentoIngreso();
        var datajs = {"Data" : $data};

        if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(datajs, event);
        else  self.DescargarReporte(datajs, event);
      }
    }

    self.DescargarReporte = function (datajs,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cReporteDocumentoIngreso/'+nombre,
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
        $("#loader").show();

        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cReporteDocumentoIngreso/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_DocumentoIngreso()+'.pdf"></iframe>';
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

var MappingDocumentoIngreso = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_DocumentoIngreso(options.data);
            }
    }
}
