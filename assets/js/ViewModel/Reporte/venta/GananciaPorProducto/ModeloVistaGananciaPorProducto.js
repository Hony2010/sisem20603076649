BuscadorModel_Gananciaporproducto = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_Gananciaporproducto = function(data,event){
      if (event)
      {
        if (data.IdProducto_Gananciaporproducto()=='1')
        {
          $('#DivBuscar_Gananciaporproducto').hide();
          $('#TextoBuscar_Gananciaporproducto').attr('type','text');
          $('#TextoBuscar_Gananciaporproducto').focus();

        }
        else
        {
          $('#DivBuscar_Gananciaporproducto').show();
          $('#TextoBuscar_Gananciaporproducto').attr('type','hidden');
          $('#TextoBuscar_Gananciaporproducto').val('');

        }
      }
    }

    self.dataReporte = function(data, event) {
      if (event) {
        var $data = {};
        $data.IdAsignacionSede = $("#Alamacen_Gananciaporproducto").val() == "" ? "%" : $("#Alamacen_Gananciaporproducto").val();
        $data.FechaInicial = data.FechaInicio_Gananciaporproducto();
        $data.FechaFinal = data.FechaFinal_Gananciaporproducto();
        $data.IdProducto = data.IdProducto_Gananciaporproducto() == 0 ? "%" : data.TextoMercaderia_Gananciaporproducto();
        $data.NombreArchivoJasper = data.NombreArchivoJasper_Gananciaporproducto();
        $data.NombreArchivoReporte = data.NombreArchivoReporte_Gananciaporproducto();
        $data.Costos = 0;
        $data.IdAsignacionSede = self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()

        alertify.confirm("Reporte de Ganancia por Producto","Se calculará el Costo Promedio Ponderado, este proceso puede tardar varios minutos dependiendo de la información registrada, de lo contrario se tomará el último calculado. <br>¿Desea procesar la información?",function () {
          $data.Costos = 1;
          if ($(event.target).attr("name") == 'pantalla') {
            self.Pantalla_Gananciaporproducto($data, event);
          } else {
            self.DescargarReporte_Gananciaporproducto($data, event);
          }
        },function () {
          $data.Costos = 0;
          if ($(event.target).attr("name") == 'pantalla') {
            self.Pantalla_Gananciaporproducto($data, event);
          } else {
            self.DescargarReporte_Gananciaporproducto($data, event);
          }
        })

      }
    }

    self.DescargarReporte_Gananciaporproducto = function (data,event) {
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
          url: SITE_URL+'/Reporte/Venta/cGananciaPorProducto/'+nombre,
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

    self.Pantalla_Gananciaporproducto = function (data,event) {
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
          url: SITE_URL+'/Reporte/Venta/cGananciaPorProducto/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Gananciaporproducto()+'.pdf"></iframe>';
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

var MappingGananciaporproducto = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Gananciaporproducto(options.data);
            }
    }
}
