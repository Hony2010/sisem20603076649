VistaModeloEmisionBoletaMasiva = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingMasivo, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.BoletaMasiva,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.BoletaMasiva.Nuevo(data,event,self.PostGuardar);
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
        // self.Nuevo(self.data.ComprobanteVentaNuevo,event);
        self.data.ComprobantesMasivo([]);
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

        var i = 1;
        self.data.ComprobantesMasivo([]);
        self.data.ComprobantesVenta([]);

        var comprobante = null;
        var detalle = null;
        var serie = null;
        var series = ko.mapping.toJS(self.data.BoletaMasiva.SeriesDocumento());
        series.forEach(function(entry, key){
          if(entry.IdCorrelativoDocumento == self.data.BoletaMasiva.IdCorrelativoDocumento())
          {
            serie = entry;
          }
        });

        var distintos = xls_object.uniqueObjects(["NumeroDoc", "Serie"]);
        distintos.forEach(function(entry, key){
          var consulta = JSON.search(xls_object, '//*[NumeroDoc="'+entry.NumeroDoc+'" and Serie="'+entry.Serie+'"]');

          var arr = Object.keys(entry).map(function (key) { return entry[key]; });
          comprobante = ko.mapping.toJS(self.data.ComprobanteVentaNuevo, mappingIgnore);
          comprobante.IdComprobanteVenta = i;
          // comprobante.SerieDocumento = arr[2];
          comprobante.SerieDocumento = serie.SerieDocumento;
          comprobante.NumeroDocumento = arr[3];
          comprobante.Cliente = arr[4];
          var msecs = Date.parse(arr[0]);
          var d = new Date(msecs);
          comprobante.FechaEmision = d.toLocaleDateString();

          comprobante.FechaMovimientoAlmacen = comprobante.FechaEmision;
          comprobante.FechaComprobante =  comprobante.FechaEmision;
          comprobante.IdCliente = 1;
          comprobante.IdTipoDocumento = 4;
          comprobante.IdMoneda = self.data.BoletaMasiva.IdMoneda();
          comprobante.IdUsuario = 5;
          comprobante.IdFormaPago = 1;
          comprobante.IdTipoTarjeta = 0;
          comprobante.IdMotivoNotaDebito = 0;
          comprobante.IdMotivoNotaCredito = 0;
          comprobante.IdTipoVenta = 1;
          comprobante.IdCorrelativoDocumento = serie.IdCorrelativoDocumento;
          comprobante.IdTipoOperacion = 1;
          comprobante.IdAsignacionSede = self.data.BoletaMasiva.IdAsignacionSede();
          comprobante.FechaVencimiento = d.toLocaleDateString();


          comprobante.DetallesComprobanteVenta = [];

          var total = 0;
          var igv = 0;
          consulta.forEach(function(entry2, key2){
            var arr2 = Object.keys(entry2).map(function (key3) { return entry2[key3]; });
            detalle = ko.mapping.toJS(comprobante.NuevoDetalleComprobanteVenta);
            // detalle.IdDetalleComprobanteVenta = i;
            detalle.IdDetalleComprobanteVenta = '';
            var codigo_producto = parseInt(arr2[7]);
            detalle.CodigoProducto = codigo_producto;

            url_json = SERVER_URL + URL_JSON_MERCADERIAS;
            var json = ObtenerJSONCodificadoDesdeURL(url_json);

            detalle.IdProducto = 0;
            var rpta = JSON.search(json, '//*[CodigoMercaderia="'+codigo_producto+'"]');

            if (rpta.length > 0)  {
              detalle.IdProducto = rpta[0].IdProducto;
              var ruta_producto = SERVER_URL + URL_RUTA_PRODUCTOS + rpta[0].IdProducto + '.json';
              var producto = ObtenerJSONCodificadoDesdeURL(ruta_producto); //obtenemos los atributos del producto
              detalle.CodigoTipoAfectacionIGV = producto[0].CodigoTipoAfectacionIGV;
              detalle = ko.mapping.fromJS(producto,{},detalle) // mapeamos todos los atributos de producto en detalle
            }

            detalle.NombreProducto = arr2[8];
            detalle.Unidad = arr2[9];
            detalle.Cantidad = arr2[10];
            detalle.PrecioUnitario = arr2[11];
            detalle.SubTotal = arr2[12];

            if(detalle.CodigoTipoAfectacionIGV == CODIGO_AFECTACION_IGV_GRAVADO) {
              detalle.ValorUnitario = parseFloat(detalle.PrecioUnitario) / 1.18;
              detalle.ValorVentaItem = parseFloat(detalle.ValorUnitario) * parseFloat(detalle.Cantidad);

            }
            else {
              detalle.ValorUnitario = parseFloat(detalle.PrecioUnitario);
              detalle.ValorVentaItem = parseFloat(detalle.ValorUnitario) * parseFloat(detalle.Cantidad);
            }

            detalle.IGVItem =  parseFloat(detalle.SubTotal) - parseFloat(detalle.ValorVentaItem);
            detalle.ISCItem = 0;
            detalle.ValorReferencial = 0;
            detalle.DescuentoItem = 0;
            detalle.DescuentoUnitario = 0;
            detalle.SaldoPendienteNotaCredito = parseFloat(detalle.SubTotal);
            detalle.SaldoPendienteEntrada = parseFloat(detalle.Cantidad);
            total += parseFloat(detalle.SubTotal);

            if(detalle.__ko_mapping__ != undefined)
              detalle.__ko_mapping__ = undefined;

            comprobante.DetallesComprobanteVenta.push(detalle);
          });



          if(detalle.CodigoTipoAfectacionIGV == CODIGO_AFECTACION_IGV_GRAVADO) {
            comprobante.IGV = (total /1.18) * 0.18; // aqui se deberia condicionar el  calculo de igv teniendo en cuenta el tipo o codigo de afectacion del profucto
            comprobante.ValorVentaGravado = total - parseFloat(comprobante.IGV);
            comprobante.ValorVentaNoGravado = 0;
          }
          else {
            comprobante.IGV = 0;
            comprobante.ValorVentaGravado = 0;
            comprobante.ValorVentaNoGravado = total;
          }

          comprobante.Total = total.toFixed(2);
          comprobante.SaldoNotaCredito = total.toFixed(2);
          comprobante.MontoLetra = Number(comprobante.Total).DenominacionMonedaSOLES();

          self.data.ComprobantesVenta.push(comprobante);
        });
        xls_object.forEach(function(entry, key){
          var arr = Object.keys(entry).map(function (key) { return entry[key]; });
          console.log(arr[0]);

          var documento = {};

          var msecs = Date.parse(arr[0]);
          var d = new Date(msecs);
          documento.FechaEmision = d.toLocaleDateString();
          documento.Tipo = arr[1];
          // documento.SerieDocumento = arr[2];
          documento.SerieDocumento = serie.SerieDocumento;
          documento.NumeroDocumento = arr[3];
          documento.RazonSocial = arr[4];
          documento.RUC = arr[5];
          documento.FormaPago = arr[6];
          documento.CodigoProducto = arr[7];
          documento.NombreProducto = arr[8];
          documento.Unidad = arr[9];
          documento.Cantidad = arr[10];
          documento.PrecioUnitario = arr[11];
          documento.SubTotal = arr[12];

          if (documento.SubTotal > 700) {
            alertify.alert('Emisión de Boleta Masiva','La Boleta N° '+documento.SerieDocumento+'-'+documento.NumeroDocumento+' tiene un monto mayor a S/ 700.00, necesita colocar los datos del Cliente.', function () {
              self.data.ComprobantesMasivo([]);
              self.data.ComprobantesVenta([]);
            })
            return false;
          }

          documento = new VistaModeloDetalleBoletaMasiva(documento);
          self.data.ComprobantesMasivo.push(documento);

          i++;
        });

        console.log(ko.mapping.toJS(self.data.ComprobantesVenta()));
        // var item = self.data.BoletaMasiva.DetallesInventarioInicial.Agregar(undefined,event);
        // item.InicializarVistaModelo(event,self.PostBusquedaProducto,self.CrearInventarioInicial);

      };

      fileReader.readAsArrayBuffer(file);

      $("#ParseExcel").val("");
      $("#loader").hide();
    }
  }

}
