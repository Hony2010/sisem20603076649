ModeloProveedores = function (data) {
    var self = this;
    var base = data;
    
    self.copiatextofiltro = ko.observable("");

    self.ListarProveedores =function(data,event,callback) {
      if(event) {
        self.ConsultarProveedores(data,event,function($data,$event){
          base.data.Proveedores([]);

          ko.utils.arrayForEach($data.resultado, function (item) {
            base.data.Proveedores.push(new VistaModeloProveedor(item));
          });

          callback($data,$event);
        });
      }
    }

    self.ListarProveedoresPorPagina =function(data,event,callback) {
      if(event) {
        self.ConsultarProveedoresPorPagina(data,event,function($data,$event){
          base.data.Proveedores([]);

          ko.utils.arrayForEach($data, function (item) {
            base.data.Proveedores.push(new VistaModeloProveedor(item));
          });

          callback(data,event);
        });
      }
    }

    self.ConsultarProveedores = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Catalogo/cProveedores/ConsultarProveedores',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

    self.ConsultarProveedoresPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Catalogo/cProveedores/ConsultarProveedoresPorPagina',
          success: function (data) {
              $("#loader").hide();
              callback(data,event);
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            alertify.alert(jqXHR.responseText);
          }
        });
      }
    }

}
