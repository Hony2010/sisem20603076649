BuscadorModel_General = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_General = function(data,event){
      if (event)
      {
        if (data.NumeroDocumentoIdentidad_General()=='1')
        {
          $('#DivBuscar_General').hide();
          $('#TextoBuscar_General').show();
          $('#TextoBuscar_General').focus();

        }
        else
        {
          $('#DivBuscar_General').show();
          $('#TextoBuscar_General').hide();
          $('#TextoBuscar_General').val('');

        }
      }
    }

    self.OnChangeAñoPeriodo = function (data,event) {
      if (event) {
        $("#loader").show();
        var Año = $('#combo-añoperiodo_General').val();
        var datajs = {"Data" : {"Ano": Año}} ;
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cCompraGeneral/ListarPeriodoPorAno',
          success: function (data) {
            if(data != "")
            {
              self.MesesPeriodo_General([]);
              ko.utils.arrayForEach(data, function(item) {
                self.MesesPeriodo_General.push(new BuscadorModel_General(item));
              });
            }
            $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event)
      {
        var $data = {};
        $data.FechaInicial = self.FechaInicio_General();
        $data.FechaFinal = self.FechaFinal_General();
        $data.NumeroDocumentoIdentidad = self.NumeroDocumentoIdentidad_General() == 0 ? '%' : self.TextoCliente_General();
        $data.IdTipoCompra = self.IdTipoCompra_General() == undefined ? '%' : self.IdTipoCompra_General();
        $data.Año = $('#combo-añoperiodo_General').val();
        $data.IdPeriodoInicial = $('#IdPeriodoInicial_General').val();
        $data.IdPeriodoFinal = $('#IdPeriodoFinal_General').val();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_General();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_General();
        var datajs = {"Data":$data};

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
          url: SITE_URL+'/Reporte/Compra/cCompraGeneral/'+nombre,
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
          url: SITE_URL+'/Reporte/Compra/cCompraGeneral/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_General()+'.pdf"></iframe>';
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

var MappingCompraGeneral = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_General(options.data);
            }
    }
}
