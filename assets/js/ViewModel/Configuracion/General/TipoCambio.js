TiposCambioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);


    self._TipoCambioCompra = ko.observable(data.TipoCambioCompra);
    self._TipoCambioVenta = ko.observable(data.TipoCambioVenta);
    self._TipoCambioPesoChileno = ko.observable(data.TipoCambioPesoChileno);

    self.__FechaCambio=ko.observable(moment(self.FechaCambio()).format('DD/MM/YYYY'));
    self._FechaCambio = ko.observable(self.__FechaCambio());

    self.truncateDecimals = function (num, digits) {
      //debugger;
      if(typeof(num) != 'undefined'){
        if(num.length > 0 ){
          var numS = num.toString(),
              decPos = numS.indexOf('.'),
              substrLength = decPos == -1 ? numS.length : 1 + decPos + digits,
              trimmedResult = numS.substr(0, substrLength),
              finalResult = isNaN(trimmedResult) ? 0 : trimmedResult;

          return parseFloat(finalResult);
        }
      }
    }

    self.FormateoDecimal = function (value) {
      if(value != null){
        //return (self.TipoCambioCompra()).toFixed(3);
        var decimal = value;
        decimal = self.truncateDecimals(decimal, c_decimal);
        //var resultado = parseDouble(decimal);
        return accounting.formatNumber(decimal, c_decimal);
      }
      else {
        return "";
      }
    }

    self.__TipoCambioCompra = ko.observable(self.FormateoDecimal(self.TipoCambioCompra()));
    self.__TipoCambioVenta = ko.observable(self.FormateoDecimal(self.TipoCambioVenta()));
    self.__TipoCambioPesoChileno = ko.observable(self.FormateoDecimal(self.TipoCambioPesoChileno()));

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log(self._FechaCambio());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._TipoCambioCompra());

        self.__FechaCambio.valueHasMutated();
        self.__TipoCambioCompra.valueHasMutated();
        self.__TipoCambioVenta.valueHasMutated();
        self.__TipoCambioPesoChileno.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.__FechaCambio("");
        self.__TipoCambioCompra("");
        self.__TipoCambioVenta("");
        self.__TipoCambioPesoChileno("");
        self.__FechaCambio(self._FechaCambio());
        //self.FechaCambio(self.__FechaCambio());
        self.__TipoCambioCompra(accounting.formatNumber(self._TipoCambioCompra(), c_decimal));
        self.__TipoCambioVenta(accounting.formatNumber(self._TipoCambioVenta(), c_decimal));
        self.__TipoCambioPesoChileno(accounting.formatNumber(self._TipoCambioPesoChileno(), c_decimal));

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");

        self._FechaCambio.valueHasMutated();
        self.__FechaCambio.valueHasMutated();

        self._FechaCambio(self.__FechaCambio());
        self._TipoCambioCompra.valueHasMutated();
        self.__TipoCambioCompra.valueHasMutated();
        self._TipoCambioCompra(self.__TipoCambioCompra());
        self._TipoCambioVenta.valueHasMutated();
        self.__TipoCambioVenta.valueHasMutated();
        self._TipoCambioVenta(self.__TipoCambioVenta());
        self._TipoCambioPesoChileno.valueHasMutated();
        self.__TipoCambioPesoChileno.valueHasMutated();
        self._TipoCambioPesoChileno(self.__TipoCambioPesoChileno());

      }
    }

    self.ValidarFecha = function(data,event) {
      if(event) {
        $(event.target).validate(function(valid, elem) {
        });
      }
    }

}

TipoCambioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}


var Mapping = {
    'TiposCambio': {
        create: function (options) {
            if (options)
              return new TiposCambioModel(options.data);
            }
    },
    'TipoCambio': {
        create: function (options) {
            if (options)
              return new TipoCambioModel(options.data);
            }
    }

}

var c_decimal, c_entero;

IndexTipoCambio = function (data) {

    var _modo_deshacer = false;
    var _codigotipocambio;
    var _nombretipocambio;
    var _simbolotipocambio;
    var _input_habilitado_tipocambio = false;
    var _idtipocambio;
    var _tipocambio;
    var _modo_nuevo_tipocambio = false;
    var _id_filatipocambio_anterior;
    var _objeto_tipo_cambio = null

    var self = this;

    //debugger;
    c_decimal = parseInt(data.dataTipoCambio.DecimalValue);
    c_entero = parseInt(data.dataTipoCambio.EnteroValue);

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarTiposCambio = function() {
        console.log("ListarTiposCambio");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cTipoCambio/ListarTiposCambio',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataTipoCambio.TiposCambio([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataTipoCambio.TiposCambio.push(new TiposCambioModel(item));
                    });
                }
            }
        });
    }

    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        self.ConsultarTiposCambioPorPagina(data,event,self.PostConsultarPorPagina);
        $("#PaginadorTipoCambio").pagination("drawPage", data.pagina);
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        self.dataTipoCambio.TiposCambio([]);
        ko.utils.arrayForEach(data, function (item) {
          self.dataTipoCambio.TiposCambio.push(new TiposCambioModel(item));
        });

        var copia_objeto = Knockout.CopiarObjeto(vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TipoCambio);

        var objeto = vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio()[0];

        self.Seleccionar(objeto, event);
      }
    }

    self.ConsultarTiposCambioPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Configuracion/General/cTipoCambio/ConsultarTiposCambioPorPagina',
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

    self.Consultar = function (data,event) {
      if(event) {
        var tecla = event.keyCode ? event.keyCode : event.which;
        if(tecla == TECLA_ENTER)
        {
          var inputs = $(event.target).closest('form').find(':input:visible');
          inputs.eq(inputs.index(event.target)+ 1).focus();

          self.ConsultarTiposCambio(data,event,self.PostConsultar);
        }
      }
    }

    self.ConsultarTiposCambio = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Configuracion/General/cTipoCambio/ConsultarTiposCambio',
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

    self.PostConsultar = function (data,event) {
      if(event) {

        var fecha_filtro = data.Filtros.CopiaFiltro;

        var objeto_seleccionar = null;

        self.dataTipoCambio.TiposCambio([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          self.dataTipoCambio.TiposCambio.push(new TiposCambioModel(item));
          if(item.FechaCambio == fecha_filtro){
           objeto_seleccionar = Knockout.CopiarObjeto(item);
          }
        });

        if (objeto_seleccionar != null) {
          self.Seleccionar(objeto_seleccionar, event);
        }
        else {
          if (fecha_filtro=="") {
            var objeto = vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio()[0];
            self.Seleccionar(objeto, event);
          }
          else {
            alertify.alert("No existe registro para esta fecha");
            var objeto = vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio()[0];
            self.Seleccionar(objeto, event)
            $("#alert-TipoCambio").empty();
            $('#alert-TipoCambio').append('<div class="alert alert-info"><strong>¡Información!</strong> Solo se muestra fechas cercanas a la búsqueda.</div>')
            setTimeout(function(){
              $("#alert-TipoCambio").empty();
            }, 8000);
          }
        }

        $("#PaginadorTipoCambio").paginador(data.Filtros,self.ConsultarPorPagina);
        self.dataTipoCambio.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");
      if (data != null) {
        if (_modo_nuevo_tipocambio == false)
        {
          var id = "#"+data.IdTipoCambio() +'_tr_tipocambio';
          $(id).addClass('active').siblings().removeClass('active');
          _tipocambio = data;
          _objeto_tipo_cambio = Knockout.CopiarObjeto(vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TipoCambio);
        }
      }
    }

    self.FilaButtonsTipoCambio = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_tipocambio);
          if(_modo_nuevo_tipocambio == true)
          return;

          _tipocambio.Deshacer(null, event);
          _input_habilitado_tipocambio = false;
          console.log("OTRA FILA AFECTADA");
          $("#btnAgregarTipoCambio").prop("disabled",false);
          self.HabilitarTablaSpanTipoCambio(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdTipoCambio() +'_tr_tipocambio';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_tipocambio == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataTipoCambio.TiposCambio(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdTipoCambio();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdTipoCambio() +'_tr_tipocambio';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_tipocambio == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idtipocambio = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataTipoCambio.TiposCambio(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idtipocambio == item.IdTipoCambio();
            });

          if(match)
          {
            _tipocambio = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputTipoCambio = function (data, option)  {
      //var id = "#"+data.IdTipoCambio();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputTipoCambio').hide();
        $(id).find('.class_SpanTipoCambio').show();
      }
      else
      {
        $(id).find('.class_InputTipoCambio').show();
        $(id).find('.class_SpanTipoCambio').hide();
      }

    }

    self.HabilitarTablaSpanTipoCambio = function (data, option)  {
      //var id = "#"+data.IdTipoCambio();
      var id = "#DataTables_Table_0__TipoCambio";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanTipoCambio').hide();
        $(id).find('.class_InputTipoCambio').show();
        //$(id).find('.guardar_button_TipoCambio').show();
        //_input_habilitado_tipocambio = true;
      }
      else {
        $(id).find('.class_SpanTipoCambio').show();
        $(id).find('.class_InputTipoCambio').hide();
        $(id).find('.guardar_button_TipoCambio').hide();
        //_input_habilitado_tipocambio = false;
      }

    }

    self.HabilitarButtonsTipoCambio = function(data, option){
      var id = "#DataTables_Table_0__TipoCambio";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_TipoCambio').prop("disabled", true);
        $(id).find('.borrar_button_TipoCambio').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_TipoCambio').prop("disabled", false);
        $(id).find('.borrar_button_TipoCambio').prop("disabled", false);
      }
    }


    self.AgregarTipoCambio = function(data,event) {
      if(event){
        console.log("AgregarTipoCambio");

        if ( _input_habilitado_tipocambio == true )
        {

        }
        else
        {
          var objeto = Knockout.CopiarObjeto(self.dataTipoCambio.TipoCambio);
          _tipocambio = new TiposCambioModel(objeto);
          self.dataTipoCambio.TiposCambio.push(_tipocambio);

          //Deshabilitando buttons
          self.HabilitarButtonsTipoCambio(null, false);
          $("#null_editar_button_TipoCambio").prop("disabled", true);
          $("#null_borrar_button_TipoCambio").prop("disabled", false);


          $("#btnAgregarTipoCambio").prop("disabled",true);

          //habilitar como destino
          console.log("ID:"+objeto.IdTipoCambio());


          var id_span_codigotipocambio ="#"+objeto.IdTipoCambio()+"_span_FechaCambio";
          var id_input_codigotipocambio ="#"+objeto.IdTipoCambio()+"_input_FechaCambio";

          var id_span_nombretipocambio ="#"+objeto.IdTipoCambio()+"_span_TipoCambioCompra";
          var id_input_nombretipocambio ="#"+objeto.IdTipoCambio()+"_input_TipoCambioCompra";

          var id_span_simbolotipocambio ="#"+objeto.IdTipoCambio()+"_span_TipoCambioVenta";
          var id_input_simbolotipocambio ="#"+objeto.IdTipoCambio()+"_input_TipoCambioVenta";

          var id_span_simbolotipocambiopesochileno ="#"+objeto.IdTipoCambio()+"_span_TipoCambioPesoChileno";
          var id_input_simbolotipocambiopesochileno ="#"+objeto.IdTipoCambio()+"_input_TipoCambioPesoChileno";

          var idbutton ="#"+objeto.IdTipoCambio()+"_button_TipoCambio";

          console.log(idbutton);

          $(id_span_codigotipocambio).hide();
          $(id_input_codigotipocambio).val("");
          $(id_input_codigotipocambio).show();

          $(id_span_nombretipocambio).hide();
          $(id_input_nombretipocambio).show();

          $(id_span_simbolotipocambio).hide();
          $(id_input_simbolotipocambio).show();

          $(id_span_simbolotipocambiopesochileno).hide();
          $(id_input_simbolotipocambiopesochileno).show();

          $(idbutton).show();
          $(id_input_codigotipocambio).focus();

          _modo_nuevo_tipocambio = true;
          _input_habilitado_tipocambio = true;

          var tabla = $('#DataTables_Table_0__TipoCambio');
          $('tr:last', tabla).addClass('active').siblings().removeClass('active');
        }
        $(".fecha-reporte").inputmask({"mask":"99/99/9999",positionCaretOnTab : false})
      }
    }

    self.InsertarTipoCambio =function(data,event){

      if(event)
      {
        console.log("InsertarTipoCambio");
        console.log(_tipocambio.TipoCambioCompra());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : objeto});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cTipoCambio/InsertarTipoCambio',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarTipoCambio");
                      console.log(data);

                      if ($.isNumeric(data.IdTipoCambio))
                      {
                        _tipocambio.IdTipoCambio(data.IdTipoCambio);
                        //deshabilitar botones agregar
                        $("#btnAgregarTipoCambio").prop("disabled",false);

                        var id_tipocambio = "#"+ _tipocambio.IdTipoCambio() +'_tr_tipocambio';
                        self.HabilitarFilaInputTipoCambio(id_tipocambio, false);
                        //debugger;
                        var idbutton ="#"+_tipocambio.IdTipoCambio()+"_button_TipoCambio";
                        $(idbutton).hide();

                        var fecha = _tipocambio.FechaCambio();
                        var cadena = fecha.toString().split("/");
                        var fdia = self.PadLeft(cadena[0], 2);
                        var fmes = self.PadLeft(cadena[1], 2);
                        var faño = self.PadLeft(cadena[2], 4);

                        var fecha_formateada = fdia + "/" + fmes + "/" + faño;
                        _tipocambio.__FechaCambio(fecha_formateada);
                        _tipocambio.__TipoCambioCompra(accounting.formatNumber(_tipocambio.TipoCambioCompra(), c_decimal));
                        _tipocambio.__TipoCambioVenta(accounting.formatNumber(_tipocambio.TipoCambioVenta(), c_decimal));
                        _tipocambio.__TipoCambioPesoChileno(accounting.formatNumber(_tipocambio.TipoCambioPesoChileno(), c_decimal));
                        //_tipocambio.__FechaCambio(fecha_formateada);
                        //_tipocambio.__FechaCambio(_tipocambio.FechaCambio());
                        //_tipocambio.__FechaCambio(moment().format("DD/MM/YYYY"));
                         _tipocambio.Confirmar(null,event);
                         self.HabilitarButtonsTipoCambio(null, true);

                        existecambio = false;
                        _input_habilitado_tipocambio = false;
                        _modo_nuevo_tipocambio = false;

                      }
                      else {
                        alertify.alert(data.IdTipoCambio);
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

    self.ActualizarTipoCambio = function(data,event) {
          console.log("ActualizarTipoCambio");
          console.log(_tipocambio.TipoCambioCompra());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : objeto});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cTipoCambio/ActualizarTipoCambio',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarTipoCambio").prop("disabled",false);
                          console.log("ID5:"+_tipocambio.IdTipoCambio());

                          console.log(data);

                          var fecha = _tipocambio.FechaCambio();
                          var cadena = fecha.toString().split("/");
                          var fdia = self.PadLeft(cadena[0], 2);
                          var fmes = self.PadLeft(cadena[1], 2);
                          var faño = self.PadLeft(cadena[2], 2);

                          var fecha_formateada = fdia + "/" + fmes + "/" + faño;
                          _tipocambio.__FechaCambio(fecha_formateada);
                          _tipocambio.FechaCambio(fecha_formateada);

                          _tipocambio.__TipoCambioCompra(accounting.formatNumber(_tipocambio.TipoCambioCompra(), c_decimal));
                          _tipocambio.__TipoCambioVenta(accounting.formatNumber(_tipocambio.TipoCambioVenta(), c_decimal));
                          _tipocambio.__TipoCambioPesoChileno(accounting.formatNumber(_tipocambio.TipoCambioPesoChileno(), c_decimal));
                          //_tipocambio.__FechaCambio(_tipocambio.FechaCambio());
                          //_tipocambio.__FechaCambio(moment(_tipocambio.__FechaCambio()).format("DD/MM/YYYY"));
                          _tipocambio.Confirmar(null,event);

                          //_tipocambio.__FechaCambio(moment().format("DD/MM/YYYY"));

                          var id_tipocambio = "#"+ _tipocambio.IdTipoCambio() +'_tr_tipocambio';
                          self.HabilitarFilaInputTipoCambio(id_tipocambio, false);

                          var idbutton ="#"+_tipocambio.IdTipoCambio()+"_button_TipoCambio";
                          $(idbutton).hide();


                          existecambio = false;
                          _input_habilitado_tipocambio = false;
                          _modo_nuevo_tipocambio = false;

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

    self.EditarTipoCambio = function(data, event) {

      if(event)
      {
        console.log("EditarTipoCambio");
        console.log("ID.:"+data.IdTipoCambio());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_tipocambio);

        if( _modo_nuevo_tipocambio == true )
        {

        }
        else {

          if (_tipocambio.IdTipoCambio() == data.IdTipoCambio())
          {

            if (_input_habilitado_tipocambio == true)
            {
              $("#btnAgregarTipoCambio").prop("disabled",false);
              data.Deshacer(null,event);
              var id_tipocambio = "#"+ data.IdTipoCambio() +'_tr_tipocambio';
              self.HabilitarFilaInputTipoCambio(id_tipocambio, false);

              var idbutton = "#"+_tipocambio.IdTipoCambio()+"_button_TipoCambio";
              $(idbutton).hide();

              _input_habilitado_tipocambio =false;

            }
            else {
              $("#btnAgregarTipoCambio").prop("disabled",true);
              var id_tipocambio = "#"+ data.IdTipoCambio() +'_tr_tipocambio';
              self.HabilitarFilaInputTipoCambio(id_tipocambio, true);

              var idbutton = "#"+data.IdTipoCambio()+"_button_TipoCambio";

              var idinput = "#"+data.IdTipoCambio()+"_input_FechaCambio";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipocambio = true;
            }

          }
          else {
            $("#btnAgregarTipoCambio").prop("disabled",true);
            if( _input_habilitado_tipocambio == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_tipocambio.IdTipoCambio());

              var id_tipocambio = "#"+ _tipocambio.IdTipoCambio() +'_tr_tipocambio';
              self.HabilitarFilaInputTipoCambio(id_tipocambio, false);

              var idbutton = "#"+_tipocambio.IdTipoCambio()+"_button_TipoCambio";

              _tipocambio.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_tipocambio = "#"+ data.IdTipoCambio() +'_tr_tipocambio';
            self.HabilitarFilaInputTipoCambio(id_tipocambio, true);

            var idbutton = "#"+data.IdTipoCambio()+"_button_TipoCambio";

            var idinput = "#"+data.IdTipoCambio()+"_input_FechaCambio";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_tipocambio = true;
          }


        }

      }

    }

    self.PreBorrarTipoCambio = function (data) {

      if(_modo_nuevo_tipocambio == false)
      {
      _tipocambio.Deshacer(null, event);
      _input_habilitado_tipocambio = false;
      $("#btnAgregarTipoCambio").prop("disabled",false);
      self.HabilitarTablaSpanTipoCambio(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarTipoCambio");
          console.log(data.IdTipoCambio());
          self.HabilitarButtonsTipoCambio(null, true);
          if (data.IdTipoCambio() != null)
            self.BorrarTipoCambio(data);
          else
          {
            $("#btnAgregarTipoCambio").prop("disabled",false);
            _input_habilitado_tipocambio = false;
            _modo_nuevo_tipocambio = false;
            self.dataTipoCambio.TiposCambio.remove(data);
            var tabla = $('#DataTables_Table_0__TipoCambio');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarTiposCambio();
          }
        });
      }, 200);

    }

    self.BorrarTipoCambio = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cTipoCambio/BorrarTipoCambio',
              success: function (data) {
                  if (data.msg != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);
                    if(data.msg != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarTipoCambio").prop("disabled",false);
                      self.HabilitarTablaSpanTipoCambio(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataTipoCambio.TiposCambio.remove(objeto);

                      var filas = vistaModeloGeneral.vmgTipoCambio.dataTipoCambio.TiposCambio().length;

                      self.dataTipoCambio.Filtros.totalfilas(data.Filtros.totalfilas);
                      if(filas == 0)
                      {
                        $("#PaginadorTipoCambio").paginador(data.Filtros,self.ConsultarPorPagina);
                        var ultimo = $("#PaginadorTipoCambio ul li:last").prev();
                        ultimo.children("a").click();
                      }
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

    self.LimpiarComa = function(data, event){
      //setTimeout(function(){
        var id_input_compra = "#" + data.IdTipoCambio() + "_input_TipoCambioCompra";
        var id_input_venta = "#" + data.IdTipoCambio() + "_input_TipoCambioVenta";
        var id_input_pesochileno = "#" + data.IdTipoCambio() + "_input_TipoCambioPesoChileno";
        $(id_input_compra).val($(id_input_compra).val().replace(/[^0-9\.]/g,''));
        $(id_input_venta).val($(id_input_venta).val().replace(/[^0-9\.]/g,''));
        
        if ($(id_input_pesochileno).is(":visible")) {
          $(id_input_pesochileno).val($(id_input_pesochileno).val().replace(/[^0-9\.]/g,''));
        }

      //}, 100);

    }

    self.OnClickTipoCambio = function(data ,event) {

      if(event)
      {
          console.log("OnClickTipoCambio");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_tipocambio);

          self.LimpiarComa(data, null);

          if( _modo_nuevo_tipocambio == true )
          {

          }
          else
          {

            $("#btnAgregarTipoCambio").prop("disabled",true);


            if(_tipocambio.IdTipoCambio() !=  data.IdTipoCambio())
            {
              if (_input_habilitado_tipocambio == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _tipocambio.Deshacer(null, event);

                //var id_tipocambio = "#" + _id_filatipocambio_anterior;
                var id_tipocambio = "#" + _tipocambio.IdTipoCambio() +'_tr_tipocambio';
                self.HabilitarFilaInputTipoCambio(id_tipocambio, false);

                var idbutton = "#"+_tipocambio.IdTipoCambio()+"_button_TipoCambio";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_tipocambio.IdTipoCambio());
              console.log(data.IdTipoCambio());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_tipocambio = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_tipocambio, "span") || $.isSubstring(id_fila_tipocambio, "input")){
                id_fila_tipocambio = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_tipocambio).find('input').attr('id');
              self.HabilitarFilaInputTipoCambio("#" + $(id_fila_tipocambio).parent()[0].id, true);

              var idbutton = "#"+data.IdTipoCambio()+"_button_TipoCambio";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_tipocambio = true;

              }
              else {
                if (_input_habilitado_tipocambio == false)
                {
                  var id_fila_tipocambio = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_tipocambio, "span") || $.isSubstring(id_fila_tipocambio, "input")){
                    id_fila_tipocambio = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputTipoCambio("#" + $(id_fila_tipocambio).parent()[0].id, true);

                  var idbutton = "#"+data.IdTipoCambio()+"_button_TipoCambio";
                  var idinput ="#"+$(id_fila_tipocambio).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_tipocambio = true;
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
    self.OnKeyUpTipoCambio = function(data, event){
      if(event)
      {
       console.log("OnKeyUpTipoCambio");

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
         var idinputcompra = _tipocambio.IdTipoCambio() + '_input_TipoCambioCompra';
         var idinputcodigo = _tipocambio.IdTipoCambio() + '_input_FechaCambio';
         var idinputventa = _tipocambio.IdTipoCambio() + '_input_TipoCambioVenta';
         var idinputpesochileno = _tipocambio.IdTipoCambio() + '_input_TipoCambioPesoChileno';

         console.log("VALUE FECHA: " + $("#"+ idinputcodigo).val());
         console.log("VALUE FECHA: " + idinputcodigo);
         console.log("VALUE FECHA: " + event.target.id);


         _tipocambio.TipoCambioCompra($("#"+idinputcompra).val());
         _tipocambio.TipoCambioVenta($("#"+idinputventa).val());
         _tipocambio.TipoCambioPesoChileno($("#"+idinputpesochileno).val());
         _tipocambio.FechaCambio($("#"+ idinputcodigo).val());

         var obj = Knockout.CopiarObjeto(_tipocambio);
         var input_id = "#" + obj.IdTipoCambio() + "_input_FechaCambio";
         console.log("ID TIPOCAMBIO:" + obj.IdTipoCambio());
        var fecha = $(input_id).val();
         var nfecha = fecha.split("/");
         var ofecha = nfecha[2] + "-" +  nfecha[1] + "-" +  nfecha[0];
         //var nofecha = moment(ofecha).format("YYYY-MM-DD");
         obj.FechaCambio(ofecha);
         console.log("FECHA TIPOCAMBIO1:" + fecha);
         console.log("FECHA TIPOCAMBIO:" + obj.FechaCambio());
         //VALIDANDO LA FECHA
         var timestamp = Date.parse(ofecha);
         if(isNaN(timestamp) == true || nfecha[2].length < 4){
           alertify.alert("Ingrese una fecha valida.");
           $(input_id).focus();
           return false;
         }

         if(_modo_nuevo_tipocambio == true)
         {

           //console.log("NFECHA: " + nfecha);
           self.InsertarTipoCambio(obj, event);
         }
         else
         {
           //_tipocambio.FechaCambio(moment(_tipocambio.FechaCambio()).toISOString());
           self.ActualizarTipoCambio(obj, event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_tipocambio == true)
        {
          if(_modo_nuevo_tipocambio == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_tipocambio);
              self.dataTipoCambio.TiposCambio.remove(_tipocambio);
              var tabla = $('#DataTables_Table_0__TipoCambio');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarTipoCambio").prop("disabled",false);
              self.HabilitarButtonsTipoCambio(null, true);
               _modo_nuevo_tipocambio = false;
               _input_habilitado_tipocambio = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_tipocambio._TipoCambioCompra());
            //revertir texto

             _tipocambio.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarTipoCambio").prop("disabled",false);

            /*var id_fila_tipocambio = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_tipocambio, "span") || $.isSubstring(id_fila_tipocambio, "input")){
              id_fila_tipocambio = "#" + $(event.target).parent()[0].id;
            }

            self.HabilitarFilaInputTipoCambio("#" + $(id_fila_tipocambio).parent()[0].id, false);*/
            self.HabilitarTablaSpanTipoCambio(null, true);

            var idbutton ="#"+_tipocambio.IdTipoCambio()+"_button_TipoCambio";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_tipocambio = false;
            _input_habilitado_tipocambio = false;
          }

        }
      }
    }

    self.GuardarTipoCambio = function(data,event) {
      if(event)
      {
         console.log("GuardarTipoCambio");
         console.log(_nombretipocambio);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputcompra = _tipocambio.IdTipoCambio() + '_input_TipoCambioCompra';
          var idinputfecha = _tipocambio.IdTipoCambio() + '_input_FechaCambio';
          var idinputventa = _tipocambio.IdTipoCambio() + '_input_TipoCambioVenta';
          var idinputpesochileno = _tipocambio.IdTipoCambio() + '_input_TipoCambioPesoChileno';


          _tipocambio.TipoCambioCompra($("#"+idinputcompra).val());
          _tipocambio.TipoCambioVenta($("#"+idinputventa).val());
          _tipocambio.TipoCambioPesoChileno($("#"+idinputpesochileno).val());
          _tipocambio.FechaCambio($("#"+idinputfecha).val());
          console.log("VALUE TIPO COMPRA: " + _tipocambio.TipoCambioVenta());
          console.log("VALUE TIPO VENTA: " + _tipocambio.TipoCambioVenta());

          console.log("COMPRA: " + $("#"+idinputcompra).val());
          console.log("VENTA: " + $("#"+idinputventa).val());
          //_tipocambio.Confirmar(null, event);

         var obj = Knockout.CopiarObjeto(_tipocambio);
        var input_id = "#" + obj.IdTipoCambio() + "_input_FechaCambio";
        var fecha = $(input_id).val();
         var nfecha = fecha.split("/");
         var ofecha = nfecha[2] + "-" +  nfecha[1] + "-" +  nfecha[0];
         //var nofecha = moment(ofecha).format("YYYY-MM-DD");
         obj.FechaCambio(ofecha);

         //VALIDANDO LA FECHA
         var timestamp = Date.parse(ofecha);
         if(isNaN(timestamp) == true || nfecha[2].length =="")
         {
           alertify.alert("Debe ingresar una fecha");
           $(input_id).focus();
           return false;
         }
         else
         {
           if(isNaN(timestamp) == true || nfecha[2].length < 4){
             alertify.alert("Ingrese una fecha valida.");
              $(input_id).focus();
              return false;
             }
         }


         if(_modo_nuevo_tipocambio == true)
         {
           self.InsertarTipoCambio(obj, event);
           //console.log("INSERTAR: " + _tipocambio.FechaCambio());
         }
         else
         {
           self.ActualizarTipoCambio(obj,event);
           //console.log("ACTUALIZAR: " + _tipocambio.FechaCambio());
         }
      }
    }

    self.PadLeft = function(value, length) {
      return (value.toString().length < length) ? self.PadLeft("0" + value, length) : value;
    }


}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
