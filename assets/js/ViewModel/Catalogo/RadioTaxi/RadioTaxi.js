
RadiosTaxiModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  self._NombreRadioTaxi = ko.observable(data.NombreRadioTaxi);

  self.Deshacer = function (data, event) {
    if (event) {
      //Poner todos las propiedades aqui.
      console.log("DESHACER:  " + self._NombreRadioTaxi());

      self.NombreRadioTaxi.valueHasMutated();
      //LIMPIANDO LAS CAJAS DE TEXTO
      self.NombreRadioTaxi("");
      self.NombreRadioTaxi(self._NombreRadioTaxi());

      return true;
    }

  }

  self.Confirmar = function (data, event) {
    if (event) {
      console.log("Confirmar");
      self._NombreRadioTaxi.valueHasMutated();
      self._NombreRadioTaxi(self.NombreRadioTaxi());

    }
  }

  //console.log("-Inicio Tipo Existencia-");
  //console.log(self._NombreRadioTaxi());
}

RadioTaxiModel = function (data) {
  var self = this;
  ko.mapping.fromJS(data, {}, self);

  //self.NombreRadioTaxi = ko.observable("");
}

var Mapping = {
  'RadiosTaxi': {
    create: function (options) {
      if (options)
        return new RadiosTaxiModel(options.data);
    }
  },
  'RadioTaxi': {
    create: function (options) {
      if (options)
        return new RadioTaxiModel(options.data);
    }
  }

}

Index = function (data) {

  var _modo_deshacer = false;
  var _nombrecostoagregado;
  var _input_habilitado = false;
  var _radiotaxi;
  var _modo_nuevo = false;
  var _id_filacostoagregado_anterior;

  var self = this;

  self.MostrarTitulo = ko.observable("");

  ko.mapping.fromJS(data, Mapping, self);
  //self.Errores = ko.validation.group(self, { deep: true });

  self.ListarRadiosTaxi = function () {
    console.log("ListarRadiosTaxi");

    $.ajax({
      type: 'POST',
      dataType: "json",
      url: SITE_URL + '/Catalogo/cRadioTaxi/ListarRadiosTaxi',
      success: function (data) {
        if (data != null) {
          console.log(data);
          self.data.RadiosTaxi([]);
          ko.utils.arrayForEach(data, function (item) {
            self.data.RadiosTaxi.push(new RadiosTaxiModel(item));
          });
        }
      }
    });
  }

  self.Seleccionar = function (data, event) {
    console.log("Seleccionar");

    if (_modo_nuevo == false) {
      var id = "#" + data.IdRadioTaxi();
      $(id).addClass('active').siblings().removeClass('active');
      _radiotaxi = data;
    }

  }

  self.FilaButtonsRadioTaxi = function (data, event) {
    console.log("BUTTONS");
    console.log("EVENTTARGET: " + $(event.target).attr('class'));
    console.log("THIS: " + $(this).attr('class'));
    if (event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')) {
      // bla bla bla
      console.log("Button");
    }
    else {
      console.log("MODO NUEVO: " + _modo_nuevo);
      if (_modo_nuevo == true)
        return;

      _radiotaxi.Deshacer(null, event);
      _input_habilitado = false;
      console.log("OTRA FILA AFECTADA");
      $("#btnAgregarRadioTaxi").prop("disabled", false);
      self.HabilitarTablaSpanRadioTaxi(null, true);

    }

  }

  self.SeleccionarAnterior = function (data) {
    var id = "#" + data.IdRadioTaxi();
    var anteriorObjeto = $(id).prev();

    //console.log("SeleccionarSiguiente");
    //console.log(siguienteObjeto);
    anteriorObjeto.addClass('active').siblings().removeClass('active');

    if (_modo_nuevo == false) //revisar
    {
      //console.log(item.IdFamiliaProducto());
      var _idfamiliaproducto = anteriorObjeto.attr("id");
      //console.log(_idfamiliaproducto);
      var match = ko.utils.arrayFirst(self.data.RadiosTaxi(), function (item) {
        //console.log(item.IdFamiliaProducto());
        return _idfamiliaproducto == item.IdRadioTaxi();
      });

      if (match) {
        _familiaproducto = match;
      }
    }
  }


  self.SeleccionarSiguiente = function (data) {
    var id = "#" + data.IdRadioTaxi();
    var siguienteObjeto = $(id).next();

    if (siguienteObjeto.length > 0) {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      siguienteObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idcostoagregado = siguienteObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.data.RadiosTaxi(), function (item) {
          //console.log(item.IdFamiliaProducto());
          return _idcostoagregado == item.IdRadioTaxi();
        });

        if (match) {
          _radiotaxi = match;
        }
      }
    }
    else {
      self.SeleccionarAnterior(data);
    }
  }


  //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
  self.HabilitarFilaInputRadioTaxi = function (data, option) {
    //var id = "#"+data.IdRadioTaxi();
    var id = data;
    //$(id).addClass('active').siblings().removeClass('active');

    if (option == false) {
      $(id).find('.class_InputRadioTaxi').hide();
      $(id).find('.class_SpanRadioTaxi').show();
    }
    else {
      $(id).find('.class_InputRadioTaxi').show();
      $(id).find('.class_SpanRadioTaxi').hide();
    }

  }

  self.HabilitarTablaSpanRadioTaxi = function (data, option) {
    //var id = "#"+data.IdRadioTaxi();
    var id = "#DataTables_Table_0";
    //$(id).addClass('active').siblings().removeClass('active');

    if (option == false) {
      $(id).find('.class_SpanRadioTaxi').hide();
      $(id).find('.class_InputRadioTaxi').show();
      //$(id).find('.guardar_button_RadioTaxi').show();
      //_input_habilitado = true;
    }
    else {
      $(id).find('.class_SpanRadioTaxi').show();
      $(id).find('.class_InputRadioTaxi').hide();
      $(id).find('.guardar_button_RadioTaxi').hide();
      //_input_habilitado = false;
    }

  }

  self.HabilitarButtonsRadioTaxi = function (data, option) {
    var id = "#DataTables_Table_0";
    //$(id).addClass('active').siblings().removeClass('active');

    if (option == false) {
      $(id).find('.editar_button_RadioTaxi').prop("disabled", true);
      $(id).find('.borrar_button_RadioTaxi').prop("disabled", true);
    }
    else {
      $(id).find('.editar_button_RadioTaxi').prop("disabled", false);
      $(id).find('.borrar_button_RadioTaxi').prop("disabled", false);
    }
  }


  self.AgregarRadioTaxi = function (data, event) {
    console.log("AgregarRadioTaxi");

    if (_input_habilitado == true) {

    }
    else {
      self.MostrarTitulo("REGISTRO DE RADIO TAXI")
      var objeto = Knockout.CopiarObjeto(self.data.RadioTaxi);
      _radiotaxi = new RadiosTaxiModel(objeto);
      self.data.RadiosTaxi.push(_radiotaxi);

      //Deshabilitando buttons
      self.HabilitarButtonsRadioTaxi(null, false);
      $("#null_editar_button_RadioTaxi").prop("disabled", true);
      $("#null_borrar_button_RadioTaxi").prop("disabled", false);


      $("#btnAgregarRadioTaxi").prop("disabled", true);

      //habilitar como destino
      console.log("ID:" + objeto.IdRadioTaxi());

      var idspan = "#" + objeto.IdRadioTaxi() + "_span_NombreRadioTaxi";
      var idinput = "#" + objeto.IdRadioTaxi() + "_input_NombreRadioTaxi";

      var idbutton = "#" + objeto.IdRadioTaxi() + "_button_RadioTaxi";

      console.log(idbutton);
      //self.HabilitarFilaInputRadioTaxi(_radiotaxi, true);
      //self.HabilitarFilaSpanRadioTaxi(_radiotaxi, false);

      $(idspan).hide();
      $(idinput).show();
      $(idbutton).show();
      $(idinput).focus();

      _modo_nuevo = true;
      _input_habilitado = true;

      var tabla = $('#DataTables_Table_0');
      $('tr:last', tabla).addClass('active').siblings().removeClass('active');
    }
  }

  self.InsertarRadioTaxi = function (data, event) {

    if (event) {
      console.log("InsertarRadioTaxi");
      console.log(_radiotaxi.NombreRadioTaxi());
      $("#loader").show();
      var objeto = data;
      var datajs = ko.toJS({ "Data": _radiotaxi });

      $.ajax({
        type: 'POST',
        data: datajs,
        dataType: "json",
        url: SITE_URL + '/Catalogo/cRadioTaxi/InsertarRadioTaxi',
        success: function (data) {
          if (data != null) {
            console.log("resultado -  InsertarRadioTaxi");
            console.log(data);

            if (!data.error) {
              _radiotaxi.IdRadioTaxi(data.resultado.IdRadioTaxi);
              //deshabilitar botones agregar
              $("#btnAgregarRadioTaxi").prop("disabled", false);

              var id_radiotaxi = "#" + _radiotaxi.IdRadioTaxi();
              self.HabilitarFilaInputRadioTaxi(id_radiotaxi, false);

              var idbutton = "#" + _radiotaxi.IdRadioTaxi() + "_button_RadioTaxi";
              $(idbutton).hide();

              _radiotaxi.Confirmar(null, event);
              self.HabilitarButtonsRadioTaxi(null, true);

              existecambio = false;
              _input_habilitado = false;
              _modo_nuevo = false;

            }
            else {
              alertify.alert("ERROR EN " + self.MostrarTitulo(), data.error.msg);
            }

          }
          $("#loader").hide();
        },
        error: function (jqXHR, textStatus, errorThrown) {
          var $data = { error: { msg: jqXHR.responseText } };
          $("#loader").hide();
          alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg);
        }
      });
    }
  }

  self.ActualizarRadioTaxi = function (data, event) {
    console.log("ActualizarRadioTaxi");
    console.log(_radiotaxi.NombreRadioTaxi());
    $("#loader").show();
    var objeto = data;
    var datajs = ko.toJS({ "Data": _radiotaxi });

    $.ajax({
      type: 'POST',
      data: datajs,
      dataType: "json",
      url: SITE_URL + '/Catalogo/cRadioTaxi/ActualizarRadioTaxi',
      success: function (data) {
        if (data != null) {
          console.log(data);

          if (!data.error) {
            //deshabilitar campo origen
            $("#btnAgregarRadioTaxi").prop("disabled", false);
            console.log("ID5:" + _radiotaxi.IdRadioTaxi());
            _radiotaxi.Confirmar(null, event);

            var id_radiotaxi = "#" + _radiotaxi.IdRadioTaxi();
            self.HabilitarFilaInputRadioTaxi(id_radiotaxi, false);

            var idbutton = "#" + _radiotaxi.IdRadioTaxi() + "_button_RadioTaxi";
            $(idbutton).hide();

            existecambio = false;
            _input_habilitado = false;
            _modo_nuevo = false;

          }
          else {
            alertify.alert("ERROR EN " + self.MostrarTitulo(), data.error.msg);
          }
        }
        $("#loader").hide();
      },
      error: function (jqXHR, textStatus, errorThrown) {
        var $data = { error: { msg: jqXHR.responseText } };
        $("#loader").hide();
        alertify.alert("HA OCURRIDO UN ERROR", $data.error.msg);
      }
    });
  }

  self.EditarRadioTaxi = function (data, event) {

    if (event) {
      console.log("EditarRadioTaxi");
      console.log("ID.:" + data.IdRadioTaxi());
      var objeto = ko.toJS(data);
      var datajs = ko.toJS(_radiotaxi);

      if (_modo_nuevo == true) {

      }
      else {
        self.MostrarTitulo("EDICIÓN DE RADIO TAXI");
        if (_radiotaxi.IdRadioTaxi() == data.IdRadioTaxi()) {

          if (_input_habilitado == true) {
            $("#btnAgregarRadioTaxi").prop("disabled", false);
            data.Deshacer(null, event);
            var id_radiotaxi = "#" + data.IdRadioTaxi();
            self.HabilitarFilaInputRadioTaxi(id_radiotaxi, false);

            var idbutton = "#" + _radiotaxi.IdRadioTaxi() + "_button_RadioTaxi";
            $(idbutton).hide();

            _input_habilitado = false;


          }
          else {
            $("#btnAgregarRadioTaxi").prop("disabled", true);
            var id_radiotaxi = "#" + data.IdRadioTaxi();
            self.HabilitarFilaInputRadioTaxi(id_radiotaxi, true);

            var idbutton = "#" + data.IdRadioTaxi() + "_button_RadioTaxi";

            var idinput = "#" + data.IdRadioTaxi() + "_input_NombreRadioTaxi";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }

        }
        else {
          $("#btnAgregarRadioTaxi").prop("disabled", true);
          if (_input_habilitado == true) {
            //deshabilitar campo origen
            console.log("ID2:" + _radiotaxi.IdRadioTaxi());

            var id_radiotaxi = "#" + _radiotaxi.IdRadioTaxi();
            self.HabilitarFilaInputRadioTaxi(id_radiotaxi, false);

            var idbutton = "#" + _radiotaxi.IdRadioTaxi() + "_button_RadioTaxi";

            _radiotaxi.Deshacer(null, event);

            $(idbutton).hide();
          }

          var id_radiotaxi = "#" + data.IdRadioTaxi();
          self.HabilitarFilaInputRadioTaxi(id_radiotaxi, true);

          var idbutton = "#" + data.IdRadioTaxi() + "_button_RadioTaxi";

          var idinput = "#" + data.IdRadioTaxi() + "_input_NombreRadioTaxi";
          $(idinput).focus();
          $(idbutton).show();

          _input_habilitado = true;
        }


      }

    }

  }

  self.PreBorrarRadioTaxi = function (data) {

    if (_modo_nuevo == false) {
      _radiotaxi.Deshacer(null, event);
      _input_habilitado = false;
      $("#btnAgregarRadioTaxi").prop("disabled", false);
      self.HabilitarTablaSpanRadioTaxi(null, true);
    }

    setTimeout(function () {
      self.MostrarTitulo("ELIMINACION DE RADIO TAXI");
      alertify.confirm(self.MostrarTitulo(), "¿Desea borrar el registro?", function () {
        console.log("BorrarRadioTaxi");
        console.log(data.IdRadioTaxi());
        self.HabilitarButtonsRadioTaxi(null, true);
        if (data.IdRadioTaxi() != null)
          self.BorrarRadioTaxi(data);
        else {
          $("#btnAgregarRadioTaxi").prop("disabled", false);
          _input_habilitado = false;
          _modo_nuevo = false;
          self.data.RadiosTaxi.remove(data);
          var tabla = $('#DataTables_Table_0');
          $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          //self.ListarRadiosTaxi();
        }
      }, function () {

      });

    }, 200);

  }

  self.BorrarRadioTaxi = function (data) {
    var objeto = data;
    var datajs = ko.toJS({ "Data": data });
    $("#loader").show()
    $.ajax({
      type: 'POST',
      data: datajs,
      dataType: "json",
      url: SITE_URL + '/Catalogo/cRadioTaxi/BorrarRadioTaxi',
      success: function (data) {
        $("#loader").hide()
        if (data != null) {
          console.log("BorrarFamiliaProducto");
          //console.log(data);

          if (data.error) {
            alertify.alert("ERROR EN " + self.MostrarTitulo(), data.error.msg);
          }
          else {
            $("#btnAgregarRadioTaxi").prop("disabled", false);
            self.HabilitarTablaSpanRadioTaxi(null, true);
            self.SeleccionarSiguiente(objeto);
            self.data.RadiosTaxi.remove(objeto);
          }
        }
      },
    });

  }


  self.OnClickRadioTaxi = function (data, event) {

    if (event) {
      console.log("OnClickRadioTaxi");
      var objeto = ko.toJS(data);
      var datajs = ko.toJS(_radiotaxi);

      if (_modo_nuevo == true) {

      }
      else {

        $("#btnAgregarRadioTaxi").prop("disabled", true);
        if (_radiotaxi.IdRadioTaxi() != data.IdRadioTaxi()) {
          if (_input_habilitado == true) {
            console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
            _radiotaxi.Deshacer(null, event);

            //var id_radiotaxi = "#" + _id_filacostoagregado_anterior;
            var id_radiotaxi = "#" + _radiotaxi.IdRadioTaxi();
            self.HabilitarFilaInputRadioTaxi(id_radiotaxi, false);

            var idbutton = "#" + _radiotaxi.IdRadioTaxi() + "_button_RadioTaxi";
            $(idbutton).hide();
          }

          console.log("INPUT ESTA HABILITADO Y PASO 2");
          console.log(_radiotaxi.IdRadioTaxi());
          console.log(data.IdRadioTaxi());
          //habilitar campo destino
          //Obteniendo ID de la fila para usarlo con los span e inputs
          var id_fila_radiotaxi = "#" + $(event.target).attr('id');
          //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
          if ($.isSubstring(id_fila_radiotaxi, "span") || $.isSubstring(id_fila_radiotaxi, "input")) {
            id_fila_radiotaxi = "#" + $(event.target).parent()[0].id;
          }
          //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
          //_id_filacostoagregado_anterior = $(id_fila_radiotaxi).parent()[0].id;
          var idspan = "#" + $(id_fila_radiotaxi).find('span').attr('id');
          var idinput = "#" + $(id_fila_radiotaxi).find('input').attr('id');
          self.HabilitarFilaInputRadioTaxi("#" + $(id_fila_radiotaxi).parent()[0].id, true);

          var idbutton = "#" + data.IdRadioTaxi() + "_button_RadioTaxi";

          $(idinput).focus();
          $(idbutton).show();

          _input_habilitado = true;

        }
        else {
          if (_input_habilitado == false) {
            var id_fila_radiotaxi = "#" + $(event.target).attr('id');

            //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
            if ($.isSubstring(id_fila_radiotaxi, "span") || $.isSubstring(id_fila_radiotaxi, "input")) {
              id_fila_radiotaxi = "#" + $(event.target).parent()[0].id;
            }


            self.HabilitarFilaInputRadioTaxi("#" + $(id_fila_radiotaxi).parent()[0].id, true);

            var idbutton = "#" + data.IdRadioTaxi() + "_button_RadioTaxi";
            var idinput = "#" + $(id_fila_radiotaxi).find('input').attr('id');
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
  jQuery.isSubstring = function (haystack, needle) {
    return haystack.indexOf(needle) !== -1;
  }

  //FUNCION DE MANEJO DE TECLAS Y ATAJOS
  self.OnKeyUpRadioTaxi = function (data, event) {
    if (event) {
      console.log("OnKeyUpRadioTaxi");

      var code = event.keyCode || event.which;

      if (code === 13) {//enter
        if (!($("#loader").css('display') == 'none')) {
          event.preventDefault();
          return false;
        }
        if (alertify.confirm().isOpen() || alertify.alert().isOpen()) {
          event.preventDefault();
          return false;
        }
        //Variable para obtener el id delinput
        var idinputnombre = _radiotaxi.IdRadioTaxi() + '_input_NombreRadioTaxi';

        if (event.target.id == idinputnombre) {
          _radiotaxi.NombreRadioTaxi(event.target.value);
        }


        if (_modo_nuevo == true) {
          self.InsertarRadioTaxi(_radiotaxi, event);
        }
        else {
          self.ActualizarRadioTaxi(_radiotaxi, event);
        }

      }

      return true;
    }
  }

  self.EscaparGlobal = function (event) {

    if (event) {
      if (_input_habilitado == true) {
        if (_modo_nuevo == true) {
          alertify.confirm("REGISTRO DE RADIO TAXI", "¿Desea perder el nuevo registro?", function () {
            self.SeleccionarAnterior(_radiotaxi);
            self.data.RadiosTaxi.remove(_radiotaxi);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');

            $("#btnAgregarRadioTaxi").prop("disabled", false);
            self.HabilitarButtonsRadioTaxi(null, true);
            _modo_nuevo = false;
            _input_habilitado = false;
          }, function () {

          });
        }
        else {
          console.log("Escape - false");
          //revertir texto
          //data.NombreRadioTaxi(_radiotaxi.NombreRadioTaxi());

          _radiotaxi.Deshacer(null, event);

          //deshabilitar botones agregar
          $("#btnAgregarRadioTaxi").prop("disabled", false);

          /*var id_fila_radiotaxi = "#" + $(event.target).attr('id');
          if($.isSubstring(id_fila_radiotaxi, "span") || $.isSubstring(id_fila_radiotaxi, "input")){
            id_fila_radiotaxi = "#" + $(event.target).parent()[0].id;
          }
          self.HabilitarFilaInputRadioTaxi("#" + $(id_fila_radiotaxi).parent()[0].id, false);*/
          self.HabilitarTablaSpanRadioTaxi(null, true);

          var idbutton = "#" + _radiotaxi.IdRadioTaxi() + "_button_RadioTaxi";
          $(idbutton).hide();

          existecambio = false;
          _modo_nuevo = false;
          _input_habilitado = false;
        }

      }
    }
  }

  self.GuardarRadioTaxi = function (data, event) {
    if (event) {
      console.log("GuardarRadioTaxi");
      console.log(_nombrecostoagregado);
      if (!($("#loader").css('display') == 'none')) {
        event.preventDefault();
        return false;
      }
      //Variable para obtener el id delinput
      var idinputnombre = _radiotaxi.IdRadioTaxi() + '_input_NombreRadioTaxi';

      if (event.target.id == idinputnombre) {
        _radiotaxi.NombreRadioTaxi(_nombrecostoagregado);
      }
      //_radiotaxi.NombreRadioTaxi(_nombrecostoagregado);

      if (_modo_nuevo == true) {
        self.InsertarRadioTaxi(_radiotaxi, event);
      }
      else {
        self.ActualizarRadioTaxi(_radiotaxi, event);
      }
    }
  }



}

function mayus(e) {
  e.value = e.value.toUpperCase();
}
