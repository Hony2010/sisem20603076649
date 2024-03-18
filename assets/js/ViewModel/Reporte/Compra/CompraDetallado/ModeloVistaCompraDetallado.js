BuscadorModel_Detallado = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
    self.TipoCompra = ko.observable("")
    self.GrupoCliente_Detallado = function(data,event){
      if (event)
      {
        if (data.NumeroDocumentoIdentidad_Detallado()=='1')
        {
          $('#DivBuscar_Detallado').hide();
          $('#TextoBuscar_Detallado').show();
          $('#TextoBuscar_Detallado').focus();

        }
        else
        {
          $('#DivBuscar_Detallado').show();
          $('#TextoBuscar_Detallado').hide();
          $('#TextoBuscar_Detallado').val('');

        }
      }
    }

    self.OnChangeAñoPeriodo = function (data,event) {
      if (event) {
        $("#loader").show();
        var Año = $('#combo-añoperiodo_Detallado').val();
        var datajs = {"Data" : {"Ano": Año}} ;
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cCompraDetallado/ListarPeriodoPorAno',
          success: function (data) {
            if(data != "")
            {
              self.MesesPeriodo_Detallado([]);
              ko.utils.arrayForEach(data, function(item) {
                self.MesesPeriodo_Detallado.push(new BuscadorModel_Detallado(item));
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

    self.FormaPago = function (event) {
      if (event) {
        if (self.FormaPago_Detallado() == ID_FORMA_PAGO_CONTADO) {
          return 'CONTADO';
        } else if (self.FormaPago_Detallado() == ID_FORMA_PAGO_CREDITO) {
          return 'CREDITO';
        } else {
          return '%';
        }
      }
    }
    self.Orden = function (event) {
      if (event) {
        if (self.Orden_Detallado() == '1') {
          return 'RazonSocial';
        } else if (self.Orden_Detallado() == '2') {
          return 'Documento';
        } else {
          return 'FechaEmision';
        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event)
      {
        var $data = {};
        $data.FechaInicio = self.FechaInicio_Detallado();
        $data.FechaFinal = self.FechaFinal_Detallado();
        $data.FormaPago = self.FormaPago(event);
        $data.NumeroDocumentoIdentidad = self.NumeroDocumentoIdentidad_Detallado() == 0 ? '%' : self.TextoCliente_Detallado();
        $data.TipoCompra = self.IdTipoCompra_Detallado() == undefined ? '%' : self.IdTipoCompra_Detallado();
        $data.Orden = self.Orden(event);
        $data.Año = $('#combo-añoperiodo_Detallado').val();
        $data.IdPeriodoInicial = $('#IdPeriodoInicial_Detallado').val();
        $data.IdPeriodoFinal = $('#IdPeriodoFinal_Detallado').val();
        $data.NombreArchivoJasper = self.NombreArchivoJasper_Detallado();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_Detallado();
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
          url: SITE_URL+'/Reporte/Compra/cCompraDetallado/'+nombre,
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
          url: SITE_URL+'/Reporte/Compra/cCompraDetallado/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Detallado()+'.pdf"></iframe>';
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

var MappingCompraDetallado = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Detallado(options.data);
            }
    }
}
