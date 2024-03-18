AccesosUsuarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    // self.EstadoGeneral = ko.observable(true);
    self.EstadoNuevo = function()
    {
      if (data.OpcionesSistema) {
        var length = parseInt(data.OpcionesSistema.length, 10); // the <input> makes `pages` a string!
        var checks = 0;
        for (var i = 0; i < length; i++) {
          if(data.OpcionesSistema[i].EstadoOpcionUsuario == '1')
          {
            checks++;
          }
        }

        if(checks == length){
          return true;
        }
        else {
          return false;
        }
      }
    }

    self.EstadoGeneral = ko.observable(self.EstadoNuevo());

    self.EstadoGeneral4 = ko.pureComputed(function(){
        var length = parseInt(data.OpcionesSistema.length, 10); // the <input> makes `pages` a string!
        var checks = 0;
        for (var i = 0; i < length; i++) {
          if(data.OpcionesSistema[i].EstadoOpcionUsuario == '1')
          {
            checks++;
          }
        }

        if(checks == length){
          return true;
        }
        else {
          return false;
        }
      });

    self.CambiarEstadoCheck = function(data, event)
    {
      if(event)
      {
        if(data.EstadoGeneral() == true)
        {
          var length = parseInt(data.OpcionesSistema().length, 10); // the <input> makes `pages` a string!
          for (var i = 0; i < length; i++) {
            data.OpcionesSistema()[i].Estado(true);
          }
          console.log(true);
          // return false;
        }
        else {
          var length = parseInt(data.OpcionesSistema().length, 10); // the <input> makes `pages` a string!
          for (var i = 0; i < length; i++) {
            data.OpcionesSistema()[i].Estado(false);
          }
          console.log(false);
        }
      }
    }

    self.OpcionesSistema = ko.computed(function() {
      var list = [];
      if (data.OpcionesSistema) {
        var length = parseInt(data.OpcionesSistema.length, 10); // the <input> makes `pages` a string!

        for (var i = 0; i < length; i++) {
          list.push(new AccesoUsuarioModel(data.OpcionesSistema[i]));
        }
      }

      return list;
    });


}

AccesoUsuarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.ContarOpciones = function(){
      var idmodulo = self.IdModuloSistema();
      var item_val = ko.utils.arrayFirst(Models.data.AccesosUsuario(), function(item) {
          return idmodulo == item.IdModuloSistema();
      });

      var length = parseInt(item_val.OpcionesSistema().length, 10); // the <input> makes `pages` a string!
      var checks = 0;
      for (var i = 0; i < length; i++) {
        if(item_val.OpcionesSistema()[i].EstadoOpcionUsuario() == '1')
        {
          checks++;
        }
      }

      if(checks == length){
        item_val.EstadoGeneral(true);
      }
      else {
        item_val.EstadoGeneral(false);
      }
    }

    self.Estado =  ko.pureComputed({
      read:function(){
        return self.EstadoOpcionUsuario() == '0' ? false : true;
      },
      write: function(value){
        if(value == true)
        {
          self.EstadoOpcionUsuario('1');
          self.ContarOpciones();
        }
        else{
          self.EstadoOpcionUsuario('0');
          self.ContarOpciones();
        }
      }
    }, this);


}

var MappingAccesoUsuario = {
    'AccesosUsuario': {
        create: function (options) {
            if (options)
              return new AccesosUsuarioModel(options.data);
            }
    },
    'AccesoUsuario': {
        create: function (options) {
            if (options)
              return new AccesoUsuarioModel(options.data);
            }
    }

}
