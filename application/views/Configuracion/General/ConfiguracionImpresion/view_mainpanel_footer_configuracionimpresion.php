<script>
  var SITE_URL = "<?php echo site_url(); ?>";
  var base_url = "<?php echo base_url();?>";
  var data=<?php echo json_encode($data); ?>

  var canvas = new fabric.Canvas('c');
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
  // Define an array with all fonts
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


  //BORANDO CON SUPRIMIR
  window.addEventListener("keydown", event =>
  {
      // delete
      if(event.keyCode === 46)
      {
        event.preventDefault();

        // this.canvas.preserveObjectStacking = false;
        alertify.confirm("Estas Seguro?", function(){
          var selection = canvas.getActiveObjects();
          canvas.discardActiveObject();
          selection.forEach(obj => canvas.remove(obj));

          // this.canvas.preserveObjectStacking = true;
          canvas.renderAll();
        });

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

<script type="text/javascript">

  $(document).ready(function () {
    //$("#opcion-canvas").prop("disabled", true);
    //$('#opcion-canvas').removeClass('disabledTab');
    $('#opcion-canvas').addClass('disabledTab');
    $('#preview_canvas').hide();
    $("#font_value_text").text($("#text-font-size").val());

    /*$("#text-font-size").change(function(){
      var valor =$(this).val();
      $("#font_value_text").text(valor);
    });*/
    $('#text-font-size').on("change mousemove", function() {
        //$(this).next().html($(this).val());
        var valor =$(this).val();
        $("#font_value_text").text(valor);
    });


  });

</script>
