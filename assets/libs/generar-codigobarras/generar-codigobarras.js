//FUNCIOON PARA AGREGAR CEROS A LA IZQUIERDA
function PadLeft(value, length) {
    return (value.toString().length < length) ? PadLeft("0" + value, length) :
    value;
}

function GenerarCodigoAleatorio(){
  var hoy = new Date();
  var year = hoy.getFullYear().toString();
  var year2 = year.substring(2);
  var month = PadLeft((hoy.getMonth() + 1), 2);
  var day = PadLeft(hoy.getDate(), 2);
  //var sem = hoy.getDay() + 1;
  var hour = PadLeft(hoy.getHours(), 2);
  var minute = PadLeft(hoy.getMinutes(), 2);
  var second = PadLeft(hoy.getSeconds(), 2);

  var codigo = year2.toString() +  month.toString() + day.toString() + hour.toString() + minute.toString() + second.toString();
  var codigos = codigo.split("");

  var impar = 0, par = 0;
  for(i in codigos)
  {
    if (codigos.hasOwnProperty(i)) {
      if(i % 2 == 0){
        par += parseInt(codigos[i]);
      }
      else{
        impar += parseInt(codigos[i]);
      }
    }
  }

  var sum_imp = parseInt(impar) * 3;
  var total = sum_imp + parseInt(par);
  //var dec_cer = total % 10;
  var dec_cer = Math.ceil10(total, 1);

  var last_digito = dec_cer - total;

  console.log("total: " + total);
  console.log("n: " + dec_cer);
  console.log("n: " + last_digito);

  return codigo + last_digito.toString();
}
