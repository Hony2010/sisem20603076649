MonedasModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);


    self._CodigoMoneda = ko.observable(data.CodigoMoneda);
    self._NombreMoneda = ko.observable(data.NombreMoneda);
    self._SimboloMoneda = ko.observable(data.SimboloMoneda);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log(self._CodigoMoneda());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreMoneda());

        self.CodigoMoneda.valueHasMutated();
        self.NombreMoneda.valueHasMutated();
        self.SimboloMoneda.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoMoneda("");
        self.NombreMoneda("");
        self.SimboloMoneda("");
        self.CodigoMoneda(self._CodigoMoneda());
        self.NombreMoneda(self._NombreMoneda());
        self.SimboloMoneda(self._SimboloMoneda());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._CodigoMoneda.valueHasMutated();
        self._CodigoMoneda(self.CodigoMoneda());
        self._NombreMoneda.valueHasMutated();
        self._NombreMoneda(self.NombreMoneda());
        self._SimboloMoneda.valueHasMutated();
        self._SimboloMoneda(self.SimboloMoneda());

      }
    }


}

MonedaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping = {
    'Monedas': {
        create: function (options) {
            if (options)
              return new MonedasModel(options.data);
            }
    },
    'Moneda': {
        create: function (options) {
            if (options)
              return new MonedaModel(options.data);
            }
    }

}

IndexMoneda = function (data) {

    var _modo_deshacer = false;
    var _codigomoneda;
    var _nombremoneda;
    var _simbolomoneda;
    var _input_habilitado_moneda = false;
    var _idmoneda;
    var _moneda;
    var _modo_nuevo_moneda = false;
    var _id_filamoneda_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarMonedas = function() {
        console.log("ListarMonedas");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cMoneda/ListarMonedas',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataMoneda.Monedas([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataMoneda.Monedas.push(new MonedasModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_moneda == false)
      {
        var id = "#"+data.IdMoneda()+'_tr_moneda';
        $(id).addClass('active').siblings().removeClass('active');
        _moneda = data;
      }

    }

    self.FilaButtonsMoneda = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_moneda);
          if(_modo_nuevo_moneda == true)
          return;

          _moneda.Deshacer(null, event);
          _input_habilitado_moneda = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarMoneda").prop("disabled",false);
          self.HabilitarTablaSpanMoneda(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdMoneda()+'_tr_moneda';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_moneda == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataMoneda.Monedas(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdMoneda();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdMoneda()+'_tr_moneda';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_moneda == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idmoneda = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataMoneda.Monedas(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idmoneda == item.IdMoneda();
            });

          if(match)
          {
            _moneda = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputMoneda = function (data, option)  {
      //var id = "#"+data.IdMoneda();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputMoneda').hide();
        $(id).find('.class_SpanMoneda').show();
      }
      else
      {
        $(id).find('.class_InputMoneda').show();
        $(id).find('.class_SpanMoneda').hide();
      }

    }

    self.HabilitarTablaSpanMoneda = function (data, option)  {
      //var id = "#"+data.IdMoneda();
      var id = "#DataTables_Table_0_moneda";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanMoneda').hide();
        $(id).find('.class_InputMoneda').show();
        //$(id).find('.guardar_button_Moneda').show();
        //_input_habilitado_moneda = true;
      }
      else {
        $(id).find('.class_SpanMoneda').show();
        $(id).find('.class_InputMoneda').hide();
        $(id).find('.guardar_button_Moneda').hide();
        //_input_habilitado_moneda = false;
      }

    }

    self.HabilitarButtonsMoneda = function(data, option){
      var id = "#DataTables_Table_0_moneda";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_Moneda').prop("disabled", true);
        $(id).find('.borrar_button_Moneda').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_Moneda').prop("disabled", false);
        $(id).find('.borrar_button_Moneda').prop("disabled", false);
      }
    }


    self.AgregarMoneda = function(data,event) {
          console.log("AgregarMoneda");

          if ( _input_habilitado_moneda == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataMoneda.Moneda);
            _moneda = new MonedasModel(objeto);
            self.dataMoneda.Monedas.push(_moneda);

            //Deshabilitando buttons
            self.HabilitarButtonsMoneda(null, false);
            $("#null_editar_button_Moneda").prop("disabled", true);
            $("#null_borrar_button_Moneda").prop("disabled", false);


            $("#btnAgregarMoneda").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdMoneda());


            var id_span_codigomoneda ="#"+objeto.IdMoneda()+"_span_CodigoMoneda";
            var id_input_codigomoneda ="#"+objeto.IdMoneda()+"_input_CodigoMoneda";

            var id_span_nombremoneda ="#"+objeto.IdMoneda()+"_span_NombreMoneda";
            var id_input_nombremoneda ="#"+objeto.IdMoneda()+"_input_NombreMoneda";

            var id_span_simbolomoneda ="#"+objeto.IdMoneda()+"_span_SimboloMoneda";
            var id_input_simbolomoneda ="#"+objeto.IdMoneda()+"_input_SimboloMoneda";

            var idbutton ="#"+objeto.IdMoneda()+"_button_Moneda";

            console.log(idbutton);

            $(id_span_codigomoneda).hide();
            $(id_input_codigomoneda).show();

            $(id_span_nombremoneda).hide();
            $(id_input_nombremoneda).show();

            $(id_span_simbolomoneda).hide();
            $(id_input_simbolomoneda).show();

            $(idbutton).show();
            $(id_input_codigomoneda).focus();

            _modo_nuevo_moneda = true;
            _input_habilitado_moneda = true;

            var tabla = $('#DataTables_Table_0_moneda');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarMoneda =function(data,event){

      if(event)
      {
        console.log("InsertarMoneda");
        console.log(_moneda.NombreMoneda());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _moneda});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cMoneda/InsertarMoneda',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarMoneda");
                      console.log(data);

                      if ($.isNumeric(data.IdMoneda))
                      {
                        _moneda.IdMoneda(data.IdMoneda);
                        //deshabilitar botones agregar
                        $("#btnAgregarMoneda").prop("disabled",false);

                        var id_moneda = "#"+ _moneda.IdMoneda()+'_tr_moneda';
                        self.HabilitarFilaInputMoneda(id_moneda, false);

                        var idbutton ="#"+_moneda.IdMoneda()+"_button_Moneda";
                        $(idbutton).hide();

                         _moneda.Confirmar(null,event);
                         self.HabilitarButtonsMoneda(null, true);

                        existecambio = false;
                        _input_habilitado_moneda = false;
                        _modo_nuevo_moneda = false;

                      }
                      else {
                        alertify.alert(data.IdMoneda);
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

    self.ActualizarMoneda = function(data,event) {
          console.log("ActualizarMoneda");
          console.log(_moneda.NombreMoneda());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _moneda});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cMoneda/ActualizarMoneda',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarMoneda").prop("disabled",false);
                          console.log("ID5:"+_moneda.IdMoneda());
                          _moneda.Confirmar(null,event);

                          var id_moneda = "#"+ _moneda.IdMoneda()+'_tr_moneda';
                          self.HabilitarFilaInputMoneda(id_moneda, false);

                          var idbutton ="#"+_moneda.IdMoneda()+"_button_Moneda";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_moneda = false;
                          _modo_nuevo_moneda = false;

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

    self.EditarMoneda = function(data, event) {

      if(event)
      {
        console.log("EditarMoneda");
        console.log("ID.:"+data.IdMoneda());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_moneda);

        if( _modo_nuevo_moneda == true )
        {

        }
        else {

          if (_moneda.IdMoneda() == data.IdMoneda())
          {

            if (_input_habilitado_moneda == true)
            {
              $("#btnAgregarMoneda").prop("disabled",false);
              data.Deshacer(null,event);
              var id_moneda = "#"+ data.IdMoneda()+'_tr_moneda';
              self.HabilitarFilaInputMoneda(id_moneda, false);

              var idbutton = "#"+_moneda.IdMoneda()+"_button_Moneda";
              $(idbutton).hide();

              _input_habilitado_moneda =false;

            }
            else {
              $("#btnAgregarMoneda").prop("disabled",true);
              var id_moneda = "#"+ data.IdMoneda()+'_tr_moneda';
              self.HabilitarFilaInputMoneda(id_moneda, true);

              var idbutton = "#"+data.IdMoneda()+"_button_Moneda";

              var idinput = "#"+data.IdMoneda()+"_input_CodigoMoneda";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_moneda = true;
            }

          }
          else {
            $("#btnAgregarMoneda").prop("disabled",true);
            if( _input_habilitado_moneda == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_moneda.IdMoneda());

              var id_moneda = "#"+ _moneda.IdMoneda()+'_tr_moneda';
              self.HabilitarFilaInputMoneda(id_moneda, false);

              var idbutton = "#"+_moneda.IdMoneda()+"_button_Moneda";

              _moneda.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_moneda = "#"+ data.IdMoneda()+'_tr_moneda';
            self.HabilitarFilaInputMoneda(id_moneda, true);

            var idbutton = "#"+data.IdMoneda()+"_button_Moneda";

            var idinput = "#"+data.IdMoneda()+"_input_CodigoMoneda";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_moneda = true;
          }


        }

      }

    }

    self.PreBorrarMoneda = function (data) {

      if(_modo_nuevo_moneda == false)
      {
      _moneda.Deshacer(null, event);
      _input_habilitado_moneda = false;
      $("#btnAgregarMoneda").prop("disabled",false);
      self.HabilitarTablaSpanMoneda(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarMoneda");
          console.log(data.IdMoneda());
          self.HabilitarButtonsMoneda(null, true);
          if (data.IdMoneda() != null)
            self.BorrarMoneda(data);
          else
          {
            $("#btnAgregarMoneda").prop("disabled",false);
            _input_habilitado_moneda = false;
            _modo_nuevo_moneda = false;
            self.dataMoneda.Monedas.remove(data);
            var tabla = $('#DataTables_Table_0_moneda');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarMonedas();
          }
        });
      }, 200);

    }

    self.BorrarMoneda = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cMoneda/BorrarMoneda',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarMoneda").prop("disabled",false);
                      self.HabilitarTablaSpanMoneda(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataMoneda.Monedas.remove(objeto);
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


    self.OnClickMoneda = function(data ,event) {

      if(event)
      {
          console.log("OnClickMoneda");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_moneda);

          if( _modo_nuevo_moneda == true )
          {

          }
          else
          {

            $("#btnAgregarMoneda").prop("disabled",true);
            if(_moneda.IdMoneda() !=  data.IdMoneda())
            {
              if (_input_habilitado_moneda == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _moneda.Deshacer(null, event);

                //var id_moneda = "#" + _id_filamoneda_anterior;
                var id_moneda = "#" + _moneda.IdMoneda()+'_tr_moneda';
                self.HabilitarFilaInputMoneda(id_moneda, false);

                var idbutton = "#"+_moneda.IdMoneda()+"_button_Moneda";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_moneda.IdMoneda());
              console.log(data.IdMoneda());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_moneda = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_moneda, "span") || $.isSubstring(id_fila_moneda, "input")){
                id_fila_moneda = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_moneda).find('input').attr('id');
              self.HabilitarFilaInputMoneda("#" + $(id_fila_moneda).parent()[0].id, true);

              var idbutton = "#"+data.IdMoneda()+"_button_Moneda";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_moneda = true;

              }
              else {
                if (_input_habilitado_moneda == false)
                {
                  var id_fila_moneda = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_moneda, "span") || $.isSubstring(id_fila_moneda, "input")){
                    id_fila_moneda = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputMoneda("#" + $(id_fila_moneda).parent()[0].id, true);

                  var idbutton = "#"+data.IdMoneda()+"_button_Moneda";
                  var idinput ="#"+$(id_fila_moneda).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_moneda = true;
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
    self.OnKeyUpMoneda = function(data, event){
      if(event)
      {
       console.log("OnKeyUpMoneda");

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
         var idinputnombre = _moneda.IdMoneda() + '_input_NombreMoneda';
         var idinputcodigo = _moneda.IdMoneda() + '_input_CodigoMoneda';
         var idinputsimbolo = _moneda.IdMoneda() + '_input_SimboloMoneda';

         if(event.target.id == idinputnombre)
         {
           _moneda.NombreMoneda(event.target.value);
         }
         else if(event.target.id == idinputcodigo)
         {
            _moneda.CodigoMoneda(event.target.value);
         }
         else if(event.target.id == idinputsimbolo)
         {
            _moneda.SimboloMoneda(event.target.value);
         }


         if(_modo_nuevo_moneda == true)
         {
           self.InsertarMoneda(_moneda,event);
         }
         else
         {
           self.ActualizarMoneda(_moneda,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_moneda == true)
        {
          if(_modo_nuevo_moneda == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_moneda);
              self.dataMoneda.Monedas.remove(_moneda);
              var tabla = $('#DataTables_Table_0_moneda');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarMoneda").prop("disabled",false);
              self.HabilitarButtonsMoneda(null, true);
               _modo_nuevo_moneda = false;
               _input_habilitado_moneda = false;
            });

          }
          else
          {
            console.log("Escape - false");
            console.log(_moneda._NombreMoneda());
            //revertir texto

             _moneda.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarMoneda").prop("disabled",false);

            /*var id_fila_moneda = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_moneda, "span") || $.isSubstring(id_fila_moneda, "input")){
              id_fila_moneda = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputMoneda("#" + $(id_fila_moneda).parent()[0].id, false);*/
            self.HabilitarTablaSpanMoneda(null, true);

            var idbutton ="#"+_moneda.IdMoneda()+"_button_Moneda";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_moneda = false;
            _input_habilitado_moneda = false;
          }

        }
      }
    }


    self.GuardarMoneda = function(data,event) {
      if(event)
      {
         console.log("GuardarMoneda");
         console.log(_nombremoneda);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _moneda.IdMoneda() + '_input_NombreMoneda';
          var idinputcodigo = _moneda.IdMoneda() + '_input_CodigoMoneda';
          var idinputsimbolo = _moneda.IdMoneda() + '_input_SimboloMoneda';

          if(event.target.id == idinputnombre)
          {
            _moneda.NombreMoneda(_nombremoneda);
          }
          else if(event.target.id == idinputcodigo)
          {
             _moneda.CodigoMoneda(_codigomoneda);
          }
          else if(event.target.id == idinputsimbolo)
          {
             _moneda.SimboloMoneda(_codigomoneda);
          }
         //_moneda.NombreMoneda(_nombremoneda);

         if(_modo_nuevo_moneda == true)
         {
           self.InsertarMoneda(_moneda,event);
         }
         else
         {
           self.ActualizarMoneda(_moneda,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
