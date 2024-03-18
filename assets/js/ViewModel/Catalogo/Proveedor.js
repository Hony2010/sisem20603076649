
ProveedoresModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

}

ProveedorModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    this.IdPersona = ko.observable();
    this.ApellidoCompleto = ko.observable("");
    this.NombreCompleto = ko.observable("");
    this.NombreAbreviado = ko.observable("");
    this.RazonSocial = ko.observable("");
    this.NumeroDocumentoIdentidad = ko.observable("");
    this.Direccion = ko.observable("");
    this.TelefonoFijo = ko.observable("");
    this.Celular = ko.observable("");
    this.Email = ko.observable();
    this.IdTipoDocumentoIdentidad = ko.observable();
    this.IdTipoPersona = ko.observable();
    this.EstadoContribuyente = ko.observable("");
    this.CondicionContribuyente = ko.observable("");
    this.Foto = ko.observable("");
    self.EstadoCondicion = ko.observable(self.EstadoContribuyente() + " - " +self.CondicionContribuyente());


    self.OnChangeInputFile = function(data,event){
         if(event)
         {
             var file =event.target.files[0];
             var filename = file.name;
             var id = event.target.attributes.id.value;
             var img = $("#img_"+id);
             readImageAsDataURL(file,img);

             var nombre_foto = filename;
             var _filename = "";
             if(nombre_foto != null || nombre_foto != ""){
               _filename = nombre_foto.split(" ").join("_");
             }

             data.Foto(_filename);

         }
     }

    self.AbrirPreview = function(data,event){

          if(event)
          {
              var img = event.target;
              var dataURL = img.src;

              if(  dataURL != '')
              {
                  $("#foto_previa").attr('src',dataURL);
                  $("#modalPreview").modal("show");
              }
          }
      }


}

NuevoProveedorModel = function (data) {
    var self = this;
    ko.mapping.fromJS(data, {}, self);

    //this.NombreProducto = ko.observable();
}


var Mapping = {
    'Proveedores': {
        create: function (options) {
            if (options)
              return new ProveedoresModel(options.data);
            }
    },
    'Proveedor': {
        create: function (options) {
            console.log('Proveedor');
            console.log(options);
            if (options)
              return new ProveedorModel(options.data);
            }
    },
    'NuevoProveedor': {
        create: function (options) {
            if (options)
              return new ProveedorModel(options.data);
            }
    }
}

jQuery.isSubstring = function (haystack, needle) {
  return haystack.indexOf(needle) !== -1;

};

var ImageURL;

Index = function (data) {
    //var _option = false;
    //var _input_habilitado = false;
    var _modo_nuevo = false;
    //var _codigo_evento_previo = 0;
    var _opcion_guardar = 1;
    var _objeto;
    var self = this;
    //var ModelsSubFamilia = new IndexSubFamilia(data);

    ImageURL = data.data.ImageURL;

    ko.mapping.fromJS(data, Mapping, self);


    self.Sugerencias = function(event)
    {
      $("#input-text-filtro").autocomplete({
          delay: 100,
          source: function (request, response) {

              // Suggest URL
              //var suggestURL = "http://suggestqueries.google.com/complete/search?client=chrome&q=%QUERY";
              //suggestURL = suggestURL.replace('%QUERY', request.term);

              // JSONP Request
              $.ajax({
                  method: 'POST',
                  dataType: 'jsonp',
                  url: SITE_URL+'/Configuracion/Catalogo/cModelo/DataServer',
                  success: function(data){
                      response(data[0]);
                  }
              });
          }
      });
    }

    self.CargarFoto = function(data) {
         var src = "";
         console.log(data.IdPersona());

         //console.log("ID PRODUCTO: " + data.IdPersona() + "  ., FOTO NOMBRE: " + data.Foto())
         if (data.IdPersona()=="" || data.IdPersona() == null || data.Foto() == null || data.Foto() == "")
             src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
         else
             src=SERVER_URL + CARPETA_IMAGENES + CARPETA_PROVEEDOR+data.IdPersona()+SEPARADOR_CARPETA+data.Foto();

         return src;
     }

    self.Consultar = function() {

    }

    self.SeleccionarAnterior = function (data)  {
      var id = "#"+data.IdPersona();
      var anteriorObjeto = $(id).prev();

      anteriorObjeto.addClass('active').siblings().removeClass('active');

      if (_modo_nuevo == false)
      {
        var match = ko.utils.arrayFirst(self.data.Proveedores(), function(item) {
              return anteriorObjeto.attr("id") == item.IdPersona();
          });

        $("#img_FileFoto").attr("src",self.CargarFoto(match));//OJO AQUI
        $("#img_FileFotoPreview").attr("src",self.CargarFoto(match));
      }
    }

    self.SeleccionarSiguiente = function (data)  {
      var id = "#"+data.IdPersona();
      var siguienteObjeto = $(id).next();

      if (siguienteObjeto.length > 0)
      {
        siguienteObjeto.addClass('active').siblings().removeClass('active');

        if (_modo_nuevo == false) //revisar
        {
          var match = ko.utils.arrayFirst(self.data.Proveedores(), function(item) {
                return siguienteObjeto.attr("id") == item.IdPersona();
            });


          $("#img_FileFoto").attr("src",self.CargarFoto(match));//OJO AQUI
          $("#img_FileFotoPreview").attr("src",self.CargarFoto(match));
        }
      }
      else {
        self.SeleccionarAnterior(data);
      }
    }

    self.Seleccionar = function (data,event)  {
      console.log("Seleccionar");

      var id = "#"+ data.IdPersona();
      $(id).addClass('active').siblings().removeClass('active');
      //debugger;

      var objeto = Knockout.CopiarObjeto(data);
      ko.mapping.fromJS(objeto, Mapping, Models.data.Proveedor);

      _objeto = Knockout.CopiarObjeto(Models.data.Proveedor);

      $("#img_FileFoto").attr("src",self.CargarFoto(objeto));//OJO AQUI
      $("#img_FileFotoPreview").attr("src",self.CargarFoto(objeto));

    }

    self.Nuevo = function(data,event) {
          //console.log("AgregarFamiliaProducto");
        if(event)
        {
          ko.mapping.fromJS(Models.data.NuevoProveedor, Mapping, Models.data.Proveedor);
          //$('#opcion-mercaderianuevo').removeClass('active').addClass("disabledTab");
          var src=BASE_URL + CARPETA_IMAGENES + "nocover.png";
          $('#img_FileFoto').attr('src', src);

          $('#btn_Limpiar').text("Limpiar");

          $("#modalProveedor").modal("show");

          setTimeout(function(){
            $("#IdPersona").focus();
          }, 1000);

          _opcion_guardar = 0;
          _modo_nuevo = true;
        }

    }

    self.SubirFoto = function() {

          var modal = document.getElementById("form");
          console.log($("#IdPersona").val());
          var data = new FormData(modal);

          $.ajax({
              type: 'POST',
              data : data,
              contentType: false,       // The content type used when sending data to the server.
              cache: false,             // To unable request pages to be cached
              processData: false,        // To send DOMDocument or non processed data file it is set to false
              mimeType: "multipart/form-data",
              url: SITE_URL+'/Catalogo/cProveedor/SubirFoto',
              success: function (data) {

                    if(_opcion_guardar!= 0 ){
                      var fila_objeto =ko.utils.arrayFirst(Models.data.Proveedores(), function(item) {
                          return Models.data.Proveedor.IdPersona() == item.IdPersona();

                        });
                        var objeto = Knockout.CopiarObjeto(Models.data.Proveedor);
                        Models.data.Proveedores.replace(fila_objeto,objeto);

                        self.Seleccionar(Models.data.Proveedor);

                        $("#modalProveedor").modal("hide");
                    }
                    else {
                      //debugger;
                      var copia_objeto = Knockout.CopiarObjeto(Models.data.Proveedor);
                      Models.data.Proveedores.push(new ProveedoresModel(copia_objeto));

                      self.Seleccionar(Models.data.Proveedor);
                      alertify.confirm("Se grabó correctamente \n¿Desea seguir agregando nuevos registros?", function(){
                        ko.mapping.fromJS(Models.data.NuevoProveedor, Mapping, Models.data.Proveedor);
                        setTimeout(function(){
                          $("#IdPersona").focus();
                        }, 200);
                      }, function(){
                        _modo_nuevo == false;
                        $("#modalProveedor").modal("hide");
                      });

                    }
              }
          });
      }

    self.Guardar =function(data,event){
      if(event)
      {
        $("#loader").show();
        var accion = "";
        if(_opcion_guardar != 0)
        {
          accion = "ActualizarProveedor";
        }
        else
        {
          accion = "InsertarProveedor";
        }
        Models.data.Proveedor.RazonSocial($("#RazonSocial").val());

        var datajs = ko.toJS({"Data": Models.data.Proveedor});
        console.log(datajs);
        console.log(data);
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cProveedor/' + accion,
          success: function (data) {
              console.log(data);
              if(_opcion_guardar != 0){
                if(data == "")
                {

                  ko.mapping.fromJS(data, Mapping, Models.data.Proveedor);

                  var nombretipopersona = $("#combo-tipopersona option:selected").text();
                  Models.data.Proveedor.NombreTipoPersona(nombretipopersona);

                  var nombretipodocumentoindentidad = $("#combo-tipodocumentoIdentidad option:selected").text();
                  Models.data.Proveedor.NombreAbreviado(nombretipodocumentoindentidad);

                  //console.log("ID PRODUCTO" + data.IdPersona);
                  self.SubirFoto();
                }
                else {
                  alertify.alert(data);
                }
              }
              else {
                if($.isNumeric(data.IdPersona))
                {
                  ko.mapping.fromJS(data, Mapping, Models.data.Proveedor);

                  var nombretipopersona = $("#combo-tipopersona option:selected").text();
                  Models.data.Proveedor.NombreTipoPersona(nombretipopersona);

                  var nombretipodocumentoindentidad = $("#combo-tipodocumentoIdentidad option:selected").text();
                  Models.data.Proveedor.NombreAbreviado(nombretipodocumentoindentidad);
                  //console.log("ID PRODUCTO" + data.IdPersona);
                  self.SubirFoto();
                }
                else {
                  alertify.alert(data);
                  $("#IdPersona").focus();
                }
              }
              $("#loader").hide();

          }
        });
      }
    }

    self.Editar  = function(data, event) {
      //
      if(event)
      {

        _opcion_guardar = 1;
        if( _modo_nuevo == true )
        {

        }
        else
        {
          self.ChangeTipoPersona(data,event);
          self.RazonSocial(data,event);
          $("#modalProveedor  ").modal("show");
          $("#IdPersonaProveedor").focus();
        }
        _modo_nuevo = false;
        $('#btn-busqueda').hide();

      }
    }

    self.PreEliminar = function (data) {
      self.titulo ="Eliminación de Mercadería";
      //setTimeout(function(){
      alertify.confirm(self.titulo,"¿Desea borrar realmente?", function(){
        console.log("PreEliminarProveedor");
        if (data.IdPersona() != null)
          self.Eliminar(data);
      },function(){});
      //}, 100);

    }

    self.Eliminar = function (data) {
        var objeto = data;
        var datajs = ko.toJS({"Data":data});

        $.ajax({
                type: 'POST',
                data : datajs,
                dataType: "json",
                url: SITE_URL+'/Catalogo/cProveedor/BorrarProveedor',
                success: function (data) {
                    if (data != null) {
                      console.log("BorrarProveedor");
                      //console.log(data);
                      if(data != "")
                      {
                        alertify.alert(data);
                      }
                      else {
                        self.SeleccionarSiguiente(objeto);
                        Models.data.Proveedores.remove(objeto);
                        /*var filas = Models.data.Proveedores().length;
                        self.data.Filtros.totalfilas(data.Filtros.totalfilas);

                        if(filas == 0) {
                          $("#Paginador").paginador(data.Filtros,self.ConsultarPorPagina);
                          var ultimo = $("#Paginador ul li:last").prev();
                          ultimo.children("a").click();
                        }*/

                      }
                    }
              },
              error : function (jqXHR, textStatus, errorThrown) {
                     //console.log(jqXHR.responseText);
                     var $data = {error:{msg:jqXHR.responseText}};
                     alertify.alert("Error en "+self.titulo,$data.error.msg,function() {});
              }
        });


    }

    self.Deshacer = function(event)
    {
      if(event)
      {
        if($('#btn_Limpiar').text() == "Deshacer")
        {
          ko.mapping.fromJS(_objeto, Mapping, Models.data.Proveedor);

        }
        else  if($('#btn_Limpiar').text() == "Limpiar")
        {
          document.getElementById("form").reset();

          setTimeout(function(){
            $("#IdPersona").focus();
          }, 500);
        }

      }
    }

    self.Cerrar = function(event)
    {
      if(event)
      {
        $("#modalProveedor").modal("hide");
        if(_modo_nuevo == true){
          _modo_nuevo = false;
        }
        self.Seleccionar(_objeto);
      }
    }

    self.ChangeTipoPersona = function(data,event)
    {
      if(event)
      {
        var IdTipoPersona = data.IdTipoPersona();
        if(IdTipoPersona == 1) //Persona Judirica
        {
          // $("#NombreCompleto").val("");
          $("#NombreCompleto").prop("disabled",true);
          // $("#ApellidoCompleto").val("");
          $("#ApellidoCompleto").prop("disabled",true);
          $("#RazonSocial").prop("disabled",false);
          // $("#RazonSocial").val("");

        }
        else if((IdTipoPersona == 2) || (IdTipoPersona == 3) ) //Persona Natural || No Domiciliado
        {
          $("#NombreCompleto").prop("disabled",false);
          $("#ApellidoCompleto").prop("disabled",false);
          $("#RazonSocial").prop("disabled",true);
          // $("#RazonSocial").val("");
        }
      }
    }

    self.RazonSocial = function(data,event)
    {
      if(event)
      {
        var IdTipoPersona = data.IdTipoPersona();
        if((IdTipoPersona == 2) || (IdTipoPersona == 3)) //Persona Judirica || No Domiciliado
        {
          var NombreCompleto = $("#NombreCompleto").val();
          var ApellidoCompleto = $("#ApellidoCompleto").val();
          $("#RazonSocial").val(ApellidoCompleto+' '+NombreCompleto);
        }
      }
    }
    self.ChangeBtnBusqueda = function(data, event ) {

      if (event) {
        var logoTipoDocumento = ko.mapping.toJS(Models.data.Proveedor.IdTipoDocumentoIdentidad);
        if ( logoTipoDocumento == 2 || logoTipoDocumento == 4 ) {
          $('#btn-busqueda').show();
          if (logoTipoDocumento == 2) {
            $('#btn-busqueda').empty();
            $('#btn-busqueda').append('<img width="25px" height="22px" src="'+BASE_URL+'assets/js/iconos/logoRENIEC.svg" alt="" title="RENIEC">');
          }
          else {
            $('#btn-busqueda').empty();
            $('#btn-busqueda').append('<img width="22px" height="20px" src="'+BASE_URL+'assets/js/iconos/logoSUNAT.svg" alt="" title="SUNAT">');
          }
        }
        else {
          $('#btn-busqueda').hide();
        }
      }
    }

    self.ConsultarSunat = function(data) {
      $("#loader").show();
        var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad}});
        $.ajax({
          type: 'POST',
          data : datajs,
          dataType: "json",
          url: SITE_URL+'/Catalogo/cProveedor/ConsultarSunat',
          success: function (data) {
            if (data['success'] == true) {
              // var objeto = new ProveedorModel(data.result);
              ko.mapping.fromJS(data.result, {}, Models.data.Proveedor);

            }
            else {
                alertify.alert(data.msg);
            }
            $("#loader").hide();
          },
          error : function (jqXHR, textStatus, errorThrown) {
            $("#loader").hide();
            console.log(jqXHR.responseText);
          }
        });
      }

      self.ConsultarReniec = function(data) {
        $("#loader").show();
          var datajs = ko.toJS({"Data":{'NumeroDocumentoIdentidad':data.NumeroDocumentoIdentidad}});
          $.ajax({
            type: 'POST',
            data : datajs,
            dataType: "json",
            url: SITE_URL+'/Catalogo/cProveedor/ConsultarReniec',
            success: function (data) {
              if (data['success'] == true) {
                ko.mapping.fromJS(data.result, {}, Models.data.Proveedor);
              }
              else {
                  alertify.alert(data.message);
              }
              $("#loader").hide();
            },
            error : function (jqXHR, textStatus, errorThrown) {
              $("#loader").hide();
              console.log(jqXHR.responseText);
            }
          });
        }

        self.ConsultaPorNumeroDocumento=function(data, event){
          if (event) {
            var numero = ko.mapping.toJS(Models.data.Proveedor.NumeroDocumentoIdentidad);
            var logoTipoDocumento = ko.mapping.toJS(Models.data.Proveedor.IdTipoDocumentoIdentidad);
            if (numero !=null) {
              if (numero.length == 8) {
                Models.data.Proveedor.IdTipoPersona(2);
              }
              else if(numero.length > 8){
                if (numero.substring(0, 2) == 20){
                  Models.data.Proveedor.IdTipoPersona(1);
                }
                else if (numero.substring(0, 1) == 1) {
                  Models.data.Proveedor.IdTipoPersona(2);
                }
              }
            }

            if (logoTipoDocumento == 2) {
              self.ConsultarReniec(data);
            }
            else if(logoTipoDocumento == 4) {
              self.ConsultarSunat(data);
            }
          }
        }
}
