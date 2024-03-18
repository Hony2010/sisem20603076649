VistaModeloRegistroCompraMasiva = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingMasivo, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.CompraMasiva,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.CompraMasiva.Nuevo(data,event,self.PostGuardar);
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        $("#loader").hide();
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        $("#loader").hide();
        // self.Nuevo(self.data.ComprobanteCompraNuevo,event);
        self.data.ComprasMasiva([]);
        self.data.ComprobantesCompra([]);
        var inputImage = document.getElementById("ParseExcel");
        inputImage.value = '';
      }
    }
  }

  self.GenerarExcel = function(data, event)
  {
    if(event)
    {
      $("#loader").show();
      var files = event.target.files,file;
      if (!files || files.length == 0) return;
      file = files[0];
      var fileReader = new FileReader();
      fileReader.onload = function (e) {
        // var filename = file.name;
        // // call 'xlsx' to read the file
        // var oFile = XLSX.read(e.target.result, {type: 'binary', cellDates:true, cellStyles:true});
        /* convert data to binary string */
        var data = new Uint8Array(e.target.result);
        var arr = new Array();
        for(var i = 0; i != data.length; ++i) arr[i] = String.fromCharCode(data[i]);
        var bstr = arr.join("");

        /* Call XLSX */
        var workbook = XLSX.read(bstr, {type:"binary", cellDates:true, cellStyles:true});
        /* DO SOMETHING WITH workbook HERE */
        var first_sheet_name = workbook.SheetNames[0];
        /* Get worksheet */
        var worksheet = workbook.Sheets[first_sheet_name];
        console.log(worksheet);
        var xls_object = XLSX.utils.sheet_to_json(worksheet,{raw:true});
        console.log(XLSX.utils.sheet_to_json(worksheet,{raw:true}));

        self.data.ComprasMasiva([]);
        self.data.ComprobantesCompra([]);

        var comprobante = null;
        var detalle = null;

        var distintos = xls_object.uniqueObjects(["NumeroDoc", "Serie", "RUC"]);
        var i = 0;
        distintos.forEach(function(entry, key){
          var error = [];

          var consulta = JSON.search(xls_object, '//*[NumeroDoc="'+entry.NumeroDoc+'" and Serie="'+entry.Serie.toUpperCase()+'" and RUC="'+entry.RUC+'"]');

          var arr = Object.keys(entry).map(function (key) { return entry[key]; });
          comprobante = ko.mapping.toJS(self.data.ComprobanteCompraNuevo, mappingIgnore);
          comprobante.IdComprobanteCompra = i;
          comprobante.SerieDocumento = arr[2];
          // comprobante.SerieDocumento = serie.SerieDocumento;
          comprobante.NumeroDocumento = arr[3];
          // comprobante.Proveedor = arr[4];
          var msecs = Date.parse(arr[0]);
          var d = new Date(msecs);
          comprobante.FechaEmision = d.toLocaleDateString();

          var documento = arr[5]; //Se obtiene el ruc de usuario
          var proveedor = self.ValidarProveedorCodigo(documento, event);
          if(proveedor != null)
          {
            comprobante.IdProveedor = proveedor.IdPersona;
            comprobante.RUC = proveedor.NumeroDocumentoIdentidad;
            comprobante.RazonSocial = proveedor.RazonSocial;
          }
          else {
            error.push("No se encontro proveedor en el sistema");
            comprobante.IdProveedor = 0;
            comprobante.RUC = documento;
            comprobante.RazonSocial = "NO DISPONIBLE";
          }

          comprobante.IdTipoDocumento = 4;
          comprobante.IdUsuario = 5;
          comprobante.IdFormaPago = 1;
          comprobante.FormaPago = arr[6];
          comprobante.IdTipoTarjeta = 0;
          comprobante.IdMotivoNotaDebito = 0;
          comprobante.IdMotivoNotaCredito = 0;
          comprobante.IdTipoCompra = TIPO_COMPRA.MERCADERIAS;
          comprobante.IdMetodoProrrateo = 0; //NINGUN METODO PRORRATEO PARA USUARIO

          comprobante.IdTipoOperacion = 1;
          comprobante.IdAsignacionSede = self.data.CompraMasiva.IdAsignacionSede();
          comprobante.FechaVencimiento = d.toLocaleDateString();


          comprobante.DetallesComprobanteCompra = [];

          var total = 0;
          var igv = 0;
          consulta.forEach(function(entry2, key2){
            var arr2 = Object.keys(entry2).map(function (key3) { return entry2[key3]; });
            detalle = ko.mapping.toJS(comprobante.NuevoDetalleComprobanteCompra);
            // detalle.IdDetalleComprobanteCompra = i;
            detalle.IdDetalleComprobanteCompra = '';

            var codigo_producto = parseInt(arr2[7]);
            // var codigo_producto = arr2[7];
            var mercaderia = self.ValidarProductoCodigo(codigo_producto, event);
            if(mercaderia != null)
            {
              detalle.CodigoProducto = mercaderia.CodigoMercaderia;
              detalle.IdProducto = mercaderia.IdProducto;
              detalle.NombreProducto = mercaderia.NombreProducto;
            }
            else {
              error.push("No se encontro producto en el sistema");
              detalle.CodigoProducto = codigo_producto;
              detalle.IdProducto = 0;
              detalle.NombreProducto = arr2[8];
            }

            detalle.AbreviaturaUnidadMedida = arr2[9];
            detalle.Cantidad = arr2[10];
            detalle.PrecioUnitario = arr2[11];
            detalle.CostoUnitario = arr2[11];
            detalle.SubTotal = arr2[12];
            detalle.ValorUnitario = parseFloat(detalle.PrecioUnitario) / 1.18;
            detalle.ValorVentaItem = parseFloat(detalle.ValorUnitario) * parseFloat(detalle.Cantidad);
            detalle.ISCItem = 0;
            detalle.ValorReferencial = 0;
            detalle.DescuentoItem = 0;
            detalle.DescuentoUnitario = 0;
            detalle.SaldoPendienteNotaCredito = parseFloat(detalle.SubTotal);
            detalle.SaldoPendienteEntrada = parseFloat(detalle.Cantidad);
            total += parseFloat(detalle.SubTotal);
            comprobante.DetallesComprobanteCompra.push(detalle);
          });

          comprobante.Observaciones = error;
          comprobante.IGV = total * 0.18;
          comprobante.ValorVentaGravado = total - parseFloat(comprobante.IGV);
          comprobante.ValorVentaNoGravado = 0;
          comprobante.Total = total.toFixed(2);
          comprobante.SaldoNotaCredito = total.toFixed(2);
          comprobante.MontoLetra = Number(comprobante.Total).DenominacionMonedaSOLES();
          self.data.ComprobantesCompra.push(comprobante);

          var documento = new VistaModeloCabeceraCompraMasiva(comprobante);
          self.data.ComprobanteCompra.push(documento);

          var id_fila = "#" + documento.IdComprobanteCompra() + "_comprobantecompra";

          if(error.length > 0)
          {
            $(id_fila).css('color', 'red');
          }
          else {
            $(id_fila).css('color', 'green');
          }

          i++;
        });
        console.log(ko.mapping.toJS(self.data.ComprobantesCompra()));
      };

      fileReader.readAsArrayBuffer(file);

      $("#loader").hide();
    }
  }


  self.ValidarProveedorCodigo = function(codigo, event)
  {
    if(event)
    {
      var codigo = codigo;
      var url_json = SERVER_URL + URL_JSON_PROVEEDORES;

      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*[NumeroDocumentoIdentidad="'+codigo+'"]');

      var item = null;
      if (rpta.length > 0)  {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        item = producto[0];
      }

      return item;
    }
  }

  self.ValidarProductoCodigo = function(codigo, event)
  {
    if(event)
    {
      var codigo = codigo.toUpperCase();
      var url_json = SERVER_URL + URL_JSON_MERCADERIAS;

      var json = ObtenerJSONCodificadoDesdeURL(url_json);
      var rpta = JSON.search(json, '//*[CodigoMercaderia="'+codigo+'"]');

      var item = null;
      if (rpta.length > 0)  {
        var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
        var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto);
        item = producto[0];
      }

      return item;
    }
  }



}
