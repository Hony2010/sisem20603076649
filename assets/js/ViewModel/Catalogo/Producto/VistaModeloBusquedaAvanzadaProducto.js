VistaModeloBusquedaAvanzadaProducto = function (data) {
  var self = this;
  var baseVista = this;
  var baseBusqueda = this;
  ko.mapping.fromJS(data, {}, self);
  ModeloBusquedaAvanzadaProducto.call(this, self);
  self.textofiltro = ko.observable("");
  self.parentCallback = null;

  self.InicializarVistaModelo = function (data, event, base, callback) {
    if (event) {
      baseVista = base;
      self.InicializarModelo(data, event);
      var DataBusqueda = self.CrearDataBusqueda(data, event, false);
      $("#PaginadorJSON").paginador(DataBusqueda, self.ConsultarPorPagina);
      var DataBusquedaLista = self.CrearDataBusqueda(data, event, false);
      $("#PaginadorJSONParaLista").paginador(DataBusquedaLista, self.ConsultarPorPaginaLista);
      //var DataBusquedaListaSimple = self.CrearDataBusquedaParaListaSimple(data, event, false);// ko.mapping.toJS(self);      
      //$("#PaginadorJSONParaListaSimple").paginador(DataBusquedaListaSimple, self.ConsultarPorPaginaListaSimple);
      if (callback) self.parentCallback = callback;
    }
  }


  self.OnKeyDownBuscarProductos = function (data, event) {

    if (event) {
      var tecla = event.keyCode ? event.keyCode : event.which;
      if (tecla == TECLA_ENTER) {
        var inputs = $(event.target).closest('form').find(':input:visible');
        inputs.eq(inputs.index(event.target) + 1).focus();
        event.preventDefault();
        self.OnClickBuscarProductos(data, event);
      }
      return true;
    }
  }

  self.OnClickBuscarProductos = function (data, event) {
    if (event) {
      var dataJSON = {
        IdFamiliaProducto: self.IdFamiliaProducto() == undefined ? '%' : self.IdFamiliaProducto(),
        IdMarca: self.IdMarca() == undefined ? '%' : self.IdMarca(),
        textofiltro: self.textofiltro() == '' ? '%' : self.textofiltro(),
        CheckConSinStock: self.CheckConSinStock() ? '1' : '0',
        IdSede: baseVista.IdSede ? baseVista.IdSede() : baseVista.IdAsignacionSede(),
        numerofilasporpagina: 10,
        pagina: 1,
        paginadefecto: 1,
        totalfilas: 0
      };

      var datajs = { Data: dataJSON };
      $('#loader').show();
      self.BuscarInventarioMercaderiasPorSede(datajs, event, self.PostOnClickBuscarProductos);
    }
  }


  self.OnKeyEnter = function (data, event) {
    var resultado = $(event.target).enterToTab(event);
    return resultado;
  }

  self.OnFocus = function (data, event) {
    if (event) {
      $(event.target).select();
    }

  }

  self.ConsultarPorPagina = function (data, event) {
    if (event) {
      baseBusqueda.data.Mercaderias([]);
      data.textofiltro = self.textofiltro();
      var resultado = self.BuscarProductosPorNombreProducto(data, event, baseVista);
      self.PostBuscar(resultado, event, baseBusqueda);
    }
  }

  self.ConsultarPorPaginaLista = function (data, event) {
    if (event) {
      baseBusqueda.data.Mercaderias([]);
      data.textofiltro = self.textofiltro();
      var resultado = self.BusquedaProductosPorLista(data, event, baseVista);
      self.PostBuscarLista(resultado, event, baseBusqueda);
    }
  }


  self.DataFiltros = function (data, event) {
    if (event) {
      var JSONpage = {
        textofiltro: '',
        pagina: 1,
        numerofilasporpagina: NUMERO_ITEMS_BUSQUEDA_JSON_IMAGENES,
        paginadefecto: 1,
        totalfilas: 0,
      };
      return JSONpage;
    }
  }

  self.CrearDataBusqueda = function (data, event, inicio = true) {
    if (event) {
      var dataJSON = ko.mapping.toJS(data, mappingIgnore);
      var JSONpage = self.DataFiltros(dataJSON, event);
      ko.mapping.fromJS(dataJSON, {}, JSONpage);
      if (inicio) {
        JSONpage.totalfilas = self.ObtenerTotalProductosJSON(JSONpage, event, baseVista);//0;//self.ObtenerTotalFilasProductos(dataJSON, event);
      }
      $("#PaginadorJSON").paginador(JSONpage, self.ConsultarPorPagina);
      return JSONpage;
    }
  }

  self.Buscar = function (data, event, base) {
    if (event) {
      baseBusqueda = base;
      base.data.Mercaderias([]);
      var dJSON = self.CrearDataBusqueda(data, event);
      console.log(dJSON);
      var resultado = self.BuscarProductosPorNombreProducto(dJSON, event, baseVista);
      // var resultado = self.FiltrarProductoPorNombreProducto(data, event, baseVista);
      self.PostBuscar(resultado, event, base);
      console.log(resultado);
    }
  }

  self.BuscarPorLista = function (data, event, base) {
    if (event) {
      $('#loader').show();
      baseBusqueda = base;
      base.data.Mercaderias([]);
      var dJSON = self.CrearDataBusquedaParaLista(data, event);
      var resultado = self.BusquedaProductosPorLista(dJSON, event, baseVista);
      self.PostBuscarLista(resultado, event, base);
      $('#loader').hide();
      console.log(resultado);
      self.ActualizarParametroSistema(data, event, function ($data) {
        if ($data.error) {
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg, function () { })
        }
      })
    }
  }

  self.CrearDataBusquedaParaLista = function (data, event, inicio = true) {
    if (event) {
      var dataJSON = ko.mapping.toJS(data, mappingIgnore);
      var JSONpage = self.DataFiltros(dataJSON, event);
      ko.mapping.fromJS(dataJSON, {}, JSONpage);
      if (inicio) {
        JSONpage.totalfilas = self.ObtenerTotalProductosParaBusquedaPorLista(JSONpage, event, baseVista);
      }
      $("#PaginadorJSONParaLista").paginador(JSONpage, self.ConsultarPorPaginaLista);
      return JSONpage;
    }
  }

  self.PostOnClickBuscarProductos = function (data, event) {
    if (event) {
      $('#loader').hide();
      var objeto = self.Mercaderias()[0];
      self.SeleccionarProducto(objeto, event);
      $("#PaginadorJSONParaListaSimple").paginador(data.Filtros, self.OnClickBuscarProductosPorPagina);
      self.totalfilas(data.Filtros.totalfilas);
    }
  }

  self.OnClickBuscarProductosPorPagina = function (data, event) {
    if (event) {
      var datajs = { Data: data };
      $('#loader').show();
      self.BuscarInventarioMercaderiasPorSedePorPagina(datajs, event, self.PostOnClickBuscarProductosPorPagina);
    }
  }

  self.PostOnClickBuscarProductosPorPagina = function (data, event) {
    if (event) {
      $('#loader').hide();
      var objeto = self.Mercaderias()[0];
      self.SeleccionarProducto(objeto, event);
      $("#PaginadorJSONParaListaSimple").pagination("drawPage", data.pagina);
    }
  }

  self.CrearDataBusquedaParaListaSimple = function (data, event, inicio = true) {
    if (event) {

      var dataJSON = {
        IdFamiliaProducto: self.IdFamiliaProducto() == undefined ? '%' : self.IdFamiliaProducto(),
        IdMarca: self.IdMarca() == undefined ? '%' : self.IdMarca(),
        textofiltro: self.textofiltro() == '' ? '%' : self.textofiltro(),
        CheckConSinStock: self.CheckConSinStock() ? '1' : '0',
        IdSede: baseVista.IdSede(),
        numerofilasporpagina: NUMERO_ITEMS_BUSQUEDA_JSON_IMAGENES,
        pagina: 1,
        paginadefecto: 1,
        totalfilas: 0
      };

      return dataJSON;
    }
  }

  self.PostBuscar = function (data, event, base) {
    if (event) {
      var resultado = data;
      if (resultado) {
        $("#BuscadorMercaderia").find('#resultado_busqueda').addClass("hide");
        resultado.forEach(function (entry, key) {
          var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + entry.IdProducto + '.json';
          var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
          if (producto[0].Foto == null || producto[0].Foto == "") {
            producto[0].Foto = BASE_URL + CARPETA_IMAGENES + "nocover.png";
          }
          else {
            producto[0].Foto = SERVER_URL + CARPETA_IMAGENES + CARPETA_MERCADERIA + producto[0].IdProducto + SEPARADOR_CARPETA + producto[0].Foto;
          }
          producto[0].IdAlmacen = baseVista.IdAsignacionSede();
          var stock = self.FiltrarStockMercaderiaPorAlmacen(producto[0], event);
          if (stock) {
            producto[0].StockProducto = accounting.formatNumber(stock[0].StockMercaderia, NUMERO_DECIMALES_VENTA);
          }
          else {
            producto[0].StockProducto = '0.00';
          }
          producto[0].Cantidad = '0.00';
          if (producto[0].PrecioUnitario == null || producto[0].PrecioUnitario == "") {
            producto[0].PrecioUnitario = '0.00';
          }
          else {
            producto[0].PrecioUnitario = accounting.formatNumber(producto[0].PrecioUnitario, NUMERO_DECIMALES_VENTA);
          }

          if (producto[0].FechaIngresoCompra != "" && producto[0].FechaIngresoCompra != null) {
            let date = producto[0].FechaIngresoCompra.split('-');
            producto[0].FechaIngresoCompra = `${date[1]}/${date[2]}/${date[0]}`
          }

          if (producto[0].IdMonedaCompra == ID_MONEDA_DOLARES) {
            producto[0].CostoUnitarioCompraDolares = accounting.formatNumber(producto[0].CostoUnitarioCompra, NUMERO_DECIMALES_VENTA);
            producto[0].CostoUnitarioCompraSoles = accounting.formatNumber(parseFloatAvanzado(producto[0].CostoUnitarioCompra) * parseFloatAvanzado(self.TipoCambio()), NUMERO_DECIMALES_VENTA);
          } else {
            producto[0].CostoUnitarioCompraSoles = accounting.formatNumber(producto[0].CostoUnitarioCompra, NUMERO_DECIMALES_VENTA);
            producto[0].CostoUnitarioCompraDolares = accounting.formatNumber(0, NUMERO_DECIMALES_VENTA);
          }

          var precioventasoles = parseFloatAvanzado((self.MargenUtilidad() / 100.00) * parseFloatAvanzado(producto[0].CostoUnitarioCompraSoles)) + parseFloatAvanzado(producto[0].CostoUnitarioCompraSoles);
          producto[0].PrecioVentaSoles = accounting.formatNumber(precioventasoles, NUMERO_DECIMALES_VENTA);


          producto[0].DescuentoUnitario = '0.00';
          producto[0].Cantidad = '1';

          producto[0].SubTotal = parseFloatAvanzado(producto[0].PrecioUnitario) * parseFloatAvanzado(producto[0].Cantidad);

          var objeto = new VistaModeloMercaderiaJSON(producto[0], baseVista);
          base.data.Mercaderias.push(objeto);
        });
      }
      else {
        $("#BuscadorMercaderia").find('#resultado_busqueda').removeClass("hide");
      }
    }
  }

  self.PostBuscarLista = function (data, event, base) {
    if (event) {
      var resultado = data;
      if (resultado) {
        $("#BuscadorMercaderia").find('#resultado_busqueda').addClass("hide");
        resultado.forEach(function (entry, key) {
          var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + entry.IdProducto + '.json';
          var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);

          producto[0].IdAlmacen = baseVista.IdAsignacionSede();
          var stock = self.FiltrarStockMercaderiaPorAlmacen(producto[0], event);
          if (stock) {
            producto[0].StockProducto = accounting.formatNumber(stock[0].StockMercaderia, NUMERO_DECIMALES_VENTA);
          } else {
            producto[0].StockProducto = '0.00';
          }
          producto[0].Cantidad = '0.00';
          if (producto[0].PrecioUnitario == null || producto[0].PrecioUnitario == "") {
            producto[0].PrecioUnitario = '0.00';
          }
          else {
            producto[0].PrecioUnitario = accounting.formatNumber(producto[0].PrecioUnitario, NUMERO_DECIMALES_VENTA);
          }

          if (producto[0].FechaIngresoCompra != "" && producto[0].FechaIngresoCompra != null) {
            let date = producto[0].FechaIngresoCompra.split('-');
            producto[0].FechaIngresoCompra = `${date[1]}/${date[2]}/${date[0]}`
          }

          if (producto[0].IdMonedaCompra == ID_MONEDA_DOLARES) {
            producto[0].CostoUnitarioCompraDolares = accounting.formatNumber(producto[0].CostoUnitarioCompra, NUMERO_DECIMALES_VENTA);
            producto[0].CostoUnitarioCompraSoles = accounting.formatNumber(parseFloatAvanzado(producto[0].CostoUnitarioCompra) * parseFloatAvanzado(self.TipoCambio()), NUMERO_DECIMALES_VENTA);
          } else {
            producto[0].CostoUnitarioCompraSoles = accounting.formatNumber(producto[0].CostoUnitarioCompra, NUMERO_DECIMALES_VENTA);
            producto[0].CostoUnitarioCompraDolares = accounting.formatNumber(0, NUMERO_DECIMALES_VENTA);
          }

          var precioventasoles = parseFloatAvanzado((self.MargenUtilidad() / 100.00) * parseFloatAvanzado(producto[0].CostoUnitarioCompraSoles)) + parseFloatAvanzado(producto[0].CostoUnitarioCompraSoles);
          producto[0].PrecioVentaSoles = accounting.formatNumber(precioventasoles, NUMERO_DECIMALES_VENTA);


          producto[0].DescuentoUnitario = '0.00';
          producto[0].Cantidad = '1';

          producto[0].SubTotal = parseFloatAvanzado(producto[0].PrecioUnitario) * parseFloatAvanzado(producto[0].Cantidad);

          var objeto = new VistaModeloMercaderiaJSON(producto[0], baseVista);
          base.data.Mercaderias.push(objeto);
        });
      }
      else {
        $("#BuscadorMercaderia").find('#resultado_busqueda').removeClass("hide");
      }
    }
  }

  self.SeleccionarProducto = function (data, event) {
    if (event) {
      if (data != undefined) {
        // var id = "#"+ data.IdProducto()+"_Producto";
        // $(id).addClass('active').siblings().removeClass('active');
        // var objeto = Knockout.CopiarObjeto(data);
        // ko.mapping.fromJS(objeto, {}, self.data.Cliente);
        // $("#img_FileFotoPreview").attr("src",data.ObtenerRutaFoto());
        // $("#TituloNombrePadreAlumno").text(data.RazonSocial());
      }
    }
  }

  self.PostOnClickBotonAñadir = function(data,event) {
    if(event) {
      $("#BuscadorMercaderiaListaSimple #TextoFiltro").focus();
      if (self.parentCallback) self.parentCallback(data,event);
    }
  }

  self.OnLimpiar = function(data,event) {
    if(event) {
      var dataJSON = {        
        numerofilasporpagina: 10,
        pagina: 1,
        paginadefecto: 1,
        totalfilas: 0
      };

      var datajs = { data: dataJSON };
      
      $("#PaginadorJSONParaListaSimple").paginador(datajs, self.PostOnLimpiar);
      self.totalfilas(datajs.totalfilas);
      
      self.Limpiar(data,event);
    }
  }

  self.PostOnLimpiar = function(data,event) {
    if(event) {

    }
  }


}  
