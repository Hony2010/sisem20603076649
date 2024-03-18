VistaModeloTipoDocumentoIdentidad = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

TiposDocumentoIdentidadModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);


    self._CodigoDocumentoIdentidad = ko.observable(data.CodigoDocumentoIdentidad);
    self._NombreTipoDocumentoIdentidad = ko.observable(data.NombreTipoDocumentoIdentidad);
    self._NombreAbreviado = ko.observable(data.NombreAbreviado);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log(self._CodigoDocumentoIdentidad());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTipoDocumentoIdentidad());

        self.CodigoDocumentoIdentidad.valueHasMutated();
        self.NombreTipoDocumentoIdentidad.valueHasMutated();
        self.NombreAbreviado.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoDocumentoIdentidad("");
        self.NombreTipoDocumentoIdentidad("");
        self.NombreAbreviado("");
        self.CodigoDocumentoIdentidad(self._CodigoDocumentoIdentidad());
        self.NombreTipoDocumentoIdentidad(self._NombreTipoDocumentoIdentidad());
        self.NombreAbreviado(self._NombreAbreviado());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._CodigoDocumentoIdentidad.valueHasMutated();
        self._CodigoDocumentoIdentidad(self.CodigoDocumentoIdentidad());
        self._NombreTipoDocumentoIdentidad.valueHasMutated();
        self._NombreTipoDocumentoIdentidad(self.NombreTipoDocumentoIdentidad());
        self._NombreAbreviado.valueHasMutated();
        self._NombreAbreviado(self.NombreAbreviado());

      }
    }


}

TipoDocumentoIdentidadModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'TiposDocumentoIdentidad': {
        create: function (options) {
            if (options)
              return new TiposDocumentoIdentidadModel(options.data);
            }
    },
    'TipoDocumentoIdentidad': {
        create: function (options) {
            if (options)
              return new TipoDocumentoIdentidadModel(options.data);
            }
    }

}

IndexTipoDocumentoIdentidad = function (data) {

    var _modo_deshacer = false;
    var _codigotipodocumentoidentidad;
    var _nombretipodocumentoidentidad;
    var _abreviaturatipodocumentoidentidad;
    var _input_habilitado_tipodocumentoidentidad = false;
    var _idtipodocumentoidentidad;
    var _tipodocumentoidentidad;
    var _modo_nuevo_tipodocumentoidentidad = false;
    var _id_filatipodocumentoidentidad_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposDocumentoIdentidad = function() {
        console.log("ListarTiposDocumentoIdentidad");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoDocumentoIdentidad/ListarTiposDocumentoIdentidad',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad([]);
                        ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad.push(new TiposDocumentoIdentidadModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_tipodocumentoidentidad == false)
      {
        var id = "#"+data.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
        $(id).addClass('active').siblings().removeClass('active');
        _tipodocumentoidentidad = data;
      }

    }

    self.FilaButtonsTipoDocumentoIdentidad = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_tipodocumentoidentidad);
          if(_modo_nuevo_tipodocumentoidentidad == true)
          return;

          console.log("OTRA FILA AFECTADA");
          _tipodocumentoidentidad.Deshacer(null, event);
          _input_habilitado_tipodocumentoidentidad = false;
          $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
          self.HabilitarTablaSpanTipoDocumentoIdentidad(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_tipodocumentoidentidad == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoDocumentoIdentidad();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_tipodocumentoidentidad == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtipodocumentoidentidad = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtipodocumentoidentidad == item.IdTipoDocumentoIdentidad();
            });

          if(match)
          {
            _tipodocumentoidentidad = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoDocumentoIdentidad = function (data, option)  {
      //var id = "#"+data.IdTipoDocumentoIdentidad();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoDocumentoIdentidad').hide();
        $(id).find('.class_SpanTipoDocumentoIdentidad').show();
      }
      else
      {
        $(id).find('.class_InputTipoDocumentoIdentidad').show();
        $(id).find('.class_SpanTipoDocumentoIdentidad').hide();
      }

    }

    self.HabilitarTablaSpanTipoDocumentoIdentidad = function (data, option)  {
      //var id = "#"+data.IdTipoDocumentoIdentidad();
      var id = "#DataTables_Table_0_tipodocumentoidentidad";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoDocumentoIdentidad').hide();
        $(id).find('.class_InputTipoDocumentoIdentidad').show();
        //$(id).find('.guardar_button_TipoDocumentoIdentidad').show();
        //_input_habilitado_tipodocumentoidentidad = true;
      }
      else {
        $(id).find('.class_SpanTipoDocumentoIdentidad').show();
        $(id).find('.class_InputTipoDocumentoIdentidad').hide();
        $(id).find('.guardar_button_TipoDocumentoIdentidad').hide();
        //_input_habilitado_tipodocumentoidentidad = false;
      }

    }

    self.HabilitarButtonsTipoDocumentoIdentidad = function(data, option){
      var id = "#DataTables_Table_0_tipodocumentoidentidad";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoDocumentoIdentidad').prop("disabled", true);
        $(id).find('.borrar_button_TipoDocumentoIdentidad').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoDocumentoIdentidad').prop("disabled", false);
        $(id).find('.borrar_button_TipoDocumentoIdentidad').prop("disabled", false);
      }
    }


    self.AgregarTipoDocumentoIdentidad = function(data,event) {
          console.log("AgregarTipoDocumentoIdentidad");

          if ( _input_habilitado_tipodocumentoidentidad == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TipoDocumentoIdentidad);
            _tipodocumentoidentidad = new TiposDocumentoIdentidadModel(objeto);
            vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad.push(_tipodocumentoidentidad);

            //Deshabilitando buttons
            self.HabilitarButtonsTipoDocumentoIdentidad(null, false);
            $("#null_editar_button_TipoDocumentoIdentidad").prop("disabled", true);
            $("#null_borrar_button_TipoDocumentoIdentidad").prop("disabled", false);


            $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdTipoDocumentoIdentidad());


            var id_span_codigotipodocumentoidentidad ="#"+objeto.IdTipoDocumentoIdentidad()+"_span_CodigoDocumentoIdentidad";
            var id_input_codigotipodocumentoidentidad ="#"+objeto.IdTipoDocumentoIdentidad()+"_input_CodigoDocumentoIdentidad";

            var id_span_nombretipodocumentoidentidad ="#"+objeto.IdTipoDocumentoIdentidad()+"_span_NombreTipoDocumentoIdentidad";
            var id_input_nombretipodocumentoidentidad ="#"+objeto.IdTipoDocumentoIdentidad()+"_input_NombreTipoDocumentoIdentidad";

            var id_span_abreviaturatipodocumentoidentidad ="#"+objeto.IdTipoDocumentoIdentidad()+"_span_NombreAbreviado";
            var id_input_abreviaturatipodocumentoidentidad ="#"+objeto.IdTipoDocumentoIdentidad()+"_input_NombreAbreviado";

            var idbutton ="#"+objeto.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";

            console.log(idbutton);

            $(id_span_codigotipodocumentoidentidad).hide();
            $(id_input_codigotipodocumentoidentidad).show();

            $(id_span_nombretipodocumentoidentidad).hide();
            $(id_input_nombretipodocumentoidentidad).show();

            $(id_span_abreviaturatipodocumentoidentidad).hide();
            $(id_input_abreviaturatipodocumentoidentidad).show();

            $(idbutton).show();
            $(id_input_codigotipodocumentoidentidad).focus();

            _modo_nuevo_tipodocumentoidentidad = true;
            _input_habilitado_tipodocumentoidentidad = true;

            var tabla = $('#DataTables_Table_0_tipodocumentoidentidad');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarTipoDocumentoIdentidad =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarTipoDocumentoIdentidad");
        console.log(_tipodocumentoidentidad.NombreTipoDocumentoIdentidad());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _tipodocumentoidentidad});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoDocumentoIdentidad/InsertarTipoDocumentoIdentidad',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoDocumentoIdentidad");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoDocumentoIdentidad))
                      {
                        _tipodocumentoidentidad.IdTipoDocumentoIdentidad(data.IdTipoDocumentoIdentidad);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);

                        var id_tipodocumentoidentidad = "#"+ _tipodocumentoidentidad.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
                        self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, false);

                        var idbutton ="#"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";
                        $(idbutton).hide();

                         _tipodocumentoidentidad.Confirmar(null,event);
                         self.HabilitarButtonsTipoDocumentoIdentidad(null, true);

                        existecambio = false;
                        _input_habilitado_tipodocumentoidentidad = false;
                        _modo_nuevo_tipodocumentoidentidad = false;

                      }
                      else {
                        alertify.alert(data.IdTipoDocumentoIdentidad);
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

    self.ActualizarTipoDocumentoIdentidad = function(data,event) {
          console.log("ActualizarTipoDocumentoIdentidad");
          console.log(_tipodocumentoidentidad.NombreTipoDocumentoIdentidad());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _tipodocumentoidentidad});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cTipoDocumentoIdentidad/ActualizarTipoDocumentoIdentidad',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
                          console.log("ID5:"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad());
                          _tipodocumentoidentidad.Confirmar(null,event);

                          var id_tipodocumentoidentidad = "#"+ _tipodocumentoidentidad.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
                          self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, false);

                          var idbutton ="#"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_tipodocumentoidentidad = false;
                          _modo_nuevo_tipodocumentoidentidad = false;
                        }
                        else {
                          alertify.alert(data);
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

    self.EditarTipoDocumentoIdentidad = function(data, event) {

      if(event)
      {
        console.log("EditarTipoDocumentoIdentidad");
        console.log("ID.:"+data.IdTipoDocumentoIdentidad());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipodocumentoidentidad);

        if( _modo_nuevo_tipodocumentoidentidad == true )
        {

        }
        else {

          if (_tipodocumentoidentidad.IdTipoDocumentoIdentidad() == data.IdTipoDocumentoIdentidad())
          {

            if (_input_habilitado_tipodocumentoidentidad == true)
            {
              $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tipodocumentoidentidad = "#"+ data.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
              self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, false);

              var idbutton = "#"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";
              $(idbutton).hide();

              _input_habilitado_tipodocumentoidentidad =false;

            }
            else {
              $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",true);
              var id_tipodocumentoidentidad = "#"+ data.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
              self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, true);

              var idbutton = "#"+data.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";

              var idinput = "#"+data.IdTipoDocumentoIdentidad()+"_input_CodigoDocumentoIdentidad";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipodocumentoidentidad = true;
            }

          }
          else {
            $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",true);
            if( _input_habilitado_tipodocumentoidentidad == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad());

              var id_tipodocumentoidentidad = "#"+ _tipodocumentoidentidad.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
              self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, false);

              var idbutton = "#"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";

              _tipodocumentoidentidad.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tipodocumentoidentidad = "#"+ data.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
            self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, true);

            var idbutton = "#"+data.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";

            var idinput = "#"+data.IdTipoDocumentoIdentidad()+"_input_CodigoDocumentoIdentidad";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_tipodocumentoidentidad = true;
          }


        }

      }

    }

    self.PreBorrarTipoDocumentoIdentidad = function (data) {

      if(_modo_nuevo_tipodocumentoidentidad == false)
      {
        _tipodocumentoidentidad.Deshacer(null, event);
        _input_habilitado_tipodocumentoidentidad = false;
        $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
        self.HabilitarTablaSpanTipoDocumentoIdentidad(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoDocumentoIdentidad");
          console.log(data.IdTipoDocumentoIdentidad());
          self.HabilitarButtonsTipoDocumentoIdentidad(null, true);
          if (data.IdTipoDocumentoIdentidad() != null){
            self.BorrarTipoDocumentoIdentidad(data);
          }
          else
          {
            $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
            _input_habilitado_tipodocumentoidentidad = false;
            _modo_nuevo_tipodocumentoidentidad = false;
            vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad.remove(data);
            var tabla = $('#DataTables_Table_0_tipodocumentoidentidad');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposDocumentoIdentidad();
          }
        });
      }, 200);
    }

    self.BorrarTipoDocumentoIdentidad = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cTipoDocumentoIdentidad/BorrarTipoDocumentoIdentidad',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
                      self.HabilitarTablaSpanTipoDocumentoIdentidad(null, true);
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad.remove(objeto);
                    }
              }
          },
          error : function (jqXHR, textStatus, errorThrown) {
                 //$("#modalMensajeTexto").empty();
                 //$("#modalMensajeTexto").append(jqXHR.responseText);
                 //$("#modalMensaje").modal("show");
                 console.log(jqXHR.responseText);
                 /*
                 setTimeout(function () {
                     $btn.button('reset');
                 }, 1000);
                 */
             }
      });

    }


    self.OnClickTipoDocumentoIdentidad = function(data ,event) {

      if(event)
      {
          console.log("OnClickTipoDocumentoIdentidad");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tipodocumentoidentidad);

          if( _modo_nuevo_tipodocumentoidentidad == true )
          {

          }
          else
          {

            $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",true);
            if(_tipodocumentoidentidad.IdTipoDocumentoIdentidad() !=  data.IdTipoDocumentoIdentidad())
            {
              if (_input_habilitado_tipodocumentoidentidad == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tipodocumentoidentidad.Deshacer(null, event);

                //var id_tipodocumentoidentidad = "#" + _id_filatipodocumentoidentidad_anterior;
                var id_tipodocumentoidentidad = "#" + _tipodocumentoidentidad.IdTipoDocumentoIdentidad()+'_tr_tipoDocumentoIdentidad';
                self.HabilitarFilaInputTipoDocumentoIdentidad(id_tipodocumentoidentidad, false);

                var idbutton = "#"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tipodocumentoidentidad.IdTipoDocumentoIdentidad());
              console.log(data.IdTipoDocumentoIdentidad());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tipodocumentoidentidad = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tipodocumentoidentidad, "span") || $.isSubstring(id_fila_tipodocumentoidentidad, "input")){
                id_fila_tipodocumentoidentidad = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_tipodocumentoidentidad).find('input').attr('id');
              self.HabilitarFilaInputTipoDocumentoIdentidad("#" + $(id_fila_tipodocumentoidentidad).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipodocumentoidentidad = true;

              }
              else {
                if (_input_habilitado_tipodocumentoidentidad == false)
                {
                  var id_fila_tipodocumentoidentidad = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tipodocumentoidentidad, "span") || $.isSubstring(id_fila_tipodocumentoidentidad, "input")){
                    id_fila_tipodocumentoidentidad = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputTipoDocumentoIdentidad("#" + $(id_fila_tipodocumentoidentidad).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";
                  var idinput ="#"+$(id_fila_tipodocumentoidentidad).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_tipodocumentoidentidad = true;
                }
                else {
                  console.log("MISMA LNEA");
                }
              }

          }

          return false;
      }

    }

    //Funcion para buscar una palabra en una cadena de texto
    jQuery.isSubstring = function(haystack, needle){
      return haystack.indexOf(needle) !== -1;
    }

    //FUNCION DE MANEJO DE TECLAS Y ATAJOS
    self.OnKeyUpTipoDocumentoIdentidad = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoDocumentoIdentidad");

       var code = event.keyCode || event.which;

       if (code === 13) {//enter

         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         if(alertify.confirm().isOpen() || alertify.alert().isOpen())
         {
           event.preventDefault();
           return false;
         }
        //Variable para obtener el id delinput
          var idinputcodigo = _tipodocumentoidentidad.IdTipoDocumentoIdentidad() + '_input_CodigoDocumentoIdentidad';
         var idinputnombre = _tipodocumentoidentidad.IdTipoDocumentoIdentidad() + '_input_NombreTipoDocumentoIdentidad';
         var idinputabreviatura = _tipodocumentoidentidad.IdTipoDocumentoIdentidad() + '_input_NombreAbreviado';


         if(event.target.id == idinputcodigo)
         {
            _tipodocumentoidentidad.CodigoDocumentoIdentidad(event.target.value);
         }
         else if(event.target.id == idinputnombre)
         {
           _tipodocumentoidentidad.NombreTipoDocumentoIdentidad(event.target.value);
         }
         else if(event.target.id == idinputabreviatura)
         {
            _tipodocumentoidentidad.NombreAbreviado(event.target.value);
         }


         if(_modo_nuevo_tipodocumentoidentidad == true)
         {
           self.InsertarTipoDocumentoIdentidad(_tipodocumentoidentidad,event);
         }
         else
         {
           self.ActualizarTipoDocumentoIdentidad(_tipodocumentoidentidad,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_tipodocumentoidentidad == true)
        {
          if(_modo_nuevo_tipodocumentoidentidad == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_tipodocumentoidentidad);
              vistaModeloCatalogo.vmcTipoDocumentoIdentidad.dataTipoDocumentoIdentidad.TiposDocumentoIdentidad.remove(_tipodocumentoidentidad);
              var tabla = $('#DataTables_Table_0_tipodocumentoidentidad');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);
              self.HabilitarButtonsTipoDocumentoIdentidad(null, true);
               _modo_nuevo_tipodocumentoidentidad = false;
               _input_habilitado_tipodocumentoidentidad = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tipodocumentoidentidad._NombreTipoDocumentoIdentidad());
            //revertir texto

             _tipodocumentoidentidad.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoDocumentoIdentidad").prop("disabled",false);

            /*var id_fila_tipodocumentoidentidad = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tipodocumentoidentidad, "span") || $.isSubstring(id_fila_tipodocumentoidentidad, "input")){
              id_fila_tipodocumentoidentidad = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoDocumentoIdentidad("#" + $(id_fila_tipodocumentoidentidad).parent()[0].id, false);*/

            self.HabilitarTablaSpanTipoDocumentoIdentidad(null, true);

            var idbutton ="#"+_tipodocumentoidentidad.IdTipoDocumentoIdentidad()+"_button_TipoDocumentoIdentidad";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_tipodocumentoidentidad = false;
            _input_habilitado_tipodocumentoidentidad = false;
          }

        }
      }
    }

    self.GuardarTipoDocumentoIdentidad = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoDocumentoIdentidad");
         console.log(_nombretipodocumentoidentidad);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }

         //Variable para obtener el id delinput
         var idinputcodigo = _tipodocumentoidentidad.IdTipoDocumentoIdentidad() + '_input_CodigoDocumentoIdentidad';
          var idinputnombre = _tipodocumentoidentidad.IdTipoDocumentoIdentidad() + '_input_NombreTipoDocumentoIdentidad';
          var idinputabreviatura = _tipodocumentoidentidad.IdTipoDocumentoIdentidad() + '_input_NombreAbreviado';


          if(event.target.id == idinputcodigo)
          {
             _tipodocumentoidentidad.CodigoDocumentoIdentidad(_codigotipodocumentoidentidad);
          }
          else if(event.target.id == idinputnombre)
          {
            _tipodocumentoidentidad.NombreTipoDocumentoIdentidad(_nombretipodocumentoidentidad);
          }
          else if(event.target.id == idinputabreviatura)
          {
             _tipodocumentoidentidad.NombreAbreviado(_codigotipodocumentoidentidad);
          }
         //_tipodocumentoidentidad.NombreTipoDocumentoIdentidad(_nombretipodocumentoidentidad);

         if(_modo_nuevo_tipodocumentoidentidad == true)
         {
           self.InsertarTipoDocumentoIdentidad(_tipodocumentoidentidad,event);
         }
         else
         {
           self.ActualizarTipoDocumentoIdentidad(_tipodocumentoidentidad,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
