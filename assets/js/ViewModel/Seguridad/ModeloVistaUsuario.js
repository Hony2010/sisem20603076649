UsuariosModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
    self.ConfirmarClaveUsuario = ko.observable(self.ClaveUsuario());
    self.ConfirmarRespuestaSeguridad = ko.observable(self.RespuestaSeguridad());

    self.CargarFoto = function(data, event) {
      if(event){
        if(data != "")
        {
          var src = "";
          console.log(data.IdPersona());

          //console.log("ID PRODUCTO: " + data.IdPersona() + "  ., FOTO NOMBRE: " + data.Foto())
          if (data.IdPersona()=="" || data.IdPersona() == null || data.Foto() == null || data.Foto() == "")
              src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
          else
              src=SERVER_URL + CARPETA_IMAGENES + CARPETA_EMPLEADO+data.IdPersona()+SEPARADOR_CARPETA+data.Foto();

          return src;
        }
      }

     }

     self.Seleccionar = function (data,event)  {

       if(event)
       {
         console.log("Seleccionar");
         Models.data.AccesosRol([]);

         var id = "#"+ data.IdUsuario();
         $(id).addClass('active').siblings().removeClass('active');
         //debugger;

         //var objeto = Knockout.CopiarObjeto(data);
         _objeto = new UsuariosModel(Knockout.CopiarObjeto(data));

         var copia_data = ko.mapping.toJS(data);
         copia_data.ConfirmarClaveUsuario = data.ClaveUsuario();
         copia_data.ConfirmarRespuestaSeguridad = data.RespuestaSeguridad();
         ko.mapping.fromJS(copia_data, {}, Models.data.Usuario);

         Models.data.Usuario.Contador(event);


         Models.data.Empleado.AgregarEventoExterno(Models.data.Usuario.CargarEmpleado, event);
         $("#NombreEmpleado").easyAutocomplete(new optionsAutoCompletadoEmpleado("#NombreEmpleado", Models.data.Empleado.CargarEmpleado));

         $("#img_FileFoto").attr("src",self.CargarFoto(data, event));//OJO AQUI
         $("#img_FileFotoPreview").attr("src",self.CargarFoto(data, event));

       }

     }

    self.SeleccionarAnterior = function (data, event)  {
      if(event){
        var id = "#"+data.IdUsuario();
        var anteriorObjeto = $(id).prev();

        anteriorObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false)
        {
          var match = ko.utils.arrayFirst(Models.data.Usuarios(), function(item) {
                return anteriorObjeto.attr("id") == item.IdUsuario();
            });

          /*if(match)
          {
            Models.data.Usuario= match;
          }*/
          $("#img_FileFoto").attr("src",self.CargarFoto(match, event));//OJO AQUI
          $("#img_FileFotoPreview").attr("src",self.CargarFoto(match, event));
        }
      }

    }

    self.SeleccionarSiguiente = function (data, event)  {
      if(event)
      {
        var id = "#"+data.IdUsuario();
        var siguienteObjeto = $(id).next();

        if (siguienteObjeto.length > 0)
        {
          siguienteObjeto.addClass('active').siblings().removeClass('active');

          if (_modo_nuevo == false) //revisar
          {
            var match = ko.utils.arrayFirst(Models.data.Usuarios(), function(item) {
                  return siguienteObjeto.attr("id") == item.IdUsuario();
              });

            /*if(match)
            {
              Models.data.Usuario = match;
            }*/
            $("#img_FileFoto").attr("src",self.CargarFoto(match, event));//OJO AQUI
            $("#img_FileFotoPreview").attr("src",self.CargarFoto(match, event));
          }
        }
        else {
          self.SeleccionarAnterior(data, event);
        }
      }

    }

    self.Editar  = function(data, event) {

      //console.log("Editar");
      if(event)
      {
        _opcion_guardar = 1;
        _modo_nuevo = false;
        if( _modo_nuevo == true )
        {

        }
        else
        {
          Models.data.AccesosUsuario([]);
          Models.MostrarTitulo("EDITAR USUARIO");
          $('#btn_LimpiarUsuario').text("Deshacer");
          $("#modalUsuario").modal("show");
          var nombreempleado = data.NombreCompleto() + ' ' + data.ApellidoCompleto();
          $("#NombreEmpleado").val(nombreempleado);
          _NombreEmpleado = nombreempleado;
          //setTimeout(function(){_NombreEmpleado = $("#NombreEmpleado").val();}, 250);

          $("#NombreUsuario").focus();

        }
        // _modo_nuevo = false;

      }
    }

    self.PreEliminar = function (data, event) {
      if(event)
      {
        setTimeout(function(){
          alertify.confirm("ELIMINACION DE USUARIO", "¿Desea borrar realmente?", function(){
            console.log("PreEliminarUsuario");

            if (data.IdUsuario() != null)
              self.Eliminar(data, event);
          }, function(){});
        }, 100);
      }

    }

    self.Eliminar = function (data, event) {
      if(event)
      {
        var objeto = data;
        //var datajs = ko.toJS({"Data":data});
        var copia_data = ko.mapping.toJS(data, mappingIgnore);
        var copiaData = ko.mapping.toJS(copia_data,{ignore: ['OpcionesSistema', 'Almacenes']});
        var datajs = {Data: JSON.stringify(copiaData)};
        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Seguridad/cUsuario/BorrarUsuario',
                success: function (data) {
                    if (data != null) {
                      console.log("BorrarUsuario");
                      //console.log(data);
                      if(data != "")
                      {
                        alertify.alert("HA OCURRIDO UN ERROR", data);
                      }
                      else {
                        //objeto = new UsuariosModel(objeto);
                        self.SeleccionarSiguiente(objeto, event);
                        Models.data.Usuarios.remove(objeto);
                      }
                    }
              },
              error : function (jqXHR, textStatus, errorThrown) {
                     console.log(jqXHR.responseText);
                 }
        });

      }
    }

}


UsuarioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.CambiarEstadoUsuario = ko.computed(function () {
      var estado = self.IndicadorEstadoUsuario() ? '1' : '0';
      self.EstadoUsuario(estado);
    }, this)

    self.Guardar =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        var nueva_data = null;

        if(_opcion_guardar != 0)
        {
          accion = "ActualizarUsuario";
          nueva_data = ko.mapping.toJS(Models.data.AccesosUsuario, mappingIgnore);
        }
        else
        {
          accion = "InsertarUsuario";
          nueva_data = ko.mapping.toJS(Models.data.AccesosRol, mappingIgnore);
        }
        var _data = ko.mapping.toJS(data, mappingIgnore);

        // var datajs = ko.mapping.toJS({"Data" : Models.data.Usuario}, mappingIgnore);
        var copia_data = ko.mapping.toJS(_data, mappingIgnore);
        copia_data.OpcionesSistema = nueva_data;

        var datajs = {Data: JSON.stringify(copia_data)};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Seguridad/cUsuario/' + accion,
          success: function (data) {
              console.log(data);
              if(_opcion_guardar != 0){
                if(!data.error)
                {
                  //ko.mapping.fromJS(_data, MappingUsuario, Models.data.Usuario);
                  ko.mapping.fromJS(data, MappingUsuario, Models.data.Usuario);
                  Models.data.Usuario.AnteriorIdPersona(Models.data.Usuario.IdPersona());
                  var fila_objeto =ko.utils.arrayFirst(Models.data.Usuarios(), function(item) {
                      return Models.data.Usuario.IdUsuario() == item.IdUsuario();
                    });

                  //fila_objeto = new UsuariosModel(fila_objeto);
                  var objeto = ko.mapping.toJS(Models.data.Usuario); //Knockout.CopiarObjeto(Models.data.Usuario);
                  objeto = new UsuariosModel(objeto);

                  Models.data.Usuarios.replace(fila_objeto, objeto);

                  objeto.Seleccionar(Models.data.Usuario, event);

                  $("#modalUsuario").modal("hide");
                }
                else {
                  alertify.alert("HA OCURRIDO UN ERROR", data.error.msg);
                }
              }
              else {
                if(!data.error)
                {
                  // var objeto = new UsuarioModel(data);
                  ko.mapping.fromJS(data, {}, Models.data.Usuario);
                  //console.log("ID PRODUCTO" + data.IdUsuario);
                  Models.data.Usuario.AnteriorIdPersona(Models.data.Usuario.IdPersona());
                  var copia_objeto = ko.mapping.toJS(Models.data.Usuario);//Knockout.CopiarObjeto(Models.data.Usuario);
                  copia_objeto = new UsuariosModel(copia_objeto);
                  Models.data.Usuarios.push(copia_objeto);

                  copia_objeto.Seleccionar(Models.data.Usuario, event);
                  _opcion_guardar = 1;
                  _modo_nuevo = false;
                  $("#modalUsuario").modal("hide");
                }
                else {
                  alertify.alert("HA OCURRIDO UN ERROR", data.error.msg, function(){
                    $("#NombreUsuario").focus();
                  });
                }
              }

              $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            //console.log(jqXHR.responseText);
            $("#loader").hide();
          }

        });
      }

    }

    self.LimpiarImagen = function(event){
      if(event){
        var src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
        $('#img_FileFoto').attr('src', src);
        $('#img_FileFotoPreview').attr('src', src);
      }

    }

    self.AccesoSistema = function(data, event)
    {
      if(event)
      {
        if(_opcion_guardar == 0){
          self.CargarAccesosRol(data, event);
            $("#modalAccesoRol").modal("show");
        }
        else{
          self.CargarAccesosUsuario(data, event);
          $("#modalAccesoUsuario").modal("show");
        }
      }
    }

    self.CargarAccesosRol = function(data, event)
    {
      if(event)
      {
        var accion = "";
        accion = "/Seguridad/cMenu/CargarOpcionesPorRol";

        var copia_data = ko.mapping.toJS(data, mappingIgnore);
        var datajs = {Data: JSON.stringify(copia_data)};

        $.ajax({
                type: 'POST',
                data: datajs,
                dataType: "json",
                url: SITE_URL + accion,
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        Models.data.AccesosRol([]);
                        ko.utils.arrayForEach(data, function (item) {
                            Models.data.AccesosRol.push(new AccesosRolModel(item));
                          });
                }
            }
        });
      }
    }

    self.CargarAccesosUsuario = function(data, event) {
      if(event) {
        var accion = "";
        accion = "/Seguridad/cMenu/CargarOpcionesPorUsuario";
        var copia_data = ko.mapping.toJS(data, mappingIgnore);
        var datajs = {Data: JSON.stringify(copia_data)};

        $.ajax({
                type: 'POST',
                data: datajs,
                dataType: "json",
                url: SITE_URL + accion,
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        Models.data.AccesosUsuario([]);
                        ko.utils.arrayForEach(data, function (item) {
                            Models.data.AccesosUsuario.push(new AccesosUsuarioModel(item));
                          });

                }
            }
        });
      }
    }

    self.Deshacer = function(event)
    {
      if(event)
      {
        if($('#btn_LimpiarUsuario').text() == "Deshacer")
        {
          var objeto = new UsuariosModel(_objeto);
          objeto.Seleccionar(_objeto, event);
          $("#NombreEmpleado").val(_NombreEmpleado);
        }
        else if($('#btn_LimpiarUsuario').text() == "Limpiar")
        {
          ko.mapping.fromJS(Models.data.NuevoUsuario, MappingUsuario, Models.data.Usuario);
          /*LIMPIEZA DE FORMULARIO*/
          //document.getElementById("form").reset();
          $("#NombreEmpleado").val("");
          //LIMPIADOR DE IMAGENES A BLANCO
          self.LimpiarImagen(event);
          $('#btn_LimpiarUsuario').text("Limpiar");


          setTimeout(function(){
            $("#NombreUsuario").focus();
          }, 500);
        }

      }
    }

    self.Cerrar = function(data, event)
    {
      if(event)
      {
        $("#modalUsuario").modal("hide");

        if(_modo_nuevo == true){
          _modo_nuevo = false;
        }

        var objeto = new UsuariosModel(_objeto);
        objeto.Seleccionar(_objeto, event);
        // data.Seleccionar(_objeto, event);

      }
    }

    self.CargarEmpleado = function(data, event){
      if(event)
      {
        var objeto = Models.data.Usuario;
        ko.mapping.fromJS(data, {}, objeto);

        //ACTUALIZAMOS EL ACCESO
        self.CargarAccesosRol(data, event);
      }

    }

    self.AgregarEmpleado = function(event){
      if(event)
      {
        $('#combo-tipopersona').val(2);
        $('#combo-tipopersona').prop("disabled", true);
        $('#btn_LimpiarEmpleado').text("Limpiar");
        //_objeto_empleado = Models.data.NuevoUsuario;

        ko.mapping.fromJS(Models.data.NuevoEmpleado, {}, Models.data.Empleado);
        Models.data.Empleado.InicializarValidator(event);
        Models.data.Usuario.Contador(event);
        $("#modalEmpleado").modal('show');

        setTimeout(function(){
          $("#NumeroDocumentoIdentidad").focus();
        },500);

      }
    }

    self.SeleccionarTodasItems = function (data, event) {
      if (event) {
        var sel = $("#selector_almacen_todos").prop("checked");
        console.log(sel);
        console.log(self.SeleccionarTodos());
        for (var i = 0; i < self.Almacenes().length; i++) {
          var id_almacen = '#' + self.Almacenes()[i].IdSede() + '_almacen';
          $(id_almacen).prop("checked", sel);
          Models.data.Usuario.Almacenes()[i].Seleccionado($(id_almacen).prop("checked"));
        }
        if (sel == true) {
          self.NumeroItemsSeleccionadas(self.Almacenes().length);
        } else {
          self.NumeroItemsSeleccionadas('0');
        }
        return true;
      }
    }

    self.CambioAlmacen = function(data, event)
    {
      if(event)
      {
        self.Contador(event);
      }
    }


    self.Contador = function(event)
    {
      if(event){
        var NumeroItemsSeleccionadas = 0;

        for (var i = 0; i < self.Almacenes().length; i++) {
          var id_almacen = '#' + self.Almacenes()[i].IdSede() + '_almacen';
          if ($(id_almacen).prop("checked")) NumeroItemsSeleccionadas++;
        }

        if(self.Almacenes().length == NumeroItemsSeleccionadas)
        {
          $("#selector_almacen_todos").prop("checked", true);
        }
        else {
          $("#selector_almacen_todos").prop("checked", false);
        }
        self.NumeroItemsSeleccionadas(NumeroItemsSeleccionadas);
      }
    }

    self.Contador(window);

    self.SeleccionarTodasItemsCaja = function (data, event) {
      if (event) {
        var sel = $("#selector_caja_todos").prop("checked");

        for (var i = 0; i < self.Cajas().length; i++) {
          var id_almacen = '#' + self.Cajas()[i].IdCaja() + '_caja';
          $(id_almacen).prop("checked", sel);
          Models.data.Usuario.Cajas()[i].Seleccionado($(id_almacen).prop("checked"));
        }
        if (sel == true) {
          self.NumeroCajasSeleccionadas(self.Cajas().length);
        } else {
          self.NumeroCajasSeleccionadas('0');
        }
        return true;
      }
    }

    self.CambioCaja = function(data, event)
    {
      if(event) {
        self.ContadorCaja(event);
      }
    }

    self.ContadorCaja = function(event)
    {
      if(event){
        var NumeroItemsSeleccionadas = 0;

        for (var i = 0; i < self.Cajas().length; i++) {
          var id_caja = '#' + self.Cajas()[i].IdCaja() + '_caja';
          if ($(id_caja).prop("checked")) {
            NumeroItemsSeleccionadas++;
          }
        }

        if(self.Cajas().length == NumeroItemsSeleccionadas)
        {
          $("#selector_caja_todos").prop("checked", true);
        }
        else {
          $("#selector_caja_todos").prop("checked", false);
        }
        self.NumeroCajasSeleccionadas(NumeroItemsSeleccionadas);
      }
    }

    self.ContadorCaja(window);

    self.SeleccionarTodasItemsTurno = function (data, event) {
      if (event) {
        var sel = $("#selector_turno_todos").prop("checked");

        for (var i = 0; i < self.Turnos().length; i++) {
          var id_almacen = '#' + self.Turnos()[i].IdTurno() + '_turno';
          $(id_almacen).prop("checked", sel);
          Models.data.Usuario.Turnos()[i].Seleccionado($(id_almacen).prop("checked"));
        }
        if (sel == true) {
          self.NumeroTurnosSeleccionadas(self.Turnos().length);
        } else {
          self.NumeroTurnosSeleccionadas('0');
        }
        return true;
      }
    }

    self.CambioTurno = function(data, event)
    {
      if(event) {
        self.ContadorTurno(event);
      }
    }

    self.ContadorTurno = function(event)
    {
      if(event){
        var NumeroItemsSeleccionadas = 0;

        for (var i = 0; i < self.Turnos().length; i++) {
          var id_turno = '#' + self.Turnos()[i].IdTurno() + '_turno';
          if ($(id_turno).prop("checked")) {
            NumeroItemsSeleccionadas++;
          }
        }

        if(self.Turnos().length == NumeroItemsSeleccionadas)
        {
          $("#selector_turno_todos").prop("checked", true);
        }
        else {
          $("#selector_turno_todos").prop("checked", false);
        }
        self.NumeroTurnosSeleccionadas(NumeroItemsSeleccionadas);
      }
    }

    self.ContadorTurno(window);

    self.OnKeyEnter = function(data,event) {
      if (event) {
        var resultado = $(event.target).enterToTab(event);
        return resultado;
      }
    }

    self.OnFocus = function(data,event) {
      if(event)  {
          $(event.target).select();
      }
    }

}

AlmacenesModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    // self.Seleccionado = ko.observable(new Boolean(data.Seleccionado));

}

var MappingUsuario = {
    'Usuarios': {
        create: function (options) {
            if (options)
              return new UsuariosModel(options.data);
            }
    },
    'Usuario': {
        create: function (options) {
            console.log('Usuario');
            console.log(options);
            if (options)
              return new UsuarioModel(options.data);
            }
    },
    'NuevoUsuario': {
        create: function (options) {
            if (options)
              return new UsuarioModel(options.data);
            }
    },
    'Almacenes': {
        create: function (options) {
            if (options)
              return new AlmacenesModel(options.data);
            }
    }
}
