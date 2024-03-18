function TruncarDecimal(num, decimals)
{

  var ndecimals = Math.pow(10, decimals);

  var numero = num;
  numero = parseFloat(numero);
  var mult = (numero * ndecimals).toFixed(decimals + 2);
  var numerotruncado = Math.floor(mult) / ndecimals;


  return numerotruncado.toFixed(decimals);
}

Number.prototype.Truncar = function(decimals) {
  var ndecimals = Math.pow(10, decimals);
  console.log(this);
  var numero = this;
  numero = parseFloat(numero);
  var numerotruncado = Math.floor(numero * ndecimals) / ndecimals;
  return numerotruncado.toFixed(decimals);
}

function isNormalInteger(str) {
    return /^\+?(0|[1-9]\d*)$/.test(str);
}
