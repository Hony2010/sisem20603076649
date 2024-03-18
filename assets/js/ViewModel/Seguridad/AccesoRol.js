var Mapping = Object.assign(
  MappingAccesoRol
);

Index = function (data) {
  var self = this;

  ko.mapping.fromJS(data, Mapping, self);
  //self.Errores = ko.validation.group(self, { deep: true });

  self.ListarAccesosRol = function() {

      var id_rol = $("#combo-rolempresa").val();
      var datajs = {Data: JSON.stringify({"IdRol": id_rol})};
      $.ajax({
              type: 'POST',
              data: datajs,
              dataType: "json",
              url: SITE_URL+'/Seguridad/cMenu/CargarOpcionesPorRol',
              success: function (data) {
                  if (data != null) {
                      console.log(data);
                      self.data.AccesosRol([]);
                      ko.utils.arrayForEach(data, function (item) {
                          self.data.AccesosRol.push(new AccesosRolModel(item));
                  });
              }
          }
      });
  }


  self.GuardarAccesoRol = function(data,event) {
        console.log("ActualizarAccesoRol");

        $("#loader").show();
        var objeto = Models.data.AccesosRol;
        var id_rol = $("#combo-rolempresa").val();

        var copiaObjeto = ko.mapping.toJS(objeto, mappingIgnore)
        var datajs = {Data: JSON.stringify(copiaObjeto), "IdRol" : id_rol};
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Seguridad/cAccesoRol/ActualizarAccesoRol',
                success: function (data) {
                    if (data != null) {
                      console.log(data);

                      if (data == "")
                      {
                        //deshabilitar campo origen
                        alertify.alert("Los datos se guardaron correctamente.", function(){alertify.alert().destroy();})
                        // $("#btnAgregarAccesoRol").prop("disabled",false);
                        // objeto.Confirmar(null,event);
                        //
                        // var id_rol = "#"+ _rol.IdAccesoRol();
                        // self.HabilitarFilaInputAccesoRol(id_rol, false);
                        //
                        // var idbutton ="#"+_rol.IdAccesoRol()+"_button_rol";
                        // $(idbutton).hide();
                        //
                        // //ACTUALIZANDO DATA Nombre
                        // var idnombretiporol = '#' + _rol.IdAccesoRol() + '_input_IdTipoAccesoRol option:selected';
                        // var nombretiporol = $(idnombretiporol).html();
                        //
                        // _rol.NombreTipoAccesoRol(nombretiporol);
                        //
                        // existecambio = false;
                        // _input_habilitado = false;
                        // _modo_nuevo = false;

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
