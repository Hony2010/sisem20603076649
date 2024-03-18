ConfiguracionImpresionModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.Editar = function(data, event)
    {
      if(event)
      {
        $("#opcion-canvas").addClass('active').siblings().removeClass('active');
        $("#canvas").addClass('active').siblings().removeClass('active');
        var objeto = Knockout.CopiarObjeto(data);
        ko.mapping.fromJS(objeto, Mapping, Models.data.Canvas);
        Models.data.Canvas.AjustarPlantilla(data, event);
        Models.data.Canvas.CargarJSON(data, event);
        $('#preview_canvas').show();
      }
    }
}

CanvasModel = function(data)
{
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self.AjustarPlantilla = function(data, event)
  {
    if(event)
    {
      var canvas_width = $("#canvas-width").val();
      var canvas_height = $("#canvas-height").val();
      canvas.setWidth(canvas_width);
      canvas.setHeight(canvas_height);
      canvas.renderAll();
      console.log(JSON.stringify(canvas));
    }
  }

  self.CambiarBackGround = function(data, event)
  {
    if(event)
    {
      var file =event.target.files[0];
      var filename = file.name;
      var id = event.target.attributes.id.value;
      var img = $("#img_background");
      readImageAsDataURL(file,img);
    }
  }

  self.AgregarFondoImagen = function(data, event)
  {
    if(event)
    {
      var data = $("#img_background").prop("src");

      fabric.Image.fromURL(data, function(img) {
           // add background image
           canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
              scaleX: canvas.width / img.width,
              scaleY: canvas.height / img.height
           });
        });
    }
  }

  self.QuitarFondoImagen = function(data, event)
  {
    if(event)
    {
      // add background image
      canvas.backgroundColor = null;
      canvas.backgroundImage = null;
      canvas.renderAll.bind(canvas)();
    }
  }

  self.AgregarTexto = function(data, event)
  {
    if(event)
    {
      var texto = $("#text_texto").val();
      var font = $("#font-family").val();
      var font_size = $("#text-font-size").val();
      var val_id = $("#text_id").val();
      var val_name = $("#text_name").val();
      var tipo = $("#tipo").val();
      var font_color = $("#text-font-color").val();
      var font_bold = "normal";
      var font_style = "normal";
      var font_underline = "";
      var font_linethrough = "";
      var font_overline = "";

      //VALIDACION
      if(tipo != 0 && val_name == "")
      {
        alertify.alert("No se puede añadir este tipo sin campo tabla.");
        $("#text_name").focus();
        return false;
      }

      if($('#text-cmd-bold').is(':checked')){
        font_bold = "bold";
      }
      if($('#text-cmd-italic').is(':checked')){
        font_style = "italic";
      }

      if($('#text_underline').is(':checked')){
        font_underline = "underline";
      }
      if($('#text_linethrough').is(':checked')){
        font_linethrough = "linethrough";
      }
      if($('#text_overline').is(':checked')){
        font_overline = "overline";
      }

      var textbox = new fabric.Textbox(texto, {
        left: 50,
        top: 50,
        lockScalingY: true,
        hasRotatingPoint: false,
        width: 150,
        fontSize: font_size,
        fontFamily: font,
        fontWeight: font_bold,
        fontStyle: font_style,
        underline: font_underline,
        linethrough: font_linethrough,
        overline: font_overline
      });

      textbox.setColor(font_color);

      textbox.toObject = (function(toObject) {
        return function() {
          return fabric.util.object.extend(toObject.call(this), {
            name: this.name,
            id: this.id,
            tipo: this.tipo
          });
        };
      })(textbox.toObject);

      textbox.id = val_id;
      textbox.name = val_name;
      textbox.tipo = tipo;

      console.log(textbox);
      //canvas.add(textbox).setActiveObject(textbox);
      canvas.add(textbox);
      //canvas.renderAll();
    }
  }

  self.BorrarObjeto = function(data, event)
  {
    if(event)
    {
      var activeObject = canvas.getActiveObject();
      if (activeObject) {
        alertify.confirm("Estas Seguro?", function(){
          canvas.remove(activeObject);
        });
      }
    }
  }

  self.CambiarImagen = function(data, event)
  {
    if(event)
    {
      var file =event.target.files[0];
      var filename = file.name;
      var id = event.target.attributes.id.value;
      var img = $("#img_image");
      readImageAsDataURL(file,img);
    }
  }

  self.AgregarImagen = function(data, event)
  {
    if(event)
    {
      var imgURL = $("#img_image").prop("src");
      var width_img = $("#image-width").val();
      var height_img = $("#image-height").val();

      fabric.Image.fromURL(imgURL, function(image) {

          image.set({
            hasRotatingPoint: false,
            width: parseInt(width_img),
            height: parseInt(height_img),
            left: 50,
            top: 70
          });

          image.toObject = (function(toObject) {
            return function() {
              return fabric.util.object.extend(toObject.call(this), {
                name: this.name,
                id: this.id,
                tipo: this.tipo
              });
            };
          })(image.toObject);

          image.id = "img";
          image.name = "image";
          image.tipo = "4";

          canvas.centerObject(image);
          canvas.add(image);
          canvas.renderAll();
      });

    }
  }

  self.ConsolaJSON = function(data, event)
  {
    if(event)
    {
      console.log(JSON.stringify(canvas));
    }
  }

  //AQUI CARGAMOS TODOS EL JSON
  self.CargarJSON = function(data, event)
  {
    if(event)
    {
      $("#loader").show();
      var archivo = data.RutaArchivoPlantillaCanvas();
      $.ajax({
        type: "POST",
        data: {"data": archivo},
        dataType: "json",
        url: SITE_URL+'/Configuracion/General/cConfiguracionImpresion/CargarJSON',
        success: function(respuesta) {
          if(respuesta != "")
          {
            console.log(respuesta);

            fabric.Text.prototype.toObject = (function(toObject) {
              return function() {
                return fabric.util.object.extend(toObject.call(this), {
                  name: this.name,
                  id: this.id,
                  tipo: this.tipo
                });
              };
            })(fabric.Text.prototype.toObject);

            fabric.Image.prototype.toObject = (function(toObject) {
              return function() {
                return fabric.util.object.extend(toObject.call(this), {
                  name: this.name,
                  id: this.id,
                  tipo: this.tipo
                });
              };
            })(fabric.Image.prototype.toObject);

            canvas.loadFromJSON(respuesta, function() {
               canvas.renderAll();
            },function(o,object){
               console.log(o,object);
               if(object.type == "image"){
                 object.set({
                   hasRotatingPoint: false
                 });
               }
               else{
                 object.set({
                   lockScalingY: true,
                   hasRotatingPoint: false
                 });
               }
            })

          }
          $("#loader").hide();
        },
        error: function() {
              console.log("No se ha podido obtener la información");
              $("#loader").hide();
          }
      });
    }
  }

  self.RenderizarJSON = function(data, event)
  {
    if(event)
    {
      $("#loader").show();
      var archivo = data.RutaArchivoConfiguracionImpresion();
      $.ajax({
        type: "POST",
        data: {"data": archivo},
        dataType: "json",
        url: SITE_URL+'/Configuracion/General/cConfiguracionImpresion/RenderizarJSONImpresion',
        success: function(respuesta) {
          console.log(respuesta);
          $("#loader").hide();
        },
        error: function() {
              console.log("No se ha podido obtener la información");
              $("#loader").hide();
          }
      });

    }
  }

  self.ImprimirArchivoJSON = function(data, event)
  {
    if(event)
    {
      $("#loader").show();
      $.ajax({
        type: "POST",
        data: {"data": ""},
        dataType: "json",
        url: SITE_URL+'/Configuracion/General/cConfiguracionImpresion/ImprimirJSON',
        success: function(respuesta) {
          console.log(respuesta);
          $("#loader").hide();
        },
        error: function() {
              console.log("No se ha podido obtener la información");
              $("#loader").hide();
          }
      });
    }
  }

  self.GuardarArchivoJSON = function(data, event)
  {
    if(event)
    {
      $("#loader").show();
      var data_json = JSON.stringify(canvas);
      data_json = JSON.parse(data_json);

      data_json["witdh"] = data.AnchoHoja();
      data_json["heigth"] = data.AltoHoja();
      data_json["archivoimpresion"] = data.RutaArchivoConfiguracionImpresion();
      data_json["archivocanvas"] = data.RutaArchivoPlantillaCanvas();
      data_json["watermark"] = data.RutaMarcaAgua();
      data_json["datos_json"] = JSON.stringify(canvas);

      $.ajax({
        type: "POST",
        data: {"data": data_json},
        dataType: "json",
        url: SITE_URL+'/Configuracion/General/cConfiguracionImpresion/CrearJSONCanvas',
        success: function(respuesta) {
          if(respuesta.msg != "")
          {
            alertify.alert(respuesta.msg);
          }
          console.log(respuesta.data);
          $("#loader").hide();
        },
        error: function() {
            console.log("No se ha podido obtener la información");
            $("#loader").hide();
          }
      });
    }
  }


}


var MappingConfiguracionImpresion = {
    'ConfiguracionImpresion': {
        create: function (options) {
            if (options)
              return new ConfiguracionImpresionModel(options.data);
            }
    },
    'Canvas': {
        create: function (options) {
            if (options)
              return new CanvasModel(options.data);
            }
    }
}
