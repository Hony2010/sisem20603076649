// self.dataUnidadMedidaUnidadMedida
UnidadesMedidaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreUnidadMedida = ko.observable(data.NombreUnidadMedida);
    self._AbreviaturaUnidadMedida = ko.observable(data.AbreviaturaUnidadMedida);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreUnidadMedida());

        self.NombreUnidadMedida.valueHasMutated();
        self.AbreviaturaUnidadMedida.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreUnidadMedida("");
        self.AbreviaturaUnidadMedida("");
        self.NombreUnidadMedida(self._NombreUnidadMedida());
        self.AbreviaturaUnidadMedida(self._AbreviaturaUnidadMedida());

        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreUnidadMedida.valueHasMutated();
        self._NombreUnidadMedida(self.NombreUnidadMedida());
        self._AbreviaturaUnidadMedida.valueHasMutated();
        self._AbreviaturaUnidadMedida(self.AbreviaturaUnidadMedida());
      }
    }

}

UnidadMedidaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

OtraUnidadesMedidaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self.EstadoSelector = ko.observable(false);

    self.CambiarEstadoCheck = function (data, event) {
      if(event){
        var id = "#"+data.IdUnidadMedida()+'_tr_unidadmedida';
        var objeto = Knockout.CopiarObjeto(data);

        if (data.EstadoSelector() == true)
        {
          $(id).addClass('active');
          vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.OtraUnidadMedida.push(new OtraUnidadesMedidaModel(objeto));
        }
        else
        {
          $(id).removeClass('active');
          //Models.data.ComunicacionBaja.remove(new ComunicacionesBajaModel(data));
          vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.OtraUnidadMedida.remove( function (item) { return item.IdUnidadMedida() == objeto.IdUnidadMedida(); } )
        }

        self.ActualizarBotonAgregar(event);
      }

    }

    self.ActualizarBotonAgregar = function(event)
    {
      if(event)
      {
        var length = vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.OtraUnidadMedida().length;
        if(length > 0)
        {
          $("#btn_AgregarOtraUnidadMedida").prop("disabled", false);
        }
        else {
          $("#btn_AgregarOtraUnidadMedida").prop("disabled", true);
        }

      }
    }


}

OtraUnidadMedidaModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

var Mapping_UnidadMedida = {
    'UnidadesMedida': {
        create: function (options) {
            if (options)
              return new UnidadesMedidaModel(options.data);
            }
    },
    'UnidadMedida': {
        create: function (options) {
            if (options)
              return new UnidadMedidaModel(options.data);
            }
    },
    'OtraUnidadesMedida': {
        create: function (options) {
            if (options)
              return new OtraUnidadesMedidaModel(options.data);
            }
    },
    'OtraUnidadMedida': {
        create: function (options) {
            if (options)
              return new OtraUnidadMedidaModel(options.data);
            }
    }

}

IndexUnidadMedida = function (data) {

    var _modo_deshacer = false;
    var _codigounidadmedidasunat;
    var _nombreunidadmedida;
    var _abreviaturaunidadmedida;
    var _input_habilitado_unidadmedida = false;
    var _idunidadmedida;
    var _unidadmedida;
    var _modo_nuevo_unidadmedida = false;
    var _id_filaunidadmedida_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping_UnidadMedida, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    /*APLICANDO PAGINACION*/
    self.ConsultarPorPagina = function (data,event) {
      if(event) {
        console.log("Consulta Por Pagina");
        console.log(data);
        var seleccion_otraunidadmedida = self.dataUnidadMedida.OtraUnidadMedida().length;
        if(seleccion_otraunidadmedida > 0 )
        {
          var mensaje = "Usted tiene " + seleccion_otraunidadmedida + " seleccionado. ¿Desea continuar?";
          alertify.confirm(mensaje, function(){
            self.CloseModal(event);
            self.ConsultarOtraUnidadesMedidaPorPagina(data,event,self.PostConsultarPorPagina);
            $("#Paginador").pagination("drawPage", data.pagina);
          });
        }
        else {
          self.ConsultarOtraUnidadesMedidaPorPagina(data,event,self.PostConsultarPorPagina);
          $("#Paginador").pagination("drawPage", data.pagina);
        }
      }
    }

    self.PostConsultarPorPagina =  function(data,event) {
      if(event) {
        self.dataUnidadMedida.OtraUnidadesMedida([]);
        ko.utils.arrayForEach(data, function (item) {
          self.dataUnidadMedida.OtraUnidadesMedida.push(new OtraUnidadesMedidaModel(item));
        });
      }
    }

    self.ConsultarOtraUnidadesMedidaPorPagina = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          cache : false,
          url: SITE_URL+'/Configuracion/General/cUnidadMedida/ConsultarOtraUnidadesMedidaPorPagina',
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

          self.ConsultarOtraUnidadesMedida(data,event,self.PostConsultar);
        }
      }
    }

    self.ConsultarOtraUnidadesMedida = function(data,event,callback) {
      if(event)
      {
        $("#loader").show();
        var datajs = ko.mapping.toJS({"Data": data});
        $.ajax({
          type: 'GET',
          dataType: 'json',
          data : datajs,
          url: SITE_URL+'/Configuracion/General/cUnidadMedida/ConsultarOtraUnidadesMedida',
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
        self.dataUnidadMedida.OtraUnidadesMedida([]);
        ko.utils.arrayForEach(data.resultado, function (item) {
          self.dataUnidadMedida.OtraUnidadesMedida.push(new OtraUnidadesMedidaModel(item));
        });

        // var objeto = Models.data.Mercaderias()[0];
        // Models.Seleccionar(objeto, event);
        //ko.mapping.fromJS(data.Filtros,{},self.data.Filtros);
        $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
        self.dataUnidadMedida.Filtros.totalfilas(data.Filtros.totalfilas);
      }
    }

    /*FIN PAGINACION*/


    self.AgregarOtraUnidadMedida = function(event){
      if(event)
      {
        $("#loader").show();
        // var datajs = ko.toJS(self.dataUnidadMedida.OtraUnidadMedida, mappingIgnore);
        var datajs = ko.toJS({"Data" : self.dataUnidadMedida.OtraUnidadMedida});
        $.ajax({
                type: 'POST',
                data: datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cUnidadMedida/ActualizarOtraUnidadMedida',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataUnidadMedida.OtraUnidadMedida([]);
                        var _item_model = null;
                        ko.utils.arrayForEach(data, function (item) {
                          var _item = ko.toJS(item, mappingIgnore);
                          _item_model = new UnidadesMedidaModel(_item);
                          self.dataUnidadMedida.UnidadesMedida.push(_item_model);
                        });
                        self.Seleccionar(_item_model, event);
                    $("#OtraUnidadMedidaModel").modal("hide");
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

    self.CloseModal = function(event)
    {
      if(event)
      {
        self.dataUnidadMedida.OtraUnidadMedida([]);
        $("#btn_AgregarOtraUnidadMedida").prop("disabled", true);
      }
    }

    self.ListarUnidadesMedida = function() {
        console.log("ListarUnidadesMedida");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cUnidadMedida/ListarUnidadesMedida',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.dataUnidadMedida.UnidadesMedida([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.dataUnidadMedida.UnidadesMedida.push(new UnidadesMedidaModel(item));
                    });
                }
            }
        });
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      if (_modo_nuevo_unidadmedida == false)
      {
        var id = "#"+data.IdUnidadMedida()+'_tr_unidadmedida';
        $(id).addClass('active').siblings().removeClass('active');
        _unidadmedida = data;
      }

    }

    self.FilaButtonsUnidadMedida = function (data, event)  {
      console.log("FILASBUTONES");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else{
          console.log("MODO NUEVO: " + _modo_nuevo_unidadmedida);
          if(_modo_nuevo_unidadmedida == true)
          return;

          console.log("OTRA FILA AFECTADA");
          _unidadmedida.Deshacer(null, event);
          _input_habilitado_unidadmedida = false;
          $("#btnAgregarUnidadMedida").prop("disabled",false);
          self.HabilitarTablaSpanUnidadMedida(null, true);

      }

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdUnidadMedida()+'_tr_unidadmedida';
      var anteriorObjeto = $(id).prev();

      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo_unidadmedida == false) //revisar
      {
        //console.log(item.IdFamiliaProducto());
        var _idfamiliaproducto = anteriorObjeto.attr("id");
        //console.log(_idfamiliaproducto);
        var match = ko.utils.arrayFirst(self.dataUnidadMedida.UnidadesMedida(), function(item) {
              //console.log(item.IdFamiliaProducto());
              return _idfamiliaproducto == item.IdUnidadMedida();
          });

        if(match)
        {
          _familiaproducto = match;
        }
      }
    }


    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdUnidadMedida()+'_tr_unidadmedida';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
      //console.log("SeleccionarSiguiente");
      //console.log(siguienteObjeto);
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_unidadmedida == false) //revisar
        {
          //console.log(item.IdFamiliaProducto());
          var _idunidadmedida = siguienteObjeto.attr("id");
          //console.log(_idfamiliaproducto);
          var match = ko.utils.arrayFirst(self.dataUnidadMedida.UnidadesMedida(), function(item) {
                //console.log(item.IdFamiliaProducto());
                return _idunidadmedida == item.IdUnidadMedida();
            });

          if(match)
          {
            _unidadmedida = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputUnidadMedida = function (data, option)  {
      //var id = "#"+data.IdUnidadMedida();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputUnidadMedida').hide();
        $(id).find('.class_SpanUnidadMedida').show();
      }
      else
      {
        $(id).find('.class_InputUnidadMedida').show();
        $(id).find('.class_SpanUnidadMedida').hide();
      }

    }

    self.HabilitarTablaSpanUnidadMedida = function (data, option)  {
      //var id = "#"+data.IdUnidadMedida();
      var id = "#DataTables_Table_0_unidadmedida";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanUnidadMedida').hide();
        $(id).find('.class_InputUnidadMedida').show();
        //$(id).find('.guardar_button_UnidadMedida').show();
        //_input_habilitado_unidadmedida = true;
      }
      else {
        $(id).find('.class_SpanUnidadMedida').show();
        $(id).find('.class_InputUnidadMedida').hide();
        $(id).find('.guardar_button_UnidadMedida').hide();
        //_input_habilitado_unidadmedida = false;
      }

    }

    self.HabilitarButtonsUnidadMedida = function(data, option){
      var id = "#DataTables_Table_0_unidadmedida";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_UnidadMedida').prop("disabled", true);
        $(id).find('.borrar_button_UnidadMedida').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_UnidadMedida').prop("disabled", false);
        $(id).find('.borrar_button_UnidadMedida').prop("disabled", false);
      }
    }

    self.AbrirUnidadesMedidaOculta = function(data, event)
    {
        if(event)
        {
          $("#btn_AgregarOtraUnidadMedida").prop("disabled", true);
          $("#loader").show();
          var datajs = ko.toJS({"Data" : ""});
          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cUnidadMedida/ListarOtraUnidadesMedida',
                  success: function (data) {
                    if (data != null) {
                      vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.OtraUnidadesMedida([]);
                      ko.utils.arrayForEach(data, function(item) {
                        vistaModeloGeneral.vmgUnidadMedida.dataUnidadMedida.OtraUnidadesMedida.push(new OtraUnidadesMedidaModel(item));
                      });

                        $("#OtraUnidadMedidaModel").modal("show");
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

    self.AgregarUnidadMedida = function(data,event) {
          console.log("AgregarUnidadMedida");

          if ( _input_habilitado_unidadmedida == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.dataUnidadMedida.UnidadMedida);
            _unidadmedida = new UnidadesMedidaModel(objeto);
            self.dataUnidadMedida.UnidadesMedida.push(_unidadmedida);

            //Deshabilitando buttons
            self.HabilitarButtonsUnidadMedida(null, false);
            $("#null_editar_button_UnidadMedida").prop("disabled", true);
            $("#null_borrar_button_UnidadMedida").prop("disabled", false);

            $("#btnAgregarUnidadMedida").prop("disabled",true);

            //habilitar como destino
            console.log("ID:"+objeto.IdUnidadMedida());

            var id_span_nombreunidadmedida ="#"+objeto.IdUnidadMedida()+"_span_NombreUnidadMedida";
            var id_input_nombreunidadmedida ="#"+objeto.IdUnidadMedida()+"_input_NombreUnidadMedida";

            var id_span_abreviaturaunidadmedida ="#"+objeto.IdUnidadMedida()+"_span_AbreviaturaUnidadMedida";
            var id_input_abreviaturaunidadmedida ="#"+objeto.IdUnidadMedida()+"_input_AbreviaturaUnidadMedida";

            var idbutton ="#"+objeto.IdUnidadMedida()+"_button_UnidadMedida";

            console.log(idbutton);

            $(id_span_nombreunidadmedida).hide();
            $(id_input_nombreunidadmedida).show();

            $(id_span_abreviaturaunidadmedida).hide();
            $(id_input_abreviaturaunidadmedida).show();

            $(idbutton).show();
            $(id_input_codigounidadmedidasunat).focus();

            _modo_nuevo_unidadmedida = true;
            _input_habilitado_unidadmedida = true;

            var tabla = $('#DataTables_Table_0_unidadmedida');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarUnidadMedida =function(data,event){

      if(event)
      {
        console.log("InsertarUnidadMedida");
        console.log(_unidadmedida.NombreUnidadMedida());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _unidadmedida});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/General/cUnidadMedida/InsertarUnidadMedida',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarUnidadMedida");
                      console.log(data);

                      if ($.isNumeric(data.IdUnidadMedida))
                      {
                        _unidadmedida.IdUnidadMedida(data.IdUnidadMedida);
                        //deshabilitar botones agregar
                        $("#btnAgregarUnidadMedida").prop("disabled",false);

                        var id_unidadmedida = "#"+ _unidadmedida.IdUnidadMedida()+'_tr_unidadmedida';
                        self.HabilitarFilaInputUnidadMedida(id_unidadmedida, false);

                        var idbutton ="#"+_unidadmedida.IdUnidadMedida()+"_button_UnidadMedida";
                        $(idbutton).hide();

                         _unidadmedida.Confirmar(null,event);
                         self.HabilitarButtonsUnidadMedida(null, true);

                        existecambio = false;
                        _input_habilitado_unidadmedida = false;
                        _modo_nuevo_unidadmedida = false;

                      }
                      else {
                        alertify.alert(data.IdUnidadMedida);
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

    self.ActualizarUnidadMedida = function(data,event) {
          console.log("ActualizarUnidadMedida");
          console.log(_unidadmedida.NombreUnidadMedida());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _unidadmedida});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/General/cUnidadMedida/ActualizarUnidadMedida',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarUnidadMedida").prop("disabled",false);
                          console.log("ID5:"+_unidadmedida.IdUnidadMedida());
                          _unidadmedida.Confirmar(null,event);

                          var id_unidadmedida = "#"+ _unidadmedida.IdUnidadMedida()+'_tr_unidadmedida';
                          self.HabilitarFilaInputUnidadMedida(id_unidadmedida, false);

                          var idbutton ="#"+_unidadmedida.IdUnidadMedida()+"_button_UnidadMedida";
                          $(idbutton).hide();

                          existecambio = false;
                          _input_habilitado_unidadmedida = false;
                          _modo_nuevo_unidadmedida = false;
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

    self.EditarUnidadMedida = function(data, event) {

      if(event)
      {
        console.log("EditarUnidadMedida");
        console.log("ID.:"+data.IdUnidadMedida());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_unidadmedida);

        if( _modo_nuevo_unidadmedida == true )
        {

        }
        else {

          if (_unidadmedida.IdUnidadMedida() == data.IdUnidadMedida())
          {

            if (_input_habilitado_unidadmedida == true)
            {
              $("#btnAgregarUnidadMedida").prop("disabled",false);
              data.Deshacer(null,event);
              var id_unidadmedida = "#"+ data.IdUnidadMedida()+'_tr_unidadmedida';
              self.HabilitarFilaInputUnidadMedida(id_unidadmedida, false);

              var idbutton = "#"+_unidadmedida.IdUnidadMedida()+"_button_UnidadMedida";
              $(idbutton).hide();

              _input_habilitado_unidadmedida =false;

            }
            else {
              $("#btnAgregarUnidadMedida").prop("disabled",true);
              var id_unidadmedida = "#"+ data.IdUnidadMedida()+'_tr_unidadmedida';
              self.HabilitarFilaInputUnidadMedida(id_unidadmedida, true);

              var idbutton = "#"+data.IdUnidadMedida()+"_button_UnidadMedida";

              var idinput = "#"+data.IdUnidadMedida()+"_input_AbreviaturaUnidadMedida";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_unidadmedida = true;
            }

          }
          else {
            $("#btnAgregarUnidadMedida").prop("disabled",true);
            if( _input_habilitado_unidadmedida == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_unidadmedida.IdUnidadMedida());

              var id_unidadmedida = "#"+ _unidadmedida.IdUnidadMedida()+'_tr_unidadmedida';
              self.HabilitarFilaInputUnidadMedida(id_unidadmedida, false);

              var idbutton = "#"+_unidadmedida.IdUnidadMedida()+"_button_UnidadMedida";

              _unidadmedida.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_unidadmedida = "#"+ data.IdUnidadMedida()+'_tr_unidadmedida';
            self.HabilitarFilaInputUnidadMedida(id_unidadmedida, true);

            var idbutton = "#"+data.IdUnidadMedida()+"_button_UnidadMedida";

            var idinput = "#"+data.IdUnidadMedida()+"_input_AbreviaturaUnidadMedida";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado_unidadmedida = true;
          }


        }

      }

    }

    self.PreBorrarUnidadMedida = function (data) {

      if(_modo_nuevo_unidadmedida == false)
      {
        _unidadmedida.Deshacer(null, event);
        _input_habilitado_unidadmedida = false;
        $("#btnAgregarUnidadMedida").prop("disabled",false);
        self.HabilitarTablaSpanUnidadMedida(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarUnidadMedida");
          console.log(data.IdUnidadMedida());
          self.HabilitarButtonsUnidadMedida(null, true);
          if (data.IdUnidadMedida() != null){
            self.BorrarUnidadMedida(data);
          }
          else
          {
            $("#btnAgregarUnidadMedida").prop("disabled",false);
            _input_habilitado_unidadmedida = false;
            _modo_nuevo_unidadmedida = false;
            self.dataUnidadMedida.UnidadesMedida.remove(data);
            var tabla = $('#DataTables_Table_0_unidadmedida');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarUnidadesMedida();
          }
        });

      }, 200);
    }

    self.BorrarUnidadMedida = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/General/cUnidadMedida/BorrarUnidadMedida',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarUnidadMedida");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarUnidadMedida").prop("disabled",false);
                      self.HabilitarTablaSpanUnidadMedida(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.dataUnidadMedida.UnidadesMedida.remove(objeto);
                        //self.ListarUnidadesMedida();
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


    self.OnClickUnidadMedida = function(data ,event) {

      if(event)
      {
          console.log("OnClickUnidadMedida");
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_unidadmedida);

          if( _modo_nuevo_unidadmedida == true )
          {

          }
          else
          {

            $("#btnAgregarUnidadMedida").prop("disabled",true);
            if(_unidadmedida.IdUnidadMedida() !=  data.IdUnidadMedida())
            {
              if (_input_habilitado_unidadmedida == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _unidadmedida.Deshacer(null, event);

                //var id_unidadmedida = "#" + _id_filaunidadmedida_anterior;
                var id_unidadmedida = "#" + _unidadmedida.IdUnidadMedida()+'_tr_unidadmedida';
                self.HabilitarFilaInputUnidadMedida(id_unidadmedida, false);

                var idbutton = "#"+_unidadmedida.IdUnidadMedida()+"_button_UnidadMedida";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_unidadmedida.IdUnidadMedida());
              console.log(data.IdUnidadMedida());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_unidadmedida = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_unidadmedida, "span") || $.isSubstring(id_fila_unidadmedida, "input")){
                id_fila_unidadmedida = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_unidadmedida).find('input').attr('id');
              self.HabilitarFilaInputUnidadMedida("#" + $(id_fila_unidadmedida).parent()[0].id, true);

              var idbutton = "#"+data.IdUnidadMedida()+"_button_UnidadMedida";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado_unidadmedida = true;

              }
              else {
                if (_input_habilitado_unidadmedida == false)
                {
                  var id_fila_unidadmedida = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_unidadmedida, "span") || $.isSubstring(id_fila_unidadmedida, "input")){
                    id_fila_unidadmedida = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputUnidadMedida("#" + $(id_fila_unidadmedida).parent()[0].id, true);

                  var idbutton = "#"+data.IdUnidadMedida()+"_button_UnidadMedida";
                  var idinput ="#"+$(id_fila_unidadmedida).find('input').attr('id');
                  $(idbutton).show()
                  $(idinput).focus();

                  _input_habilitado_unidadmedida = true;
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
    self.OnKeyUpUnidadMedida = function(data, event){
      if(event)
      {
       console.log("OnKeyUpUnidadMedida");

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
         var idinputnombre = _unidadmedida.IdUnidadMedida() + '_input_NombreUnidadMedida';
         var idinputabreviatura = _unidadmedida.IdUnidadMedida() + '_input_AbreviaturaUnidadMedida';

         if(event.target.id == idinputnombre)
         {
           _unidadmedida.NombreUnidadMedida(event.target.value);
         }
         else if(event.target.id == idinputabreviatura)
         {
            _unidadmedida.AbreviaturaUnidadMedida(event.target.value);
         }


         if(_modo_nuevo_unidadmedida == true)
         {
           self.InsertarUnidadMedida(_unidadmedida,event);
         }
         else
         {
           self.ActualizarUnidadMedida(_unidadmedida,event);
         }

       }

       return true;
      }
    }

    self.EscaparGlobal = function(event){

      if(event)
      {
        if(_input_habilitado_unidadmedida == true)
        {
          if(_modo_nuevo_unidadmedida == true)
          {
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_unidadmedida);
              self.data.UnidadesMedida.remove(_unidadmedida);
              var tabla = $('#DataTables_Table_0_unidadmedida');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarUnidadMedida").prop("disabled",false);
              self.HabilitarButtonsUnidadMedida(null, true);
               _modo_nuevo_unidadmedida = false;
               _input_habilitado_unidadmedida = false;
            });

          }
          else
          {
            console.log("Escape - false");
            console.log(_unidadmedida._NombreUnidadMedida());
            //revertir texto

             _unidadmedida.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarUnidadMedida").prop("disabled",false);

            /*var id_fila_unidadmedida = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_unidadmedida, "span") || $.isSubstring(id_fila_unidadmedida, "input")){
              id_fila_unidadmedida = "#" + $(event.target).parent()[0].id;
            }

            self.HabilitarFilaInputUnidadMedida("#" + $(id_fila_unidadmedida).parent()[0].id, false);*/
            self.HabilitarTablaSpanUnidadMedida(null, true);

            var idbutton ="#"+_unidadmedida.IdUnidadMedida()+"_button_UnidadMedida";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo_unidadmedida = false;
            _input_habilitado_unidadmedida = false;
          }

        }
      }
    }


    self.GuardarUnidadMedida = function(data,event) {
      if(event)
      {
         console.log("GuardarUnidadMedida");
         console.log(_nombreunidadmedida);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = _unidadmedida.IdUnidadMedida() + '_input_NombreUnidadMedida';
          var idinputabreviatura = _unidadmedida.IdUnidadMedida() + '_input_AbreviaturaUnidadMedida';

          if(event.target.id == idinputnombre)
          {
            _unidadmedida.NombreUnidadMedida(_nombreunidadmedida);
          }
          else if(event.target.id == idinputabreviatura)
          {
             _unidadmedida.AbreviaturaUnidadMedida(_codigounidadmedidasunat);
          }
         //_unidadmedida.NombreUnidadMedida(_nombreunidadmedida);

         if(_modo_nuevo_unidadmedida == true)
         {
           self.InsertarUnidadMedida(_unidadmedida,event);
         }
         else
         {
           self.ActualizarUnidadMedida(_unidadmedida,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
