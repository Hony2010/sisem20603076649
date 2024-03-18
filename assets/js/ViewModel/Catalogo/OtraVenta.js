
OtrasVentaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreProducto = ko.observable(data.NombreProducto);
    self._CodigoOtraVenta = ko.observable(data.CodigoOtraVenta);

    self._IdTipoProducto = ko.observable(data.IdTipoProducto);
    self._NombreTipoProducto = ko.observable(data.NombreTipoProducto);

    self._IdTipoAfectacionIGV = ko.observable(data.IdTipoAfectacionIGV);
    self._NombreTipoAfectacionIGV = ko.observable(data.NombreTipoAfectacionIGV);

      self.Deshacer = function (data,event)  {
      if (event)
      {
        self.CodigoOtraVenta.valueHasMutated();
        self.NombreProducto.valueHasMutated();
        self.IdTipoProducto.valueHasMutated();
        self.IdTipoAfectacionIGV.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.CodigoOtraVenta("");
        self.NombreProducto("");
        self.IdTipoProducto("");
        self.IdTipoAfectacionIGV("");
        self.NombreProducto(self._NombreProducto());
        self.CodigoOtraVenta(self._CodigoOtraVenta());
        self.IdTipoProducto(self._IdTipoProducto());
        self.IdTipoAfectacionIGV(self._IdTipoAfectacionIGV());
        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreProducto.valueHasMutated();
        self._NombreProducto(self.NombreProducto());

        self._CodigoOtraVenta.valueHasMutated();
        self._CodigoOtraVenta(self.CodigoOtraVenta());

        self._IdTipoProducto.valueHasMutated();
        self._IdTipoProducto(self.IdTipoProducto());


        self._IdTipoAfectacionIGV.valueHasMutated();
        self._IdTipoAfectacionIGV(self.IdTipoAfectacionIGV());

      }
    }
}

OtraVentaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping = {
    'OtrasVenta': {
        create: function (options) {
            if (options)
              return new OtrasVentaModel(options.data);
            }
    },
    'OtraVenta': {
        create: function (options) {
            if (options)
              return new OtraVentaModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombreotraventa;
    var _abreviaturaotraventa;
    var _input_habilitado = false;
    var _idotraventa;
    var _otraventa;
    var _modo_nuevo = false;
    var _id_filaotraventa_anterior;

    var self = this;
    self.MostrarTitulo = ko.observable("");

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarOtrasVenta = function() {
        console.log("ListarOtrasVenta");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Catalogo/cOtraVenta/ListarOtrasVenta',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.OtrasVenta([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.OtrasVenta.push(new OtrasVentaModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo == false)
      {
        var id = "#"+data.IdProducto();
        $(id).addClass('active').siblings().removeClass('active');
        _otraventa = data;

      }

    }

    self.FilaButtonsOtraVenta = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo);
          if(_modo_nuevo == true)
          return;

          console.log("OTRA FILA AFECTADA");
          _otraventa.Deshacer(null, event);
          _input_habilitado = false;
          $("#btnAgregarOtraVenta").prop("disabled",false);
          self.HabilitarTablaSpanOtraVenta(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdProducto();
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        //console.log(item.IdProducto());
        var _idotraventa = anteriorObjeto.attr("id");
        //console.log(_idotraventa);
        var match = ko.utils.arrayFirst(self.data.OtrasVenta(), function(item) {
              //console.log(item.IdProducto());
              return _idotraventa == item.IdProducto();
          });

        if(match)
        {
          _idotraventa = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdProducto();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {
          //console.log(item.IdProducto());
          var _idotraventa = siguienteObjeto.attr("id");
          //console.log(_idotraventa);
          var match = ko.utils.arrayFirst(self.data.OtrasVenta(), function(item) {
                //console.log(item.IdProducto());
                return _idotraventa == item.IdProducto();
            });

          if(match)
          {
            _otraventa = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputOtraVenta = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputOtraVenta').hide();
        $(id).find('.class_SpanOtraVenta').show();
      }
      else
      {
        $(id).find('.class_InputOtraVenta').show();
        $(id).find('.class_SpanOtraVenta').hide();
      }

    }

    self.HabilitarTablaSpanOtraVenta = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanOtraVenta').hide();
        $(id).find('.class_InputOtraVenta').show();
        //$(id).find('.guardar_button_OtraVenta').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanOtraVenta').show();
        $(id).find('.class_InputOtraVenta').hide();
        $(id).find('.guardar_button_OtraVenta').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsOtraVenta = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_OtraVenta').prop("disabled", true);
        $(id).find('.borrar_button_OtraVenta').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_OtraVenta').prop("disabled", false);
        $(id).find('.borrar_button_OtraVenta').prop("disabled", false);
      }
    }

    self.OnChangeTipoAfectacionIGV = function (data, event) {
      if (event) {
        if(data.IdTipoAfectacionIGV() == TIPO_AFECTACION_IGV.GRAVADO) {
          data.IdTipoPrecio(TIPO_PRECIO.PRECIO_UNITARIO_INCLUIDO_IGV);
        }
        else {
          data.IdTipoPrecio(TIPO_PRECIO.VALOR_REFERENCIAL_OPERACION_GRATUITA);
        }

        Models.data.TiposAfectacionIGV().forEach(function(item) {
          if (item.IdTipoAfectacionIGV() == data.IdTipoAfectacionIGV()) {
            data.CodigoTipoAfectacionIGV(item.CodigoTipoAfectacionIGV())
          }
        });
        Models.data.TiposPrecio().forEach(function(item) {
          if (item.IdTipoPrecio() == data.IdTipoPrecio()) {
            data.CodigoTipoPrecio(item.CodigoTipoPrecio())
          }
        });
      }
    }

    self.AgregarOtraVenta = function(data,event) {
          console.log("AgregarOtraVenta");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            self.MostrarTitulo("REGISTRO DE OTRA VENTA")
            var objeto = Knockout.CopiarObjeto(self.data.OtraVenta);
            console.log(objeto);
            _otraventa = new OtrasVentaModel(objeto);
            self.data.OtrasVenta.push(_otraventa);

            //Deshabilitando buttons
            self.HabilitarButtonsOtraVenta(null, false);
            $("#null_editar_button_OtraVenta").prop("disabled", true);
            $("#null_borrar_button_OtraVenta").prop("disabled", false);


            $("#btnAgregarOtraVenta").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdProducto());
            var id_span_nombreotraventa ="#"+objeto.IdProducto()+"_span_NombreProducto";
            var id_input_nombreotraventa ="#"+objeto.IdProducto()+"_input_NombreProducto";

            var id_span_codigootraventa ="#"+objeto.IdProducto()+"_span_CodigoOtraVenta";
            var id_input_codigootraventa ="#"+objeto.IdProducto()+"_input_CodigoOtraVenta";

            var id_span_idtipootraventa ="#"+objeto.IdProducto()+"_span_IdTipoProducto";
            var id_input_idtipootraventa ="#"+objeto.IdProducto()+"_input_IdTipoProducto";
            var id_combo_idtipootraventa ="#"+objeto.IdProducto()+"_combo_IdTipoProducto";

            var id_span_idtipoafectacionigv ="#"+objeto.IdProducto()+"_span_IdTipoAfectacionIGV";
            var id_input_idtipoafectacionigv ="#"+objeto.IdProducto()+"_input_IdTipoAfectacionIGV";
            var id_combo_idtipoafectacionigv ="#"+objeto.IdProducto()+"_combo_IdTipoAfectacionIGV";

            var idbutton ="#"+objeto.IdProducto()+"_button_OtraVenta";

            console.log(idbutton);
            //var id_tipootraventa = '#' + self.IdProducto() +  '_input_IdTipoProducto';
            // $(id_input_idtipootraventa).selectpicker("refresh");

            $(id_span_nombreotraventa).hide();
            $(id_input_nombreotraventa).show();

            $(id_span_codigootraventa).hide();
            $(id_input_codigootraventa).show();

            $(id_span_idtipootraventa).hide();
            $(id_combo_idtipootraventa).show();

            $(id_span_idtipoafectacionigv).hide();
            $(id_combo_idtipoafectacionigv).show();

            $(idbutton).show();
            $(id_input_codigootraventa).focus();

            _modo_nuevo = true;
            _input_habilitado = true;
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarOtraVenta =function(data,event){

      if(event)
      {
        self.OnChangeTipoAfectacionIGV(data, event);

        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _otraventa});

        $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cOtraVenta/InsertarOtraVenta',
            success: function (data) {
                if (data != null) {
                console.log("resultado -  InsertarOtraVenta");
                console.log(data);

                if (!data.error)
                {
                  _otraventa.IdProducto(data.resultado.IdProducto);
                  //deshabilitar botones agregar
                  $("#btnAgregarOtraVenta").prop("disabled",false);

                  var id_otraventa = "#"+ _otraventa.IdProducto();
                  self.HabilitarFilaInputOtraVenta(id_otraventa, false);

                  var idbutton ="#"+_otraventa.IdProducto()+"_button_OtraVenta";
                  $(idbutton).hide();

                   _otraventa.Confirmar(null,event);
                   self.HabilitarButtonsOtraVenta(null, true);

                   //ACTUALIZANDO DATA Nombre
                   var idnombretipoproducto = '#' + _otraventa.IdProducto() + '_input_IdTipoProducto option:selected';
                   var nombretipoproducto = $(idnombretipoproducto).html();

                   var idnombretipoafectacionigv = '#' + _otraventa.IdProducto() + '_input_IdTipoAfectacionIGV option:selected';
                   var nombretipocfectacionigv = $(idnombretipoafectacionigv).html();

                   _otraventa.NombreTipoProducto(nombretipoproducto);
                   _otraventa.NombreTipoAfectacionIGV(nombretipocfectacionigv);

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

    self.ActualizarOtraVenta = function(data,event) {

      self.OnChangeTipoAfectacionIGV(data, event);

          console.log("ActualizarOtraVenta");
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _otraventa});

          $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cOtraVenta/ActualizarOtraVenta',
            success: function (data) {
                if (data != null) {
                  console.log(data);

                  if (data == "")
                  {
                    //deshabilitar campo origen
                    $("#btnAgregarOtraVenta").prop("disabled",false);
                    console.log("ID5:"+_otraventa.IdProducto());
                    _otraventa.Confirmar(null,event);

                    var id_otraventa = "#"+ _otraventa.IdProducto();
                    self.HabilitarFilaInputOtraVenta(id_otraventa, false);

                    var idbutton ="#"+_otraventa.IdProducto()+"_button_OtraVenta";
                    $(idbutton).hide();

                    //ACTUALIZANDO DATA Nombre
                    var idnombretipoproducto = '#' + _otraventa.IdProducto() + '_input_IdTipoProducto option:selected';
                    var nombretipoproducto = $(idnombretipoproducto).html();

                    var idnombretipoafectacionigv = '#' + _otraventa.IdProducto() + '_input_IdTipoAfectacionIGV option:selected';
                    var nombretipocfectacionigv = $(idnombretipoafectacionigv).html();


                    _otraventa.NombreTipoProducto(nombretipoproducto);
                    _otraventa.NombreTipoAfectacionIGV(nombretipocfectacionigv);


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

    self.EditarOtraVenta = function(data, event) {

      if(event)
      {
        console.log("EditarOtraVenta");
        console.log("ID.:"+data.IdProducto());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_otraventa);

        if( _modo_nuevo == true )
        {

        }
        else {

          if (_otraventa.IdProducto() == data.IdProducto())
          {
            self.MostrarTitulo("EDICIÓN DE OTRA VENTA")

            if (_input_habilitado == true)
            {
              $("#btnAgregarOtraVenta").prop("disabled",false);
              data.Deshacer(null,event);
              var id_otraventa = "#"+ data.IdProducto();
              self.HabilitarFilaInputOtraVenta(id_otraventa, false);

              var idbutton = "#"+_otraventa.IdProducto()+"_button_OtraVenta";
              $(idbutton).hide();

              _input_habilitado =false;

            }
            else {
              $("#btnAgregarOtraVenta").prop("disabled",true);
              var id_otraventa = "#"+ data.IdProducto();
              self.HabilitarFilaInputOtraVenta(id_otraventa, true);

              var idbutton = "#"+data.IdProducto()+"_button_OtraVenta";

              var idinput = "#"+data.IdProducto()+"_input_Direccion";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarOtraVenta").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_otraventa.IdProducto());

              var id_otraventa = "#"+ _otraventa.IdProducto();
              self.HabilitarFilaInputOtraVenta(id_otraventa, false);

              var idbutton = "#"+_otraventa.IdProducto()+"_button_OtraVenta";

              _otraventa.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_otraventa = "#"+ data.IdProducto();
            self.HabilitarFilaInputOtraVenta(id_otraventa, true);

            var idbutton = "#"+data.IdProducto()+"_button_OtraVenta";

            var idinput = "#"+data.IdProducto()+"_input_Direccion";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarOtraVenta = function (data) {

      if(_modo_nuevo == false)
      {
        _otraventa.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarOtraVenta").prop("disabled",false);
        self.HabilitarTablaSpanOtraVenta(null, true);
      }
      self.MostrarTitulo("ELIMINACION DE OTRA VENTA")
      setTimeout(function(){
        alertify.confirm(self.MostrarTitulo(),"¿Desea borrar el registro?", function(){
          console.log("BorrarOtraVenta");
          console.log(data.IdProducto());
          self.HabilitarButtonsOtraVenta(null, true);
          if (data.IdProducto() != null){
            self.BorrarOtraVenta(data);
          }
          else
          {
            $("#btnAgregarOtraVenta").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.OtrasVenta.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarOtrasVenta();
          }
        },function() {

        });
      }, 200);
    }

    self.BorrarOtraVenta = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
        type: 'POST',
        data : datajs,
        dataType: "json",
        url: SITE_URL+'/Catalogo/cOtraVenta/BorrarOtraVenta',
        success: function (data) {
            if (data != null) {
              console.log("BorrarOtraVenta");
              //console.log(data);
              if(data.msg != "")
              {
                alertify.alert("ERROR EN "+self.MostrarTitulo(),data.error.msg);
              }
              else {
                $("#btnAgregarOtraVenta").prop("disabled",false);
                self.HabilitarTablaSpanOtraVenta(null, true);
                self.SeleccionarSiguiente(objeto);
                self.data.OtrasVenta.remove(objeto);
              }
            }
          },
          error : function (jqXHR, textStatus, errorThrown) {
            var $data = {error:{msg:jqXHR.responseText}};
            alertify.alert("HA OCURRIDO UN ERROR",$data.error.msg);
          }
      });

    }


    self.OnClickOtraVenta = function(data ,event) {

      if(event)
      {
          console.log("OnClickOtraVenta");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_otraventa);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarOtraVenta").prop("disabled",true);
            if(_otraventa.IdProducto() !=  data.IdProducto())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _otraventa.Deshacer(null, event);

                //var id_otraventa = "#" + _id_filaotraventa_anterior;
                var id_otraventa = "#" + _otraventa.IdProducto();
                self.HabilitarFilaInputOtraVenta(id_otraventa, false);

                var idbutton = "#"+_otraventa.IdProducto()+"_button_OtraVenta";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_otraventa.IdProducto());
              console.log(data.IdProducto());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_otraventa = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_otraventa, "span") || $.isSubstring(id_fila_otraventa, "input")){
                id_fila_otraventa = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_otraventa).find('input').attr('id');
              self.HabilitarFilaInputOtraVenta("#" + $(id_fila_otraventa).parent()[0].id, true);

              var idbutton = "#"+data.IdProducto()+"_button_OtraVenta";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_otraventa = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_otraventa, "span") || $.isSubstring(id_fila_otraventa, "input")){
                    id_fila_otraventa = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputOtraVenta("#" + $(id_fila_otraventa).parent()[0].id, true);

                  var idbutton = "#"+data.IdProducto()+"_button_OtraVenta";
                  var idinput ="#"+$(id_fila_otraventa).find('input').attr('id');
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
    self.OnKeyUpOtraVenta = function(data, event){
      if(event)
      {
       console.log("OnKeyUpOtraVenta");

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
          var idinputnombre = '#' + _otraventa.IdProducto() + '_input_NombreProducto';
          var idinputcodigo = '#' + _otraventa.IdProducto() + '_input_CodigoOtraVenta';
          var idinputtipo ='#' +  _otraventa.IdProducto() + '_input_IdTipoProducto';
          var idinputtipoafectacionigv ='#' +  _otraventa.IdProducto() + '_input_IdTipoAfectacionIGV';

          _otraventa.NombreProducto($(idinputnombre).val());
          _otraventa.CodigoOtraVenta($(idinputcodigo).val());
          _otraventa.IdTipoProducto($(idinputtipo).val());
          _otraventa.IdTipoAfectacionIGV($(idinputtipoafectacionigv).val());



         if(_modo_nuevo == true)
         {
           self.InsertarOtraVenta(_otraventa,event);
         }
         else
         {
           self.ActualizarOtraVenta(_otraventa,event);
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
            alertify.confirm("REGISTRO DE OTRA VENTA","¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_otraventa);
              self.data.OtrasVenta.remove(_otraventa);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarOtraVenta").prop("disabled",false);
              self.HabilitarButtonsOtraVenta(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            },function() {

            });
          }
          else
          {
            console.log("Escape - false");
            //revertir texto

             _otraventa.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarOtraVenta").prop("disabled",false);

            /*var id_fila_otraventa = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_otraventa, "span") || $.isSubstring(id_fila_otraventa, "input")){
              id_fila_otraventa = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputOtraVenta("#" + $(id_fila_otraventa).parent()[0].id, false);*/
            self.HabilitarTablaSpanOtraVenta(null, true);
            var idbutton ="#"+_otraventa.IdProducto()+"_button_OtraVenta";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarOtraVenta = function(data,event) {
      if(event)
      {
         console.log("GuardarOtraVenta");
         console.log(_nombreotraventa);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
         var idinputnombre = '#' + _otraventa.IdProducto() + '_input_NombreProducto';
         var idinputcodigo = '#' + _otraventa.IdProducto() + '_input_CodigoOtraVenta';
         var idinputtipo ='#' +  _otraventa.IdProducto() + '_input_IdTipoProducto';
         var idinputtipoafectacionigv ='#' +  _otraventa.IdProducto() + '_input_IdTipoAfectacionIGV';

         _otraventa.NombreProducto($(idinputnombre).val());
         _otraventa.CodigoOtraVenta($(idinputcodigo).val());
         _otraventa.IdTipoProducto($(idinputtipo).val());
         _otraventa.IdTipoAfectacionIGV($(idinputtipoafectacionigv).val());



         if(_modo_nuevo == true)
         {
           self.InsertarOtraVenta(_otraventa,event);
         }
         else
         {
           self.ActualizarOtraVenta(_otraventa,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
