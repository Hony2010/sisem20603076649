function Mapper()
{

}

Mapper.mapeo = function(origen, destino)
{
  var resultado = [];
  for (var i in destino) {
    if(origen.hasOwnProperty(i))
    {
      if(origen[i] !== null)
      {
        resultado[i] = origen[i];
      }
    }
    else {
      resultado[i] = destino[i];
    }
  }
  return resultado;
}

Mapper.mapeo_header = function(objeto)
{
  var resultado = [];
  for (var i in objeto) {
    if(objeto.hasOwnProperty(i))
    {
      resultado.push({'name': i});
    }
  }
  return resultado;
}
