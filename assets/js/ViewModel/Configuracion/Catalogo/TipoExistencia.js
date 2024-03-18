
TiposExistenciaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreTipoExistencia = ko.observable(data.NombreTipoExistencia);
    self._CodigoTipoExistencia = ko.observable(data.CodigoTipoExistencia);

    self.VistaOptions = ko.pureComputed(function(){
      return self.IndicadorEstado() == "T" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log(self._CodigoTipoExistencia());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreTipoExistencia());

        self.CodigoTipoExistencia.valueHasMutated();
        self.NombreTipoExistencia.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoTipoExistencia("");
        self.NombreTipoExistencia("");
        self.CodigoTipoExistencia(self._CodigoTipoExistencia());
        self.NombreTipoExistencia(self._NombreTipoExistencia());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._CodigoTipoExistencia.valueHasMutated();
        self._CodigoTipoExistencia(self.CodigoTipoExistencia());
        self._NombreTipoExistencia.valueHasMutated();
        self._NombreTipoExistencia(self.NombreTipoExistencia());

      }
    }

    //console.log("-Inicio Tipo Existencia-");
    //console.log(self._NombreTipoExistencia());
}

TipoExistenciaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'TiposExistencia': {
        create: function (options) {
            if (options)
              return new TiposExistenciaModel(options.data);
            }
    },
    'TipoExistencia': {
        create: function (options) {
            if (options)
              return new TipoExistenciaModel(options.data);
            }
    }

}

IndexTipoExistencia = function (data) {

    var _modo_deshacer = false;
    var _codigotipoexistencia;
    var _nombretipoexistencia;
    var _input_habilitado_tipoexistencia = false;
    var _idtipoexistencia;
    var _tipoexistencia;
    var _modo_nuevo_tipoexistencia = false;
    var _id_filatipoexistencia_anterior;
    var _copia_unica_tipo_existencia = null;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposExistencia = function() {
        console.log("ListarTiposExistencia");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoExistencia/ListarTiposExistencia',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia([]);
                        ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia.push(new TiposExistenciaModel(item));
                    });
                }
            }
        });
    }

    self.SeleccionarNormal = function(data){
      if(data.IndicadorEstado() == "T" && _modo_nuevo_tipoexistencia == false){
        self.Seleccionar(data);
      }
      else {
        self.Seleccionar(data);
      }
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_tipoexistencia == false){
        var id = "#"+data.IdTipoExistencia()+ '_tr_TipoExistencia';
        $(id).addClass('active').siblings().removeClass('active');
        _tipoexistencia = data;
      }

    }

    self.FilaButtonsTipoExistencia = function (data, event)  {
      console.log("BUTTONS");
      console.log("EVENTTARGET: " + $(event.target).attr('class'));
      console.log("THIS: " + $(this).attr('class'));
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else
      {
          console.log("MODO NUEVO: " + _modo_nuevo_tipoexistencia);
          if(_modo_nuevo_tipoexistencia == true)
          return;

          _tipoexistencia.Deshacer(null, event);
          _input_habilitado_tipoexistencia = false;
          $("#btnAgregarTipoExistencia").prop("disabled",false);
          self.HabilitarTablaSpanTipoExistencia(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoExistencia()+ '_tr_TipoExistencia' ;
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_tipoexistencia == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoExistencia();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoExistencia()+ '_tr_TipoExistencia' ;
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_tipoexistencia == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtipoexistencia = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtipoexistencia == item.IdTipoExistencia();
            });

          if(match)
          {
            _tipoexistencia = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoExistencia = function (data, option)  {
      //var id = "#"+data.IdTipoExistencia();
      var id = data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoExistencia').hide();
        $(id).find('.class_SpanTipoExistencia').show();
      }
      else
      {
        $(id).find('.class_InputTipoExistencia').show();
        $(id).find('.class_SpanTipoExistencia').hide();
      }

    }

    self.HabilitarTablaSpanTipoExistencia = function (data, option)  {
      //var id = "#"+data.IdTipoExistencia();
      var id = "#DataTables_Table_0_tipoExistencia";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoExistencia').hide();
        $(id).find('.class_InputTipoExistencia').show();
        //$(id).find('.guardar_button_TipoExistencia').show();
        //_input_habilitado_tipoexistencia = true;
      }
      else {
        $(id).find('.class_SpanTipoExistencia').show();
        $(id).find('.class_InputTipoExistencia').hide();
        $(id).find('.guardar_button_TipoExistencia').hide();
        //_input_habilitado_tipoexistencia = false;
      }

    }

    self.HabilitarButtonsTipoExistencia = function(data, option){
      var id = "#DataTables_Table_0_tipoExistencia";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoExistencia').prop("disabled", true);
        $(id).find('.borrar_button_TipoExistencia').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoExistencia').prop("disabled", false);
        $(id).find('.borrar_button_TipoExistencia').prop("disabled", false);
      }
    }


    self.AgregarTipoExistencia = function(data,event) {
          console.log("AgregarTipoExistencia");

          if ( _input_habilitado_tipoexistencia == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TipoExistencia);
            _tipoexistencia = new TiposExistenciaModel(objeto);
            vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia.push(_tipoexistencia);

            //Deshabilitando buttons
            self.HabilitarButtonsTipoExistencia(null, false);
            $("#null_editar_button_TipoExistencia").prop("disabled", true);
            $("#null_borrar_button_TipoExistencia").prop("disabled", false);


            $("#btnAgregarTipoExistencia").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdTipoExistencia());

            var idspan ="#"+objeto.IdTipoExistencia()+"_span_NombreTipoExistencia";
            var idinput ="#"+objeto.IdTipoExistencia()+"_input_NombreTipoExistencia";

            var idspancodigo ="#"+objeto.IdTipoExistencia()+"_span_CodigoTipoExistencia";
            var idinputcodigo ="#"+objeto.IdTipoExistencia()+"_input_CodigoTipoExistencia";

            var idbutton ="#"+objeto.IdTipoExistencia()+"_button_TipoExistencia";

            console.log(idbutton);
            //self.HabilitarFilaInputTipoExistencia(_tipoexistencia, true);
            //self.HabilitarFilaSpanTipoExistencia(_tipoexistencia, false);

            $(idspan).hide();
            $(idinput).show();
            $(idspancodigo).hide();
            $(idinputcodigo).show();
            $(idbutton).show();
            $(idinputcodigo).focus();

            _modo_nuevo_tipoexistencia = true;
            _input_habilitado_tipoexistencia = true;

            var tabla = $('#DataTables_Table_0_tipoExistencia');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');

          }
    }

    self.InsertarTipoExistencia =function(data,event){

      if(event)
      {
        $("#loader").show();
        console.log("InsertarTipoExistencia");
        console.log(_tipoexistencia.NombreTipoExistencia());

        var objeto = data;
        var datajs = ko.toJS({"Data" : _tipoexistencia});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cTipoExistencia/InsertarTipoExistencia',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoExistencia");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoExistencia))
                      {
                        _tipoexistencia.IdTipoExistencia(data.IdTipoExistencia);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoExistencia").prop("disabled",false);

                        var id_tipoexistencia = "#"+ _tipoexistencia.IdTipoExistencia()+'_tr_TipoExistencia';
                        self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, false);

                        var idbutton ="#"+_tipoexistencia.IdTipoExistencia()+"_button_TipoExistencia";
                        $(idbutton).hide();

                         _tipoexistencia.Confirmar(null,event);
                         self.HabilitarButtonsTipoExistencia(null, true);

                        existecambio = false;
                        _input_habilitado_tipoexistencia = false;
                        _modo_nuevo_tipoexistencia = false;

                      }
                      else {
                        alertify.alert(data.IdTipoExistencia);
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

    self.ActualizarTipoExistencia = function(data,event) {
          console.log("ActualizarTipoExistencia");
          console.log(_tipoexistencia.NombreTipoExistencia());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _tipoexistencia});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cTipoExistencia/ActualizarTipoExistencia',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoExistencia").prop("disabled",false);
                          console.log("ID5:"+_tipoexistencia.IdTipoExistencia());
                          _tipoexistencia.Confirmar(null,event);

                          var id_tipoexistencia = "#"+ _tipoexistencia.IdTipoExistencia()+'_tr_TipoExistencia';
                          self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, false);

                          var idbutton ="#"+_tipoexistencia.IdTipoExistencia()+"_button_TipoExistencia";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_tipoexistencia = false;
                          _modo_nuevo_tipoexistencia = false;

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

    self.EditarTipoExistencia = function(data, event) {

      if(event)
      {
        console.log("EditarTipoExistencia");
        console.log("ID.:"+data.IdTipoExistencia());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipoexistencia);

        if( _modo_nuevo_tipoexistencia == true )
        {

        }
        else {

          if (_tipoexistencia.IdTipoExistencia() == data.IdTipoExistencia())
          {

            if (_input_habilitado_tipoexistencia == true)
            {
              $("#btnAgregarTipoExistencia").prop("disabled",false);
              _tipoexistencia.Deshacer(null,event);
              var id_tipoexistencia = "#"+ data.IdTipoExistencia()+'_tr_TipoExistencia';
              self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, false);

              var idbutton = "#"+_tipoexistencia.IdTipoExistencia()+"_button_TipoExistencia";
              $(idbutton).hide();

              _input_habilitado_tipoexistencia =false;


            }
            else {
              $("#btnAgregarTipoExistencia").prop("disabled",true);
              var id_tipoexistencia = "#"+ data.IdTipoExistencia()+'_tr_TipoExistencia';
              self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, true);

              var idbutton = "#"+data.IdTipoExistencia()+"_button_TipoExistencia";

              var idinput = "#"+data.IdTipoExistencia()+"_input_CodigoTipoExistencia";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipoexistencia = true;
            }

          }
          else {
            $("#btnAgregarTipoExistencia").prop("disabled",true);
            if( _input_habilitado_tipoexistencia == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tipoexistencia.IdTipoExistencia());

              var id_tipoexistencia = "#"+ _tipoexistencia.IdTipoExistencia()+'_tr_TipoExistencia';
              self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, false);

              var idbutton = "#"+_tipoexistencia.IdTipoExistencia()+"_button_TipoExistencia";

              _tipoexistencia.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tipoexistencia = "#"+ data.IdTipoExistencia()+'_tr_TipoExistencia';
            self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, true);

            var idbutton = "#"+data.IdTipoExistencia()+"_button_TipoExistencia";

            var idinput = "#"+data.IdTipoExistencia()+"_input_CodigoTipoExistencia";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_tipoexistencia = true;
          }


        }

      }

    }

    self.PreBorrarTipoExistencia = function (data, event) {

      if(_modo_nuevo_tipoexistencia == false)
      {
        _tipoexistencia.Deshacer(null, event);
        _input_habilitado_tipoexistencia = false;
        $("#btnAgregarTipoExistencia").prop("disabled",false);
        self.HabilitarTablaSpanTipoExistencia(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoExistencia");
          console.log(data.IdTipoExistencia());
          self.HabilitarButtonsTipoExistencia(null, true);
          if (data.IdTipoExistencia() != null){
            self.BorrarTipoExistencia(data);
          }
          else
          {
            $("#btnAgregarTipoExistencia").prop("disabled",false);
            _input_habilitado_tipoexistencia = false;
            _modo_nuevo_tipoexistencia = false;
            vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia.remove(data);

            var tabla = $('#DataTables_Table_0_tipoExistencia');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposExistencia();
          }
        });
      }, 200);

    }

    self.BorrarTipoExistencia = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cTipoExistencia/BorrarTipoExistencia',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);

                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoExistencia").prop("disabled",false);
                      self.HabilitarTablaSpanTipoExistencia(null, true);
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia.remove(objeto);
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


    self.OnClickTipoExistencia = function(data ,event) {

      if(event)
      {
        console.log("OnClickTipoExistencia");
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipoexistencia);

        if (data.IndicadorEstado() == "T" && _modo_nuevo_tipoexistencia == false)
        {
          if(_input_habilitado_tipoexistencia==false)
            return;

          _tipoexistencia.Deshacer(null, event);
          _input_habilitado_tipoexistencia = false;
          $("#btnAgregarTipoExistencia").prop("disabled",false);
          // $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
          // $(".btn_subfamiliaproducto").prop("disabled",false);

          self.HabilitarTablaSpanTipoExistencia(null, true);
          //$('#opcion-subfamiliaproducto').removeClass('disabledTab');
        }
        else {
          if( _modo_nuevo_tipoexistencia == true )
          {

          }
          else
          {

            $("#btnAgregarTipoExistencia").prop("disabled",true);
            if(_tipoexistencia.IdTipoExistencia() !=  data.IdTipoExistencia())
            {
              if (_input_habilitado_tipoexistencia == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tipoexistencia.Deshacer(null, event);

                //var id_tipoexistencia = "#" + _id_filatipoexistencia_anterior;
                var id_tipoexistencia = "#" + _tipoexistencia.IdTipoExistencia()+'_tr_TipoExistencia';
                self.HabilitarFilaInputTipoExistencia(id_tipoexistencia, false);

                var idbutton = "#"+_tipoexistencia.IdTipoExistencia()+"_button_TipoExistencia";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tipoexistencia.IdTipoExistencia());
              console.log(data.IdTipoExistencia());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tipoexistencia = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tipoexistencia, "span") || $.isSubstring(id_fila_tipoexistencia, "input")){
                id_fila_tipoexistencia = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filatipoexistencia_anterior = $(id_fila_tipoexistencia).parent()[0].id;
              var idspan ="#"+$(id_fila_tipoexistencia).find('span').attr('id');
              var idinput ="#"+$(id_fila_tipoexistencia).find('input').attr('id');
              self.HabilitarFilaInputTipoExistencia("#" + $(id_fila_tipoexistencia).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoExistencia()+"_button_TipoExistencia";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipoexistencia = true;

            }
            else {
              if (_input_habilitado_tipoexistencia == false)
              {
                var id_fila_tipoexistencia = "#" + $(event.target).attr('id');

                //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                if($.isSubstring(id_fila_tipoexistencia, "span") || $.isSubstring(id_fila_tipoexistencia, "input")){
                  id_fila_tipoexistencia = "#" + $(event.target).parent()[0].id;
                }

                self.HabilitarFilaInputTipoExistencia("#" + $(id_fila_tipoexistencia).parent()[0].id, true);

                var idbutton = "#"+data.IdTipoExistencia()+"_button_TipoExistencia";
                var idinput ="#"+$(id_fila_tipoexistencia).find('input').attr('id');
                $(idbutton).show()
                $(idinput).focus();

                _input_habilitado_tipoexistencia = true;
              }
              else {
                console.log("MISMA LNEA");
              }
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
    self.OnKeyUpTipoExistencia = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoExistencia");

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
         var idinputnombre = _tipoexistencia.IdTipoExistencia() + '_input_NombreTipoExistencia';
         var idinputcodigo = _tipoexistencia.IdTipoExistencia() + '_input_CodigoTipoExistencia';

         if(event.target.id == idinputnombre)
         {
           _tipoexistencia.NombreTipoExistencia(event.target.value);
         }
         else if(event.target.id == idinputcodigo)
         {
            _tipoexistencia.CodigoTipoExistencia(event.target.value);
         }


         if(_modo_nuevo_tipoexistencia == true)
         {
           self.InsertarTipoExistencia(_tipoexistencia,event);
         }
         else
         {
           self.ActualizarTipoExistencia(_tipoexistencia,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_tipoexistencia == true)
        {
          if(_modo_nuevo_tipoexistencia == true)
          {
            alertify.confirm("¿Desea borrar el registro?", function(){
              self.SeleccionarAnterior(_tipoexistencia);
              vistaModeloCatalogo.vmcTipoExistencia.dataTipoExistencia.TiposExistencia.remove(_tipoexistencia);
              var tabla = $('#DataTables_Table_0_tipoExistencia');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoExistencia").prop("disabled",false);
              self.HabilitarButtonsTipoExistencia(null, true);

               _modo_nuevo_tipoexistencia = false;
               _input_habilitado_tipoexistencia = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tipoexistencia._NombreTipoExistencia());
            //revertir texto
            //data.NombreTipoExistencia(_tipoexistencia.NombreTipoExistencia());

             _tipoexistencia.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoExistencia").prop("disabled",false);

            /*var id_fila_tipoexistencia = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tipoexistencia, "span") || $.isSubstring(id_fila_tipoexistencia, "input")){
              id_fila_tipoexistencia = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputTipoExistencia("#" + $(id_fila_tipoexistencia).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoExistencia(null, true);

            var idbutton ="#"+_tipoexistencia.IdTipoExistencia()+"_button_TipoExistencia";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_tipoexistencia = false;
            _input_habilitado_tipoexistencia = false;
          }

        }
      }
    }

    self.GuardarTipoExistencia = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoExistencia");
         console.log(_nombretipoexistencia);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _tipoexistencia.IdTipoExistencia() + '_input_NombreTipoExistencia';
          var idinputcodigo = _tipoexistencia.IdTipoExistencia() + '_input_CodigoTipoExistencia';

          if(event.target.id == idinputnombre)
          {
            _tipoexistencia.NombreTipoExistencia(_nombretipoexistencia);
          }
          else if(event.target.id == idinputcodigo)
          {
             _tipoexistencia.CodigoTipoExistencia(_codigotipoexistencia);
          }
         //_tipoexistencia.NombreTipoExistencia(_nombretipoexistencia);

         if(_modo_nuevo_tipoexistencia == true)
         {
           self.InsertarTipoExistencia(_tipoexistencia,event);
         }
         else
         {
           self.ActualizarTipoExistencia(_tipoexistencia,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
