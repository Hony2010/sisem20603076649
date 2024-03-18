

function readImageURL(input, output) {

    if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
            //output.attr('href', e.target.result);
            child = output.find("img");
            child.attr('src', e.target.result);
            //return e.target.result;
        }

        reader.readAsDataURL(input.files[0]);
    }
}

function CargarImagen (input,output) {

    readImageURL(input,output);
};

/*********************************************** */

function readImageAsDataURL(input, image) {

    if (input) {
        var reader = new FileReader();

        reader.onload = function (e) {

            image.attr('src', e.target.result);
        }

        reader.readAsDataURL(input);
    }
}


jQuery.fn.extend({
  readAsImage : function(event,callback) {
    var file =event.target.files[0];

    if(file)  {
      var filename = file.name;

      var filenamefoto = "";
      if(filename != null || filename != "")  {
        filenamefoto = filename.split(" ").join("_");
      }

      var sizeByte = file.size;
      var sizeKiloByte = parseInt(sizeByte / 1024);
      var sizeMegaByte = parseInt(sizeKiloByte / 1024);

      if(sizeKiloByte > TAMANO_MAXIMO_IMAGEN_KB ) {
        alertify.alert("Error en Validación de Imagen",'La imagen que trata de subir, tiene un tamaño de '+ sizeMegaByte +'MB que supera el limite permitido (Maximo '+TAMANO_MAXIMO_IMAGEN_MB+'MB)',function(){
          callback(undefined);
        });
      }
      else {
        var reader = new FileReader();
        reader.onload = function (e) {
            var data = { filename : filenamefoto, source : e.target.result };
            callback(data);
        };

        reader.readAsDataURL(file);
      }
    }
    else {
      callback(undefined);
    }
  }
});
