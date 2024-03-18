var ImageURLEmpleado = data.data.ImageURLEmpleado;
var _modo_nuevo_empleado = false;
var _opcion_guardar_empleado = 1;
var _objeto_empleado = null;
var _NombreEmpleado = "";

var Mapping = Object.assign(
  MappingEmpleado
);


Index = function (data) {
    var self = this;

    ko.mapping.fromJS(data, Mapping, self);

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ConsultarEmpleadosPorPagina(data,event,self.PostConsultarPorPagina);
        $("#Paginador").pagination("drawPage", data.pagina);
      }
    }
    
    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        self.data.Empleados([]);
        ko.utils.arrayForEach(data, function (item) {
          self.data.Empleados.push(new EmpleadosModel(item));
        });

        var objeto = Models.data.Empleados()[0];
        var _seleccionar_objeto = new EmpleadosModel(_objeto_empleado);
        _seleccionar_objeto.Seleccionar(objeto, event);
      }
    }

    self.ConsultarEmpleadosPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Catalogo/cEmpleado/ConsultarEmpleadosPorPagina',
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

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          var inputs = $(event.target).closest('formEmpleado').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();

          self.ConsultarEmpleados(data,event,self.PostConsultar);
        }
      }
    }

    self.ConsultarEmpleados = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Catalogo/cEmpleado/ConsultarEmpleados',
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

    self.PostConsultar = function (data,event) {
      if(event) {
        self.data.Empleados([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          self.data.Empleados.push(new EmpleadosModel(item));
        });

        var objeto = Models.data.Empleados()[0];
        if (objeto != undefined) {
          objeto.Seleccionar(objeto, event);
        }
        //ko.mapping.fromJS(data.Filtros,{},self.data.Filtros);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.data.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }

    self.NuevoEmpleado = function(data, event) {
          //console.log("AgregarFamiliaProducto");
      if(event)
      {
        Models.data.Empleado.IdTipoDocumentoIdentidad(ID_TIPO_DOCUMENTO_IDENTIDAD_DNI);
        _objeto_empleado = Knockout.CopiarObjeto(Models.data.Empleado);
        var objeto = Knockout.CopiarObjeto(Models.data.NuevoEmpleado);
        ko.mapping.fromJS( objeto, {}, Models.data.Empleado);

        var src= BASE_URL + CARPETA_IMAGENES + "nocover.png";
        $('#img_FileFoto').attr('src', src);

        $('#btn_LimpiarEmpleado').text("Limpiar");
        //document.getElementById('btn_LimpiarEmpleado').textContent = "Limpiar";
        $("#modalEmpleado").modal("show");

        setTimeout(function(){
          $("#IdEmpleado").focus();
        }, 1000);

        _opcion_guardar_empleado = 0;
        _modo_nuevo_empleado = true;

        // Models.data.Empleado.ChangeBtnBusqueda(data);
        Models.data.Empleado.EstaProcesado(false);
        Models.data.Empleado.opcionProceso(opcionProceso.Nuevo);
        Models.data.Empleado.InicializarVistaModelo(data, event);
      }
    }

}
