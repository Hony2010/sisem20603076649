//SE LISTAN LAS TECLAS A USARSE EN LA cVistaModeloGeneral
window.AccesoKey = {};
window.AccesoKey.Data = [];
window.AccesoKey.Clear = function() {
  window.AccesoKey.Data = [];
}
window.AccesoKey.Agregar = function(id, key)
{
  this.Data.push({"id": id, "key": key, "tipo": 1});
}

window.AccesoKey.AgregarKeyOption = function(form, id, key)
{
  this.Data.push({"idform": form, "id": id, "key": key, "tipo": 3});
}

window.AccesoKey.AgregarMultiTab = function(base, idbase, idsecondary, key)
{
  this.Data.push({"base": base, "idbase": idbase, "idsecondary": idsecondary, "key": key, "tipo": 2});
}

window.AccesoKey.CorrerOpcion = function(code){
  if(event)
  {
      if($("#loader").is(":visible"))
      {
        // console.log("PETICIONES MULTIPLES PARADAS");
        return false;
      }

      if(alertify.alert().isOpen() || alertify.confirm().isOpen())
      {
        // console.log("ALERTIFY MULTIPLES PARADAS");
        return false;
      }

      var teclas = window.AccesoKey.Data;

      var opciones = [];
      teclas.forEach(function(entry, key){
        if(entry.key == code)
        {
          opciones.push(entry);
        }
      });

      console.log(opciones);
      if(opciones.length > 0)
      {
        if(opciones.length == 1){
          if(opciones[0].tipo == 2)
          {
            if($(opciones[0].base).is(":visible"))
            {
              document.getElementById(opciones[0].idbase).focus();
              document.getElementById(opciones[0].idbase).click();
            }
            else {
              document.getElementById(opciones[0].idsecondary).focus();
              document.getElementById(opciones[0].idsecondary).click();
            }
          }
          else if(opciones[0].tipo == 1){
            document.getElementById(opciones[0].id).focus();
            document.getElementById(opciones[0].id).click();
          }
          else {
            if($(opciones[0].idform).is(":visible"))
            {
              $(opciones[0].idform).find(opciones[0].id).focus();
              $(opciones[0].idform).find(opciones[0].id).click();
            }
          }
        }
        else {
          var modals_length = $("body").find(".modal").filter(":visible").length;
          if(modals_length > 0)
          {
            var modal = $("body").find(".modal").filter(":visible");
            // if(modals_length == 1)
            // {
            //
            // }
            // else {
              var ultimo_modal = modal[modals_length-1];
              // var id_form = $(ultimo_modal).find("form").prop("id");
              var objeto_key = null;
              opciones.forEach(function(entry,  key){
                var validar_key = $(ultimo_modal).find(entry.id);
                if(validar_key.length > 0)
                {
                  objeto_key = entry;
                }
              });

              if(objeto_key != null)
              {
                $(objeto_key.idform).find(objeto_key.id).focus();
                $(objeto_key.idform).find(objeto_key.id).click();
              }
              // $.each( modal, function( key, value ) {
              //   console.log( key + ": " + value );
              //   console.log(value);
              // });
              // alert(modal);
            // }

          }
          else {
            var objeto_keys_visible = [];
            opciones.forEach(function(entry,  key){
              if($(entry.idform).is(":visible"))
              {
                objeto_keys_visible.push(entry);
              }
            });

            $(objeto_keys_visible[0].idform).find(objeto_keys_visible[0].id).focus();
            $(objeto_keys_visible[0].idform).find(objeto_keys_visible[0].id).click();
          }
        }
				return false;
      }

  }
}

window.RecorrerLista = {};
window.RecorrerLista.Data = [];

window.RecorrerLista.Clear = function () {
  window.RecorrerLista.Data = [];
}

window.RecorrerLista.Agregar = function(id, key)
{
  this.Data.push({"id": id});
}

window.RecorrerLista.CorrerOpcion = function(code){
  if(event)
  {
      var lista = window.RecorrerLista.Data;
      var opciones = [];

      // lista.forEach(function(entry, key){
      //   if(entry.key == code)
      //   {
      //     opciones.push(entry);
      //   }
      // });

      if(lista.length > 0)
      {
        if(lista.length == 1){
          if($(lista[0].id).is(":visible"))
          {
            var lista_a = $(lista[0].id).find("a");
            var active_a = $(lista_a).filter(".active");

            if(code == TECLA_ARROW_UP)
            {
              $(active_a).prev().click();
            }
            else if(code == TECLA_ARROW_DOWN){
              $(active_a).next().click();
            }
          }
        }
      }

  }
}

window.RecorrerTabla = {};
window.RecorrerTabla.Data = [];

window.RecorrerTabla.Clear = function () {
  window.RecorrerTabla.Data = [];
}
window.RecorrerTabla.Agregar = function(id, key)
{
  this.Data.push({"id": id});
}

window.RecorrerTabla.CorrerOpcion = function(code){
  if(event)
  {
      var tabla = window.RecorrerTabla.Data;
      var opciones = [];

      tabla.forEach(function(entry, key){
        if($(entry.id).is(":visible"))
        {
          opciones.push(entry);
        }
      });

      if(opciones.length > 0)
      {
        if(opciones.length == 1){
          if($(opciones[0].id).is(":visible"))
          {
            var tabla_tr = $(opciones[0].id).find("tr");
            var active_tr = $(tabla_tr).filter(".active");

            if(code == TECLA_ARROW_UP)
            {
              $(active_tr).prev().click();
            }
            else if(code == TECLA_ARROW_DOWN){
              $(active_tr).next().click();
            }
          }
        }
        else {
          var modals_length = $("body").find(".modal").filter(":visible").length;
          if(modals_length > 0)
          {
            var modal = $("body").find(".modal").filter(":visible");
            if(modals_length == 1)
            {
              if($(opciones[1].id).is(":visible"))
              {
                var tabla_tr = $(opciones[1].id).find("tr");
                var active_tr = $(tabla_tr).filter(".active");

                if(code == TECLA_ARROW_UP)
                {
                  $(active_tr).prev().click();
                }
                else if(code == TECLA_ARROW_DOWN){
                  $(active_tr).next().click();
                }
              }
            }
            else {
              var ultimo_modal = modal[modals_length-1];
              // var id_form = $(ultimo_modal).find("form").prop("id");
              var objeto_key = null;
              opciones.forEach(function(entry,  key){
                var validar_key = $(ultimo_modal).find(entry.id);
                if(validar_key.length > 0)
                {
                  objeto_key = entry;
                }
              });

              if(objeto_key != null)
              {
                if($(objeto_key.id).is(":visible"))
                {
                  var tabla_tr = $(objeto_key.id).find("tr");
                  var active_tr = $(tabla_tr).filter(".active");

                  if(code == TECLA_ARROW_UP)
                  {
                    $(active_tr).prev().click();
                  }
                  else if(code == TECLA_ARROW_DOWN){
                    $(active_tr).next().click();
                  }
                }
              }
              // $.each( modal, function( key, value ) {
              //   console.log( key + ": " + value );
              //   console.log(value);
              // });
              // alert(modal);
            }

          }

        }
      }

  }
}

window.RecorrerPaginador = {};
window.RecorrerPaginador.Data = [];

window.RecorrerPaginador.Clear = function () {
  window.RecorrerPaginador.Data = [];
}

window.RecorrerPaginador.Agregar = function(id, key)
{
  this.Data.push({"id": id});
}

window.RecorrerPaginador.CorrerOpcion = function(code){
  if(event)
  {
      var tabla = window.RecorrerPaginador.Data;
      var opciones = [];

      // tabla.forEach(function(entry, key){
      //   if(entry.key == code)
      //   {
      //     opciones.push(entry);
      //   }
      // });

      if(tabla.length > 0)
      {
        if(tabla.length == 1){
          if($(tabla[0].id).is(":visible"))
          {
            var tabla_tr = $(tabla[0].id).find("li");
            var tabla_tam = tabla_tr.length;
            var active_tr = $(tabla_tr).filter(".active");

            if(code == TECLA_REPAG) //anterior
            {
              $(active_tr).prev().find("a").click();
            }
            else if(code == TECLA_AVPAG)//siguiente
            {
              $(active_tr).next().find("a").click();
            }
            else if(code == TECLA_INICIO)//inicio
            {
              var primero = $(tabla_tr)[1];
              $(primero).find("a").click();
            }
            else if(code == TECLA_FIN)//fin
            {
              var ultimo = $(tabla_tr)[tabla_tam-2];
              $(ultimo).find("a").click();
            }
          }
        }
      }

  }
}

$(document).ready(function(){
	$(window).keydown(function(event){
    var code = event.which;
    if (event.altKey)
    {
      if(code != TECLA_ALT)
      {
        window.AccesoKey.CorrerOpcion(code);
      }
      console.log(code);
      return false;
    }

    window.RecorrerLista.CorrerOpcion(code);

    // if (event.ctrlKey && event.altKey)
    // {
    //   window.RecorrerTabla.CorrerOpcion(code);
    //   return false;
    // }
	});
});
window.AllKeys = {};
window.AllKeys.Clear = function () {
  window.AccesoKey.Clear();
  window.RecorrerLista.Clear();
  window.RecorrerTabla.Clear();
  window.RecorrerPaginador.Clear();
}
