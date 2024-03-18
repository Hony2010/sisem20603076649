VistaModeloImportacionRegistro = function (data) {
  var self = this;

  ko.mapping.fromJS(data, MappingMasivo, self);

  self.Inicializar = function ()  {
    self.Nuevo(self.data.ImportacionMasiva,window);
  }

  self.Nuevo = function(data,event) {
    if(event) {
      self.data.ImportacionMasiva.Nuevo(data,event,self.PostGuardar);
    }
  }

  self.PostGuardar = function(data,event) {
    if(event) {
      if(data.error) {
        $("#loader").hide();
        var inputImage = document.getElementById("ParseExcel");
        inputImage.value = '';
        alertify.alert(data.error.msg,function()  {
        });
      }
      else {
        $("#loader").hide();
        var inputImage = document.getElementById("ParseExcel");
        inputImage.value = '';
      }
    }
  }

  self.CargarExcel = function(data, event)
  {
    if(event)
    {
      self.data.ImportacionMasiva.DetallesImportacionMasiva([]);
      self.GenerarExcel(data, event, self.data.ImportacionMasiva.Estructura());
    }
  }

  self.GenerarExcel = function(data, event, estructura)
  {
    if(event)
    {
      // $("#loader").show();
      var files = event.target.files,file;
      if (!files || files.length == 0) return;
      file = files[0];
      var fileReader = new FileReader();
      fileReader.onload = function (e) {

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

        xls_object.forEach(function(entry, key){
          // console.log(entry);
          // var cliente = ko.mapping.toJS(entry, {}, estructura);
          // returns a new object with the values at each key mapped using mapFn(value)
          var origen = ko.mapping.toJS(estructura);
          var data_mapper = Mapper.mapeo(entry, origen);
          var data_json = Object.assign({}, data_mapper);

          self.data.ImportacionMasiva.DetallesImportacionMasiva.push(data_json);

        });


      };

      fileReader.readAsArrayBuffer(file);

      // $("#loader").hide();
    }
  }
}
