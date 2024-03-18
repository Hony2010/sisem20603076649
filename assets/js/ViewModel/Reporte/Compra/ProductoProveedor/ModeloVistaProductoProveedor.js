BuscadorModel_ProductoProveedor = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProveedor_ProductoProveedor = function(data,event){
      if (event)
      {
        if (data.IdProveedor_ProductoProveedor()=='1')
        {
          $('#DivBuscarProveedor_ProductoProveedor').hide();
          $('#TextoBuscarProveedor_ProductoProveedor').show();
          $('#TextoBuscarProveedor_ProductoProveedor').focus();

        }
        else
        {
          $('#DivBuscarProveedor_ProductoProveedor').show();
          $('#TextoBuscarProveedor_ProductoProveedor').hide();
          $('#TextoBuscarProveedor_ProductoProveedor').val('');

        }
      }
    }

    self.GrupoProducto_ProductoProveedor = function(data,event){
      if (event)
      {
        if (data.IdProducto_ProductoProveedor()=='1')
        {
          $('#DivBuscarMercaderia_ProductoProveedor').hide();
          $('#TextoBuscarMercaderia_ProductoProveedor').show();
          $('#TextoBuscar_Mercaderia_ProductoProveedor').focus();

        }
        else
        {
          $('#DivBuscarMercaderia_ProductoProveedor').show();
          $('#TextoBuscarMercaderia_ProductoProveedor').hide();
          $('#TextoBuscar_Mercaderia_ProductoProveedor').val('');

        }
      }
    }

    self.DescargarReporte_ProductoProveedor = function (data,event) {
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
          url: SITE_URL+'/Reporte/Compra/cReporteProductoProveedor/'+nombre,
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

    self.Pantalla_ProductoProveedor = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data":data});
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Compra/cReporteProductoProveedor/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_ProductoProveedor()+'.pdf"></iframe>';
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

var MappingProductoProveedor = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_ProductoProveedor(options.data);
            }
    }
}
