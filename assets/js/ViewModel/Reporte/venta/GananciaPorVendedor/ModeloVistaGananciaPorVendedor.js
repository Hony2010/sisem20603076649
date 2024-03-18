BuscadorModel_Gananciaporvendedor = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoProducto_Gananciaporvendedor = function(data,event){
      if (event)
      {
        if (data.IdProducto_Gananciaporvendedor()=='1')
        {
          $('#DivBuscar_Gananciaporvendedor').hide();
          $('#TextoBuscar_Gananciaporvendedor').attr('type','text');
          $('#TextoBuscar_Gananciaporvendedor').focus();

        }
        else
        {
          $('#DivBuscar_Gananciaporvendedor').show();
          $('#TextoBuscar_Gananciaporvendedor').attr('type','hidden');
          $('#TextoBuscar_Gananciaporvendedor').val('');

        }
      }
    }

    self.dataReporte = function(data, event) {
      if (event) {
        var $data = {};
        $data.IdSede = $("#Agencia_Gananciaporvendedor").val() == "" ? "%" : $("#Agencia_Gananciaporvendedor").val();
        $data.FechaInicial = data.FechaInicio_Gananciaporvendedor();
        $data.FechaFinal = data.FechaFinal_Gananciaporvendedor();
        $data.IdProducto = data.IdProducto_Gananciaporvendedor() == 0 ? "%" : data.TextoMercaderia_Gananciaporvendedor();
        $data.AliasVendedor = $("#Vendedor_Gananciaporvendedor").val() == "" ? "%" : $("#Vendedor_Gananciaporvendedor").val();
        $data.NombreArchivoJasper = data.NombreArchivoJasper_Gananciaporvendedor();
        $data.NombreArchivoReporte = data.NombreArchivoReporte_Gananciaporvendedor();
        $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
        if ($(event.target).attr("name") == 'pantalla') {
          self.Pantalla_Gananciaporvendedor($data, event);
        } else {
          self.DescargarReporte_Gananciaporvendedor($data, event);
        }
      }
    }

    self.DescargarReporte_Gananciaporvendedor = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        var datajs = {"Data":data};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cGananciaPorVendedor/'+nombre,
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

    self.Pantalla_Gananciaporvendedor = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var objeto = data;
        var datajs = {"Data":data};
        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cGananciaPorVendedor/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Gananciaporvendedor()+'.pdf"></iframe>';
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

var MappingGananciaporvendedor = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Gananciaporvendedor(options.data);
            }
    }
}
