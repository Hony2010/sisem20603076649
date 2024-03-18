function ListadoSubFamilia(){

}

ListadoSubFamilia.CargarSubFamilia = function(data, listajson, objeto){
  objeto.empty();
  var id_familia = data.IdFamiliaProducto();
  var id_subfamilia = data.IdSubFamiliaProducto();
  //debugger;
    $.each(listajson, function (key, entry) {
      if(id_familia == entry.IdFamiliaProducto)
      {
        var sel = "";
        if(id_subfamilia != "")
        {
          if(id_subfamilia == entry.IdSubFamiliaProducto)
          {
            sel = 'selected="true"';
          }
        }
        objeto.append($('<option '+sel+'></option>').attr('value', entry.IdSubFamiliaProducto).text(entry.NombreSubFamiliaProducto));
      }

    })
}
