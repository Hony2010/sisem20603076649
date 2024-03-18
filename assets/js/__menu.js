var _menu = $('#menu');
var _menu_items = $('#opcion-menu-tabs')
var _menu_left = $('#opcion-left');
var _menu_mobile = $('#menu-mobile');
var _lista_menu = $('#option-menu');

cargar_menu_left = function (key, entry){
  var activo = "";
  if (entry.name == menu___op_left) {
    activo = "active";
  }
  if (entry.href !="#") {
    _menu_left.append('<li class="menu-opcion '+activo+'"><a rel="'+entry.name+'" class="opcion-left" href="'+BASE_URL+entry.href+'"><div class="info-box bg "><div class="icon"><i class="material-icons"><span class="'+entry.icono+'"></span></i></div><div class="content"><div class="text">'+entry.nombre+'</div></div></div></a></li>');
  }
  else {
    _menu_left.append('<li class="menu-opcion '+activo+'"><a rel="'+entry.name+'" class="opcion-left" href="'+entry.href+'"><div class="info-box bg '+entry.class+'" data-css="'+BASE_URL+entry.css+'"><div class="icon"><i class="material-icons"><span class="'+entry.icono+'"></span></i></div><div class="content"><div class="text">'+entry.nombre+'</div></div></div></a></li>');
  }
}

$(document).ready(function() {

  // $.getJSON(url_menu, function (data) {
    var data = ObtenerJSONDesdeURL(url_menu);
    $('.tabnavcont').empty()

    $.each(data.Menu, function (key, entry) {
      var activo = "";

      var id_content = entry.id_ul;
      var nombre_tab = entry.name;
      if (entry.name == menu___tabs) {
        activo = "active";
      }
      _menu.append('<li class="'+activo+'" role="presentation" ><a id="'+key+'_'+entry.name+'" href="#'+entry.name+'" rel="'+entry.name+'" class="option-menu-tabs navtabs headnav"  role="tab" data-toggle="tab" name="'+entry.name+'" data-key="'+entry.key+'"> '+entry.Nombre+' </a></li>');
      $('.tabnavcont').append('<div class="tab-pane '+activo+'" id="'+entry.name+'" role="tabpanel"><nav id="chapternav" class="chapternav" ><div class="chapternav-wrapper dropdownmenu"><ul id="'+entry.id_ul+'" class="chapternav-items"> </ul></div></nav></div>');

      if (entry.Data!=null) {
        $.each(entry.Data, function (key2, entrySub) {
          nombre_item = entrySub.name
          var activo = "";
          var url = "";

          var status = entrySub.status;

            if (entrySub.name == menu___item) activo = "active";
            if (entrySub.href != "#") url = BASE_URL
            var listaitems = ['Clientes','Proveedores','Empleados','Mercaderías','Servicios','Activos Fijos','Otras Venta','Gastos','Costos Agregados','Reporte de Catalogos','Importación Masiva','Emitir Factura','Emitir Boleta','Emitir Nota de Crédito','Emitir Nota de Débito','Emitir Orden de Pedido','Emitir Boleta Tipo T','Emitir Boleta Tipo Z','Lista de Precios','Lista de Raleo','Verificación de Correlatividad','Importación de Boleta Masiva','Consulta de Ventas','Reporte de Ventas','Importacion de Venta','Envio de Facturas','Resumen Diario','Comunicacion de Baja','Publicación a Pagina Web','Generación Archivo CFC','Consulta de CPE','Validacion CPE','Emitir Boleta Viaje'];
            if (listaitems.filter( item => item == entrySub.name ).length > 0 ) {
              $('#'+id_content).append('<li class="chapternav-item '+activo+'"><a id="'+id_content+'_'+entrySub.name+'" title="'+entrySub.title+'" rel="'+entrySub.name+'" class="opcion-item-tabs chapternav-link" name="'+entrySub.name+'"  href="#'+entrySub.name+'" data-url="'+url+entrySub.href+'" data-key="'+entrySub.key+'"><figure class="chapternav-icon"> <span class="'+entrySub.icono+'"></span> </figure><span class="chapternav-label">'+entrySub.SubItem+'</span></a></li>');
            } else {
              $('#'+id_content).append('<li class="chapternav-item '+activo+'"><a id="'+id_content+'_'+entrySub.name+'" title="'+entrySub.title+'" rel="'+entrySub.name+'" class="opcion-item-tabs chapternav-link" name="'+entrySub.name+'"  href="'+url+entrySub.href+'" data-key="'+entrySub.key+'"><figure class="chapternav-icon"> <span class="'+entrySub.icono+'"></span> </figure><span class="chapternav-label">'+entrySub.SubItem+'</span></a></li>');
            }

        })
      }
    });
  // });

  $('body').on('click','.MenuDrop',function () {
    var nombre_menu_drop =  $(this).attr("name");
    $('.contenido').remove();
    $('.sidebar__menu').append('<div class="contenido">'+nombre_menu_drop+'</div>');

    $("#modal-option").modal("hide"); //mobile
    $('.dashboard').addClass('dashboard_menu'); //mobile
    btn_mobile();//mobile
  })

  $('body').on('click', '.chapternav-item', function() {
    $('.chapternav-item').removeClass('active');
    $(this).addClass('active');
  });

  $('body').on('click', '.menu-opcion', function() {
    $('.menu-opcion').removeClass('active')
    $(this).addClass('active');
  });

  //----------menu mobil------------//

  var btn_mobile = function(){
    if ($('.dashboard').hasClass('dashboard_menu')) {
      document.getElementById("btn-mobile").innerHTML='';
      document.getElementById("btn-mobile").innerHTML='<i class="fa fa-chevron-left"></i>';
    }
    else {
      document.getElementById("btn-mobile").innerHTML='';
      document.getElementById("btn-mobile").innerHTML='<i class="fa fa-bars"></i>';
    }
  };

  $('body').on('click', '#opciones-mobile', function() {
    $('.dashboard').removeClass('dashboard_menu');
    btn_mobile();
    $("#modal-option-mobile").modal("show");
    _menu_mobile.empty();
    $.getJSON(url_menu, function (data) {
      $.each(data, function (key, entry) {
        _menu_mobile.append('<li class="opciones"><a href="#" class="option-menu-mobile" name="'+entry.name+'"><div class="op-modal"><div class="op-text">'+entry.Nombre+'</div></div></a></li>');
      })
    });
  });


  $('body').on('click', '.option-menu-mobile', function() {
    var nombre_opcion = $(this).attr("name");
    var id_menu = $(this).attr("name");

    $.ajax({
      type: 'POST',
      data : {"sub_tab_menu":id_menu},
      url: SITE_URL+'/Seguridad/cSeguridad/seleccionador_tab',
      success: function (data) {
        if(data != "")
        {
          menu___tabs = data;
          $("#modal-option-mobile").modal("hide");
          $("#modal-option").modal("show");
          _menu_left.empty();
          _lista_menu.empty();
          $.getJSON(url_menu, function (data) {

            $.each(data[nombre_opcion].Data, function (key, entry) {
              var url = "";
              var nombre_item = entry.name;
              var dropdown = ""
              if (entry.href != "#") url = BASE_URL;
              if(entry.desplegable!=null) dropdown = '<ul id="mobil_'+entry.name+'"></ul>';
              _lista_menu.append('<div class="col-xs-4 col-ms-4 col-md-2 chapternav-items-phone text-center"><li class="chapternav-item-phone"><a class="chapternav-link opcion-item-mobile" name="'+entry.name+'" href="'+url+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.SubItem+'</span></a>'+dropdown+'</li></div>');

              if (entry.desplegable != null) {
                $.each(entry.desplegable, function (key2, entryDrop) {
                  $('#mobil_'+nombre_item).append('<li class="sub-menu"><a class="MenuDrop" href="#" name="mercaderia" >'+entryDrop.nombre+'</a></li>');
                })
              }
            })
          });
        }
      }
    });

  });

  $('body').on('click', '.opcion-item-mobile', function() {
    var nombre_opcion = $(this).attr("name");
    if (menu___tabs!="ventas") $("#modal-option").modal("hide");
    _menu_left.empty();
    $('.contenido').remove();
    $.getJSON(url_menu, function (data) {
      $.each(data[menu___tabs].Data[nombre_opcion].Data, function (key, entry) {
        if (entry.css) {
          _menu_left.append('<li class="menu-opcion"><a rel="'+entry.name+'" class="opcion-left" href="#"><div class="info-box bg '+entry.class+'" data-css="'+BASE_URL+entry.css+'"><div class="icon"><i class="material-icons"><span class="'+entry.icono+'"></span></i></div><div class="content"><div class="text">'+entry.nombre+'</div></div></div></a></li>');
          $('.dashboard').addClass('dashboard_menu');
          btn_mobile();
        }
        else {
          _menu_left.append('<li class="menu-opcion"><a rel="'+entry.name+'" class="opcion-left" href="'+BASE_URL+entry.href+'"><div class="info-box bg "><div class="icon"><i class="material-icons"><span class="'+entry.icono+'"></span></i></div><div class="content"><div class="text">'+entry.nombre+'</div></div></div></a></li>');
          $('.dashboard').addClass('dashboard_menu');
          btn_mobile();
        }
      })
    });
  });

  $('body').on('click', '.chapternav-item-phone', function() {
    $('.chapternav-item-phone').removeClass('active')
    $(this).addClass('active');
  });

  $("#btn-regresar").click(function () {
    $("#modal-option").modal("hide");
    $("#modal-option-mobile").modal("show");
  });

  $('body').on('click', '.header-navbar-mobile__menu button', function() {
    btn_mobile();
  });


  //logo//
  $('body').on('click', '#LogoSistema', function() {
    var id_menu = "catalogos"
    $.ajax({
      type: 'POST',
      data : {"sub_tab_menu":id_menu},
      url: SITE_URL+'/Seguridad/cSeguridad/seleccionador_tab',
      success: function (data) {
        if(data != "")
        {

        }
      }
    });
  });

});
