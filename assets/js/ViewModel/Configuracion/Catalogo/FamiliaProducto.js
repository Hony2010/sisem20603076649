
FamiliasProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreFamiliaProducto = ko.observable(data.NombreFamiliaProducto);

    self.VistaOptions = ko.pureComputed(function(){
      return self.NoEspecificado() == "S" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log("Deshacer");
        self.NombreFamiliaProducto.valueHasMutated();
        self.NombreFamiliaProducto("");
        self.NombreFamiliaProducto(self._NombreFamiliaProducto());
        return true;
      }
    }

    self.Confirmar = function(data,event) {
        if (event) {
          //console.log("Confirmar");
          self._NombreFamiliaProducto.valueHasMutated();
          self._NombreFamiliaProducto(self.NombreFamiliaProducto());
        }
    }
}

FamiliaProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

NuevaFamiliaProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping1 = {
    'FamiliasProducto': {
        create: function (options) {
            if (options)
              return new FamiliasProductoModel(options.data);
            }
    }/*,
    'SubFamiliasProducto': {
        create: function (options) {
          if (options)
            return new SubFamiliasProductoModel(options.data);
            }
    }*/,
    'FamiliaProducto': {
        create: function (options) {
            if (options)
              return new FamiliaProductoModel(options.data);
            }
    },
    'NuevaFamiliaProducto': {
        create: function (options) {
            if (options)
              return new NuevaFamiliaProductoModel(options.data);
            }
    }/*,
    'SubFamiliaProducto': {
        create: function (options) {
            if (options)
              return new SubFamiliaProductoModel(options.data);
            }
    }*/

}

IndexFamilia = function (data) {
    //var _option = false;
    var _input_habilitado_familiaproducto = false;
    var _modo_nuevo_familiaproducto = false;
    var _codigo_evento_previo = 0;
    var _familiaproducto = null;
    var self = this;
    var ModelsSubFamilia = new IndexSubFamilia(data);

    ko.mapping.fromJS(data, Mapping1, self);
    //self.Errores = ko.validation.group(self, { deep: true });
    self.EstaHabilitadaVistaFamiliaProducto = function () {
      console.log("EstaHabilitadaVistaFamiliaProducto");
      if ( $("#brand_familiaproducto").find(".opt_familiaproducto").prop("disabled") == true)
        return false;
      else
        return true;
    }

    self.HabilitarFilaFamiliaProducto =function (data, option) {

      var idspan ="#"+data.IdFamiliaProducto()+"_span_NombreFamiliaProducto";
      var idinput ="#"+data.IdFamiliaProducto()+"_input_NombreFamiliaProducto";
      var idbuttonguardar ="#"+data.IdFamiliaProducto()+"_button_FamiliaProducto";

      if (option == false)
      {
        $(idspan).show();
        $(idinput).hide();
        $(idbuttonguardar).hide();
        $("#brand_familiaproducto").find(".btn_subfamiliaproducto").prop("disabled",false);
        $("#btnAgregarFamiliaProducto").prop("disabled",false);
        $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
      }
      else {
        $(idspan).hide();
        $(idinput).show();
        $(idbuttonguardar).show();
        $(idinput).focus();
        $("#brand_familiaproducto").find(".btn_subfamiliaproducto").prop("disabled",true);
        $("#btnAgregarFamiliaProducto").prop("disabled",true);
        $("#btnAgregarSubFamiliaProducto").prop("disabled",true);
      }

      //_option  = option;
      //self.HabilitarOpcionesVistaSubFamiliaProducto(_option);
    }

    self.HabilitarOpcionesVistaFamiliaProducto = function(option) {
      //console.log("HabilitarOpcionesVistaSubFamiliaProducto");
      if (option == true)
        $("#brand_familiaproducto").find('.opt_familiaproducto').prop('disabled',true);
      else
        $("#brand_familiaproducto").find('.opt_familiaproducto').prop("disabled",false);
    }

    self.ListarFamiliasProducto = function() {
        //console.log("ListarFamiliasProducto");

        $.ajax({
                type: 'POST',
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cFamiliaProducto/ListarFamiliasProducto',
                success: function (data) {
                    if (data != null) {
                        console.log(data);
                        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto([]);
                        ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto.push(new FamiliasProductoModel(item));
                    });
                }
            }
        });
    }

    self.HabilitarTablaSpanFamiliaProducto = function (data, option)  {
      //var id = "#"+data.IdTipoExistencia();
      var id = "#DataTables_Table_0_familiaProducto";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanFamiliaProducto').hide();
        $(id).find('.class_InputFamiliaProducto').show();
      }
      else {
        $(id).find('.class_SpanFamiliaProducto').show();
        $(id).find('.class_InputFamiliaProducto').hide();
        $(id).find('.guardar_button_FamiliaProducto').hide();
        //_input_habilitado_familiaproducto = false;
      }

    }

    self.FilaButtonsFamiliaProducto = function (data, event)  {
      //console.log("FilaButtonsMarca");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else
      {
          console.log("MODO NUEVO: " + _modo_nuevo_familiaproducto);
          if(_modo_nuevo_familiaproducto == true)
          return;

          self.RefrescarFamiliaProducto(data, event);
      }

    }

    self.RefrescarFamiliaProducto = function(data, event){
      if (event) {
        // vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.Deshacer(null, event);
        data.Deshacer(null, event);
        _input_habilitado_familiaproducto = false;
        $("#btnAgregarFamiliaProducto").prop("disabled",false);
        $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
        //$('#opcion-subfamiliaproducto').removeClass('disabledTab');
        $("#brand_familiaproducto").find(".btn_subfamiliaproducto").prop("disabled",false);
        self.HabilitarTablaSpanFamiliaProducto(null,  true);
      }
    }

    self.SeleccionarNormal = function(data){
        if(data.NoEspecificado() == "S" && _modo_nuevo_familiaproducto == false){
        self.Seleccionar(data);
      }
      else {
        self.Seleccionar(data);
      }
    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdFamiliaProducto()+'_tr_familiaProducto';
      var anteriorObjeto = $(id).prev();

      anteriorObjeto.addClass('active').siblings().removeClass('active');

      //if (_modo_nuevo_familiaproducto == false)
      //{
        var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto(), function(item) {
              return anteriorObjeto.attr("name") == item.IdFamiliaProducto();
          });

          vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto= match;

        if(match)
        {
          vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto= match;
          self.PrimerSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto);
          $("#TituloNombreFamiliaProducto").text(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.NombreFamiliaProducto());
        }
      //}
    }

    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdFamiliaProducto()+'_tr_familiaProducto';
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_familiaproducto == false) //revisar
        {
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto(), function(item) {
                return siguienteObjeto.attr("name") == item.IdFamiliaProducto();
            });

          if(match)
          {
            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto= match;
            self.PrimerSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto);
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    self.HabilitarButtonAgregarFamiliaProducto = function(data, event){
      if(_modo_nuevo_familiaproducto == true || _input_habilitado_familiaproducto == true)
      return;
      $("#btnAgregarFamiliaProducto").prop("disabled",false);
    }

    self.DeshabilitarButtonAgregarFamiliaProducto = function(data, event){
      if(_modo_nuevo_familiaproducto == true || _input_habilitado_familiaproducto == true)
      return;
      $("#btnAgregarFamiliaProducto").prop("disabled",true);
    }

    self.Seleccionar = function (data,event)  {
      //console.log("Seleccionar");
      if (self.EstaHabilitadaVistaFamiliaProducto() == false)
        return false;

      if (_modo_nuevo_familiaproducto == false)
      {
         _familiaproducto= data;
         vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto = data;

        var id = "#"+data.IdFamiliaProducto()+'_tr_familiaProducto';
        $(id).addClass('active').siblings().removeClass('active');
        $("#TituloNombreFamiliaProducto").text(data.NombreFamiliaProducto());

        // self.PrimerSubFamiliaProducto(data);

      }
    }

    self.AgregarFamiliaProducto = function(data,event) {
          if ( _input_habilitado_familiaproducto == true )
          {
          }
          else
          {
            var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.NuevaFamiliaProducto);
            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto  = new FamiliasProductoModel(objeto);
            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto.push(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto);

            //$('#opcion-subfamiliaproducto').removeClass('active').addClass("disabledTab");
            _familiaproducto= vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto;
            self.HabilitarFilaFamiliaProducto(objeto , true);

            $("#-1_tr_familiaProducto td:first").text("");

            self.HabilitarOpcionesVistaFamiliaProducto(true);
            var id_borrar_familiaproducto = "#-1_borrar_button_FamiliaProducto";
            $(id_borrar_familiaproducto).prop("disabled", false);

            /*SELECCIONANDO ULTIMA FILA*/
            var tabla = $('#DataTables_Table_0_familiaProducto');
            $('tr:last', tabla).addClass('active').siblings().removeClass('active');

            _modo_nuevo_familiaproducto = true;
            _input_habilitado_familiaproducto = true;
          }
    }

    self.InsertarFamiliaProducto =function(data,event){

      if(event)
      {
        //console.log("InsertarFamiliaProducto");
        $("#loader").show();
        var datajs = ko.toJS({"Data" : data});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cFamiliaProducto/InsertarFamiliaProducto',
                success: function (data) {
                    if (data != null) {
                      if ($.isNumeric(data.IdFamiliaProducto))
                      {
                        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.IdFamiliaProducto(data.IdFamiliaProducto);
                        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.Confirmar(null,event);
                        self.HabilitarFilaFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto, false);
                        existecambio = false;
                        _input_habilitado_familiaproducto = false;
                        _modo_nuevo_familiaproducto = false;

                        self.HabilitarOpcionesVistaFamiliaProducto(false);

                        //vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.Marca = data;
                        self.PrimerSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto);
                        //HABILITANDO EL TAB MODELO
                        //$('#opcion-subfamiliaproducto').removeClass('disabledTab');


                        $("#TituloNombreFamiliaProducto").text(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.NombreFamiliaProducto());
                      }
                      else {
                        alertify.alert(data.IdFamiliaProducto);
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

    self.ActualizarFamiliaProducto = function(data,event) {
          console.log("ActualizarFamiliaProducto");
          var _copia_familiaproducto = data;

          var datajs = ko.toJS({"Data" : data});
          $("#loader").show();

          $.ajax({
                  type: 'POST',
                  data : datajs,
                  dataType: "json",
                  url: SITE_URL+'/Configuracion/Catalogo/cFamiliaProducto/ActualizarFamiliaProducto',
                  success: function (data) {
                      if (data != null) {
                        if (data == "")
                        {
                          //deshabilitar campo origen
                          _copia_familiaproducto.Confirmar(null,event);
                          self.HabilitarFilaFamiliaProducto(_copia_familiaproducto,false);
                          $("#TituloNombreFamiliaProducto").text(_copia_familiaproducto.NombreFamiliaProducto());
                          //$('#opcion-subfamiliaproducto').removeClass('disabledTab');
                          existecambio = false;
                          _input_habilitado_familiaproducto = false;
                          _modo_nuevo_familiaproducto = false;
                        }
                        else
                        {
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

    self.EditarFamiliaProducto = function(data, event) {

      if(event)
      {
          console.log("EditarFamiliaProducto");
          _codigo_evento_previo=2;

          if( _modo_nuevo_familiaproducto == true )
          {

          }
          else
          {
            //$('#opcion-subfamiliaproducto').removeClass('active').addClass("disabledTab");

            if (self.EstaHabilitadaVistaFamiliaProducto() == false)
              return false;

            if (vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.IdFamiliaProducto() == data.IdFamiliaProducto())
            {
              if (_input_habilitado_familiaproducto == true)
              {
                //deshabilitar origen
                //$('#opcion-subfamiliaproducto').removeClass("disabledTab");
                _familiaproducto.Deshacer(null,event);
                self.HabilitarFilaFamiliaProducto(data,false);
                _input_habilitado_familiaproducto =false;
              }
              else
              {
                //habilitar origen
               self.HabilitarFilaFamiliaProducto(data,true);
               _input_habilitado_familiaproducto = true;
              }
           }
           else
           {
              if (_input_habilitado_familiaproducto == true)
              {
                //deshabilitar origen
                _familiaproducto.Deshacer(null,event);
                self.HabilitarFilaFamiliaProducto(_familiaproducto,false);
              }

              //habilitar destino
              self.HabilitarFilaFamiliaProducto(data,true);
              _input_habilitado_familiaproducto = true;
           }

          }
      }
    }

    self.PreBorrarFamiliaProducto = function (data) {
        if(_modo_nuevo_familiaproducto == false)
        {
          self.RefrescarFamiliaProducto(data, event);
        }

        setTimeout(function(){
          alertify.confirm("¿Desea borrar realmente?", function(){
            console.log("PreBorrarFamiliaProducto");

            if (data.IdFamiliaProducto() != null && _modo_nuevo_familiaproducto == false)
            {
              self.BorrarFamiliaProducto(data);
            }
            else
            {
              self.HabilitarOpcionesVistaFamiliaProducto(false);
              //self.HabilitarFilaFamiliaProducto(data,false);

              self.SeleccionarAnterior(data);
              vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto.remove(data);

              //HABILITANDO EL TAB MODELO
              //$('#opcion-subfamiliaproducto').removeClass('disabledTab');
              /*HABILITANDO CARPETAS Y SUBCARPETAS*/
              $("#brand_familiaproducto").find(".btn_subfamiliaproducto").prop("disabled",false);
              $("#btnAgregarFamiliaProducto").prop("disabled",false);
              $("#btnAgregarSubFamiliaProducto").prop("disabled",false);

              _input_habilitado_familiaproducto = false;
              _modo_nuevo_familiaproducto = false;
            }
          });
        }, 200);
        console.log(document.activeElement);
    }

    self.BorrarFamiliaProducto = function (data) {
      var objeto = data;
      // var objeto = Knockout.CopiarObjeto(data);
      var datajs = ko.toJS({"Data":data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cFamiliaProducto/BorrarFamiliaProducto',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarFamiliaProducto");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto.remove(objeto);

                      //$('#opcion-subfamiliaproducto').removeClass('disabledTab');

                      self.HabilitarTablaSpanFamiliaProducto(null,  true);

                      $("#brand_familiaproducto").find(".btn_subfamiliaproducto").prop("disabled",false);

                      $("#btnAgregarFamiliaProducto").prop("disabled",false);
                      $("#btnAgregarSubFamiliaProducto").prop("disabled",false);

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

    self.OnClickNombreFamiliaProducto = function(data ,event) {

      if(event)
      {
        if(data.NoEspecificado() == "S" && _modo_nuevo_familiaproducto == false)
        {
          if(_input_habilitado_familiaproducto==false)
            return;

          _familiaproducto.Deshacer(null, event);
          _input_habilitado_familiaproducto = false;
          $("#btnAgregarFamiliaProducto").prop("disabled",false);
          $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
          $(".btn_subfamiliaproducto").prop("disabled",false);

          self.HabilitarTablaSpanFamiliaProducto(null, true);
          //$('#opcion-subfamiliaproducto').removeClass('disabledTab');

        }
        else {
          _codigo_evento_previo=1;
          //console.log("OnClickFamiliaProducto");
          if( _modo_nuevo_familiaproducto == true )
          {

          }
          else
          {
            //var copia_objeto=Knockout.CopiarObjeto(new FamiliaProductoModel(data));
            //$('#opcion-subfamiliaproducto').removeClass('active').addClass('disabledTab');
            if (self.EstaHabilitadaVistaFamiliaProducto() == false)
              return false;

            if(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.IdFamiliaProducto() !=  data.IdFamiliaProducto())
            {
              if (_input_habilitado_familiaproducto == true)
              {
                //deshabilitar campo origen
                _familiaproducto.Deshacer(null,event);
                self.HabilitarFilaFamiliaProducto(_familiaproducto,false);
              }

              //habilitar como destino
              self.HabilitarFilaFamiliaProducto(data,true);
              _input_habilitado_familiaproducto = true;
            }
            else
            {
              if (_input_habilitado_familiaproducto == false)
              {
                self.HabilitarFilaFamiliaProducto(data,true);
                _input_habilitado_familiaproducto = true;
              }
              else
              {
                //
              }
            }

          }

          return false;
        }
      }
    }

    self.OnKeyUpNombreFamiliaProducto = function(data, event){

      if(event)
      {
       //console.log("OnKeyUpNombreFamiliaProducto");

       var code = event.keyCode || event.which;

       existecambio = true;

       if (code === 13)
       {//enter
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

         if(_modo_nuevo_familiaproducto == true)
         {
           self.InsertarFamiliaProducto(data,event);
         }
         else
         {
           self.ActualizarFamiliaProducto(data,event);
         }
       }

       return true;
      }
    }

    self.EscaparGlobal = function(event)
    {
      if(event)
      {
        if(_input_habilitado_familiaproducto == true)
        {
          if(_modo_nuevo_familiaproducto == true)
          {
            var nuevo_objeto = ko.mapping.fromJS(ko.toJS(_familiaproducto));
            alertify.confirm("¿Desea perder la nueva familia?", function(){
              self.HabilitarOpcionesVistaFamiliaProducto(false);
              self.HabilitarFilaFamiliaProducto(nuevo_objeto,false);
              _modo_nuevo_familiaproducto = false;
              _input_habilitado_familiaproducto = false;
              self.SeleccionarAnterior(nuevo_objeto);
              vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliasProducto.remove(_familiaproducto);
            });
          }
          else
          {
            console.log("escape - modo edicion");
            //deshabilitar botones
            _familiaproducto.Deshacer(null,event);
            self.HabilitarFilaFamiliaProducto(_familiaproducto,false);
            existecambio=false;
            _modo_nuevo_familiaproducto = false;
            _input_habilitado_familiaproducto = false;
          }

          //$('#opcion-subfamiliaproducto').removeClass('disabledTab');

        }
      }
    }

    self.GuardarFamiliaProducto = function(data,event) {
      if(event)
      {
         console.log("GuardarFamiliaProducto");

         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }

         if(_modo_nuevo_familiaproducto == true)
         {
           self.InsertarFamiliaProducto(data,event);
         }
         else
         {
           self.ActualizarFamiliaProducto(data,event);
         }
      }
    }

    self.PrimerSubFamiliaProducto = function(data) {
        console.log("PrimerSubFamiliaProducto");
        if(_modo_nuevo_familiaproducto)
        {

        }
        else {
          if(data.IdFamiliaProducto() != null)
          {
            var datajs = ko.toJS({"Data":data});
            //console.log(_option);

            $.ajax({
                    type: 'POST',
                    data : datajs,
                    dataType: "json",
                    url: SITE_URL+'/Configuracion/Catalogo/cSubFamiliaProducto/ConsultarSubFamiliaProducto',
                    success: function (data) {
                        if (data != null) {
                            console.log(data);
                            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto([]);
                            ko.utils.arrayForEach(data, function (item) {
                            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto.push(new SubFamiliasProductoModel(item));
                        });
                        $('#tabla-subfamiliaproducto tbody tr:first').addClass('active');
                        //self.HabilitarOpcionesVistaSubFamiliaProducto(_option);
                    }
                }
            });
          }
        }
    }

    self.ConsultarSubFamiliaProducto = function(data) {

        if(_modo_nuevo_familiaproducto)
        {

        }
        else {
          if(data.IdFamiliaProducto() != null)
          {

            var datajs = ko.toJS({"Data":data});

            $.ajax({
                    type: 'POST',
                    data : datajs,
                    dataType: "json",
                    url: SITE_URL+'/Configuracion/Catalogo/cSubFamiliaProducto/ConsultarSubFamiliaProducto',
                    success: function (data) {
                        if (data != null) {
                            console.log(data);
                            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto([]);
                            ko.utils.arrayForEach(data, function (item) {
                                vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto.push(new SubFamiliasProductoModel(item));
                        });

                        $('#tabla-subfamiliaproducto tbody tr:first').addClass('active');
                        $("#modalSubFamiliaProducto").modal("show");
                        $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
                        //$("#opcion-subfamiliaproducto").addClass('active').siblings().removeClass('active');
                        //$("#subfamiliaproducto").addClass('active').siblings().removeClass('active');
                        //$("#btnAgregarFamiliaProducto").prop("disabled",true);
                    }
                }
            });
          }
        }
    }

    //Funcionalidad para invocar a SubFamilia
    //self.SeleccionarSubFamiliaProducto = function (data,event) {
      //ModelsSubFamilia.SeleccionarSubFamiliaProducto(data,event);
    //}
    self.SeleccionarNormalSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.SeleccionarNormalSubFamiliaProducto(data,event);
    }

    self.AgregarSubFamiliaProducto = function(data,event) {
      ModelsSubFamilia.AgregarSubFamiliaProducto(data,event);
    }

    self.EscaparGlobalSubFamilia = function(event) {
      ModelsSubFamilia.EscaparGlobalSubFamilia(event);
    }

    self.OnClickNombreSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.OnClickNombreSubFamiliaProducto(data,event);
    }

    self.EditarSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.EditarSubFamiliaProducto(data,event);
    }

    self.OnKeyUpNombreSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.OnKeyUpNombreSubFamiliaProducto(data,event);
    }

    self.GuardarSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.GuardarSubFamiliaProducto(data,event);
    }

    self.PreBorrarSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.PreBorrarSubFamiliaProducto(data,event);
    }

    self.FilaButtonsSubFamiliaProducto = function (data,event) {
      ModelsSubFamilia.FilaButtonsSubFamiliaProducto(data,event);
    }

    self.OutModal = function (data,event) {
      ModelsSubFamilia.OutModal(data,event);
    }

}
