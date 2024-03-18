var ImageURL = data.data.ImageURLCliente;
var _modo_nuevo = false;
var _opcion_guardar = 1;
var _objeto = null;
var _NombreEmpleado = "";
//DECLARACIONES PARA EMPLEADO
var ImageURLEmpleado = data.data.ImageURLEmpleado;
var _modo_nuevo_empleado = false;
var _opcion_guardar_empleado = 0;
var _objeto_empleado = "";


var Mapping = Object.assign(
  MappingUsuario,
  MappingEmpleado,
  MappingAccesoRol,
  MappingAccesoUsuario
);


Index = function (data) {
    var self = this;
    self.MostrarTitulo = ko.observable("");

    ko.mapping.fromJS(data, Mapping, self);

    self.NuevoUsuario = function(data,event) {
          //console.log("AgregarUsuario");
        if(event)
        {

          _objeto = Knockout.CopiarObjeto(Models.data.Usuario);
          _nuevo_objeto = ko.mapping.toJS(Models.data.NuevoUsuario);
          ko.mapping.fromJS(_nuevo_objeto, MappingUsuario, Models.data.Usuario);
          console.log("MOSTRANDO DATA NUEVO USUARIO");
          console.log(Models.data.Usuario);

          //LIMPIADOR DE IMAGENES A BLANCO

          Models.data.Usuario.LimpiarImagen(event);

          $("#NombreEmpleado").val("");
          $('#btn_LimpiarUsuario').text("Limpiar");
          $("#modalUsuario").modal("show");

          setTimeout(function(){
            $("#NombreUsuario").focus();
          }, 500);

          Models.MostrarTitulo("REGISTRAR USUARIO");
          _opcion_guardar = 0;
          _modo_nuevo = true;
        }

    }

    self.GuardarAccesoRol = function(data,event) {
          console.log("ActualizarAccesoRol");

          $("#loader").show();
          var objeto = Models.data.AccesosRol;

          var datajs = ko.mapping.toJS({"Data" : objeto}, mappingIgnore)
          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL +'/Configuracion/General/cAccesoRol/ActualizarAccesoRol',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarAccesoRol").prop("disabled",false);
                          console.log("ID5:"+_rol.IdAccesoRol());
                          _rol.Confirmar(null,event);

                          var id_rol = "#"+ _rol.IdAccesoRol();
                          self.HabilitarFilaInputAccesoRol(id_rol, false);

                          var idbutton ="#"+_rol.IdAccesoRol()+"_button_rol";
                          $(idbutton).hide();

                          //ACTUALIZANDO DATA Nombre
                          var idnombretiporol = '#' + _rol.IdAccesoRol() + '_input_IdTipoAccesoRol option:selected';
                          var nombretiporol = $(idnombretiporol).html();

                          _rol.NombreTipoAccesoRol(nombretiporol);

                          existecambio = false;
                          _input_habilitado = false;
                          _modo_nuevo = false;

                        }
                        else {
                          alertify.alert(data);
                        }

                    }

                    $("#loader").hide();
                },
                error : function (jqXHR, textStatus, errorThrown) {
                  //console.log(jqXHR.responseText);
                  $("#loader").hide();
                }
          });
    }

}
