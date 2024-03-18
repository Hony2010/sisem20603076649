$( document ).ready(function() {

  $('body').on('keyup, keydown', '.input-date', function(event){
    var inputLength = event.target.value.length;
    if (event.keyCode != 8){
      if(inputLength === 2 || inputLength === 5){
        var thisVal = event.target.value;
        thisVal += '/';
        $(event.target).val(thisVal);
    	}
    }
  });

  /*$('.input-date').bind('keyup','keydown', function(event) {
    var inputLength = event.target.value.length;
    if (event.keyCode != 8){
      if(inputLength === 2 || inputLength === 5){
        var thisVal = event.target.value;
        thisVal += '/';
        $(event.target).val(thisVal);
      }
    }
  });*/

})

function FechaFormato(){

}

/**
 * Convierte un texto de la forma 2017-01-10 a la forma
 * 10/01/2017
 *
 * @param {string} texto Texto de la forma 2017-01-10
 * @return {string} texto de la forma 10/01/2017
 *
 */
FechaFormato.FormatearFechaYYYYMMDD = function(fechaentrada)
{
  return fechaentrada.replace(/^(\d{4})-(\d{2})-(\d{2})$/g,'$3/$2/$1');
}
