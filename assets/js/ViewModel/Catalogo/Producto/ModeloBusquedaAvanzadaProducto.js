ModeloBusquedaAvanzadaProducto = function (data) {
  var self = this;
  var base = data;

  self.InicializarModelo = function (data, event) {
    if (event) {
    }
  }

  self.ObtenerTotalFilasProductos = function (data, event) {
    if (event) {
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      return json.length;
    }
  }

  self.ObtenerTotalProductosJSON = function (data, event, baseVista) {
    if (event) {
      var filtro = data;//ko.mapping.toJS(data);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = "";

      if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
          opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.ZOFRA + '"';
        }
        else {
          opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
        }
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      return resultado.length;
    }
  }

  self.BuscarProductosPorNombreProducto = function (data, event, baseVista) {
    if (event) {
      var filtro = data;//ko.mapping.toJS(data);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = "";

      if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
          opcionExtra = ' .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.ZOFRA + '"';
        }
        else {
          opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
        }
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        opcionExtra = ' .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      if (resultado.length > 0) {
        var totalDatos = parseFloat(filtro.totalfilas);
        var filasPagina = parseFloat(filtro.numerofilasporpagina);
        var pagina = parseFloat(filtro.pagina);

        var num = (totalDatos / filasPagina);
        var pos = num.toString().indexOf(".");
        var res = String(num).substring((pos + 1), num.length);

        // var page = (totalDatos / filasPagina);
        // if(res > 0)
        // {
        //   page = (totalDatos / filasPagina)+1;
        // }
        // page = Math.trunc(page);
        var inicio = (pagina - 1) * filasPagina;
        var final = inicio + filasPagina;
        resultado = resultado.filter(function (entry, key) {
          return key >= inicio && key < final
        });
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  self.ObtenerProductosParaBusquedaPorListaSimple = function (data, event, baseVista) {
    if (event) {
      var json = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_MERCADERIAS);

      var opcionExtra = '.IdOrigenMercaderia *= ""';

      if (!baseVista.IdComprobanteCompra) {
        if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
          opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
        }
        else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
          if (baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
            opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.ZOFRA + '"';
          }
          else {
            opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
          }
        }
        else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
          opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';
        }
      }

      var nombreFamiliaProducto = data.NombreFamiliaProducto() ? data.NombreFamiliaProducto() : "";
      var nombreMarca = data.NombreMarca() ? data.NombreMarca() : "";
      var nombreProducto = $("#BuscadorMercaderiaListaSimple #TextoFiltro").val();

      var filtroStock = data.CheckConSinStock() ? `&& .StockMercaderia > 0` : ''

      var filtro = ` && (.NombreProducto *= "${nombreProducto}" && .NombreFamiliaProducto *= "${nombreFamiliaProducto}" && .NombreMarca *= "${nombreMarca}" ${filtroStock})`;

      var query = `.{(${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE}) ${filtro}}`;

      var resultado = JSPath.apply(query, json);
      return resultado

    }
  }

  self.ObtenerProductosParaBusquedaPorLista = function (data, event, baseVista) {
    if (event) {

      var json = ObtenerJSONCodificadoDesdeURL(SERVER_URL + URL_JSON_MERCADERIAS);

      var opcionExtra = "";

      if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
          opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.ZOFRA + '"';
        }
        else {
          opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
        }
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        opcionExtra = '.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      var arrayFiltro = data.textofiltro.split(" ");
      var filtro = ""
      arrayFiltro.forEach(element => {
        // filtro += ` && (.NombreLargoProducto *== "${element}" || .NombreLargoProducto ==* "${element}") `;
        filtro += ` && (.NombreLargoProducto *= "${element}")`;
      });

      var query = `.{(${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE}) ${filtro}}`;

      var resultado = JSPath.apply(query, json);
      return resultado

    }
  }

  self.ObtenerTotalProductosParaBusquedaPorLista = function (data, event, baseVista) {
    if (event) {
      var filtro = data;

      var resultado = self.ObtenerProductosParaBusquedaPorLista(filtro, event, baseVista);
      return resultado.length;
    }
  }

  self.BusquedaProductosPorLista = function (data, event, baseVista) {
    if (event) {
      var filtro = data;

      var resultado = self.ObtenerProductosParaBusquedaPorLista(filtro, event, baseVista);

      if (resultado.length > 0) {
        var totalDatos = parseFloat(filtro.totalfilas);
        var filasPagina = parseFloat(filtro.numerofilasporpagina);
        var pagina = parseFloat(filtro.pagina);

        var num = (totalDatos / filasPagina);
        var pos = num.toString().indexOf(".");
        var res = String(num).substring((pos + 1), num.length);

        var inicio = (pagina - 1) * filasPagina;
        var final = inicio + filasPagina;

        resultado = resultado.filter((entry, key) => key >= inicio && key < final);
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  //SOLO PARA MERCADERIAS
  self.FiltrarProductoPorNombreProducto = function (data, event, baseVista) {
    if (event) {
      var filtro = ko.mapping.toJS(data);
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;
      var json = ObtenerJSONCodificadoDesdeURL(url_json);

      var opcionExtra = "";

      if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_FACTURA) {
        opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_BOLETA) {
        if (baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_T || baseVista.IdSubTipoDocumento() == ID_SUBTIPO_DOCUMENTO_BOLETA_Z) {
          opcionExtra = ' .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.ZOFRA + '"';
        }
        else {
          opcionExtra = ' (.IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"' + ' || .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.DUA + '")';
        }
      }
      else if (baseVista.IdTipoDocumento() == ID_TIPO_DOCUMENTO_ORDEN_PEDIDO) {
        opcionExtra = ' .IdOrigenMercaderia == "' + ORIGEN_MERCADERIA.GENERAL + '"';
      }

      var query = `.{(.NombreProducto *= "${filtro.textofiltro}" || .CodigoMercaderia *= "${filtro.textofiltro}") && (${opcionExtra} && .EstadoProducto == ${ESTADO_PRODUCTO.VISIBLE})}`;
      // var query = '.{(.NombreProducto *= "'+filtro.textofiltro+'" || .CodigoMercaderia *= "'+filtro.textofiltro+'") '+opcionExtra+'}';
      var resultado = JSPath.apply(query, json);
      if (resultado.length > 0) {
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  self.FiltrarStockMercaderiaPorAlmacen = function (data, event) {
    if (event) {
      var filtro = ko.mapping.toJS(data);
      var json = data;

      var resultado = JSPath.apply('.{.IdAsignacionSede *= $Texto}', data.ListaStock, { Texto: filtro.IdAlmacen });
      if (resultado.length > 0) {
        return resultado;
      }
      else {
        return false;
      }
    }
  }

  self.ActualizarParametroSistema = function (data, event, callback) {
    if (event) {
      var arraydata = [
        {
          IdParametroSistema: ID_PARAMETRO_TIPO_CAMBIO_BUSQUEDA_AVANZADA_PRODUCTO,
          ValorParametroSistema: data.TipoCambio(),
          NombreParametroSistema: 'ParametroTipoCambioBusquedaAvanzadaProducto'
        },
        {
          IdParametroSistema: ID_PARAMETRO_MARGEN_UTILIDAD_BUSQUEDA_AVANZADA_PRODUCTO,
          ValorParametroSistema: data.MargenUtilidad(),
          NombreParametroSistema: 'ParametroMargenUtiilidadBusquedaAvanzadaProducto'
        }
      ]

      arraydata.forEach(item => {
        var datajs = { Data: item };
        $.ajax({
          method: 'POST',
          data: datajs,
          dataType: 'json',
          url: SITE_URL + '/Configuracion/General/cParametroSistema/ActualizarParametroSistema',
          success: function (data) {
            callback(data, event);
          },
          error: function (jqXHR, textStatus, errorThrown) {
            callback({ error: { msg: jqXHR.responseText } }, event);
          }
        });
      });
    }
  }

  self.BuscarInventarioMercaderiasPorSede = function (data,event,callback) {
    self.ListarInventarioMercaderiasPorSede(data, event, function ($data,$event) {                  
      self.Mercaderias([]);
      ko.utils.arrayForEach($data.resultado, function (item) {
        var objeto = new VistaModeloProducto(item);
        self.Mercaderias.push(objeto);
      });
      
      callback($data,$event);
    });    
  }

  self.BuscarInventarioMercaderiasPorSedePorPagina = function (data,event,callback) {
    self.ListarInventarioMercaderiasPorSedePorPagina(data, event, function ($data,$event) {      
      self.Mercaderias([]);
      ko.utils.arrayForEach($data, function (item) {
        var objeto = new VistaModeloProducto(item);
        self.Mercaderias.push(objeto);
      });
      
      callback($data,$event);
    });    
  }

  self.ListarInventarioMercaderiasPorSede = function (data, event, callback) {
    if (event) {
      $.ajax({
        method: 'GET',
        data: data,
        dataType: 'json',
        url: SITE_URL + '/Inventario/cInventario/ListarInventarioMercaderiasPorSede',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.ListarInventarioMercaderiasPorSedePorPagina = function (data, event, callback) {
    if (event) {
      $.ajax({
        method: 'GET',
        data: data,
        dataType: 'json',
        url: SITE_URL + '/Inventario/cInventario/ListarInventarioMercaderiasPorSedePorPagina',
        success: function (data) {
          callback(data, event);
        },
        error: function (jqXHR, textStatus, errorThrown) {
          callback({ error: { msg: jqXHR.responseText } }, event);
        }
      });
    }
  }

  self.Limpiar = function(data, event) {
    if(event) {
      self.Mercaderias([]);      
    }
  }

}
