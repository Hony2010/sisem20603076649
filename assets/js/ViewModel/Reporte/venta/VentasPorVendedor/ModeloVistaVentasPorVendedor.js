BuscadorModel_Vendedor = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.GrupoCliente_Vendedor = function(data,event){
      if (event)
      {
        if (data.NumeroDocumentoIdentidad_Vendedor()=='1')
        {
          $('#DivBuscar_Vendedor').hide();
          $('#TextoBuscar_Vendedor').attr('type','text');
          $('#TextoBuscar_Vendedor').focus();

        }
        else
        {
          $('#DivBuscar_Vendedor').show();
          $('#TextoBuscar_Vendedor').attr('type','hidden');
          $('#TextoBuscar_Vendedor').val('');

        }
      }
    }

    self.ParseDataReporte = function (data, event) {
      if (event) {
        var $data = {
          Vendedor: $('#Vendedor_Vendedor').val() == "" ? '%' : $('#Vendedor_Vendedor').val(),
          FechaInicio: self.FechaInicio_Vendedor(),
          FechaFinal: self.FechaFinal_Vendedor(),
          HoraInicio: self.HoraInicio_Vendedor(),
          HoraFinal: self.HoraFinal_Vendedor(),
          NombreArchivoJasper: self.NombreArchivoJasper_Vendedor(),
          NombreArchivoReporte: self.NombreArchivoReporte_Vendedor(),
          Vendedores: self.VendedoresSeleccionados(),
          IdAsignacionSede: self.IdAsignacionSede() == undefined ? '%' : self.IdAsignacionSede()
      }
        return $data;
      }
    }

    self.DescargarReporte_Vendedor = function (data,event) {
      if (event) {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";

        var datajs ={"Data":self.ParseDataReporte(data, event)};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cVentasPorVendedor/'+nombre,
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

    self.Pantalla_Vendedor = function (data,event) {
      if (event)
      {
        $("#loader").show();
        var btn  = $(event.target).attr("name");
        var nombre = (btn == "excel") ? "GenerarReporteEXCEL" : "GenerarReportePDF";
        var datajs ={"Data":self.ParseDataReporte(data, event)};

        document.getElementById("contenedorpdf").innerHTML="";
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Reporte/Venta/cVentasPorVendedor/GenerarReportePANTALLA',
          success: function (data) {
            $("#loader").hide();
            if(data != "")
            {
              document.getElementById("contenedorpdf").innerHTML='<iframe class="embed-responsive-item" src="'+SERVER_URL+'assets/reportes/'+self.NombreArchivoReporte_Vendedor()+'.pdf"></iframe>';
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

    self.SeleccionarTodosVendedores = function (data, event) {
      if (event) {
        var selectorTodos = $(event.target).prop('checked');
        var vendedores = ko.mapping.toJS(self.Vendedores);
        var vendedoresSeleccionados = [];
        vendedores.forEach(function (value, key) {
          $('#'+value.IdUsuario+'_Usuario').prop('checked', selectorTodos);
          vendedoresSeleccionados.push(value.AliasUsuarioVenta);
        })
        self.NumeroVendedoresSeleccionados(selectorTodos ? vendedores.length : 0);
        self.VendedoresSeleccionados(selectorTodos ? vendedoresSeleccionados : []);
      }
    }
  
    self.SeleccionarUsuario = function (data, event) {
      if (event) {
        var selectorIndividual = $(event.target).prop('checked');
        var vendedoresSeleccionados = ko.mapping.toJS(self.VendedoresSeleccionados);
        self.NumeroVendedoresSeleccionados(selectorIndividual ? self.NumeroVendedoresSeleccionados() + 1 : self.NumeroVendedoresSeleccionados() - 1);
  
        if (self.NumeroVendedoresSeleccionados() == self.TotalVendedores()) {
          $('#SelectorVendedores').prop('checked', true);
        } else {
          $('#SelectorVendedores').prop('checked', false);
        }
  
        if (selectorIndividual) {
          vendedoresSeleccionados.push(data.AliasUsuarioVenta());
        } else {
          vendedoresSeleccionados = vendedoresSeleccionados.filter(function(value) {
            return value != data.AliasUsuarioVenta();
          });
        }
        self.VendedoresSeleccionados(vendedoresSeleccionados);
      }
    }
}

var MappingVentasPorVendedor = {
    'Buscador': {
        create: function (options) {
            if (options)
              return new BuscadorModel_Vendedor(options.data);
            }
    }
}
