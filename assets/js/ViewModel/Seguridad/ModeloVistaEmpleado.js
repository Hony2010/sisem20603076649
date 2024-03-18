EmpleadoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.OnChangeInputFile = function(data,event){
         if(event)
         {
             var file =event.target.files[0];
             var filename = file.name;
             var id = event.target.attributes.id.value;
             var img = $("#img_"+id);
             readImageAsDataURL(file,img);

             var nombre_foto = filename;
             var _filename = "";
             if(nombre_foto != null || nombre_foto != ""){
               _filename = nombre_foto.split(" ").join("_");
             }

             data.Foto(_filename);

         }
     }

    self.AbrirPreview = function(data,event){

          if(event)
          {
              var img = event.target;
              var dataURL = img.src;

              if(  dataURL != '')
              {
                  $("#foto_previa").attr('src',dataURL);
                  $("#modalPreview").modal("show");
              }
          }
      }


      /*EMPLEADO - EMPLEADO - EMPLEADO*/
      self.AgregarEventoExterno = function(data, event){
        if(event)
        {
          self.EventoExterno = data;
        }
      }

      self.CargarEmpleado = function(data, event) {
        if(event)
        {
          var datajs = ko.toJS({"Data": data.IdEmpleado});

          $.ajax({
              method: 'POST',
              data : datajs,
              dataType: 'json',
              url: SITE_URL+'/Catalogo/cEmpleado/ListarEmpleadosPorId',
              success: function(data) {
                  if(data != null)
                  {
                    //self.EventoExterno(data[0], event);
                    Models.data.Usuario.CargarEmpleado(data[0], event);
                  }
                  else
                  {
                    alertify.alert("Seleccione un Empleado");
                  }
              }
            });
        }

      }

       self.SubirFoto = function(data, event) {
         if(event){
           var modal = document.getElementById("form");
           console.log($("#IdEmpleado").val());
           var data = new FormData(modal);

           $.ajax({
               type: 'POST',
               data : data,
               contentType: false,       // The content type used when sending data to the server.
               cache: false,             // To unable request pages to be cached
               processData: false,        // To send DOMDocument or non processed data file it is set to false
               mimeType: "multipart/form-data",
               url: SITE_URL+'/Catalogo/cEmpleado/SubirFoto',
               success: function (data) {

                   var copia_objeto = Knockout.CopiarObjeto(Models.data.Empleado);
                   alertify.confirm("¿Desea seguir agregando nuevos registros?", function(){
                     ko.mapping.fromJS(Models.data.NuevoEmpleado, {}, Models.data.Empleado);
                     document.getElementById("form").reset();
                     self.LimpiarImagen(event);
                     setTimeout(function(){
                       $("#NumeroDocumentoIdentidad").focus();
                     }, 500);
                   }, function(){
                     _modo_nuevo == false;
                     $("#modalEmpleado").modal("hide");
                   });

               }
           });
         }

        }

       self.Guardar =function(data,event){
         if(event)
         {

           var accion = "InsertarEmpleado";
           Models.data.Empleado.RazonSocial($("#RazonSocial").val());

           var datajs = ko.toJS({"Data": Models.data.Empleado});
           console.log(datajs);
           console.log(data);
           $.ajax({
             type: 'POST',
             data : datajs,
             dataType: "json",
             url: SITE_URL+'/Catalogo/cEmpleado/' + accion,
             success: function (data) {
                 console.log(data);

                   if($.isNumeric(data.IdEmpleado))
                   {
                     var objeto = Knockout.CopiarObjeto(data);
                     objeto = new EmpleadoModel(data);
                     ko.mapping.fromJS(objeto, MappingEmpleado, Models.data.Empleado);

                     self.SubirFoto(data, event);
                   }
                   else {
                     alertify.alert(data.IdEmpleado);
                     $("#NumeroDocumentoIdentidad").focus();
                   }

                 //console.log(match);
             }
           });
         }

       }

       self.ChangeRazonSocial = function(data,event)
       {
         if(event)
         {
           var IdTipoPersona = data.IdTipoPersona();
           if((IdTipoPersona == 2) || (IdTipoPersona == 3)) //Persona Judirica || No Domiciliado
           {
             var NombreCompleto = $("#NombreCompleto").val();
             var ApellidoCompleto = $("#ApellidoCompleto").val();
             var RazonSocial = ApellidoCompleto+' '+NombreCompleto;
             $("#RazonSocial").val(RazonSocial);
           }
         }
       }

       self.ChangeTipoPersona = function(data,event)
       {
         if(event)
         {
           var IdTipoPersona = data.IdTipoPersona();
           if(IdTipoPersona == 1) //Persona Judirica
           {
             $("#NombreCompleto").val("");
             $("#NombreCompleto").prop("disabled",true);
             $("#ApellidoCompleto").val("");
             $("#ApellidoCompleto").prop("disabled",true);
             $("#RazonSocial").prop("disabled",false);
             //$("#RazonSocial").val("");

           }
           else if((IdTipoPersona == 2) || (IdTipoPersona == 3) ) //Persona Natural || No Domiciliado
           {
             $("#NombreCompleto").prop("disabled",false);
             $("#ApellidoCompleto").prop("disabled",false);
             $("#RazonSocial").prop("disabled",true);
             $("#RazonSocial").val("");
           }
         }
       }

       self.LimpiarImagen = function(event){
         if(event){
           var src= BASE_URL + CARPETA_IMAGENES + "nocover.png";
           $('#img_FileFoto').attr('src', src);
           $('#img_FileFotoPreview').attr('src', src);
         }
       }

       self.Deshacer = function(event)
       {
         if(event)
         {
           if($('#btn_LimpiarEmpleado').text() == "Limpiar")
           {
             ko.mapping.fromJS(Models.data.NuevoEmpleado, {}, Models.data.Empleado);
             document.getElementById("form").reset();
             self.LimpiarImagen(event);
             $('#combo-tipopersona').val(2);
             $('#combo-tipopersona').prop("disabled", true);
             setTimeout(function(){
               $("#NumeroDocumentoIdentidad").focus();
             }, 500);
           }

         }
       }

       self.Cerrar = function(event)
       {
         if(event)
         {
           if(_modo_nuevo == true){
             _modo_nuevo = false;
           }
           $("#modalEmpleado").modal("hide");

         }
       }
}


var MappingEmpleado = {
  'Empleado': {
      create: function (options) {
          if (options)
            return new EmpleadoModel(options.data);
          }
  },
  'NuevoEmpleado': {
      create: function (options) {
          if (options)
            return new EmpleadoModel(options.data);
          }
  }
}
