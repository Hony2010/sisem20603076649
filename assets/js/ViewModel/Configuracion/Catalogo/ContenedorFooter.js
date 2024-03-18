window.onbeforeunload = function(e) {
  //e = e || window.event;
  if (existecambio)
  {
    e.preventDefault = true;
    e.cancelBubble = true;
    e.returnValue = 'test';
  }
  //e.preventDefault();
  console.log("-window-");
  console.log(e);
}

$(document).ready(function(){
  $(window).keydown(function(event){
    //alert(event.keyCode);
    //switch (event.keyCode) {
      if (event.altKey)
      {
        var code = event.keyCode;
        if(code == 78 || code == 110) // ALT + N = Nuevo FAmilia
        {
          if($("#contenedor_familia").hasClass('active'))
          {
            //Validar si esta en vista familia o subfamilia
            if($("#modalSubFamiliaProducto").is(':visible'))
            {
              vistaModeloCatalogo.vmcFamilia.AgregarSubFamiliaProducto(null,event);
            }
            else {
              vistaModeloCatalogo.vmcFamilia.AgregarFamiliaProducto(null,event);
            }
            /*if( $("#opcion-familiaproducto").attr("class") =="active")
            {
            }
            else {
            }*/
          }
          else if($("#contenedor_linea").hasClass('active'))
          {
            vistaModeloCatalogo.vmcLineaProducto.AgregarLineaProducto(null,event);
          }
          else if($("#contenedor_marca").hasClass('active'))
          {
            if($("#modalModelo").is(':visible'))
            {
              vistaModeloCatalogo.vmcMarca.AgregarModelo(null,event);
            }
            else {
              vistaModeloCatalogo.vmcMarca.AgregarMarca(null,event);
            }
          }
          else if($("#contenedor_tipo_de_existencia").hasClass('active'))
          {
            vistaModeloCatalogo.vmcTipoExistencia.AgregarTipoExistencia(null,event);
          }
          else if($("#contenedor_fabricante").hasClass('active'))
          {
            vistaModeloCatalogo.vmcFabricante.AgregarFabricante(null,event);
          }
          else if($("#contenedor_tipo_de_servicio").hasClass('active'))
          {
            vistaModeloCatalogo.vmcTipoServicio.AgregarTipoServicio(null,event);
          }
          else if($("#contenedor_tipo_doc_identidad").hasClass('active'))
          {
            vistaModeloCatalogo.vmcTipoDocumentoIdentidad.AgregarTipoDocumentoIdentidad(null,event);
          }

        }
      }
 });

 $('body').keydown(function(){
   var code = event.keyCode;
   if(code == 27)
   {
     if($("#contenedor_familia").hasClass('active'))
     {
       //Validar si esta en vista familia o subfamilia
       if($("#modalSubFamiliaProducto").is(':visible'))
       {
         vistaModeloCatalogo.vmcFamilia.EscaparGlobalSubFamilia(event);
       }
       else {
         vistaModeloCatalogo.vmcFamilia.EscaparGlobal(event);
       }
     }
     else if($("#contenedor_linea").hasClass('active'))
     {
       vistaModeloCatalogo.vmcLineaProducto.EscaparGlobal(event);
     }
     else if($("#contenedor_marca").hasClass('active'))
     {
       if($("#modalModelo").is(':visible'))
       {
         vistaModeloCatalogo.vmcMarca.EscaparGlobalModelo(event);
       }
       else {
         vistaModeloCatalogo.vmcMarca.EscaparGlobal(event);
       }
     }
     else if($("#contenedor_tipo_de_existencia").hasClass('active'))
     {
       vistaModeloCatalogo.vmcTipoExistencia.EscaparGlobal(event);
     }
     else if($("#contenedor_fabricante").hasClass('active'))
     {
       vistaModeloCatalogo.vmcFabricante.EscaparGlobal(event);
     }
     else if($("#contenedor_tipo_de_servicio").hasClass('active'))
     {
       vistaModeloCatalogo.vmcTipoServicio.EscaparGlobal(event);
     }
     else if($("#contenedor_tipo_doc_identidad").hasClass('active'))
     {
       vistaModeloCatalogo.vmcTipoDocumentoIdentidad.EscaparGlobal(event);
     }
     //Models.EscaparGlobal(event);
   }
 });
});

$("#modalSubFamiliaProducto").on("hidden.bs.modal", function(e){
  vistaModeloCatalogo.vmcFamilia.OutModal(window);
});

$("#modalModelo").on("hidden.bs.modal", function(e){
  vistaModeloCatalogo.vmcMarca.OutModal(window);
});
