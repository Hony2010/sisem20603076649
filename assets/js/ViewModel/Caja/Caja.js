
CajasModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self._NombreCaja = ko.observable(data.NombreCaja);
  self._IdMoneda = ko.observable(data.IdMoneda);
  self._NombreMoneda = ko.observable(data.NombreMoneda);
  self._NumeroCaja = ko.observable(data.NumeroCaja);

  self.Deshacer = function (data,event)  {
    if (event)
    {
      //Poner todos las propiedades aqui.
      console.log("DESHACER:  " + self._NombreCaja());

      self.NombreCaja.valueHasMutated();
      //LIMPIANDO LAS CAJAS DE TEXTO
      self.NombreCaja("");
      self.NumeroCaja("");
      self.NombreCaja(self._NombreCaja());
      self.IdMoneda(self._IdMoneda());
      self.NombreMoneda(self._NombreMoneda());
      self.NumeroCaja(self._NumeroCaja());

      return true;
    }

  }

  self.Confirmar = function(data,event){
    if (event) {
      console.log("Confirmar");
      self._NombreCaja.valueHasMutated();
      self._NombreCaja(self.NombreCaja());
      self._IdMoneda(self.IdMoneda());
      self._NombreMoneda(self.NombreMoneda());
      self._NumeroCaja(self.NumeroCaja());
    }
  }
}

CajaModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);
}

var Mapping = {
  'Cajas': {
    create: function (options) {
      if (options)
        return new CajasModel(options.data);
      }
  },
  'Caja': {
    create: function (options) {
      if (options)
        return new CajaModel(options.data);
      }
  }

}

Index = function (data) {

  var _modo_deshacer = false;
  var _nombrecostoagregado;
  var _input_habilitado = false;
  var _idcaja;
  var _caja;
  var _modo_nuevo = false;
  var _id_filacostoagregado_anterior;

  var self = this;

  self.MostrarTitulo = ko.observable("");

  ko.mapping.fromJS(data, Mapping, self);

  self.ListarCajas = function() {
    $.ajax({
      type: 'POST',
      dataType: "json",
      url: SITE_URL+'/Caja/cCaja/ListarCajas',
        success: function (data) {
          if (data != null) {
            console.log(data);
            self.data.Cajas([]);
            ko.utils.arrayForEach(data, function (item) {
              self.data.Cajas.push(new CajasModel(item));
            });
          }
        }
    });
  }

  self.Seleccionar = function (data,event)  {
    console.log("Seleccionar");

    if (_modo_nuevo == false) {
      var id = "#"+data.IdCaja();
      $(id).addClass('active').siblings().removeClass('active');
      _caja = data;
    }

  }

  self.FilaButtonsCaja = function (data, event) {
    console.log("BUTTONS");
    console.log("EVENTTARGET: " + $(event.target).attr('class'));
    console.log("THIS: " + $(this).attr('class'));
    if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
      console.log("Button");
    }
    else{
      console.log("MODO NUEVO: " + _modo_nuevo);
      if(_modo_nuevo == true){
        return;
      }

      _caja.Deshacer(null, event);
      _input_habilitado = false;
      console.log("OTRA FILA AFECTADA");
      $("#btnAgregarCaja").prop("disabled",false);
      self.HabilitarTablaSpanCaja(null, true);
    }
  }

  self.SeleccionarAnterior = function (data)  {
    var id = "#"+data.IdCaja();
    var anteriorObjeto = $(id).prev();

    anteriorObjeto.addClass('active').siblings().removeClass('active');

    if (_modo_nuevo == false) //revisar
    {
      var _idfamiliaproducto = anteriorObjeto.attr("id");
      var match = ko.utils.arrayFirst(self.data.Cajas(), function(item) {
            return _idfamiliaproducto == item.IdCaja();
        });

      if(match)
      {
        _familiaproducto = match;
      }
    }
  }


  self.SeleccionarSiguiente = function (data)  {
    var id = "#"+data.IdCaja();
    var siguienteObjeto = $(id).next();

    if (siguienteObjeto.length > 0)
    {
      siguienteObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        var _idcaja = siguienteObjeto.attr("id");
        var match = ko.utils.arrayFirst(self.data.Cajas(), function(item) {
              return _idcaja == item.IdCaja();
          });

        if(match)
        {
          _caja = match;
        }
      }
    }
    else {
      self.SeleccionarAnterior(data);
    }
  }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
  self.HabilitarFilaInputCaja = function (data, option)  {
    var id =data;
    if(option == false)
    {
      $(id).find('.class_InputCaja').hide();
      $(id).find('.class_SpanCaja').show();
    }
    else
    {
      $(id).find('.class_InputCaja').show();
      $(id).find('.class_SpanCaja').hide();
    }

  }

  self.HabilitarTablaSpanCaja = function (data, option)  {
    //var id = "#"+data.IdCaja();
    var id = "#DataTables_Table_0";
    //$(id).addClass('active').siblings().removeClass('active');

    if(option == false)
    {
      $(id).find('.class_SpanCaja').hide();
      $(id).find('.class_InputCaja').show();
      //$(id).find('.guardar_button_Caja').show();
      //_input_habilitado = true;
    }
    else {
      $(id).find('.class_SpanCaja').show();
      $(id).find('.class_InputCaja').hide();
      $(id).find('.guardar_button_Caja').hide();
      //_input_habilitado = false;
    }

  }

  self.HabilitarButtonsCaja = function(data, option){
    var id = "#DataTables_Table_0";
    //$(id).addClass('active').siblings().removeClass('active');

    if(option == false)
    {
      $(id).find('.editar_button_Caja').prop("disabled", true);
      $(id).find('.borrar_button_Caja').prop("disabled", true);
    }
    else {
      $(id).find('.editar_button_Caja').prop("disabled", false);
      $(id).find('.borrar_button_Caja').prop("disabled", false);
    }
  }


    self.AgregarCaja = function(data,event) {

      if ( _input_habilitado == true )
      {

      }
      else
      {
        self.MostrarTitulo("REGISTRO DE CAJA")
        var objeto = Knockout.CopiarObjeto(self.data.Caja);
        _caja = new CajasModel(objeto);
        self.data.Cajas.push(_caja);

        //Deshabilitando buttons
        self.HabilitarButtonsCaja(null, false);
        $("#null_editar_button_Caja").prop("disabled", true);
        $("#null_borrar_button_Caja").prop("disabled", false);


        $("#btnAgregarCaja").prop("disabled",true);

        //habilitar como destino
        console.log("ID:"+objeto.IdCaja());

        var idspan ="#"+objeto.IdCaja()+"_span_NombreCaja";
        var idinput ="#"+objeto.IdCaja()+"_input_NombreCaja";

        var idspanmoneda ="#"+objeto.IdCaja()+"_span_NombreMoneda";
        var idinputmoneda ="#"+objeto.IdCaja()+"_combo_IdMoneda";

        var idspannumerocaja ="#"+objeto.IdCaja()+"_span_NumeroCaja";
        var idinputnumerocaja ="#"+objeto.IdCaja()+"_input_NumeroCaja";

        var idbutton ="#"+objeto.IdCaja()+"_button_Caja";

        console.log(idbutton);
        //self.HabilitarFilaInputCaja(_caja, true);
        //self.HabilitarFilaSpanCaja(_caja, false);

        $(idspan).hide();
        $(idinput).show();

        $(idspanmoneda).hide();
        $(idinputmoneda).show();

        $(idspannumerocaja).hide();
        $(idinputnumerocaja).show();

        $(idbutton).show();
        $(idinput).focus();

        _modo_nuevo = true;
        _input_habilitado = true;

        var tabla = $('#DataTables_Table_0');
        $('tr:last', tabla).addClass('active').siblings().removeClass('active');
      }
    }

    self.InsertarCaja =function(data,event){
      if(event)
      {
        console.log("InsertarCaja");
        console.log(_caja.NombreCaja());
        $("#loader").show();
        var objeto = data;
        var datajs = {"Data": JSON.stringify(ko.mapping.toJS(_caja))};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Caja/cCaja/InsertarCaja',
          success: function (data) {
            if (data != null) {
              if (data.IdCaja) {
                _caja.IdCaja(data.IdCaja);
                //deshabilitar botones agregar
                $("#btnAgregarCaja").prop("disabled",false);

                var id_caja = "#"+ _caja.IdCaja();
                self.HabilitarFilaInputCaja(id_caja, false);

                var idbutton ="#"+_caja.IdCaja()+"_button_Caja";
                $(idbutton).hide();

                var nombremoneda = $('#' + _caja.IdCaja() + '_input_IdMoneda option:selected').html();
                _caja.NombreMoneda(nombremoneda);

                _caja.Confirmar(null,event);
                self.HabilitarButtonsCaja(null, true);

                existecambio = false;
                _input_habilitado = false;
                _modo_nuevo = false;

              }
              else {
                alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
              }

            }
            $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var $data = {error:{msg:jqXHR.responseText}};
            $("#loader").hide();
            alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg);
          }
        });
      }
    }

    self.ActualizarCaja = function(data,event) {
      console.log("ActualizarCaja");
      console.log(_caja.NombreCaja());
      $("#loader").show();
      var objeto = data;
      var datajs = {"Data": JSON.stringify(ko.mapping.toJS(_caja))};

        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Caja/cCaja/ActualizarCaja',
          success: function (data) {
            if (data != null) {
              if (data.IdCaja) {
                //deshabilitar campo origen
                $("#btnAgregarCaja").prop("disabled",false);

                var nombremoneda = $('#' + _caja.IdCaja() + '_input_IdMoneda option:selected').html();
                _caja.NombreMoneda(nombremoneda);

                _caja.Confirmar(null,event);

                var id_caja = "#"+ _caja.IdCaja();
                self.HabilitarFilaInputCaja(id_caja, false);

                var idbutton ="#"+_caja.IdCaja()+"_button_Caja";
                $(idbutton).hide();


                existecambio = false;
                _input_habilitado = false;
                _modo_nuevo = false;

              }
              else {
                alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
              }
            }
            $("#loader").hide();
        },
        error : function (jqXHR, textStatus, errorThrown) {
          var $data = {error:{msg:jqXHR.responseText}};
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg);
        }
      });
    }

    self.EditarCaja = function(data, event) {

      if(event)
      {
        console.log("EditarCaja");
        console.log("ID.:"+data.IdCaja());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_caja);

        if( _modo_nuevo == true )
        {

        }
        else {
          self.MostrarTitulo("EDICIÓN DE Caja");
          if (_caja.IdCaja() == data.IdCaja())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarCaja").prop("disabled",false);
              data.Deshacer(null,event);
              var id_caja = "#"+ data.IdCaja();
              self.HabilitarFilaInputCaja(id_caja, false);

              var idbutton = "#"+_caja.IdCaja()+"_button_Caja";
              $(idbutton).hide();

              _input_habilitado =false;


            }
            else {
              $("#btnAgregarCaja").prop("disabled",true);
              var id_caja = "#"+ data.IdCaja();
              self.HabilitarFilaInputCaja(id_caja, true);

              var idbutton = "#"+data.IdCaja()+"_button_Caja";

              var idinput = "#"+data.IdCaja()+"_input_NombreCaja";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarCaja").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_caja.IdCaja());

              var id_caja = "#"+ _caja.IdCaja();
              self.HabilitarFilaInputCaja(id_caja, false);

              var idbutton = "#"+_caja.IdCaja()+"_button_Caja";

              _caja.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_caja = "#"+ data.IdCaja();
            self.HabilitarFilaInputCaja(id_caja, true);

            var idbutton = "#"+data.IdCaja()+"_button_Caja";

            var idinput = "#"+data.IdCaja()+"_input_NombreCaja";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarCaja = function (data) {

      if(_modo_nuevo == false)
      {
        _caja.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarCaja").prop("disabled",false);
        self.HabilitarTablaSpanCaja(null, true);
      }

      setTimeout(function(){
        self.MostrarTitulo("ELIMINACION DE CAJA");
        alertify.confirm(self.MostrarTitulo(),"¿Desea borrar el registro?", function(){
          console.log("BorrarCaja");
          console.log(data.IdCaja());
          self.HabilitarButtonsCaja(null, true);
          if (data.IdCaja() != null)
            self.BorrarCaja(data);
          else
          {
            $("#btnAgregarCaja").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.Cajas.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarCajas();
          }
        },function () {

        });

      }, 200);

    }

    self.BorrarCaja = function (data) {
      var objeto = data;
      var datajs = {"Data": JSON.stringify(ko.mapping.toJS(data))};

      $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Caja/cCaja/BorrarCaja',
          success: function (data) {
            if (data != null) {
            if(data.IdCaja)
            {
              $("#btnAgregarCaja").prop("disabled",false);
              self.HabilitarTablaSpanCaja(null, true);
              self.SeleccionarSiguiente(objeto);
              self.data.Cajas.remove(objeto);
            }
            else {
              alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
            }
          }
        },
      });

    }


    self.OnClickCaja = function(data ,event) {

      if(event)
      {
          console.log("OnClickCaja");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_caja);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarCaja").prop("disabled",true);
            if(_caja.IdCaja() !=  data.IdCaja())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _caja.Deshacer(null, event);

                //var id_caja = "#" + _id_filacostoagregado_anterior;
                var id_caja = "#" + _caja.IdCaja();
                self.HabilitarFilaInputCaja(id_caja, false);

                var idbutton = "#"+_caja.IdCaja()+"_button_Caja";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_caja.IdCaja());
              console.log(data.IdCaja());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_caja = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_caja, "span") || $.isSubstring(id_fila_caja, "input")){
                id_fila_caja = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              //_id_filacostoagregado_anterior = $(id_fila_caja).parent()[0].id;
              var idspan ="#"+$(id_fila_caja).find('span').attr('id');
              var idinput ="#"+$(id_fila_caja).find('input').attr('id');
              self.HabilitarFilaInputCaja("#" + $(id_fila_caja).parent()[0].id, true);

              var idbutton = "#"+data.IdCaja()+"_button_Caja";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_caja = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_caja, "span") || $.isSubstring(id_fila_caja, "input")){
                    id_fila_caja = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputCaja("#" + $(id_fila_caja).parent()[0].id, true);

                  var idbutton = "#"+data.IdCaja()+"_button_Caja";
                  var idinput ="#"+$(id_fila_caja).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado = true;
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
    self.OnKeyUpCaja = function(data, event){
      if(event)
      {
       console.log("OnKeyUpCaja");

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
         var idinputnombre = _caja.IdCaja() + '_input_NombreCaja';

         if(event.target.id == idinputnombre)
         {
           _caja.NombreCaja(event.target.value);
         }


         if(_modo_nuevo == true)
         {
           self.InsertarCaja(_caja,event);
         }
         else
         {
          self.MostrarTitulo("EDICIÓN DE CAJA");
           self.ActualizarCaja(_caja,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado == true)
        {
          if(_modo_nuevo == true)
          {
            alertify.confirm("REGISTRO DE Caja","¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_caja);
              self.data.Cajas.remove(_caja);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarCaja").prop("disabled",false);
              self.HabilitarButtonsCaja(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            },function() {

            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto
            //data.NombreCaja(_caja.NombreCaja());

             _caja.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarCaja").prop("disabled",false);

            /*var id_fila_caja = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_caja, "span") || $.isSubstring(id_fila_caja, "input")){
              id_fila_caja = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputCaja("#" + $(id_fila_caja).parent()[0].id, false);*/
            self.HabilitarTablaSpanCaja(null, true);

            var idbutton ="#"+_caja.IdCaja()+"_button_Caja";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarCaja = function(data,event) {
      if(event)
      {
         console.log("GuardarCaja");
         console.log(_nombrecostoagregado);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _caja.IdCaja() + '_input_NombreCaja';

          if(event.target.id == idinputnombre)
          {
            _caja.NombreCaja(_nombrecostoagregado);
          }
         //_caja.NombreCaja(_nombrecostoagregado);

         if(_modo_nuevo == true)
         {
           self.InsertarCaja(_caja,event);
         }
         else
         {
          self.MostrarTitulo("EDICIÓN DE CAJA");
          self.ActualizarCaja(_caja,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
