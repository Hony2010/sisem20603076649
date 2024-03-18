BuscadorModel_StockProductoMarca = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto = function(data,event){
      if (event)
      {
        if (data.Producto_StockProductoMarca()=='1')
        {
          $('#DivBuscar_StockProductoMarca').hide();
          $('#TextoBuscar_StockProductoMarca').show();
          $('#TextoBuscar_StockProductoMarca').focus();

        }
        else
        {
          $('#DivBuscar_StockProductoMarca').show();
          $('#TextoBuscar_StockProductoMarca').hide();
          $('#TextoBuscar_StockProductoMarca').val('');

        }
      }
    }

    self.OnChangeFecha = function(data,event){
      if (event)
      {
        $('#form_StockProductoMarca').resetearValidaciones();
        if (data.Fecha_StockProductoMarca()=='1')
        {
          // data.FechaHoy_StockProductoMarca("");
          $('#FechaDeterminada_StockProductoMarca').attr('disabled',false)
          $('#FechaHoy_StockProductoMarca').attr('disabled',true)
        }
        else
        {
          data.FechaDeterminada_StockProductoMarca("");
          $('#FechaDeterminada_StockProductoMarca').attr('disabled',true)
          // $('#FechaHoy_StockProductoMarca').attr('disabled',false)
        }
      }
    }


    self.SeleccionarTodosComprobantes = function (data, event) {
      if (event) {
        var selectorTodos = $(event.target).prop('checked');
        var tipoDocumentosVenta = ko.mapping.toJS(self.TiposDocumentoVenta);
        var tipoDocumentos = [];
        tipoDocumentosVenta.forEach(function (value, key) {
          $('#'+value.IdTipoDocumento+'_TipoDocumento_StockProductoMarca').prop('checked', selectorTodos);
          tipoDocumentos.push(value.IdTipoDocumento);
        })
        var total = tipoDocumentosVenta.length;
        self.NumeroDocumentosSeleccionados(selectorTodos ?  total : 0);
        self.TiposDocumento(selectorTodos ? tipoDocumentos : []);
      }
    }

    self.SeleccionarComprobante = function (data, event) {
      if (event) {
        var selectorIndividual = $(event.target).prop('checked');
        var tipoDocumentos = ko.mapping.toJS(self.TiposDocumento);
        self.NumeroDocumentosSeleccionados(selectorIndividual ? self.NumeroDocumentosSeleccionados() + 1 : self.NumeroDocumentosSeleccionados() - 1);

        if (self.NumeroDocumentosSeleccionados() == self.TotalTipoDocumentos()) {
          $('#SelectorTipoDocumentos_StockProductoMarca').prop('checked', true);
        } else {
          $('#SelectorTipoDocumentos_StockProductoMarca').prop('checked', false);
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

    self.OnClickBtnReportes = function (data,event) {
      if (event) {
        var $data = {};
        $data.IdAsignacionSede = $('#Almacen_StockProductoMarca').val() == "" ? "%" : $('#Almacen_StockProductoMarca').val();
        $data.IdMarca = $('#Marca_StockProductoMarca').val() == "" ? "%" : $('#Marca_StockProductoMarca').val();
        $data.Orden = self.Orden_StockProductoMarca() == '0' ? "SaldoFisico desc" : "NombreProducto asc";
        $data.IdProducto = self.Producto_StockProductoMarca() == '0' ? "%" : $('#IdProducto_StockProductoMarca').val();
        $data.Fecha = self.Fecha_StockProductoMarca();
        $data.FechaActual = self.FechaHoy_StockProductoMarca();
        $data.FechaDeterminada = self.FechaDeterminada_StockProductoMarca();
        $data.NombreArchivoReporte = self.NombreArchivoReporte_StockProductoMarca();
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
          url: SITE_URL+'/Reporte/Inventario/cStockProductoMarca/'+nombre,
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
          url: SITE_URL+'/Reporte/Inventario/cStockProductoMarca/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_StockProductoMarca()+'.pdf"></iframe>';
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

var MappingStockProductoMarca = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_StockProductoMarca(options.data);
            }
    }
}
