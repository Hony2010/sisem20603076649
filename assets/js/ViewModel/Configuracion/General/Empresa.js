EmpresaModel = function (data) {
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

             data.Logotipo(_filename);

         }
     }

     self.OnFocus = function(data,event,callback) {
       if(event)  {
           $(event.target).select();
           if(callback) callback(data,event);
       }
     }

     self.OnKeyEnter = function(data,event) {
       if(event) {
         var resultado = $(event.target).enterToTab(event);
         return resultado;
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


}


var Mapping_e = {
    'Empresa': {
        create: function (options) {
            if (options)
              return new EmpresaModel(options.data);
            }
    }
}

jQuery.isSubstring = function (haystack, needle) {
  return haystack.indexOf(needle) !== -1;

};

var ImageURL;

IndexEmpresa = function (data) {
    //var _option = false;
    //var _input_habilitado = false;
    //var _modo_nuevo = false;
    //var _codigo_evento_previo = 0;
    //var _opcion_guardar = 1;
    var self = this;

    //var ModelsSubFamilia = new IndexSubFamilia(data);

    ImageURL = data.dataEmpresa.ImageURL;
    //Id_Empresa = data.data.Id_Empresa;

    ko.mapping.fromJS(data, Mapping_e, self);

    var _objeto = Knockout.CopiarObjeto(self.dataEmpresa.Empresa);

    self.CargarFoto = function(data) {
         var src = "";
         console.log(data.IdEmpresa());

         //console.log("ID PRODUCTO: " + data.IdProducto() + "  ., FOTO NOMBRE: " + data.Foto())
         if (data.IdEmpresa()=="" || data.IdEmpresa() == null || data.Logotipo() == null || data.Logotipo() == "")
             src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
         else
             src=SERVER_URL + CARPETA_IMAGENES + CARPETA_EMPRESA+data.IdEmpresa()+SEPARADOR_CARPETA+data.Logotipo();

         return src;
     }

    self.Consultar = function() {
      
    }

    self.SubirFoto = function() {

          var modal = document.getElementById("form");
          console.log($("#IdProducto").val());
          var data = new FormData(modal);

          $.ajax({
              type: 'POST',
              data : data,
              contentType: false,       // The content type used when sending data to the server.
              cache: false,             // To unable request pages to be cached
              processData: false,        // To send DOMDocument or non processed data file it is set to false
              mimeType: "multipart/form-data",
              url: SITE_URL+'/Configuracion/General/cEmpresa/SubirFoto',
              success: function (data) {
                  //console.log(data);
                  //Models.Guardar();
                  //ACTUALIZANDO LA FILA EN MERADERIAS
                  _objeto = Knockout.CopiarObjeto(vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa);
                  alertify.alert("Se modificaron correctamente los datos.");
              },
              error : function (jqXHR, textStatus, errorThrown) {
                //console.log(jqXHR.responseText);
                $("#loader").hide();
              }
          });
      }

    self.Guardar =function(data,event){
      if(event)
      {
        //debugger;
        /*var _filename = vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.Foto();
        _filename = _filename.replace(' ', '_');
        vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.Foto(_filename);
        console.log("NAME REPLACE: " + _filename);*/
        if(!($("#loader").css('display') == 'none'))
        {
          event.preventDefault();
          return false;
        }
        $("#loader").show();
        var datajs = ko.toJS({"Data": vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa});
        console.log(datajs);
        console.log(data);
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Configuracion/General/cEmpresa/ActualizarEmpresa',
          success: function (data) {
              console.log(data);
                if(data == "")
                {
                  ko.mapping.fromJS(data, Mapping, vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa);
                  //console.log("ID PRODUCTO" + data.IdProducto);
                  self.SubirFoto();
                }
                else {
                  alertify.alert(data);
                }

              $("#loader").hide();
              //console.log(match);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }
        });
      }

    }

    self.Deshacer = function(event)
    {
      ko.mapping.fromJS(_objeto, Mapping, vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa);

      // $("#combo-gironegocio").selectpicker("refresh");
      // $("#combo-regimentributario").selectpicker("refresh");

      var src;
      if (vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.IdEmpresa()=="" || vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.IdEmpresa() == null || vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.Logotipo() == null || vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.Logotipo() == "")
      {
        src= BASE_URL + CARPETA_IMAGENES + "nocover.png";
      }
      else
      {
        src=SERVER_URL + CARPETA_IMAGENES + CARPETA_EMPRESA+vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.IdEmpresa()+SEPARADOR_CARPETA+vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.Logotipo();
      }
      $('#img_FileFoto').attr('src', src);
    }


}
