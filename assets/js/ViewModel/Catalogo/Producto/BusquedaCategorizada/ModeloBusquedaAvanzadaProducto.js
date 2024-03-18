ModeloBusquedaAvanzadaProducto = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function (data,event) {
    if(event) {
    }
  }

  self.ObtenerTotalFilasProductos = function(data, event)
  {
    if(event)
    {
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      return json.length;
    }
  }

  self.ObtenerTotalProductosJSON = function(data, event, baseVista)
  {
    if(event)
    {
      var filtro = data;//ko.mapping.toJS(data);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = '.IdFamiliaProducto == "' + filtro.idfamilia + '" && .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';//"";

      
      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      return resultado.length;
    }
  }

  self.BuscarProductosPorNombreProducto = function(data, event, baseVista)
  {
    if(event)
    {
      var filtro = data;//ko.mapping.toJS(data);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      if ($('.btn-subfamilias').is(":visible")) {
        var opcionExtra = '.IdFamiliaProducto == "' + filtro.idfamilia +  '" && .IdSubFamiliaProducto == "' + filtro.idsubfamilia + '" && .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';//"";
      } else {
        var opcionExtra = '.IdFamiliaProducto == "' + filtro.idfamilia + '" && .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';//"";
      }

      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      if(resultado.length > 0)
      {
        resultado.sort(SortByName);
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  self.BuscarProductosPorSubFamilia = function(data, event, baseVista)
  {
    if(event)
    {
      var filtro = data;
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = '.IdSubFamiliaProducto == "' + filtro.idsubfamilia + '" && .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';//"";
      
      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      if(resultado.length > 0)
      {
        resultado.sort(SortByName);
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  //SOLO PARA MERCADERIAS
  self.FiltrarProductoPorNombreProducto = function(data, event, baseVista)
  {
    if(event)
    {
      var filtro = ko.mapping.toJS(data);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';//"";

      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      if(resultado.length > 0)
      {
        resultado.sort(SortByName);
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  // self.FiltrarStockMercaderiaPorAlmacen = function(data, event)
  // {
  //   if(event)
  //   {
  //     var filtro = ko.mapping.toJS(data);
  //     var json = data;

  //     var resultado = JSPath.apply('.{.IdAsignacionSede *= $Texto}', data.ListaStock, {Texto: filtro.IdAlmacen});
  //     if(resultado.length > 0)
  //     {
  //       return resultado;
  //     }
  //     else {
  //       return false;
  //     }
  //   }
  // }

}
