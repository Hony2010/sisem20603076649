BuscadorModelFormato8 = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = {} ;
        $data.IdPeriodoInicial = self.IdPeriodoInicio(); 
        $data.IdPeriodoFinal = self.IdPeriodoFin();
        $data.Anio =self.Año();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_Formato8();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_Formato8();
        
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
          url: SITE_URL+'/Reporte/Compra/cReporteFormato8_1Compra/'+nombre,
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
          url: SITE_URL+'/Reporte/Compra/cReporteFormato8_1Compra/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Formato8()+'.pdf"></iframe>';
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
          url: SITE_URL+'/Reporte/Compra/cReporteFormato8_1Compra/ListarPeriodoPorAno',
          success: function (data) {
            if(data != "")
            {
              self.MesesPeriodo_Formato8([]);
              ko.utils.arrayForEach(data, function(item) {
                self.MesesPeriodo_Formato8.push(new BuscadorModelFormato8(item));
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

var MappingFormato8 = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModelFormato8(options.data);
            }
    }
}
