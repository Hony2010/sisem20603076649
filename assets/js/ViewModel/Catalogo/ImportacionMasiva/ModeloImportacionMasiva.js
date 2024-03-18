ModeloImportacionMasiva = function (data) {
  var self = this;
  var base = data;

  self.opcionProceso = ko.observable(opcionProceso.Nuevo);
  self.EstaProcesado = ko.observable(false);
  self.showImportacionMasiva = ko.observable(false);

  self.InicializarModelo = function (event) {
    if(event) {

    }
  }

  self.NuevoImportacionMasiva = function(data,event) {
    if(event) {
      self.EstaProcesado(false);
      var copia = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(copia,{},self);
      var _mappingIgnore  =ko.toJS(mappingIgnore);
      var mappingfinal = Object.assign(_mappingIgnore,{ include : "DetallesImportacionMasiva" });
      self.ImportacionMasivaInicial = ko.mapping.toJS(data,mappingfinal);
      self.opcionProceso(opcionProceso.Nuevo);
      self.titulo ="Importación Masiva";
    }
  }

  self.GuardarImportacionMasiva = function (data, event,callback) {
    if (event)  {
      var datajs =ko.mapping.toJS(data, mappingIgnore);
      datajs = {Data: JSON.stringify(datajs)};

      self.Insertar(datajs, event, function($data, $event){
        if($data.error)
        {
          callback($data,$event);
        }
        else {
          callback($data,$event);
        }
      });

    }
  }

  self.Insertar = function(data,event,callback) {
    if(event) {
      $.ajax({
        type: 'POST',
        data : data,
        dataType : "json",
        url: SITE_URL+'/Catalogo/cImportacionMasiva/RegistrarImportacionMasiva',
        success: function (data) {
          callback(data,event);
        },
        error :  function (jqXHR, textStatus, errorThrown) {
          var data = {error:{msg:jqXHR.responseText}};
          callback(data,event);
        }
      });
    }
  }



}
