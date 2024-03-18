VistaModeloBusquedaAvanzadaProducto = function (data) {
  var self = this;
  var baseVista = this;
  var baseBusqueda = this;
  ko.mapping.fromJS(data, {}, self);
  ModeloBusquedaAvanzadaProducto.call(this, self);
  self.textofiltro = ko.observable("");

  self.InicializarVistaModelo = function (data, event, base) {
    if (event) {
      self.InicializarModelo(data, event);
      var DataBusqueda = self.CrearDataBusqueda(data, event, false);
      $("#PaginadorJSON").paginador(DataBusqueda, self.ConsultarPorPagina);
      baseVista = base;
    }
  }

  // self.ConsultarPorPagina = function (data,event) {
  //   if(event) {
  //     console.log(data);
  //     // var data = ko.mapping.toJS(data, mappingIgnore);
  //     baseBusqueda.data.Mercaderias([]);
  //     data.textofiltro = self.textofiltro();
  //     var resultado = self.BuscarProductosPorNombreProducto(data, event, baseVista);
  //     self.PostBuscar(resultado, event, baseBusqueda);
  //     $("#PaginadorJSON").pagination("drawPage", data.pagina);
  //     // self.ListarComprobantesCompraPorPagina(data,event,self.PostConsultarPorPagina);
  //   }
  // }

  self.DataFiltros = function (data, event) {
    if (event) {
      var JSONpage = {};
      JSONpage.textofiltro = '';
      JSONpage.idfamilia = '';
      JSONpage.pagina = 1;
      JSONpage.numerofilasporpagina = NUMERO_ITEMS_BUSQUEDA_JSON_IMAGENES;
      JSONpage.paginadefecto = 1;
      JSONpage.totalfilas = 0;
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
      // console.log(dJSON);
      if ($(event.target).data('familia') == 'undefined' || $(event.target).data('familia') == undefined) {
        var familias = $('.btn-familias').filter('.active');
        if (familias.length > 0) {
          dJSON.idfamilia = $(familias[0]).data('familia');
        }
        else {
          var familias = $('.btn-familias');
          dJSON.idfamilia = $(familias[0]).data('familia');
        }

        var subfamilias = $('.btn-subfamilias').filter('.active');
        if (familias.length > 0) {
          dJSON.idsubfamilia = $(subfamilias[0]).data('subfamilia');
        }
        else {
          var subfamilias = $('.btn-subfamilias');
          dJSON.idsubfamilia = $(subfamilias[0]).data('subfamilia');
        }
      }
      else {
        dJSON.idfamilia = $(event.target).data('familia');
      }
      var resultado = self.BuscarProductosPorNombreProducto(dJSON, event, baseVista);
      // var resultado = self.FiltrarProductoPorNombreProducto(data, event, baseVista);
      self.PostBuscar(resultado, event, base);
      //console.log(resultado);
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

          // producto[0].IdAlmacen = baseVista.IdAsignacionSede();
          // var stock = self.FiltrarStockMercaderiaPorAlmacen(producto[0], event);
          // if(stock)
          // {
          //   producto[0].StockProducto = accounting.formatNumber(stock[0].StockMercaderia, NUMERO_DECIMALES_VENTA);
          // }
          // else {
          //   producto[0].StockProducto = '0.00';
          // }

          producto[0].Cantidad = '0.00';
          if (producto[0].PrecioUnitario == null || producto[0].PrecioUnitario == "") {
            producto[0].PrecioUnitario = '0.00';
          }
          else {
            producto[0].PrecioUnitario = accounting.formatNumber(producto[0].PrecioUnitario, NUMERO_DECIMALES_VENTA);
          }

          producto[0].DescuentoUnitario = '0.00';
          var objeto = new VistaModeloMercaderiaJSON(producto[0], baseVista);
          base.data.Mercaderias.push(objeto);
        });
      }
      else {
        $("#BuscadorMercaderia").find('#resultado_busqueda').removeClass("hide");
      }
    }
  }

  self.BuscarMercaderiaPorSubFamilia = function (data, event, base) {
    if (event) {
      base.data.Mercaderias([]);
      var dJSON = self.CrearDataBusqueda(data, event);
      var idsubfamilia = $(event.target).data('subfamilia');

      if (idsubfamilia == 'undefined' || idsubfamilia == undefined) {
        var subfamilias = $('.btn-subfamilias').filter('.active');
        if (subfamilias.length > 0) {
          dJSON.idsubfamilia = $(subfamilias[0]).data('subfamilia');
        }
        else {
          var subfamilias = $('.btn-subfamilias');
          dJSON.idsubfamilia = $(subfamilias[0]).data('subfamilia');
        }
      }
      else {
        dJSON.idsubfamilia = $(event.target).data('subfamilia');
      }

      var resultado = self.BuscarProductosPorSubFamilia(dJSON, event, baseVista);
      self.PostBuscar(resultado, event, base);
      console.log(resultado);
    }
  }

  self.BuscarSubFamilias = function (data, event, base) {
    if (event) {
      var idfamilia = $(event.target).data('familia');
      base.data.SubFamiliasProductoFiltrado([])

      if (idfamilia != 'undefined' || idfamilia != undefined) {
        var subfamiliasproducto = base.data.SubFamiliasProducto();
        var nuevalista = [];

        subfamiliasproducto.forEach(function (entry, key) {
          if (entry.IdFamiliaProducto() == idfamilia) {
            nuevalista.push(entry);
          }
        })
        base.data.SubFamiliasProductoFiltrado(nuevalista);
        $(".btn-subfamilias").eq(0).addClass("active").click();
      }
    }
  }


  self.BuscarMercaderiaPorCodigoDeBarras = function (data, event, base) {
    if (event) {
      baseBusqueda = base;
      base.data.Mercaderias([]);
      var dJSON = self.CrearDataBusqueda(data, event);
      // console.log(dJSON);
      if ($(event.target).data('familia') == 'undefined' || $(event.target).data('familia') == undefined) {
        var familias = $('.btn-familias').filter('.active');
        if (familias.length > 0) {
          dJSON.idfamilia = $(familias[0]).data('familia');
        }
        else {
          var familias = $('.btn-familias');
          dJSON.idfamilia = $(familias[0]).data('familia');
        }

        var subfamilias = $('.btn-subfamilias').filter('.active');
        if (familias.length > 0) {
          dJSON.idsubfamilia = $(subfamilias[0]).data('subfamilia');
        }
        else {
          var subfamilias = $('.btn-subfamilias');
          dJSON.idsubfamilia = $(subfamilias[0]).data('subfamilia');
        }
      }
      else {
        dJSON.idfamilia = $(event.target).data('familia');
      }
      dJSON.CodigoBarras = $("#CodigoBarras").val();

      var resultado = self.BuscarProductosPorCodigoMercaderia(dJSON, event, baseVista);
      debugger
      self.PostBuscarMercaderiaPorCodigoDeBarras(resultado, event, base);
    }
  }

  self.PostBuscarMercaderiaPorCodigoDeBarras = function (data, event, base) {
    if (event) {
      var resultado = data;
      if (resultado) {
        $("#BuscadorMercaderia").find('#resultado_busqueda').addClass("hide");
        resultado.forEach(function (entry, key) {
          var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + entry.IdProducto + '.json';
          var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto)[0];

          if (producto.Foto == null || producto.Foto == "") {
            producto.Foto = BASE_URL + CARPETA_IMAGENES + "nocover.png";
          }
          else {
            producto.Foto = SERVER_URL + CARPETA_IMAGENES + CARPETA_MERCADERIA + producto.IdProducto + SEPARADOR_CARPETA + producto.Foto;
          }

          producto.Cantidad = '0.00';
          if (producto.PrecioUnitario == null || producto.PrecioUnitario == "") {
            producto.PrecioUnitario = '0.00';
          }
          else {
            producto.PrecioUnitario = accounting.formatNumber(producto.PrecioUnitario, NUMERO_DECIMALES_VENTA);
          }

          producto.DescuentoUnitario = '0.00';
          var objeto = new VistaModeloMercaderiaJSON(producto, baseVista);
          base.data.Mercaderias.push(objeto);
          base.data.PuntoVenta.OnClickAgregarMercaderiaImagenPuntoVenta(objeto, event)
        });
        $("#CodigoBarras").select();
      }
      else {
        $("#BuscadorMercaderia").find('#resultado_busqueda').removeClass("hide");
      }
    }
  }
}
