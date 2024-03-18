BuscadorModel_MovimientoMercaderia = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_MovimientoMercaderia()=='1')
        {
          $('#DivBuscar_MovimientoMercaderia').hide();
          $('#TextoBuscar_MovimientoMercaderia').show();
          $('#TextoBuscar_MovimientoMercaderia').focus();

        }
        else
        {
          $('#DivBuscar_MovimientoMercaderia').show();
          $('#TextoBuscar_MovimientoMercaderia').hide();
          $('#TextoBuscar_MovimientoMercaderia').val('');

        }
      }
    }

    self.SeleccionarTodosComprobantes = function (data, event) {
      if (event) {
        var selectorTodos = $(event.target).prop('checked');
        var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
        var tipoDocumentos = [];
        tipoDocumentosVenta.forEach(function (value, key) {
          $('#'+value.CodigoTipoDocumento+'_TipoDocumento_MovimientoMercaderia').prop('checked', selectorTodos);
          tipoDocumentos.push(value.CodigoTipoDocumento);
        })
        var total = tipoDocumentosVenta.length;
        self.NumeroDocumentosSeleccionados(selectorTodos ? total : 0);
        self.TiposDocumento(selectorTodos ? tipoDocumentos : []);
      }
    }

    self.SeleccionarComprobante = function (data, event) {
      if (event) {
        var selectorIndividual = $(event.target).prop('checked');
        var tipoDocumentos = ko.mapping.toJS(self.TiposDocumento);
        self.NumeroDocumentosSeleccionados(selectorIndividual ? self.NumeroDocumentosSeleccionados() + 1 : self.NumeroDocumentosSeleccionados() - 1);

        if (self.NumeroDocumentosSeleccionados() == self.TotalTipoDocumentos()) {
          $('#SelectorTipoDocumentos_MovimientoMercaderia').prop('checked', true);
        } else {
          $('#SelectorTipoDocumentos_MovimientoMercaderia').prop('checked', false);
        }

        if (selectorIndividual) {
          tipoDocumentos.push(data.CodigoTipoDocumento());
        } else {
          tipoDocumentos = tipoDocumentos.filter(function(value) {
            return value != data.CodigoTipoDocumento();
          });
        }
        self.TiposDocumento(tipoDocumentos);
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event)
      {
        var idAsignacionSede = $("#Alamacen_MovimientoMercaderia").val();
        var idProducto = data.Producto_MovimientoMercaderia();

        var objeto = {
          Saldos : 0,
          FechaInicial: self.FechaInicio_MovimientoMercaderia(),
          FechaFinal: self.FechaFin_MovimientoMercaderia(),
          IdAsignacionSede: idAsignacionSede = (idAsignacionSede != "") ? idAsignacionSede : "%",
          IdProducto : idProducto = ( idProducto != 0) ? $("#IdProducto_MovimientoMercaderia").val() : "%",
          NombreArchivoReporte:self.NombreArchivoReporte_MovimientoMercaderia() ,
          NombreArchivoJasper: self.NombreArchivoJasper_MovimientoMercaderia(),
          TiposDocumento : self.TiposDocumento()
        };

        alertify.confirm("Reporte de Kardex","Para este reporte es recomendable que estén los saldos actualizados, <br> Este proceso puede demorar varios minutos dependiendo de la información que tenga registrada. <br> ¿Desea actualizar los saldos?",function() {
          objeto.Saldos = 1;

          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(objeto, event);
          else  self.DescargarReporte(objeto, event);
        },function() {
          objeto.Saldos = 0;

          if ($(event.target).attr("name") == "pantalla") self.MostrarReporte(objeto, event);
          else  self.DescargarReporte(objeto, event);
        });
      }
    }

    self.DescargarReporte = function (data,event) {
      if (event)
      {
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        var datajs = {"Data":data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cMovimientoMercaderia/'+nombre,
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
        document.getElementById("contenedorpdf").innerHTML="";
        var datajs = {"Data":data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cMovimientoMercaderia/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_MovimientoMercaderia()+'.pdf"></iframe>';
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

var MappingMovimientoMercaderia = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_MovimientoMercaderia(options.data);
            }
    }
}
