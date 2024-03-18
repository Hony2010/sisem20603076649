
CostosServicioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreProducto = ko.observable(data.NombreProducto);

    self._IdTipoProducto = ko.observable(data.IdTipoProducto);
    self._NombreTipoProducto = ko.observable(data.NombreTipoProducto);

    self._IdUnidadMedida = ko.observable(data.IdUnidadMedida);
    self._NombreUnidadMedida = ko.observable(data.NombreUnidadMedida);

    self._IdTipoExistencia = ko.observable(data.IdTipoExistencia);
    self._NombreTipoExistencia = ko.observable(data.NombreTipoExistencia);


    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log(self._Direccion());
        //Poner todos las propiedades aqui.
        console.log("DESHACER:  " + self._NombreProducto());

        self.NombreProducto.valueHasMutated();
        self.IdTipoProducto.valueHasMutated();
        self.IdUnidadMedida.valueHasMutated();
        self.IdTipoExistencia.valueHasMutated();
        //LIMPIANDO LAS CAJAS DE TEXTO
        self.NombreProducto("");
        self.IdTipoProducto("");
        self.IdUnidadMedida("");
        self.IdTipoExistencia("");
        self.NombreProducto(self._NombreProducto());
        self.IdTipoProducto(self._IdTipoProducto());
        self.IdUnidadMedida(self._IdUnidadMedida());
        self.IdTipoExistencia(self._IdTipoExistencia());

        var id_tipocostoservicio = '#' + self.IdProducto() +  '_input_IdTipoProducto';

        console.log("_ID:" + self._IdTipoProducto());
        console.log("ID:" + self.IdTipoProducto());
        return true;
      }

    }

    self.Confirmar = function(data,event){
      if (event) {
        console.log("Confirmar");
        self._NombreProducto.valueHasMutated();
        self._NombreProducto(self.NombreProducto());
        self._IdTipoProducto.valueHasMutated();
        self._IdTipoProducto(self.IdTipoProducto());
        self._IdUnidadMedida.valueHasMutated();
        self._IdUnidadMedida(self.IdUnidadMedida());
        self._IdTipoExistencia.valueHasMutated();
        self._IdTipoExistencia(self.IdTipoExistencia());

      }
    }


}

CostoServicioModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping = {
    'CostosServicio': {
        create: function (options) {
            if (options)
              return new CostosServicioModel(options.data);
            }
    },
    'CostoServicio': {
        create: function (options) {
            if (options)
              return new CostoServicioModel(options.data);
            }
    }

}

Index = function (data) {

    var _modo_deshacer = false;
    var _nombrecostoservicio;
    var _abreviaturacostoservicio;
    var _input_habilitado = false;
    var _idcostoservicio;
    var _costoservicio;
    var _modo_nuevo = false;
    var _id_filacostoservicio_anterior;

    var self = this;

    ko.mapping.fromJS(data, Mapping, self);
    //self.Errores = ko.validation.group(self, { deep: true });

    self.ListarCostosServicio = function() {
        console.log("ListarCostosServicio");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Catalogo/cCostoServicio/ListarCostosServicio',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        self.data.CostosServicio([]);
                        ko.utils.arrayForEach(data, function (item) {
                            self.data.CostosServicio.push(new CostosServicioModel(item));
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
        _costoservicio = data;

      }

    }

    self.FilaButtonsCostoServicio = function (data, event)  {
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
          _costoservicio.Deshacer(null, event);
          _input_habilitado = false;
          $("#btnAgregarCostoServicio").prop("disabled",false);
          self.HabilitarTablaSpanCostoServicio(null, true);

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
        var _idcostoservicio = anteriorObjeto.attr("id");
        //console.log(_idcostoservicio);
        var match = ko.utils.arrayFirst(self.data.CostosServicio(), function(item) {
              //console.log(item.IdProducto());
              return _idcostoservicio == item.IdProducto();
          });

        if(match)
        {
          _idcostoservicio = match;
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
          var _idcostoservicio = siguienteObjeto.attr("id");
          //console.log(_idcostoservicio);
          var match = ko.utils.arrayFirst(self.data.CostosServicio(), function(item) {
                //console.log(item.IdProducto());
                return _idcostoservicio == item.IdProducto();
            });

          if(match)
          {
            _costoservicio = match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }


    //FUNCION PARA MOSTRAR Y OCULTAR INPUTS DE UNA DETERMINADA FILA
    self.HabilitarFilaInputCostoServicio = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id =data;
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_InputCostoServicio').hide();
        $(id).find('.class_SpanCostoServicio').show();
      }
      else
      {
        $(id).find('.class_InputCostoServicio').show();
        $(id).find('.class_SpanCostoServicio').hide();
      }

    }

    self.HabilitarTablaSpanCostoServicio = function (data, option)  {
      //var id = "#"+data.IdProducto();
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanCostoServicio').hide();
        $(id).find('.class_InputCostoServicio').show();
        //$(id).find('.guardar_button_CostoServicio').show();
        //_input_habilitado = true;
      }
      else {
        $(id).find('.class_SpanCostoServicio').show();
        $(id).find('.class_InputCostoServicio').hide();
        $(id).find('.guardar_button_CostoServicio').hide();
        //_input_habilitado = false;
      }

    }

    self.HabilitarButtonsCostoServicio = function(data, option){
      var id = "#DataTables_Table_0";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.editar_button_CostoServicio').prop("disabled", true);
        $(id).find('.borrar_button_CostoServicio').prop("disabled", true);
      }
      else {
        $(id).find('.editar_button_CostoServicio').prop("disabled", false);
        $(id).find('.borrar_button_CostoServicio').prop("disabled", false);
      }
    }


    self.AgregarCostoServicio = function(data,event) {
          console.log("AgregarCostoServicio");

          if ( _input_habilitado == true )
          {

          }
          else
          {
            var objeto = Knockout.CopiarObjeto(self.data.CostoServicio);
            //objeto.NombreTipoProducto = "AGENCIA";
            //objeto.IdTipoProducto = 1;
            console.log(objeto);
            _costoservicio = new CostosServicioModel(objeto);
            self.data.CostosServicio.push(_costoservicio);

            //Deshabilitando buttons
            self.HabilitarButtonsCostoServicio(null, false);
            $("#null_editar_button_CostoServicio").prop("disabled", true);
            $("#null_borrar_button_CostoServicio").prop("disabled", false);


            $("#btnAgregarCostoServicio").prop("disabled",true);
            //habilitar como destino
            console.log("ID:"+objeto.IdProducto());
            var id_span_nombrecostoservicio ="#"+objeto.IdProducto()+"_span_NombreProducto";
            var id_input_nombrecostoservicio ="#"+objeto.IdProducto()+"_input_NombreProducto";

            var id_span_idtipocostoservicio ="#"+objeto.IdProducto()+"_span_IdTipoProducto";
            var id_input_idtipocostoservicio ="#"+objeto.IdProducto()+"_input_IdTipoProducto";
            var id_combo_idtipocostoservicio ="#"+objeto.IdProducto()+"_combo_IdTipoProducto";

            var id_span_idunidadmedida ="#"+objeto.IdProducto()+"_span_IdUnidadMedida";
            var id_input_idunidadmedida ="#"+objeto.IdProducto()+"_input_IdUnidadMedida";
            var id_combo_idunidadmedida ="#"+objeto.IdProducto()+"_combo_IdUnidadMedida";

            var id_span_idtipoexistencia ="#"+objeto.IdProducto()+"_span_IdTipoExistencia";
            var id_input_idtipoexistencia ="#"+objeto.IdProducto()+"_input_IdTipoExistencia";
            var id_combo_idtipoexistencia ="#"+objeto.IdProducto()+"_combo_IdTipoExistencia";

            var idbutton ="#"+objeto.IdProducto()+"_button_CostoServicio";

            console.log(idbutton);
            //var id_tipocostoservicio = '#' + self.IdProducto() +  '_input_IdTipoProducto';

            $(id_span_nombrecostoservicio).hide();
            $(id_input_nombrecostoservicio).show();

            $(id_span_idtipocostoservicio).hide();
            $(id_combo_idtipocostoservicio).show();

            $(id_span_idunidadmedida).hide();
            $(id_combo_idunidadmedida).show();

            $(id_span_idtipoexistencia).hide();
            $(id_combo_idtipoexistencia).show();

            $(idbutton).show();
            $(id_input_nombrecostoservicio).focus();

            _modo_nuevo = true;
            _input_habilitado = true;

            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
          }
    }

    self.InsertarCostoServicio =function(data,event){

      if(event)
      {
        console.log("InsertarCostoServicio");
        console.log(_costoservicio.NombreProducto());
        $("#loader").show();
        var objeto = data;
        var datajs = ko.toJS({"Data" : _costoservicio});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Catalogo/cCostoServicio/InsertarCostoServicio',
                success: function (data) {
                      if (data != null) {
                      console.log("resultado -  InsertarCostoServicio");
                      console.log(data);

                      if ($.isNumeric(data.IdProducto))
                      {
                        _costoservicio.IdProducto(data.IdProducto);
                        //deshabilitar botones agregar
                        $("#btnAgregarCostoServicio").prop("disabled",false);

                        var id_costoservicio = "#"+ _costoservicio.IdProducto();
                        self.HabilitarFilaInputCostoServicio(id_costoservicio, false);

                        var idbutton ="#"+_costoservicio.IdProducto()+"_button_CostoServicio";
                        $(idbutton).hide();

                         _costoservicio.Confirmar(null,event);
                         self.HabilitarButtonsCostoServicio(null, true);

                         //ACTUALIZANDO DATA Nombre
                         var idnombretipoproducto = '#' + _costoservicio.IdProducto() + '_input_IdTipoProducto option:selected';
                         var nombretipoproducto = $(idnombretipoproducto).html();

                         var idnombreunidadmedida = '#' + _costoservicio.IdProducto() + '_input_IdUnidadMedida option:selected';
                         var nombreunidadmedida = $(idnombreunidadmedida).html();

                         var idnombretipoexistencia = '#' + _costoservicio.IdProducto() + '_input_IdTipoExistencia option:selected';
                         var nombretipoexistencia = $(idnombretipoexistencia).html();

                         _costoservicio.NombreTipoProducto(nombretipoproducto);
                         _costoservicio.AbreviaturaUnidadMedida(nombreunidadmedida);
                         _costoservicio.NombreTipoExistencia(nombretipoexistencia);


                        existecambio = false;
                        _input_habilitado = false;
                        _modo_nuevo = false;

                      }
                      else {
                        alertify.alert(data.IdProducto);
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

    self.ActualizarCostoServicio = function(data,event) {
          console.log("ActualizarCostoServicio");
          console.log(_costoservicio.NombreProducto());
          $("#loader").show();
          var objeto = data;
          var datajs = ko.toJS({"Data" : _costoservicio});

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Catalogo/cCostoServicio/ActualizarCostoServicio',
                  success: function (data) {
                      if (data != null) {
                        console.log(data);

                        if (data == "")
                        {
                          //deshabilitar campo origen
                          $("#btnAgregarCostoServicio").prop("disabled",false);
                          console.log("ID5:"+_costoservicio.IdProducto());
                          _costoservicio.Confirmar(null,event);

                          var id_costoservicio = "#"+ _costoservicio.IdProducto();
                          self.HabilitarFilaInputCostoServicio(id_costoservicio, false);

                          var idbutton ="#"+_costoservicio.IdProducto()+"_button_CostoServicio";
                          $(idbutton).hide();

                          //ACTUALIZANDO DATA Nombre
                          var idnombretipoproducto = '#' + _costoservicio.IdProducto() + '_input_IdTipoProducto option:selected';
                          var nombretipoproducto = $(idnombretipoproducto).html();

                          var idnombreunidadmedida = '#' + _costoservicio.IdProducto() + '_input_IdUnidadMedida option:selected';
                          var nombreunidadmedida = $(idnombreunidadmedida).html();

                          var idnombretipoexistencia = '#' + _costoservicio.IdProducto() + '_input_IdTipoExistencia option:selected';
                          var nombretipoexistencia = $(idnombretipoexistencia).html();

                          _costoservicio.NombreTipoProducto(nombretipoproducto);
                          _costoservicio.AbreviaturaUnidadMedida(nombreunidadmedida);
                          _costoservicio.NombreTipoExistencia(nombretipoexistencia);

                          existecambio = false;
                          _input_habilitado = false;
                          _modo_nuevo = false;
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

    self.EditarCostoServicio = function(data, event) {

      if(event)
      {
        console.log("EditarCostoServicio");
        console.log("ID.:"+data.IdProducto());
        var objeto = ko.toJS(data);
        var datajs = ko.toJS(_costoservicio);

        if( _modo_nuevo == true )
        {

        }
        else {

          if (_costoservicio.IdProducto() == data.IdProducto())
          {

            if (_input_habilitado == true)
            {
              $("#btnAgregarCostoServicio").prop("disabled",false);
              data.Deshacer(null,event);
              var id_costoservicio = "#"+ data.IdProducto();
              self.HabilitarFilaInputCostoServicio(id_costoservicio, false);

              var idbutton = "#"+_costoservicio.IdProducto()+"_button_CostoServicio";
              $(idbutton).hide();

              _input_habilitado =false;

            }
            else {
              $("#btnAgregarCostoServicio").prop("disabled",true);
              var id_costoservicio = "#"+ data.IdProducto();
              self.HabilitarFilaInputCostoServicio(id_costoservicio, true);

              var idbutton = "#"+data.IdProducto()+"_button_CostoServicio";

              var idinput = "#"+data.IdProducto()+"_input_NombreProducto";
              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;
            }

          }
          else {
            $("#btnAgregarCostoServicio").prop("disabled",true);
            if( _input_habilitado == true)
            {
              //deshabilitar campo origen
              console.log("ID2:"+_costoservicio.IdProducto());

              var id_costoservicio = "#"+ _costoservicio.IdProducto();
              self.HabilitarFilaInputCostoServicio(id_costoservicio, false);

              var idbutton = "#"+_costoservicio.IdProducto()+"_button_CostoServicio";

              _costoservicio.Deshacer(null,event);

              $(idbutton).hide();
            }

            var id_costoservicio = "#"+ data.IdProducto();
            self.HabilitarFilaInputCostoServicio(id_costoservicio, true);

            var idbutton = "#"+data.IdProducto()+"_button_CostoServicio";

            var idinput = "#"+data.IdProducto()+"_input_NombreProducto";
            $(idinput).focus();
            $(idbutton).show();

            _input_habilitado = true;
          }


        }

      }

    }

    self.PreBorrarCostoServicio = function (data) {

      if(_modo_nuevo == false)
      {
        _costoservicio.Deshacer(null, event);
        _input_habilitado = false;
        $("#btnAgregarCostoServicio").prop("disabled",false);
        self.HabilitarTablaSpanCostoServicio(null, true);
      }

      setTimeout(function(){
        alertify.confirm("¿Desea borrar el registro?", function(){
          console.log("BorrarCostoServicio");
          console.log(data.IdProducto());
          self.HabilitarButtonsCostoServicio(null, true);
          if (data.IdProducto() != null){
            self.BorrarCostoServicio(data);
          }
          else
          {
            $("#btnAgregarCostoServicio").prop("disabled",false);
            _input_habilitado = false;
            _modo_nuevo = false;
            self.data.CostosServicio.remove(data);
            var tabla = $('#DataTables_Table_0');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');
            //self.ListarCostosServicio();
          }
        });
      }, 200);
    }

    self.BorrarCostoServicio = function (data) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Catalogo/cCostoServicio/BorrarCostoServicio',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarCostoServicio");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      $("#btnAgregarCostoServicio").prop("disabled",false);
                      self.HabilitarTablaSpanCostoServicio(null, true);
                      self.SeleccionarSiguiente(objeto);
                      self.data.CostosServicio.remove(objeto);
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


    self.OnClickCostoServicio = function(data ,event) {

      if(event)
      {
          console.log("OnClickCostoServicio");
          console.log("Modo Nuevo " + _modo_nuevo);
          var objeto = ko.toJS(data);
          var datajs = ko.toJS(_costoservicio);

          if( _modo_nuevo == true )
          {

          }
          else
          {

            $("#btnAgregarCostoServicio").prop("disabled",true);
            if(_costoservicio.IdProducto() !=  data.IdProducto())
            {
              if (_input_habilitado == true)
              {
                console.log("INPUT ESTA HABILITADO Y ESTAS DENTRO DE DISTINTO");
                _costoservicio.Deshacer(null, event);

                //var id_costoservicio = "#" + _id_filacostoservicio_anterior;
                var id_costoservicio = "#" + _costoservicio.IdProducto();
                self.HabilitarFilaInputCostoServicio(id_costoservicio, false);

                var idbutton = "#"+_costoservicio.IdProducto()+"_button_CostoServicio";
                $(idbutton).hide();
              }

              console.log("INPUT ESTA HABILITADO Y PASO 2");
              console.log(_costoservicio.IdProducto());
              console.log(data.IdProducto());
              //habilitar campo destino
              //Obteniendo ID de la fila para usarlo con los span e inputs
              var id_fila_costoservicio = "#" + $(event.target).attr('id');
              //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
              if($.isSubstring(id_fila_costoservicio, "span") || $.isSubstring(id_fila_costoservicio, "input")){
                id_fila_costoservicio = "#" + $(event.target).parent()[0].id;
              }
              //Guardar Variable de id de la fila, para ocultar los inputs al cambiar de posicion
              var idinput ="#"+$(id_fila_costoservicio).find('input').attr('id');
              self.HabilitarFilaInputCostoServicio("#" + $(id_fila_costoservicio).parent()[0].id, true);

              var idbutton = "#"+data.IdProducto()+"_button_CostoServicio";

              $(idinput).focus();
              $(idbutton).show();

              _input_habilitado = true;

              }
              else {
                if (_input_habilitado == false)
                {
                  var id_fila_costoservicio = "#" + $(event.target).attr('id');

                  //CONDICION, por si se da click en un input o span, obtiene el id del padre, que es la fila
                  if($.isSubstring(id_fila_costoservicio, "span") || $.isSubstring(id_fila_costoservicio, "input")){
                    id_fila_costoservicio = "#" + $(event.target).parent()[0].id;
                  }


                  self.HabilitarFilaInputCostoServicio("#" + $(id_fila_costoservicio).parent()[0].id, true);

                  var idbutton = "#"+data.IdProducto()+"_button_CostoServicio";
                  var idinput ="#"+$(id_fila_costoservicio).find('input').attr('id');
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
    self.OnKeyUpCostoServicio = function(data, event){
      if(event)
      {
       console.log("OnKeyUpCostoServicio");

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
          var idinputnombre = '#' + _costoservicio.IdProducto() + '_input_NombreProducto';
          var idinputtipo ='#' +  _costoservicio.IdProducto() + '_input_IdTipoProducto';
          var idinputunidadmedida ='#' +  _costoservicio.IdProducto() + '_input_IdUnidadMedida';
          var idinputtipoexistencia ='#' +  _costoservicio.IdProducto() + '_input_IdTipoExistencia';

          _costoservicio.NombreProducto($(idinputnombre).val());
          _costoservicio.IdTipoProducto($(idinputtipo).val());
          _costoservicio.IdUnidadMedida($(idinputunidadmedida).val());
          _costoservicio.IdTipoExistencia($(idinputtipoexistencia).val());



         if(_modo_nuevo == true)
         {
           self.InsertarCostoServicio(_costoservicio,event);
         }
         else
         {
           self.ActualizarCostoServicio(_costoservicio,event);
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
            alertify.confirm("¿Desea perder el nuevo registro?", function(){
              self.SeleccionarAnterior(_costoservicio);
              self.data.CostosServicio.remove(_costoservicio);
              var tabla = $('#DataTables_Table_0');
              $('tr:last', tabla).addClass('active').siblings().removeClass('active');

              $("#btnAgregarCostoServicio").prop("disabled",false);
              self.HabilitarButtonsCostoServicio(null, true);
               _modo_nuevo = false;
               _input_habilitado = false;
            });
          }
          else
          {
            console.log("Escape - false");
            console.log(_costoservicio._NombreProducto());
            //revertir texto

             _costoservicio.Deshacer(null, event);

            //deshabilitar botones agregar
            $("#btnAgregarCostoServicio").prop("disabled",false);

            /*var id_fila_costoservicio = "#" + $(event.target).attr('id');
            if($.isSubstring(id_fila_costoservicio, "span") || $.isSubstring(id_fila_costoservicio, "input")){
              id_fila_costoservicio = "#" + $(event.target).parent()[0].id;
            }
            self.HabilitarFilaInputCostoServicio("#" + $(id_fila_costoservicio).parent()[0].id, false);*/
            self.HabilitarTablaSpanCostoServicio(null, true);

            var idbutton ="#"+_costoservicio.IdProducto()+"_button_CostoServicio";
            $(idbutton).hide();

            existecambio=false;
            _modo_nuevo = false;
            _input_habilitado = false;
          }

        }
      }
    }

    self.GuardarCostoServicio = function(data,event) {
      if(event)
      {
         console.log("GuardarCostoServicio");
         console.log(_nombrecostoservicio);
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         //Variable para obtener el id delinput
          var idinputnombre = '#' + _costoservicio.IdProducto() + '_input_NombreProducto';
          var idinputtipo ='#' +  _costoservicio.IdProducto() + '_input_IdTipoProducto';
          var idinputunidadmedida ='#' +  _costoservicio.IdProducto() + '_input_IdUnidadMedida';
          var idinputtipoexistencia ='#' +  _costoservicio.IdProducto() + '_input_IdTipoExistencia';

          _costoservicio.NombreProducto($(idinputnombre).val());
          _costoservicio.IdTipoProducto($(idinputtipo).val());
          _costoservicio.IdUnidadMedida($(idinputunidadmedida).val());
          _costoservicio.IdTipoExistencia($(idinputtipoexistencia).val());


         if(_modo_nuevo == true)
         {
           self.InsertarCostoServicio(_costoservicio,event);
         }
         else
         {
           self.ActualizarCostoServicio(_costoservicio,event);
         }
      }
    }



}

function mayus(e) {
    e.value = e.value.toUpperCase();
}
