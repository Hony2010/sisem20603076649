BuscadorModelComprasMensuales = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.DescargarReporte_ComprasMensuales = function (data,event) {
      if (event)
      {
        $("#loader").show();

        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";

        var IdPeriodoInicial = $("#IdPeriodoInicial_ComprasMensuales").val();
        var IdPeriodoFinal = $("#IdPeriodoFinal_ComprasMensuales").val();
        var Año = $('#combo-añoperiodo').val();
        var NombreArchivoJasper = self.NombreArchivoJasper_ComprasMensuales();
        var NombreArchivoReporte = self.NombreArchivoReporte_ComprasMensuales();

        var objeto = {"IdPeriodoInicial" : IdPeriodoInicial, "IdPeriodoFinal" : IdPeriodoFinal, "Ano" : Año, "NombreArchivoJasper" : NombreArchivoJasper, "NombreArchivoReporte" : NombreArchivoReporte };
        var datajs = {"Data":objeto};
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cComprasMensuales/'+nombre,
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

    self.Pantalla_ComprasMensuales = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var IdPeriodoInicial = $("#IdPeriodoInicial_ComprasMensuales").val();
        var IdPeriodoFinal = $("#IdPeriodoFinal_ComprasMensuales").val();
        var Año = $('#combo-añoperiodo').val();
        var NombreArchivoJasper = self.NombreArchivoJasper_ComprasMensuales();
        var NombreArchivoReporte = self.NombreArchivoReporte_ComprasMensuales();

        var objeto = {"IdPeriodoInicial" : IdPeriodoInicial, "IdPeriodoFinal" : IdPeriodoFinal, "Ano" : Año, "NombreArchivoJasper" : NombreArchivoJasper, "NombreArchivoReporte" : NombreArchivoReporte };
        var datajs = {"Data":objeto};
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cComprasMensuales/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_ComprasMensuales()+'.pdf"></iframe>';
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

    self.OnChangeAñoPeriodo = function (data,event) {
      if (event) {
        $("#loader").show();
        var Año = $('#combo-añoperiodo').val();
        var datajs = {"Data" : {"Ano": Año}} ;
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cComprasMensuales/ListarPeriodoPorAno',
          success: function (data) {
            if(data != "")
            {
              self.MesesPeriodo_ComprasMensuales([]);
              ko.utils.arrayForEach(data, function(item) {
                self.MesesPeriodo_ComprasMensuales.push(new BuscadorModelComprasMensuales(item));
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

    self.ValidarFecha = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }
}

var MappingComprasMensuales = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModelComprasMensuales(options.data);
            }
    }
}
