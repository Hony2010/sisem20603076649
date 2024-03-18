BuscadorModel_D = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_D = function(data,event){
      if (event)
      {
        /*
        if (data.NumeroDocumentoIdentidad_D()=='1')
        {
          $('#DivBuscar_D').hide();
          $('#TextoBuscar_D').show();
          $('#TextoBuscar_D').focus();

        }
        else
        {
          $('#DivBuscar_D').show();
          $('#TextoBuscar_D').hide();
          $('#TextoBuscar_D').val('');

        }*/
      }
    }

    self.DescargarReporte_D = function (data,event) {
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
          url: SITE_URL+'/Reporte/Catalogo/cListaClientes/'+nombre,
          success: function (data) {
            $("#loader").hide();
            if (data.error == "") {
              window.location = data.url;
            }
            else {
              alertify.alert("Error en reporte listado clientes", data.error)
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert("Ha ocurrido un error",jqXHR.responseText);
          }
        });
      }
    }


    self.Pantalla_D = function (data,event) {
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
          url: SITE_URL+'/Reporte/Catalogo/cListaClientes/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_D()+'.pdf"></iframe>';
              $('#modalReporteVistaPrevia').modal('show');
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            alertify.alert("Ha ocurrido un error",jqXHR.responseText);
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

var MappingListaClientes = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_D(options.data);
            }
    }
}
