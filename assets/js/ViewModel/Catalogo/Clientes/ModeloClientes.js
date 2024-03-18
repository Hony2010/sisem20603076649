ModeloClientes = function (data) {
    var self = this;
    var base = data;

    self.copiatextofiltro = ko.observable("");

    self.ListarClientes =function(data,event,callback) {
      if(event) {
        self.ConsultarClientes(data,event,function($data,$event){
          base.data.Clientes([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.Clientes.push(new VistaModeloCliente(item));
          });

          callback($data,$event);
        });
      }
    }

    self.ListarClientesPorPagina =function(data,event,callback) {
      if(event) {
        self.ConsultarClientesPorPagina(data,event,function($data,$event){
          base.data.Clientes([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.Clientes.push(new VistaModeloCliente(item));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarClientes = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Catalogo/cClientes/ConsultarClientes',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.ConsultarClientesPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Catalogo/cClientes/ConsultarClientesPorPagina',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.ConsultarAlumno = function(data, event) {
      if (event) {
        if(data.IdPersona() != null)
        {
          $("#loader").show();
          var datajs = ko.toJS({"Data":data});
          $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cAlumno/ConsultarAlumno',
            success: function (data) {
              $("#loader").hide();
              if (data != null) {
                console.log(data);
                ViewModels.data.Alumnos([]);
                ko.utils.arrayForEach(data, function (item) {
                  ViewModels.data.Alumnos.push(new AlumnosModel(item));
                });

                $('#tabla-subfamiliaproducto tbody tr:first').addClass('active');
                $("#modalAlumno").modal("show");
                ViewModels.data.Alumno.SeleccionarAlumno(ViewModels.data.Alumnos()[0], event);
              }
            }
          });
        }
      }
    }
}
