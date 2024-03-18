BuscadorModel_MovimientoAlmacenValorado = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_MovimientoAlmacenValorado()=='1')
        {
          $('#DivBuscar_MovimientoAlmacenValorado').hide();
          $('#TextoBuscar_MovimientoAlmacenValorado').show();
          $('#TextoBuscar_MovimientoAlmacenValorado').focus();

        }
        else
        {
          $('#DivBuscar_MovimientoAlmacenValorado').show();
          $('#TextoBuscar_MovimientoAlmacenValorado').hide();
          $('#TextoBuscar_MovimientoAlmacenValorado').val('');

        }
      }
    }

    self.SeleccionarTodosComprobantes = function (data, event) {
      if (event) {
        var selectorTodos = $(event.target).prop('checked');
        var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
        var tipoDocumentos = [];
        tipoDocumentosVenta.forEach(function (value, key) {
          $('#'+value.CodigoTipoDocumento+'_TipoDocumento_MovimientoAlmacenValorado').prop('checked', selectorTodos);
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
          $('#SelectorTipoDocumentos_MovimientoAlmacenValorado').prop('checked', true);
        } else {
          $('#SelectorTipoDocumentos_MovimientoAlmacenValorado').prop('checked', false);
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
        var idAsignacionSede = $("#Alamacen_MovimientoAlmacenValorado").val();
        var idProducto = self.Producto_MovimientoAlmacenValorado();

        var objeto = {
          Saldos : 0,
          FechaInicial: self.FechaInicio_MovimientoAlmacenValorado(),
          FechaFinal: self.FechaFin_MovimientoAlmacenValorado(),
          IdAsignacionSede: (idAsignacionSede != "") ? idAsignacionSede : "%",
          IdProducto : idProducto = ( idProducto != 0) ? $("#IdProducto_MovimientoAlmacenValorado").val() : "%",
          NombreArchivoReporte:self.NombreArchivoReporte_MovimientoAlmacenValorado(),
          NombreArchivoJasper: self.NombreArchivoJasper_MovimientoAlmacenValorado(),
          TiposDocumento : self.TiposDocumento()
        };

        alertify.confirm("Reporte de Kardex Valorado","Para este reporte es recomendable que estén los saldos actualizados, \n Este proceso puede demorar varios minutos dependiendo de la información que tenga registrada. \n\n ¿Desea actualizar los saldos?",function() {
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

    self.DescargarReporte = function(data,event) {
      if (event) {
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        var datajs = {"Data" : data};
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cMovimientoAlmacenValorado/'+nombre,
          success: function (data) {
            $("#loader").hide();
            if (data.error == "") {
              window.location = data.url;
            }
            else {
              alertify.alert("Error en reporte", data.error)
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
      if (event){
        var datajs = {"Data":data};
        document.getElementById("contenedorpdf").innerHTML="";
        $("#loader").show();
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cMovimientoAlmacenValorado/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_MovimientoAlmacenValorado()+'.pdf"></iframe>';
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

var MappingMovimientoAlmacenValorado = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_MovimientoAlmacenValorado(options.data);
            }
    }
}
