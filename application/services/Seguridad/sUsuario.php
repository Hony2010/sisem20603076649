<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sUsuario extends MY_Service {

  public $Usuario = array();
  public $Persona = array();

  public function __construct()
  {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('herencia');
        $this->load->service("Seguridad/sPreguntaSeguridad");
        $this->load->service("Catalogo/sCliente");
        $this->load->service('Seguridad/sAccesoUsuarioAlmacen');
        $this->load->service('Seguridad/sAccesoCajaUsuario');
        $this->load->service('Seguridad/sAccesoTurnoUsuario');
        $this->load->service('Configuracion/General/sSede');
        $this->load->service('Configuracion/General/sConstanteSistema');
        $this->load->model('Seguridad/mUsuario');
        $this->load->model('Seguridad/mAccesoUsuario');
        $this->load->model('Catalogo/mPersona');
        $this->load->model('Configuracion/General/mModuloSistema');
        $this->Usuario = $this->mUsuario->Usuario;
        $this->Persona = $this->mPersona->Persona;
        $this->Usuario = $this->herencia->Heredar($this->Persona,$this->Usuario);
  }

  function Inicializar()
  {
    $this->Usuario["Foto"] = "";
    $this->Usuario["IdRol"] = "";
    $this->Usuario["NombreRol"] = "";
    $this->Usuario["ConfirmarClaveUsuario"] = "";
    $this->Usuario["ConfirmarRespuestaSeguridad"] = "";
    // $this->Usuario["Almacenes"] = array();
    $this->Usuario["NumeroItemsSeleccionadas"] = 0;
    $this->Usuario["SeleccionarTodos"] = false;
    $this->Usuario["SeleccionarTodosTurnos"] = false;
    $this->Usuario["NumeroTurnosSeleccionadas"] = 0;
    $this->Usuario["SeleccionarTodosCajas"] = false;
    $this->Usuario["NumeroCajasSeleccionadas"] = 0;
    $this->Usuario["IndicadorVistaPrecioMinimo"] = false;
    $this->Usuario["IndicadorExoneradoPrecioMinimo"] = false;
    $this->Usuario["IndicadorEstadoUsuario"] = true;
    $this->Usuario["IndicadorEditarPrecioUnitarioVenta"] = true;
    $this->Usuario["IndicadorCrearProducto"] = true;
    $this->Usuario["IndicadorPermisoAnularComprobanteVenta"] = true;
    $this->Usuario["IndicadorPermisoEditarComprobanteVenta"] = true;
    $this->Usuario["IndicadorPermisoEliminarComprobanteVenta"] = true;
    $this->Usuario["IndicadorPermisoCobranzaRapida"] = true;
    $this->Usuario["IndicadorPermisoStockNegativo"] = true;

    $parametrocaja = $this->sConstanteSistema->ObtenerParametroCaja();
    $this->Usuario["ParametroCaja"] = $parametrocaja ;
    //$almacenes = $this->sSede->ListarSedesTipoAlmacen();
    $dataListarUsuarios["EstadoUsuario"]="%";
    $Usuarios = $this->ListarUsuarios($dataListarUsuarios);
    $listadoalmacenes =$this->sSede->ListarSedesTipoAlmacen();
    $listadocajas = ($parametrocaja == 1) ? $this->sAccesoCajaUsuario->ListarCajasDesdeAccesoCajaUsuario() : array();
    $listadoturnos = ($parametrocaja == 1) ? $this->sAccesoTurnoUsuario->ListarTurnosDesdeAccesoTurnoUsuario() : array();

    $this->Usuario["Almacenes"] = $listadoalmacenes;
    $this->Usuario["Cajas"] = $listadocajas;
    $this->Usuario["Turnos"] = $listadoturnos;
    // print_r($this->Usuario["Almacenes"]);
    // exit;

    foreach ($Usuarios as $key=>$value) {
      if($parametrocaja == 1)
      {
        $data_cajas = $this->sAccesoCajaUsuario->ObtenerAccesosCajaUsuarioPorIdUsuario($value);
        $Usuarios[$key] = array_merge($Usuarios[$key], $data_cajas);
        
        $data_turnos = $this->sAccesoTurnoUsuario->ObtenerAccesosTurnoUsuarioPorIdUsuario($value);
        $Usuarios[$key] = array_merge($Usuarios[$key], $data_turnos);
      }
      
      $Usuarios[$key]["Almacenes"] = $listadoalmacenes;
      $almacenesusuario = $this->sAccesoUsuarioAlmacen->ConsultarAlmacenesUsuario($Usuarios[$key]["IdUsuario"]);
      $almacenes = $Usuarios[$key]["Almacenes"];

      $Usuarios[$key]["SeleccionarTodos"] = false;
      $Usuarios[$key]["NumeroItemsSeleccionadas"] = 0;

      $totalfilas = 0;
      foreach($almacenes  as $key1 =>$value1)
      {
        $almacenes[$key1]["Seleccionado"] = false;

        foreach($almacenesusuario  as $key2 =>$value2)
        {
          if($value1["IdSede"] == $value2["IdSede"])
          {
            $almacenes[$key1]["IdAccesoUsuarioAlmacen"] = $value2["IdAccesoUsuarioAlmacen"];
            if($value2["IndicadorEstado"] == 'A')
            {
              $almacenes[$key1]["Seleccionado"] = true;
            }
            $totalfilas++;
          }
        }

      }

      if(count($almacenes) == $totalfilas){
        $Usuarios[$key]["SeleccionarTodos"] = true;
      }
      $Usuarios[$key]["NumeroItemsSeleccionadas"] = $totalfilas;

      $Usuarios[$key]["Almacenes"] =$almacenes;

      $Usuarios[$key]["IndicadorVistaPrecioMinimo"] = ($value["IndicadorVistaPrecioMinimo"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorExoneradoPrecioMinimo"] = ($value["IndicadorExoneradoPrecioMinimo"] == 0) ? false : true;

      $Usuarios[$key]["IndicadorEditarPrecioUnitarioVenta"] = ($value["IndicadorEditarPrecioUnitarioVenta"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorCrearProducto"] = ($value["IndicadorCrearProducto"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorPermisoAnularComprobanteVenta"] = ($value["IndicadorPermisoAnularComprobanteVenta"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorPermisoEditarComprobanteVenta"] = ($value["IndicadorPermisoEditarComprobanteVenta"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorPermisoEliminarComprobanteVenta"] = ($value["IndicadorPermisoEliminarComprobanteVenta"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorPermisoCobranzaRapida"] = ($value["IndicadorPermisoCobranzaRapida"] == 0) ? false : true;
      $Usuarios[$key]["IndicadorPermisoStockNegativo"] = ($value["IndicadorPermisoStockNegativo"] == 0) ? false : true;
    }

    $PreguntasSeguridad = $this->sPreguntaSeguridad->ListarPreguntasSeguridad();
    $ImageURLCliente = $this->sCliente->ObtenerUrlCarpetaImagenes();

    $data = array("data" =>
          array(
            'Usuario'=>$this->Usuario,
            'NuevoUsuario'=>$this->Usuario,
            'Usuarios'=>$Usuarios,
            'PreguntasSeguridad'=>$PreguntasSeguridad,
            'ImageURLCliente' =>$ImageURLCliente,
            'AccesosRol' => array(),
            'AccesoRol' => array()
          )
      );

    return $data;
  }

  function ListarUsuarios($data = null) {
    $resultado = $this->mUsuario->ListarUsuarios($data);

    foreach ($resultado as $key => $value) {
      $resultado[$key]["IndicadorEstadoUsuario"] = ($value["EstadoUsuario"] == 0) ? false : true;
    }
    
    return $resultado;
  }

  function ObtenerTotalUsuariosActivos()
  {
    $resultado = $this->mUsuario->ObtenerTotalUsuariosActivos();
    return $resultado;
  }

  function ListarUsuariosPorSede($data)
  {
    $resultado = $this->mUsuario->ListarUsuariosPorSede($data);
    return $resultado;
  }

  function ValidarCantidadUsuarios()
  {
    $usuarios=$this->ListarUsuarios();
    if (count($usuarios) >= LICENCIA_CANTIDAD_USUARIO)
    {
      $texto = "usuarios";
      if(LICENCIA_CANTIDAD_USUARIO == 1)
      {
        $texto = "usuario";
      }
      return "La licencia solo permite el uso de ".LICENCIA_CANTIDAD_USUARIO." ".$texto.", revise su base de datos.";
    }
    else
    {
      return "";
    }
  }

  function ValidarNombreUsuario($data)
  {
    $nombre=$data["NombreUsuario"];
    if ($nombre == "")
    {
      return "Debe completar el Nombre";
    }
    else
    {
      return "";
    }
  }

  function ValidarAliasUsuario($data)
  {
    $nombre=$data["AliasUsuarioVenta"];
    if ($nombre == "")
    {
      return "Debe completar el Alias de Usuario";
    }
    else
    {
      return "";
    }
  }

  function ValidarClaveUsuario($data)
  {
    $clave=$data["ClaveUsuario"];
    if ($clave == "")
    {
      return "Debe completar la Clave";
    }
    else
    {
      return "";
    }
  }

  function ValidarConfirmarClaveUsuario($data)
  {
    $confirmarclave=$data["ConfirmarClaveUsuario"];

    if ($confirmarclave == "")
    {
      return "Debe completar la confirmación de clave";
    }
    else
    {
      return "";
    }
  }

  function ValidarRespuestaSeguridad($data)
  {
    $respuesta=$data["RespuestaSeguridad"];
    if ($respuesta == "")
    {
      return "Debe Ingresar la Respuesta";
    }
    else
    {
      return "";
    }
  }

  function ValidarConfirmarRespuestaSeguridad($data)
  {
    $confirmarrespuesta=$data["ConfirmarRespuestaSeguridad"];
    if ($confirmarrespuesta == "")
    {
      return "Debe completar la confirmación de respuesta";
    }
    else
    {
      return "";
    }
  }

  function ValidarUsuario($data)
  {
    $nombre = $this->ValidarNombreUsuario($data);
    $alias = $this->ValidarAliasUsuario($data);
    $clave = $this->ValidarClaveUsuario($data);
    $confirmarclave = $this->ValidarConfirmarClaveUsuario($data);
    $respuesta = $this->ValidarRespuestaSeguridad($data);
    $confirmarrespuesta = $this->ValidarConfirmarRespuestaSeguridad($data);

    if ($nombre != "")
    {
      return $nombre;
    }
    else if ($alias != "")
    {
      return $alias;
    }
    else if ($clave != "")
    {
      return $clave;
    }
    else if ($confirmarclave != "")
    {
      return $confirmarclave;
    }
    else if ( $data["ClaveUsuario"] != $data["ConfirmarClaveUsuario"])
    {
      return "Las claves no son iguales";
    }
    else if ($respuesta != "")
    {
      return $respuesta;
    }
    else if ($confirmarrespuesta != "")
    {
      return $confirmarrespuesta;
    }
    else if ($data["RespuestaSeguridad"] != $data["ConfirmarRespuestaSeguridad"])
    {
      return "Las respuestas de seguridad no son iguales";
    }
    else
    {
      return "";
    }
  }

  function ValidarDuplicadoDeNombreUsuarioParaInsertar($data)
  {
    $resultado = $this->mUsuario->ObtenerDuplicadoDeNombreUsuarioParaInsertar($data);
    if (Count($resultado)>0)
    {
      return "Este Nombre de Usuario ya fue registrado";
    }
    else
    {
      return "";
    }
  }

  function ValidarDuplicadoDeNombreUsuarioParaActualizar($data)
  {
    $resultado = $this->mUsuario->ObtenerDuplicadoDeNombreUsuarioParaActualizar($data);
    if (Count($resultado)>0)
    {
      return "Este Nombre de Usuario ya fue registrado";
    }
    else
    {
      return "";
    }
  }

  function ValidarDuplicadoDeAliasUsuarioVentaParaInsertar($data)
  {
    $resultado = $this->mUsuario->ObtenerDuplicadoDeAliasUsuarioVentaParaInsertar($data);
    if (Count($resultado)>0)
    {
      return "Este Alias de Usuario ya fue registrado";
    }
    else
    {
      return "";
    }
  }

  function ValidarDuplicadoDeAliasUsuarioVentaParaActualizar($data)
  {
    $resultado = $this->mUsuario->ObtenerDuplicadoDeAliasUsuarioVentaParaActualizar($data);
    if (Count($resultado)>0)
    {
      return "Este Alias de Usuario ya fue registrado";
    }
    else
    {
      return "";
    }
  }


  function InsertarUsuario($data) {

    $data["NombreUsuario"] = trim($data["NombreUsuario"]);
    $data["AliasUsuarioVenta"] = trim($data["AliasUsuarioVenta"]);
    $data["ClaveUsuario"] = trim($data["ClaveUsuario"]);
    $data["RespuestaSeguridad"] = trim($data["RespuestaSeguridad"]);
    $resultado1 = $this->ValidarUsuario($data);
    $resultado2 = $this->ValidarDuplicadoDeNombreUsuarioParaInsertar($data);
    $resultado3 = $this->ValidarDuplicadoDeAliasUsuarioVentaParaInsertar($data);
    $usuarios = $this->ValidarCantidadUsuarios();
    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($resultado2 != "")
    {
      return $resultado2;
    }
    else if ($resultado3 != "")
    {
      return $resultado3;
    }
    else if ($usuarios != "")
    {
      return $usuarios;
    }
    else
    {
      
      $data["IndicadorVistaPrecioMinimo"] = $data["IndicadorVistaPrecioMinimo"]  == true ? 1 : 0 ;
      $data["IndicadorExoneradoPrecioMinimo"] = $data["IndicadorExoneradoPrecioMinimo"]  == true ? 1 : 0 ;
      $data["IndicadorCrearProducto"] = $data["IndicadorCrearProducto"]  == true ? 1 : 0;
      $data["IndicadorEditarPrecioUnitarioVenta"] = $data["IndicadorEditarPrecioUnitarioVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoAnularComprobanteVenta"] = $data["IndicadorPermisoAnularComprobanteVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoEditarComprobanteVenta"] = $data["IndicadorPermisoEditarComprobanteVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoEliminarComprobanteVenta"] = $data["IndicadorPermisoEliminarComprobanteVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoCobranzaRapida"] = $data["IndicadorPermisoCobranzaRapida"]  == true ? 1 : 0;
      $data["IndicadorPermisoStockNegativo"] = $data["IndicadorPermisoStockNegativo"]  == true ? 1 : 0;

      $resultado=$this->mUsuario->InsertarUsuario($data);
      $data["IdUsuario"] = $resultado;

      $parametrocaja = $this->sConstanteSistema->ObtenerParametroCaja();
      if($parametrocaja == 1) {
        $cajas = $this->sAccesoCajaUsuario->AgregarAccesosCajaUsuario($data["Cajas"], $resultado);
        $turnos = $this->sAccesoTurnoUsuario->AgregarAccesosTurnoUsuario($data["Turnos"], $resultado);
        $data["Cajas"] = $cajas;
        $data["Turnos"] = $turnos;
      }
      
      return $data;
    }
  }

  function ActualizarUsuario($data)
  {
    $data["NombreUsuario"] = trim($data["NombreUsuario"]);
    $data["AliasUsuarioVenta"] = trim($data["AliasUsuarioVenta"]);
    $data["ClaveUsuario"] = trim($data["ClaveUsuario"]);
    $data["RespuestaSeguridad"] = trim($data["RespuestaSeguridad"]);

    $resultado1 = $this->ValidarUsuario($data);
    $resultado2 = $this->ValidarDuplicadoDeNombreUsuarioParaActualizar($data);
    $resultado3 = $this->ValidarDuplicadoDeAliasUsuarioVentaParaActualizar($data);
    if ($resultado1 != "")
    {
      return $resultado1;
    }
    else if ($resultado2 != "")
    {
      return $resultado2;
    }
    else if ($resultado3 != "")
    {
      return $resultado3;
    }
    else
    {
      $data["IndicadorVistaPrecioMinimo"] = $data["IndicadorVistaPrecioMinimo"]  == true ? 1 : 0;
      $data["IndicadorExoneradoPrecioMinimo"] = $data["IndicadorExoneradoPrecioMinimo"]  == true ? 1 : 0;
      $data["IndicadorCrearProducto"] = $data["IndicadorCrearProducto"]  == true ? 1 : 0;
      $data["IndicadorEditarPrecioUnitarioVenta"] = $data["IndicadorEditarPrecioUnitarioVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoAnularComprobanteVenta"] = $data["IndicadorPermisoAnularComprobanteVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoEditarComprobanteVenta"] = $data["IndicadorPermisoEditarComprobanteVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoEliminarComprobanteVenta"] = $data["IndicadorPermisoEliminarComprobanteVenta"]  == true ? 1 : 0;
      $data["IndicadorPermisoCobranzaRapida"] = $data["IndicadorPermisoCobranzaRapida"]  == true ? 1 : 0;
      $data["IndicadorPermisoStockNegativo"] = $data["IndicadorPermisoStockNegativo"]  == true ? 1 : 0;
      
      $resultado = $this->mUsuario->ActualizarUsuario($data);
      $almacenes = $this->AgregarAccesosUsuarioAlmacen($data);
      $resultado["Almacenes"] = $almacenes;

      $parametrocaja = $this->sConstanteSistema->ObtenerParametroCaja();
      if($parametrocaja == 1)
      {
        $cajas = $this->sAccesoCajaUsuario->AgregarAccesosCajaUsuario($data["Cajas"], $data["IdUsuario"]);
        $turnos = $this->sAccesoTurnoUsuario->AgregarAccesosTurnoUsuario($data["Turnos"], $data["IdUsuario"]);
        $resultado["Cajas"] = $cajas;
        $resultado["Turnos"] = $turnos;
      }

      return $resultado;
    }
  }

  function BorrarUsuario($data)
  {
      $resultado = $this->mUsuario->BorrarUsuario($data);
      $this->sAccesoUsuarioAlmacen->BorrarAccesoUsuarioAlmacenPorUsuario($data);
      $parametrocaja = $this->sConstanteSistema->ObtenerParametroCaja();
      if($parametrocaja == 1)
      {
        $this->sAccesoCajaUsuario->BorrarAccesosCajaUsuarioPorUsuario($data);
      $this->sAccesoTurnoUsuario->BorrarAccesosTurnoUsuarioPorUsuario($data);
      }
      
      return "";
  }

  function BloquearUsuario($data)
  {
      $resultado = $this->mUsuario->BloquearUsuario($data);
      return "";
  }

  function ActivarUsuario($data)
  {
      $resultado = $this->mUsuario->ActivarUsuario($data);
      return "";
  }

  function AgregarAccesosUsuarioAlmacen($data)
  {
    foreach ($data["Almacenes"] as $key => $value) {
      $value["IdUsuario"] = $data["IdUsuario"];
      $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
      if($seleccionado == false)
      {
        $value["IndicadorEstado"] = "I";
      }
      else {
        $value["IndicadorEstado"] = "A";
      }
      $response = $this->sAccesoUsuarioAlmacen->AgregarAccesoUsuarioAlmacen($value);
      $data["Almacenes"][$key]["IdAccesoUsuarioAlmacen"] = $response["IdAccesoUsuarioAlmacen"];
      $data["Almacenes"][$key]["Seleccionado"] = $seleccionado;
    }
    return $data["Almacenes"];
  }

  function InsertarAccesoUsuarioAlmacen($data)
  {
    foreach ($data["Almacenes"] as $key=>$value) {
      $value["IdUsuario"] = $data["IdUsuario"];
      $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
      if($seleccionado == false)
      {
        $value["IndicadorEstado"] = "I";
      }
      else {
        $value["IndicadorEstado"] = "A";
      }
      $resultado = $this->sAccesoUsuarioAlmacen->InsertarAccesoUsuarioAlmacen($value);
      $data["Almacenes"][$key]["IdAccesoUsuarioAlmacen"] = $resultado;
    }

    // $this->ActualizarAccesoUsuarioAlmacen($data);
    return $data["Almacenes"];
  }

  function ActualizarAccesoUsuarioAlmacen($data)
  {
    foreach ($data["Almacenes"] as $key=>$value) {
      $seleccionado = filter_var($value["Seleccionado"], FILTER_VALIDATE_BOOLEAN);
      if($seleccionado == false)
      {
        $value["IndicadorEstado"] = "I";
      }
      else {
        $value["IndicadorEstado"] = "A";
      }
      $resultado = $this->sAccesoUsuarioAlmacen->ActualizarAccesoUsuarioAlmacen($value);
    }
    return "";
  }

  // function BorrarAccesoUsuarioAlmacen($data)
  // {
  //   foreach ($data["Almacenes"] as $key=>$value) {
  //     $resultado = $this->sAccesoUsuarioAlmacen->BorrarAccesoUsuarioAlmacen($value);
  //   }
  //   return "";
  // }

  function ActualizarTemaUsuario($data)
  {
    $resultado = $this->mUsuario->ActualizarUsuario($data);
    return $resultado;
  }

  //#PARA CAJA
  function ObtenerTurnoActualUsuario($data)
  {
    $hoy = $this->Base->ObtenerFechaServidor("H:i:s");
    $response = $this->sAccesoTurnoUsuario->ObtenerTurnosPorIdUsuario($data);
    $turno = null;
    if(count($response) > 0)
    {
      foreach ($response as $key => $value) {
        $from = $value["HoraInicioHolgura"];//$value["HoraInicio"];
        $to = $value["HoraFinHolgura"];//$value["HoraFinal"];
        if(hourIsBetween($from, $to, $hoy))
        {
          $turno = $value;
        }
      }
    }
    return $turno;
  }

  function ValidarTurnoUsuario($data)
  {
    $turno = $this->sUsuario->ObtenerTurnoActualUsuario($data);
    if($turno != null)
    {
      $sesionUsuario = $this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC);
      $sesionUsuario['Turno'] = $turno;
      $usuario = array("Usuario_".LICENCIA_EMPRESA_RUC => $sesionUsuario);
      $this->session->set_userdata($usuario);
      return $turno;
    }
    else
    {
      return "Su turno no se encuentra disponible en esta hora.";
    }
  }  

  function ValidarUsuarioParaVentaJSON($data)
  {
    $resultado = $this->mUsuario->ObtenerDuplicadoDeNombreUsuarioParaInsertar($data);
    if(count($resultado) > 0)
    {
      return $resultado[0];
    }
    else
    {
      return array();
    }
  }

  function ObtenerUsuarioPorAliasUsuarioVenta($data)
  {
    $resultado = $this->mUsuario->ObtenerUsuarioPorAliasUsuarioVenta($data);
    return $resultado;
  }

  
  function CrearJSONUsuariosTodos() {
    $url = DIR_ROOT_ASSETS . '/data/usuario/usuarios.json';
    $data_json = $this->PrepararDataJSONUsuario();
    $resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
    return $resultado;
  }

  function PrepararDataJSONUsuario() {
    $response = array();
    $usuarios = $this->ListarUsuarios();

    foreach ($usuarios as $key => $value) {
      $nueva_fila = array(
        "IdUsuario" => $value["IdUsuario"],
        "IdPersona" => $value["IdPersona"],
        "NombreUsuario" => $value["NombreUsuario"],
        "AliasUsuarioVenta" => $value["AliasUsuarioVenta"]        
      );

      array_push($response, $nueva_fila);
    }

    return $response;
  }

  function ObtenerUsuario($data) {    
      $resultado = $this->mUsuario->ObtenerUsuario($data);
      return $resultado;
  }
}
