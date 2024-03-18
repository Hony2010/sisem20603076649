
SubFamiliasProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    self._NombreSubFamiliaProducto = ko.observable(data.NombreSubFamiliaProducto);

    self.VistaOptions = ko.pureComputed(function(){
      return self.NoEspecificado() == "S" ? "hidden" : "visible";
    }, this);

    self.Deshacer = function (data,event)  {
      if (event)
      {
        //console.log("Deshacer");
        self.NombreSubFamiliaProducto.valueHasMutated();
        self.NombreSubFamiliaProducto("");
        self.NombreSubFamiliaProducto(self._NombreSubFamiliaProducto());
        return true;
      }
    }

    self.Confirmar = function(data,event) {
        if (event) {
          //console.log("Confirmar");
          self._NombreSubFamiliaProducto.valueHasMutated();
          self._NombreSubFamiliaProducto(self.NombreSubFamiliaProducto());
        }
    }
}

SubFamiliaProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

NuevaSubFamiliaProductoModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);
}

var Mapping2 = {

    'SubFamiliasProducto': {
        create: function (options) {
          if (options)
            return new SubFamiliasProductoModel(options.data);
            }
    },
    'NuevaSubFamiliaProducto': {
        create: function (options) {
            if (options)
              return new NuevaSubFamiliaProductoModel(options.data);
            }
    },
    'SubFamiliaProducto': {
        create: function (options) {
            if (options)
              return new SubFamiliaProductoModel(options.data);
            }
    }
}

IndexSubFamilia = function (data) {

    //var _option = false;
    var _input_habilitado_subfamiliaproducto = false;
    var _modo_nuevo_subfamiliaproducto = false;
    var _subfamiliaproducto = null;
    var _codigo_evento_previo = 0;
    var self = this;

    ko.mapping.fromJS(data, Mapping2, self);

    self.EstaHabilitadaVistaSubFamiliaProducto = function () {
      console.log("EstaHabilitadoOpcionesVistaSubFamiliaProducto");
      if ( $("#subfamiliaproducto").find('.opt_subfamiliaproducto').prop("disabled") == true)
        return false;
      else
        return true;
    }
    self.HabilitarTablaSpanSubFamiliaProducto = function (data, option)  {
      //var id = "#"+data.IdTipoExistencia();
      var id = ".DataTables_Table_subfamiliaproducto";
      //$(id).addClass('active').siblings().removeClass('active');

      if(option == false)
      {
        $(id).find('.class_SpanSubFamiliaProducto').hide();
        $(id).find('.class_InputSubFamiliaProducto').show();
      }
      else {
        $(id).find('.class_SpanSubFamiliaProducto').show();
        $(id).find('.class_InputSubFamiliaProducto').hide();
        $(id).find('.guardar_button_SubFamiliaProducto').hide();
        //_input_habilitado_subfamiliaproducto = false;
      }

    }

    self.FilaButtonsSubFamiliaProducto = function (data, event)  {
      //console.log("FilaButtons");
      if(event.target.classList.contains('btn') || event.target.classList.contains('glyphicon')){
        // bla bla bla
        console.log("Button");
      }
      else
      {
          console.log("MODO NUEVO: " + _modo_nuevo_subfamiliaproducto);
          if(_modo_nuevo_subfamiliaproducto == true)
          return;

          if(_input_habilitado_subfamiliaproducto == false)
          return;

          self.RefrescarSubFamiliaProducto(data, event);
      }

    }


    self.RefrescarSubFamiliaProducto = function(data, event){
      if (event) {
        data.Deshacer(null,event);
        _input_habilitado_subfamiliaproducto = false;
        $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
        //$('#opcion-familiaproducto').removeClass('disabledTab');
        self.HabilitarTablaSpanSubFamiliaProducto(null,  true);
      }
    }

    self.HabilitarFilaSubFamiliaProducto =function (data, option) {


      var idspan ="#"+data.IdSubFamiliaProducto()+"_span_NombreSubFamiliaProducto";
      var idinput ="#"+data.IdSubFamiliaProducto()+"_input_NombreSubFamiliaProducto";
      var idbuttonguardar ="#"+data.IdSubFamiliaProducto()+"_button_SubFamiliaProducto";

      if (option == false)
      {
        $(idspan).show();
        $(idinput).hide();
        $(idbuttonguardar).hide();
        //$("#brand").find("button").prop("disabled",false);
        //$("#btnAgregarFamiliaProducto").prop("disabled",false);
        $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
      }
      else {
        $(idspan).hide();
        $(idinput).show();
        $(idbuttonguardar).show();
        $(idinput).focus();
        //$("#brand").find("button").prop("disabled",true);
        //$("#btnAgregarFamiliaProducto").prop("disabled",true);
        $("#btnAgregarSubFamiliaProducto").prop("disabled",true);
      }

    }

    self.SeleccionarAnterior = function (data)  {
        var id ="#"+data.IdSubFamiliaProducto()+"_subfamiliaproducto";
        var anteriorObjeto = $(id).prev();

        anteriorObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_subfamiliaproducto == false)
        {
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto(), function(item) {
                return anteriorObjeto.attr("id") == item.IdSubFamiliaProducto();
            });

          if(match)
          {
            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto= match;
          }
        }
    }

    self.SeleccionarSiguiente = function (data)  {
      var id ="#"+data.IdSubFamiliaProducto()+"_subfamiliaproducto";
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo_subfamiliaproducto == false) //revisar
        {
          var match = ko.utils.arrayFirst(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto(), function(item) {
                return siguienteObjeto.attr("id") == item.IdSubFamiliaProducto();
            });

          if(match)
          {
            vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto= match;
          }
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    self.SeleccionarNormalSubFamiliaProducto = function(data){

      if(data.NoEspecificado() == "S" && _modo_nuevo_subfamiliaproducto == false){

        self.SeleccionarSubFamiliaProducto(data);
      }
      else {
        self.SeleccionarSubFamiliaProducto(data);
      }
    }

    self.SeleccionarSubFamiliaProducto = function (data,event) {

      if (_modo_nuevo_subfamiliaproducto == false)
      {

        var id = "#"+data.IdSubFamiliaProducto()+"_subfamiliaproducto";
        $(id).addClass('active').siblings().removeClass('active');
        //$("#TituloNombreFamiliaProducto").text(data.NombreFamiliaProducto());
        //if (vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.IdSubFamiliaProducto() != data.IdSubFamiliaProducto())
          //self.PrimerSubFamiliaProducto(data);
        _subfamiliaproducto = data;

        if (_codigo_evento_previo > 0 )
        {
          vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto = data;
          _codigo_evento_previo = 0;
        }
      }
    }

    //self.ListarSubFamiliasProducto = function() {
    //  self.ConsultarSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto);// self._familiaproducto
    //}

    self.HabilitarOpcionesVistaSubFamiliaProducto = function(option) {
      //console.log("btn_modelo");
      if (option == true)
        $("#subfamiliaproducto").find('.opt_subfamiliaproducto').prop('disabled',true);
      else
        $("#subfamiliaproducto").find('.opt_subfamiliaproducto').prop("disabled",false);
    }



    self.AgregarSubFamiliaProducto = function(data,event) {
      console.log("SubAgregarFamiliaProducto");

      if ( _input_habilitado_subfamiliaproducto == true )
      {

      }
      else
      {
        //console.log(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.IdFamiliaProducto());
        var objeto = Knockout.CopiarObjeto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.NuevaSubFamiliaProducto);
        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto  = new SubFamiliasProductoModel(objeto);
        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto.push(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto);
        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.IdFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.FamiliaProducto.IdFamiliaProducto());

        self.HabilitarFilaSubFamiliaProducto(objeto , true);


        //$('#opcion-familiaproducto').removeClass('active').addClass("disabledTab");

        /*OCULTAMOS EL TEXTO DEL ID EN LA FILA NUEVA*/
        $("#-1_subfamiliaproducto td:first").text("");

        /*DESHABILITANDO BOTONES DE CONTROL Y HABILITANDO EL EDITAR DEL NUEVO*/
        self.HabilitarOpcionesVistaSubFamiliaProducto(true);
        var id_borrar_subfamiliaproducto = "#-1_borrar_button_SubFamiliaProducto";
        $(id_borrar_subfamiliaproducto).prop("disabled", false);

        /*DESHABILITAMOS EL BOTON MARCA*/
        // $("#btnAgregarFamiliaProducto").prop("disabled",true);
        /*SELECCIONANDO ULTIMA FILA*/
        var tabla = $('.DataTables_Table_subfamiliaproducto');
        $('tr:last', tabla).addClass('active').siblings().removeClass('active');

        setTimeout(function(){
          $("#-1_input_NombreSubFamiliaProducto").focus();
        }, 150);

        _modo_nuevo_subfamiliaproducto = true;
        _input_habilitado_subfamiliaproducto = true;
      }

    }

    self.InsertarSubFamiliaProducto = function(data,event){

      if(event)
      {
        //console.log("InsertarSubFamiliaProducto");
        $("#loader").show();
        var datajs = ko.toJS({"Data" : data});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Configuracion/Catalogo/cSubFamiliaProducto/InsertarSubFamiliaProducto',
                success: function (data) {
                    if (data != null) {

                      if ($.isNumeric(data.IdSubFamiliaProducto))
                      {
                        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.IdSubFamiliaProducto(data.IdSubFamiliaProducto);
                        vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.Confirmar(null,event);
                        self.HabilitarFilaSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto, false);

                        self.HabilitarOpcionesVistaSubFamiliaProducto(false);

                        //$('#opcion-familiaproducto').removeClass('disabledTab');
                        existecambio = false;
                        _input_habilitado_subfamiliaproducto = false;
                        _modo_nuevo_subfamiliaproducto = false;
                      }
                      else {
                        alertify.alert(data.IdSubFamiliaProducto);
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

    self.ActualizarSubFamiliaProducto = function(data,event){
      $("#loader").show();
      console.log("ActualizarSubFamiliaProducto");
      var datajs = ko.toJS({"Data" : data});

      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cSubFamiliaProducto/ActualizarSubFamiliaProducto',
              success: function (data) {
                  if (data != null) {

                    if (data == "")
                    {
                      //deshabilitar campo origen
                      vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.Confirmar(null,event);
                      self.HabilitarFilaSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto,false);
                      //$('#opcion-familiaproducto').removeClass('disabledTab');

                      existecambio = false;
                      _input_habilitado_subfamiliaproducto = false;
                      _modo_nuevo_subfamiliaproducto = false;
                    }
                    else{
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

    self.OnClickNombreSubFamiliaProducto = function(data,event) {

      if(event)
      {
        if(data.NoEspecificado() == "S" && _modo_nuevo_subfamiliaproducto == false)
        {

          if(_input_habilitado_subfamiliaproducto==false)
            return;

          _subfamiliaproducto.Deshacer(null, event);
          _input_habilitado_subfamiliaproducto = false;
          //$("#btnAgregarMarca").prop("disabled",false);
          $("#btnAgregarSubFamiliaProducto").prop("disabled",false);
          self.HabilitarTablaSpanSubFamiliaProducto(null, true);
          //$('#opcion-familiaproducto').removeClass('disabledTab');

        }
        else {

          _codigo_evento_previo=1;
          //console.log("OnClickFamiliaProducto");
          if( _modo_nuevo_subfamiliaproducto == true )
          {

          }
          else
          {
            //$('#opcion-familiaproducto').removeClass('active').addClass('disabledTab');
            if(self.EstaHabilitadaVistaSubFamiliaProducto() == false)
            {
              return false;
            }

            if(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.IdSubFamiliaProducto() !=  data.IdSubFamiliaProducto())
            {
              if (_input_habilitado_subfamiliaproducto == true)
              {
                //deshabilitar campo origen
                _subfamiliaproducto.Deshacer(null,event);
                self.HabilitarFilaSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto,false);
              }

              //habilitar como destino
              self.HabilitarFilaSubFamiliaProducto(data,true);
              _input_habilitado_subfamiliaproducto = true;
            }
            else
            {
              if (_input_habilitado_subfamiliaproducto == false)
              {
                self.HabilitarFilaSubFamiliaProducto(data,true);
                _input_habilitado_subfamiliaproducto = true;
              }
              else
              {
                //
              }
            }

          }
        }
          return false;
      }

    }

    self.EditarSubFamiliaProducto = function(data,event) {

        if(event)
        {
            console.log("EditarSubFamiliaProducto");
            _codigo_evento_previo=2;

            if( _modo_nuevo_subfamiliaproducto == true )
            {

            }
            else
            {
              //$('#opcion-familiaproducto').removeClass('active').addClass("disabledTab");
              if(self.EstaHabilitadaVistaSubFamiliaProducto() == false)
              {
                return false;
              }

              if (vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto.IdSubFamiliaProducto() == data.IdSubFamiliaProducto())
              {
                if (_input_habilitado_subfamiliaproducto == true)
                {
                  //deshabilitar origen
                  //$('#opcion-familiaproducto').removeClass('disabledTab');
                  _subfamiliaproducto.Deshacer(null,event);
                  self.HabilitarFilaSubFamiliaProducto(data,false);
                  _input_habilitado_subfamiliaproducto =false;
                }
                else
                {
                  //habilitar origen
                 self.HabilitarFilaSubFamiliaProducto(data,true);
                 _input_habilitado_subfamiliaproducto = true;
                }
              }
             else
             {
                if (_input_habilitado_subfamiliaproducto == true)
                {
                  //deshabilitar origen
                  _subfamiliaproducto.Deshacer(null,event);
                  self.HabilitarFilaSubFamiliaProducto(vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliaProducto,false);
                }

                //habilitar destino
                self.HabilitarFilaSubFamiliaProducto(data,true);
                _input_habilitado_subfamiliaproducto = true;
             }

            }
        }
    }

    self.OnKeyUpNombreSubFamiliaProducto = function (data,event){

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


           if(_modo_nuevo_subfamiliaproducto == true)
           {
             self.InsertarSubFamiliaProducto(data,event);
           }
           else
           {
             self.ActualizarSubFamiliaProducto(data,event);
           }
         }

         return true;
        }
    }

    self.EscaparGlobalSubFamilia = function(event)
    {
      if(event)
      {
        if(_input_habilitado_subfamiliaproducto == true)
        {
          if(_modo_nuevo_subfamiliaproducto == true)
          {
            //alertify.confirm("¿Desea perder la nueva sub familia?", function(){
              // self.HabilitarFilaSubFamiliaProducto(_subfamiliaproducto,false);
              // self.SeleccionarAnterior(_subfamiliaproducto);
              // vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto.remove(_subfamiliaproducto);
              // self.HabilitarOpcionesVistaSubFamiliaProducto(false);
              //$('#opcion-familiaproducto').removeClass('disabledTab');
              _modo_nuevo_subfamiliaproducto = false;
              _input_habilitado_subfamiliaproducto = false;
            //});
          }
          else
          {
            //$('#opcion-familiaproducto').removeClass('disabledTab');
            console.log("escape - modo edicion");
            //deshabilitar botones
            _subfamiliaproducto.Deshacer(null,event);
            self.HabilitarFilaSubFamiliaProducto(_subfamiliaproducto,false);
            existecambio=false;
            _modo_nuevo_subfamiliaproducto = false;
            _input_habilitado_subfamiliaproducto = false;
          }

        }
      }
    }

    self.GuardarSubFamiliaProducto = function (data,event) {
      if(event)
      {
         console.log("GuardarSubFamiliaProducto");
         if(!($("#loader").css('display') == 'none'))
         {
           event.preventDefault();
           return false;
         }
         if(_modo_nuevo_subfamiliaproducto == true)
         {
           self.InsertarSubFamiliaProducto(data,event);
         }
         else
         {
           self.ActualizarSubFamiliaProducto(data,event);
         }
      }

    }

    self.PreBorrarSubFamiliaProducto = function(data,event){

          if(_modo_nuevo_subfamiliaproducto == false)
          {
            self.RefrescarSubFamiliaProducto(data,  event);
          }

          setTimeout(function(){
            alertify.confirm("¿Desea borrar el registro?", function(){
              console.log("BorrarSubFamiliaProducto");

              if (data.IdSubFamiliaProducto() != null && _modo_nuevo_subfamiliaproducto == false)
              {
                self.BorrarSubFamiliaProducto(data);
              }
              else
              {
                self.HabilitarOpcionesVistaSubFamiliaProducto(false);

                //$('#opcion-familiaproducto').removeClass('disabledTab');

                self.HabilitarFilaSubFamiliaProducto(data,false);
                _input_habilitado_subfamiliaproducto = false;
                _modo_nuevo_subfamiliaproducto = false;
                self.SeleccionarAnterior(data);
                vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto.remove(data);
              }
            });
          },200);
    }

    self.BorrarSubFamiliaProducto = function(data,event) {
      var objeto = data;
      var datajs = ko.toJS({"Data":data});
      console.log(datajs);
      $.ajax({
              type: 'POST',
              data : datajs,
              dataType: "json",
              url: SITE_URL+'/Configuracion/Catalogo/cSubFamiliaProducto/BorrarSubFamiliaProducto',
              success: function (data) {
                  if (data != null) {
                    console.log("BorrarSubFamiliaProducto");
                    //console.log(data);
                    if(data != "")
                    {
                      alertify.alert(data);
                    }
                    else {
                      self.SeleccionarSiguiente(objeto);
                      vistaModeloCatalogo.vmcFamilia.dataFamiliaProducto.SubFamiliasProducto.remove(objeto);
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

    self.OutModal = function(event){
      if(event){
        _input_habilitado_subfamiliaproducto = false;
        _modo_nuevo_subfamiliaproducto = false;
      }
    }


}
