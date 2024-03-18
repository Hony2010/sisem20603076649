<script>
  var SITE_URL = "<?php echo site_url(); ?>";

  var base_url = "<?php echo base_url();?>";
  var data=<?php echo json_encode($data); ?>
</script>

<script>
  var existecambio = false;
</script>

<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/ModeloVistaConfiguracionImpresion.js"></script>
<script src="<?php echo base_url()?>assets/js/ViewModel/Configuracion/General/ConfiguracionImpresion.js"></script>

<script>
    var Models = new Index(data);
    ko.applyBindingsWithValidation(Models);
    //var Models2 = new IndexEmpleado(data);
    //ko.applyBindings(Models2, document.getElementById("modalEmpleado"));
</script>
<script type="text/javascript">
  var canvas = new fabric.Canvas('c');
  // Define an array with all fonts
  canvas.on('mouse:down', function(e) {
    //e.target.set('fill', 'red');
    //canvas.renderAll();
    if(e.target != null)
    {
      console.log(e.target);

    }
  });

  canvas.on('mouse:out', function(e) {
    //e.target.set('fill', 'green');
    //canvas.renderAll();
  });

  // canvas moving limit
  canvas.on('object:moving', function (e) {
          var obj = e.target;
           // if object is too big ignore
          if(obj.currentHeight > obj.canvas.height || obj.currentWidth > obj.canvas.width){
              return;
          }
          obj.setCoords();
          // top-left  corner
          if(obj.getBoundingRect().top < 0 || obj.getBoundingRect().left < 0){
              obj.top = Math.max(obj.top, obj.top-obj.getBoundingRect().top);
              obj.left = Math.max(obj.left, obj.left-obj.getBoundingRect().left);
          }
          // bot-right corner
          if(obj.getBoundingRect().top+obj.getBoundingRect().height  > obj.canvas.height || obj.getBoundingRect().left+obj.getBoundingRect().width  > obj.canvas.width){
              obj.top = Math.min(obj.top, obj.canvas.height-obj.getBoundingRect().height+obj.top-obj.getBoundingRect().top);
              obj.left = Math.min(obj.left, obj.canvas.width-obj.getBoundingRect().width+obj.left-obj.getBoundingRect().left);
          }
  });
  // end canvas moving limit

  //console.log(JSON.stringify(canvas));
  function Ajustar()
  {
    var canvas_width = $("#canvas-width").val();
    var canvas_height = $("#canvas-height").val();
    canvas.setWidth(canvas_width);
    canvas.setHeight(canvas_height);
    canvas.renderAll();
    console.log(JSON.stringify(canvas));
  }

  function Addtext(){
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
      alert("No se puede añadir este tipo sin campo tabla.");
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

  function json_load()
  {
    console.log(JSON.stringify(canvas));
  }

  function SetImage()
  {
    //var image = $("#image");
    //console.log(image);
    var data = $("#img_background").prop("src");

    fabric.Image.fromURL(data, function(img) {
         // add background image
         canvas.setBackgroundImage(img, canvas.renderAll.bind(canvas), {
            scaleX: canvas.width / img.width,
            scaleY: canvas.height / img.height
         });
      });
      /*var img = new Image();
      img.onload = function(){
         canvas.setBackgroundImage(img.src, canvas.renderAll.bind(canvas), {
                  originX: 'left',
                  originY: 'top',
                  left: 0,
                  top: 0
              });
      };
      img.src = $("#img_background").prop("src");*/

  }

  function ChangeImage()
  {
    var file =event.target.files[0];
    var filename = file.name;
    var id = event.target.attributes.id.value;
    var img = $("#img_background");
    //alert(img);
    readImageAsDataURL(file,img);
  }

  function ChangeImages()
  {
    var file =event.target.files[0];
    var filename = file.name;
    var id = event.target.attributes.id.value;
    var img = $("#img_image");
    //alert(img);
    readImageAsDataURL(file,img);
  }

  canvas.on("object:scaling", function(e) {
    console.log("Scaling_image");
      var target = e.target;
      if (!target || target.type !== 'image') {
          return;
      }
      var sX = target.scaleX;
      var sY = target.scaleY;
      target.width *= sX;
      target.height *= sY;
      target.scaleX = 1;
      target.scaleY = 1;
  });

  function InsertarImagen()
  {
    var imgURL = $("#img_image").prop("src");
    //var pugImg = new Image();
    var width_img = $("#image-width").val();
    var height_img = $("#image-height").val();

    fabric.Image.fromURL(imgURL, function(image) {
        //var scale = 200 / image.width;
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
    /*pugImg.onload = function (img) {
        var pug = new fabric.Image(pugImg, {
            hasRotatingPoint: false,
            width: parseInt(width_img),
            height: parseInt(height_img),
            left: 50,
            top: 70
        });

        pug.toObject = (function(toObject) {
          return function() {
            return fabric.util.object.extend(toObject.call(this), {
              name: this.name,
              id: this.id,
              tipo: this.tipo
            });
          };
        })(pug.toObject);

        pug.id = "img";
        pug.name = "image";
        pug.tipo = "4";
        //pug.src = imgURL;
        canvas.add(pug);
    };

    pugImg.src = imgURL;*/


  }

  function NotSetImage()
  {
     // add background image
     canvas.backgroundColor = null;
     canvas.backgroundImage = null;
     canvas.renderAll.bind(canvas)();
  }


  // select all objects
  function deleteObjects(){
    var activeObject = canvas.getActiveObject();
    //var activeGroup = canvas.getActiveObjects();
      if (activeObject) {
          if (confirm('Estas Seguro?')) {
              canvas.remove(activeObject);
          }
      }
      /*else if (activeGroup) {
          if (confirm('Are you sure?')) {
              var objectsInGroup = activeGroup.getObjects();
              canvas.discardActiveGroup();
              objectsInGroup.forEach(function(object) {
              canvas.remove(object);
              });
          }
      }*/
  }

  function EnviarDatos()
  {
    var data_json = JSON.stringify(canvas);
    data_json = JSON.parse(data_json);

    data_json["witdh"] = $("#canvas-width").val();
    data_json["heigth"] = $("#canvas-height").val();
    data_json["datos_json"] = JSON.stringify(canvas);

    //alert(SITE_URL+'/cCanvas/CrearJSONCanvas');
    $.ajax({
      type: "POST",
      data: {"data": data_json},
      url: SITE_URL+'/Seguridad/cCanvas/CrearJSONCanvas',
      success: function(respuesta) {
        if(respuesta.msg != "")
        {
          alert(respuesta.msg);
        }
        console.log(respuesta.data);
      },
      error: function() {
          console.log("No se ha podido obtener la información");
        }
    });
  }

  //BORANDO CON SUPRIMIR
  window.addEventListener("keydown", event =>
  {
      // delete
      if(event.keyCode === 46)
      {
        event.preventDefault();

        // this.canvas.preserveObjectStacking = false;
        if (confirm('Estas Seguro?')) {
          var selection = canvas.getActiveObjects();
          canvas.discardActiveObject();
          selection.forEach(obj => canvas.remove(obj));

          // this.canvas.preserveObjectStacking = true;
          canvas.renderAll();
        }

      }
  });

</script>

<script type="text/javascript">
// GROUP ON SELECTION
$('#objAlignLeft').click(function() {
  var activeObj = canvas.getActiveObject();
  var activeObjs = canvas.getActiveObjects();
  activeObjs.forEach(function(obj) {
    var itemWidth = obj.getBoundingRect().width;
    var itemHeight = obj.getBoundingRect().height;

    // ================================
    // OBJECT ALIGNMENT: " H-LEFT "
    // ================================
    obj.set({
      left: -(activeObj.width / 2),
      originX: 'left'
    });
    obj.setCoords();
    canvas.renderAll();

  });

});

$('#objAlignCenter').click(function() {
  var activeObj = canvas.getActiveObject();
  var activeObjs = canvas.getActiveObjects();
  activeObjs.forEach(function(obj) {
    var itemWidth = obj.getBoundingRect().width;
    var itemHeight = obj.getBoundingRect().height;

    // ================================
    // OBJECT ALIGNMENT: " H-LEFT "
    // ================================
    obj.set({
      left: (0 - itemWidth/2),
      originX: 'left'
    });
    obj.setCoords();
    canvas.renderAll();

  });

});

$('#objAlignRight').click(function() {

  var activeObj = canvas.getActiveObject();
  var activeObjs = canvas.getActiveObjects();
  activeObjs.forEach(function(obj) {
    var itemWidth = obj.getBoundingRect().width;
    var itemHeight = obj.getBoundingRect().height;

    // ================================
    // OBJECT ALIGNMENT: " H-LEFT "
    // ================================
    obj.set({
      left: (activeObj.width/2 - itemWidth/2),
      originX: 'center'
    });
    obj.setCoords();
    canvas.renderAll();

  });

});

$('#objAlignTop').click(function() {

  var activeObj = canvas.getActiveObject();
  var activeObjs = canvas.getActiveObjects();
  activeObjs.forEach(function(obj) {
    var itemWidth = obj.getBoundingRect().width;
    var itemHeight = obj.getBoundingRect().height;

    // ================================
    // OBJECT ALIGNMENT: " H-LEFT "
    // ================================
    obj.set({
      top: -activeObj.height / 2 + itemHeight / 2
      //top: -activeObj.height + itemHeight
    });
    obj.setCoords();
    canvas.renderAll();

  });

});

$('#objAlignBottom').click(function() {

  var activeObj = canvas.getActiveObject();
  var activeObjs = canvas.getActiveObjects();
  activeObjs.forEach(function(obj) {
    var itemWidth = obj.getBoundingRect().width;
    var itemHeight = obj.getBoundingRect().height;

    // ================================
    // OBJECT ALIGNMENT: " H-LEFT "
    // ================================
    obj.set({
      top: activeObj.height / 2 - itemHeight / 2
    });
    obj.setCoords();
    canvas.renderAll();

  });

});

</script>



<!-- prueba -->
<script type="text/javascript">
  $(document).ready(function () {
    $("#btn_cargar").click(function(){
      $.ajax({
        type: "POST",
        data: {"data": ""},
        url: SITE_URL+'/Seguridad/cCanvas/CargarJSON',
        success: function(respuesta) {
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

          //Bloqueando scalable y
          /*var Objs = canvas._objects;
          Objs.forEach(function(obj) {
            // ================================
            // OBJECT LOCKSCALING Y
            // ================================
              obj.set({
                lockScalingY: true,
                hasRotatingPoint: false
              });
            canvas.renderAll();
          });*/

        },
        error: function() {
              console.log("No se ha podido obtener la información");
          }
      });
    });

    $("#btn_parsear").click(function(){
      $.ajax({
        type: "POST",
        data: {"data": ""},
        url: SITE_URL+'/Seguridad/cCanvas/RenderizarJSONImpresion',
        success: function(respuesta) {
          console.log(respuesta);
        },
        error: function() {
              console.log("No se ha podido obtener la información");
          }
      });
    });

    $("#btn_imprimir").click(function(){
      $.ajax({
        type: "POST",
        data: {"data": ""},
        url: SITE_URL+'/Seguridad/cCanvas/ImprimirJSON',
        success: function(respuesta) {
          console.log(respuesta);
        },
        error: function() {
              console.log("No se ha podido obtener la información");
          }
      });
    });


  });
</script>
