
var url_menu = BASE_URL +'assets/data/menu/menu.json';

var _menu = $('#menu');
var _menu_mobile = $('#menu-mobile');
var _lista_menu = $('#option-menu');
var _lista_menu = $('#option-menu');
var _lista_menu_tabs = $('#option-menu-tabs');
var _lista_menu_left = $('#opcion-left');

$(document).ready(function() {

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

  $.getJSON(url_menu, function (data1) {
    $.each(data1["menu"].Data, function (key, entry) {
      var activo = "";
      if (entry.id == menu___tabs) {
        activo = "active";
      }
      _menu.append('<li class="'+activo+'" role="presentation" ><a href="'+entry.href+'" rel="'+entry.id+'" class="option-menu-tabs navtabs headnav"  role="tab" data-toggle="tab" name="'+entry.name+'"> '+entry.nombre+' </a></li>');
    })
  });
  _lista_menu_tabs.empty();
  $.getJSON(url_menu, function (data3) {
    $.each(data3[menu___tabs].Data, function (key, entry) {
      var activo = "";
      if (entry.id == menu___item) {
        activo = "active";
      }
      if (entry.href == "#") {
        if (menu___tabs == "ventas") {
          _lista_menu_tabs.append('<li class="chapternav-item"><a class="chapternav-link '+entry.class+'" rel="'+entry.id+'" name="'+entry.name+'"href="'+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a><ul id="'+entry.name+'"><li class="sub-menu"><a href="#" name="mercaderia" >Mercaderia</a></li><li class="sub-menu"><a href="#" name="activoFijo">Activo Fijo</a></li><li class="sub-menu"><a href="#" name="servicios">Servicios</a></li><li class="sub-menu"><a href="#" name="otrasVentas">Otras Ventas</a></li></ul></li>');
        }
        else{
          _lista_menu_tabs.append('<li class="chapternav-item '+activo+'"><a  rel="'+entry.id+'" class="opcion-item-tabs chapternav-link" name="'+entry.name+'"  href="'+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a></li>');
        }
        $('#consultas_y_reportes').remove();
        $('.Reporte').addClass('opcion-item-tabs');
      }
      else {
        _lista_menu_tabs.append('<li class="chapternav-item '+activo+'"><a  rel="'+entry.id+'" class="opcion-item-tabs chapternav-link" name="'+entry.name+'"  href="'+BASE_URL+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a></li>');
      }
    })
  });

  $('body').on('click', '.header-navbar-mobile__menu button', function() {
    btn_mobile();
  });

  $('body').on('click', '.option-menu-tabs', function() {

    var id_menu = $(this).prop("rel");
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

    var nombre_menu_tabs = $(this).attr("name");

    _lista_menu_tabs.empty();
    _lista_menu_left.empty();
    $.getJSON(url_menu, function (data3) {
      if (data3[nombre_menu_tabs])
      {
        if (data3[nombre_menu_tabs].url)
        {
          $.each(data3[nombre_menu_tabs].Data, function (key, entry) {
          _lista_menu_tabs.append('<li class="chapternav-item"><a class="chapternav-link" rel="'+entry.id+'"  name="'+entry.name+'"href="'+BASE_URL+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a></li>');
          })
        }
        else
        {
          if (nombre_menu_tabs == "ventas") {
            $.each(data3[nombre_menu_tabs].Data, function (key, entry) {
              _lista_menu_tabs.append('<li class="chapternav-item"><a class="chapternav-link '+entry.class+'" rel="'+entry.id+'" name="'+entry.name+'"href="'+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a><ul id="'+entry.name+'"><li class="sub-menu"><a href="#" name="mercaderia" >Mercaderia</a></li><li class="sub-menu"><a href="#" name="activoFijo">Activo Fijo</a></li><li class="sub-menu"><a href="#" name="servicios">Servicios</a></li><li class="sub-menu"><a href="#" name="otrasVentas">Otras Ventas</a></li></ul></li>');
            })
            $('#consultas_y_reportes').remove();
            $('.Reporte').addClass('opcion-item-tabs');
          }
          else {
            $.each(data3[nombre_menu_tabs].Data, function (key, entry) {
              _lista_menu_tabs.append('<li class="chapternav-item "><a class="opcion-item-tabs chapternav-link" rel="'+entry.id+'"  name="'+entry.name+'"href="'+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a></li>');
            })
          }
        }
      }
    });
  });

  $('body').on('click', '.opcion-item-tabs', function() {

    var nombre_item_tabs = $(this).attr("name");
    _lista_menu_left.empty();
    $.getJSON(url_menu, function (data4) {
      if(data4[nombre_item_tabs])
      {
        $('#modal-option').modal('hide');
        $('.dashboard').addClass('dashboard_menu');
        btn_mobile();
        $.each(data4[nombre_item_tabs].Data, function (key, entry) {
          _lista_menu_left.append('<li class="menu-opcion"><a href="'+BASE_URL+entry.href+'"><div class="info-box bg "><div class="icon"><i class="material-icons"><span class="'+entry.icono+'"></span></i></div><div class="content"><div class="text">'+entry.nombre+'</div></div></div></a></li>');
        })
      }
    });
  });

  $('body').on('click', '.chapternav-link', function() {

    var id_menu = $(this).prop("rel");
    // alert(id_menu);
    $.ajax({
      type: 'POST',
      data : {"sub_item_menu":id_menu},
      url: SITE_URL+'/Seguridad/cSeguridad/seleccionador_item',
      success: function (data) {
        if(data != "")
        {
        }
      }
    });
  });


  $('body').on('click', '#opciones-mobile', function() {
    $("#modal-option-mobile").modal("show");
    _menu_mobile.empty();
    $.getJSON(url_menu, function (data3) {
      $.each(data3["menu"].Data, function (key, entry) {
        _menu_mobile.append('<li class="opciones"><a href="'+entry.href+'" class="option-menu" name="'+entry.name+'"><div class="op-modal"><div class="op-text">'+entry.nombre+'</div></div></a></li>');
      })
    });
  });

$('body').on('click', '.option-menu', function() {
    var opcion = $(this).attr("name");
    _lista_menu.empty();
    $.getJSON(url_menu, function (data2) {
      if (data2[opcion])
      {
        if (data2[opcion].url)
        {
          $.each(data2[opcion].Data, function (key, entry) {
            _lista_menu.append('<div class="col-xs-4 col-ms-4 col-md-2 text-center"><li class="chapternav-item-phone"><a class="chapternav-link opcion-item-tabs" name="'+entry.name+'" href="'+BASE_URL+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a></li></div>');
          })
        }
        else
        {
          if (opcion == "ventas") {
            $.each(data2[opcion].Data, function (key, entry) {
              _lista_menu.append('<div class="col-xs-4 col-ms-4 col-md-2 text-center chapternav-items-phone"> <li class="chapternav-item-phone"><a class="chapternav-link " name="'+entry.name+'" id="'+entry.id+'" href="'+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a><ul id="'+entry.name+'"><li><a href="#" class="sub-menu">Mercaderia</a></li><li><a href="#" class="sub-menu">Activo Fijo</a></li><li><a href="#" class="sub-menu">Servicios</a></li><li><a href="#" class="sub-menu">Otras Ventas</a></li></ul></li></div>');
            });
            $('#consultas_y_reportes').remove();
          }
          else {
            $.each(data2[opcion].Data, function (key, entry) {
              _lista_menu.append('<div class="col-xs-4 col-ms-4 col-md-2 text-center"><li class="chapternav-item-phone"><a class="chapternav-link opcion-item-tabs" name="'+entry.name+'" id="'+entry.id+'" href="'+entry.href+'"><figure class="chapternav-icon"> <span class="'+entry.icono+'"></span> </figure><span class="chapternav-label">'+entry.nombre+'</span></a></li></div>');
            });
          }
        }
      }
    });
    $("#modal-option-mobile").modal("hide");
    $("#modal-option").modal("show");
  });

  $("#btn-regresar").click(function () {
    $("#modal-option").modal("hide");
    $("#modal-option-mobile").modal("show");
  });

  $('body').on('click', '.chapternav-item', function() {
    $('.chapternav-item').removeClass('active')
    $(this).addClass('active');
  });

  $('body').on('click', '.sub-menu', function() {

    var subMenu = $(this).parent().attr('id');
    _lista_menu_left.empty();
    $.getJSON(url_menu, function (data4) {
      if(data4[subMenu])
      {
        $('#modal-option').modal('hide');
        $('.dashboard').addClass('dashboard_menu');
        btn_mobile();
        $.each(data4[subMenu].Data, function (key, entry) {
          _lista_menu_left.append('<li class="menu-opcion"><a href="'+BASE_URL+entry.href+'"><div class="info-box bg "><div class="icon"><i class="material-icons"><span class="'+entry.icono+'"></span></i></div><div class="content"><div class="text">'+entry.nombre+'</div></div></div></a></li>');
        })
      }
    });
  });
  $('body').on('click', '#bntsession', function() {
    $('#lbl-tabs').empty();
    $('#lbl-item').empty();
    $('#lbl-tabs').append(menu___tabs);
    $('#lbl-item').append(menu___item);
  });
  });
