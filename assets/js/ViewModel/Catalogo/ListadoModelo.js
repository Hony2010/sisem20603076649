function ListadoModelo(){

}


ListadoModelo.CargarModelo = function(data, listajson, objeto){
    objeto.empty();
    var id_marca = data.IdMarca();
    var id_modelo = data.IdModelo();
    //debugger;
      $.each(listajson, function (key, entry) {
        if(id_marca == entry.IdMarca)
        {
          var sel = "";
          if(id_modelo != "" || id_modelo != null)
          {
            if(id_modelo == entry.IdModelo)
            {
              sel = 'selected="true"';
            }
          }
          objeto.append($('<option '+sel+'></option>').attr('value', entry.IdModelo).text(entry.NombreModelo));
        }

      })

}
