window.onbeforeunload = function(e) {
  //e = e || window.event;
  if (existecambio != undefined) {
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
          if($("#contenedor_forma_de_pago").hasClass('active'))
          {
            vistaModeloGeneral.vmgFormaPago.AgregarFomarPago(null,event);
          }
          else if($("#contenedor_giro_del_negocio").hasClass('active'))
          {
            vistaModeloGeneral.vmgGiroNegocio.AgregarGiroNegocio(null,event);
          }
          else if($("#contenedor_grupo_de_parametro").hasClass('active'))
          {
            vistaModeloGeneral.vmgGrupoParametro.AgregarGrupoParametro(null,event);
          }
          else if($("#contenedor_medio_de_pago").hasClass('active'))
          {
            vistaModeloGeneral.vmgMedioPago.AgregarMedioPago(null,event);
          }
          else if($("#contenedor_moneda").hasClass('active'))
          {
            vistaModeloGeneral.vmgMoneda.AgregarMoneda(null,event);
          }
          else if($("#contenedor_regimen_tributario").hasClass('active'))
          {
            vistaModeloGeneral.vmgRegimenTributario.AgregarRegimenTributario(null,event);
          }
          else if($("#contenedor_sede").hasClass('active'))
          {
            vistaModeloGeneral.vmgSede.AgregarSede(null,event);
          }
          else if($("#contenedor_tipo_de_cambio").hasClass('active'))
          {
            vistaModeloGeneral.vmgTipoCambio.AgregarTipoCambio(null,event);
          }
          else if($("#contenedor_tipo_de_documento").hasClass('active'))
          {
            vistaModeloGeneral.vmgTipoDocumento.AgregarTipoDocumento(null,event);
          }
          else if($("#contenedor_tipo_de_sede").hasClass('active'))
          {
            vistaModeloGeneral.vmgTipoSede.AgregarTipoSede(null,event);
          }
          else if($("#contenedor_unidad_de_medida").hasClass('active'))
          {
            vistaModeloGeneral.vmgUnidadMedida.AgregarUnidadMedida(null,event);
          }
          else if($("#contenedor_correlativo_de_documento").hasClass('active'))
          {
            vistaModeloGeneral.vmgCorrelativoDocumento.AgregarCorrelativoDocumento(null,event);
          }

        }
      }
 });

 $('body').keydown(function(){
   var code = event.keyCode;
   if(code == 27)
   {
     if($("#contenedor_forma_de_pago").hasClass('active'))
     {
       vistaModeloGeneral.vmgFormaPago.EscaparGlobal(event);
     }
     else if($("#contenedor_giro_del_negocio").hasClass('active'))
     {
       vistaModeloGeneral.vmgGiroNegocio.EscaparGlobal(event);
     }
     else if($("#contenedor_grupo_de_parametro").hasClass('active'))
     {
       vistaModeloGeneral.vmgGrupoParametro.EscaparGlobal(event);
     }
     else if($("#contenedor_medio_de_pago").hasClass('active'))
     {
       vistaModeloGeneral.vmgMedioPago.EscaparGlobal(event);
     }
     else if($("#contenedor_moneda").hasClass('active'))
     {
       vistaModeloGeneral.vmgMoneda.EscaparGlobal(event);
     }
     else if($("#contenedor_regimen_tributario").hasClass('active'))
     {
       vistaModeloGeneral.vmgRegimenTributario.EscaparGlobal(event);
     }
     else if($("#contenedor_sede").hasClass('active'))
     {
       vistaModeloGeneral.vmgSede.EscaparGlobal(event);
     }
     else if($("#contenedor_tipo_de_cambio").hasClass('active'))
     {
       vistaModeloGeneral.vmgTipoCambio.EscaparGlobal(event);
     }
     else if($("#contenedor_tipo_de_documento").hasClass('active'))
     {
       vistaModeloGeneral.vmgTipoDocumento.EscaparGlobal(event);
     }
     else if($("#contenedor_tipo_de_sede").hasClass('active'))
     {
       vistaModeloGeneral.vmgTipoSede.EscaparGlobal(event);
     }
     else if($("#contenedor_unidad_de_medida").hasClass('active'))
     {
       vistaModeloGeneral.vmgUnidadMedida.EscaparGlobal(event);
     }
     else if($("#contenedor_correlativo_de_documento").hasClass('active'))
     {
       vistaModeloGeneral.vmgCorrelativoDocumento.EscaparGlobal(event);
     }
   }
 });
});
// --------------------------
$(document).ready(function(){

  $(".fecha-reporte").inputmask({"mask":"99/99/9999",positionCaretOnTab : false});
// Empresa
   var _idempresa = vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.IdEmpresa();
   var _logotipo = vistaModeloGeneral.vmgEmpresa.dataEmpresa.Empresa.Logotipo();

   if (_idempresa=="" || _idempresa == null || _logotipo == null || _logotipo == "")
   {
     src= BASE_URL + CARPETA_IMAGENES + "nocover.png";
   }
   else
   {
     src=SERVER_URL + CARPETA_IMAGENES + CARPETA_EMPRESA+_idempresa+"/"+_logotipo;
   }

   $('#img_FileFoto').attr('src', src);

// fin Empresa

   var texto2;
   var decimal2 = "";
   var entero2 = "";

   $("#brand_tipocambio").on("keypress keyup", ".decimal-control", function (event) {
        $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if($(this).val() == "")
        {
          if(event.which == 46)
          {
            event.preventDefault();
          }
        }

         if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();

         }

         var texto = $(this).val();

         var contar = $(this).val().length;
         var inicio = texto.indexOf(".");
         var dtexto = texto.split('.');
         //var fin = texto.substring(inicio + 1, contar);
         console.log("C_ENTEROS: " + c_entero);
         console.log("C_DECIMAL: " + c_decimal);
         console.log("ENTEROS: " + dtexto[0]);
         console.log("DECIMAL: " + dtexto[1]);

         if(inicio > -1){
           if(dtexto[0].length < c_entero + 1){
             texto2 = texto;
             entero2 = dtexto[0];
             }
           if(dtexto[1].length < c_decimal + 1){
             texto2 = texto;
             decimal2 = dtexto[1];
               }
         }
         else {
           if(dtexto[0].length < c_entero + 1){
             entero2 = dtexto[0];
             texto2 = texto;
           }
         }

         if(inicio > -1){
           //debugger;
           if(dtexto[0].length > c_entero){
             if(dtexto[0] != entero2)
             {
               //alert(entero2);
               console.log("ENTERO[]: " + dtexto[0]);
               console.log("ENTERO: " + entero2);
               $(this).val(entero2 + "." + decimal2);
             }
           }

           if(dtexto[1].length > c_decimal){
             if(dtexto[1] != decimal2)
             {
               console.log("DECIMAL[]: " + dtexto[1]);
               console.log("DECIMAL: " + decimal2);
               console.log("TOTAL: " + entero2 + "." + decimal2);
               $(this).val(entero2 + "." + decimal2);
             }
           }
         }
         else {
           if(dtexto[0].length > c_entero)
           {
             if(dtexto[0] != entero2)
             {
               console.log("ENTERO[]: " + dtexto[0]);
               console.log("ENTERO: " + entero2);
                //event.preventDefault();
                $(this).val(entero2);
               //$(this).val(texto2);
             }
           }
         }
    });
});
function validarNum(e)
{
    tecla = (document.all) ? e.keyCode : e.which;
    if (tecla == 8 || tecla == 44 || tecla == 46) return true;
    patron = /\d/;
    //permitidos= /[0-9]+([,])?([0-9]2)?$/;
    //patron = ^\d+(?:[.,]\d+)?$;
    te = String.fromCharCode(tecla);
    //console.log(te);
    return patron.test(te);
}
//
// function validarDate(e)
// {
//     tecla = (document.all) ? e.keyCode : e.which;
//     if (tecla == 8 || tecla == 47) return true;
//     patron = /\d/;
//     //permitidos= /[0-9]+([,])?([0-9]2)?$/;
//     //patron = ^\d+(?:[.,]\d+)?$;
//     te = String.fromCharCode(tecla);
//     //console.log(te);
//     return patron.test(te);
// }

$("#OtraUnidadMedidaModel").on("hidden.bs.modal", function(e){
  vistaModeloGeneral.vmgUnidadMedida.CloseModal(window);
});
// $("#OtraUnidadMedidaModel").click(function(e){
//   var container = $("#OtraUnidadMedidaModel_content");
//   if (!container.is(e.target) && container.has(e.target).length === 0)
//   {
//       vistaModeloGeneral.vmgUnidadMedida.CloseModal(window);
//   }
// });

var input = ko.toJS(vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.Filtros);
$("#Paginador").paginador(input, vistaModeloGeneral.vmgUnidadMedida.ConsultarPorPagina);
