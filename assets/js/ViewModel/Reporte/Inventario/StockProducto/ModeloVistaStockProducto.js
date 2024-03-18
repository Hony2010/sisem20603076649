BuscadorModel_StockProducto = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_StockProducto()=='1')
        {
          $('#DivBuscar_StockProducto').hide();
          $('#TextoBuscar_StockProducto').show();
          $('#TextoBuscar_StockProducto').focus();

        }
        else
        {
          $('#DivBuscar_StockProducto').show();
          $('#TextoBuscar_StockProducto').hide();
          $('#TextoBuscar_StockProducto').val('');

        }
      }
    }

    self.SeleccionarTodosComprobantes = function (data, event) {
      if (event) {
        var selectorTodos = $(event.target).prop('checked');
        var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
        var tipoDocumentos = [];
        tipoDocumentosVenta.forEach(function (value, key) {
          $('#'+value.IdTipoDocumento+'_TipoDocumento_StockProducto').prop('checked', selectorTodos);
          tipoDocumentos.push(value.IdTipoDocumento);
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
          $('#SelectorTipoDocumentos_StockProducto').prop('checked', true);
        } else {
          $('#SelectorTipoDocumentos_StockProducto').prop('checked', false);
        }

        if (selectorIndividual) {
          tipoDocumentos.push(data.IdTipoDocumento());
        } else {
          tipoDocumentos = tipoDocumentos.filter(function(value) {
            return value != data.IdTipoDocumento();
          });
        }
        self.TiposDocumento(tipoDocumentos);
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_StockProducto').resetearValidaciones();
        if (data.Fecha_StockProducto()=='1')
        {
          // data.FechaHoy_StockProducto("");
          $('#FechaDeterminada_StockProducto').attr('disabled',false)
          $('#FechaHoy_StockProducto').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_StockProducto("");
          $('#FechaDeterminada_StockProducto').attr('disabled',true)
          // $('#FechaHoy_StockProducto').attr('disabled',false)
        }
      }
    }

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = ko.mapping.toJS(data);
        $data.IdAsignacionSede = $data.IdSede_StockProducto == undefined ? "%" : $data.IdSede_StockProducto;
        $data.Orden = $data.Orden_StockProducto == '0' ? "SaldoFisico desc" : "NombreProducto asc";
        $data.IdProducto = $data.Producto_StockProducto == '0' ? "%" : $('#IdProducto_StockProducto').val();
        $data.Saldos  = 0 ;
        $data.TiposDocumento = self.TiposDocumento();


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
        var datajs = {"Data" : data};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Inventario/cStockProducto/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cStockProducto/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_StockProducto()+'.pdf"></iframe>';
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

var MappingStockProducto = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_StockProducto(options.data);
            }
    }
}
