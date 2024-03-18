<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sProducto extends MY_Service {

    public $Producto = array();

    public function __construct()
    {
          parent::__construct();
          $this->load->database();
          $this->load->model("Base");
          $this->load->library('shared');
          $this->load->library('mapper');
          $this->load->model('Catalogo/mProducto');
          $this->load->service('Configuracion/General/sParametroSistema');
          $this->load->service('Configuracion/General/sConstanteSistema');
          $this->Producto = $this->mProducto->Producto;
    }

    function ValidarNombreProducto($data)
    {
      $nombre=$data["NombreProducto"];
      $cadena = strstr($nombre, '"');
      $comillasimple = (int) substr_count($nombre,"'");
      $comillasimple = $comillasimple%2;
      if ($nombre == "")
      {
        return "Debe ingresar el nombre del Producto";
      }
      /*elseif ($cadena) {
        return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples.";
      }
      elseif ($comillasimple != 0) {
        return "En Nombre del Producto no se puede utilizar comillas simples en cantidad impar."; 
      }*/
      else
      {
        return "";
      }
    }

    function ValidarNombreLargoProducto($data)
    {
      $nombre=$data["NombreLargoProducto"];
      $cadena = strstr($nombre, '"');
      $comillasimple = (int) substr_count($nombre,"'");
      $comillasimple = $comillasimple%2;
      if ($nombre == "")
      {
        return "Debe ingresar el nombre largo del Producto";
      }
      /*elseif ($cadena) {
        return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples.";
      }
      elseif ($comillasimple != 0) {
        return "En Nombre Largo del Producto no se puede utilizar comillas simples en cantidad impar."; 
      }*/
      else
      {
        return "";
      }
    }

    function InsertarProducto($data, $extra = false)
    {
      $data["NombreProducto"] = trim($data["NombreProducto"]);
      $resultado = $this->ValidarNombreProducto($data);

      $resultado2 = "";
      if($extra)
      {
        $parametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
        $resultado2 = ($parametroRubroRepuesto == 1) ? $this->ValidarNombreLargoProducto($data) : "";
      }

      if ($resultado != "")
      {
        return $resultado;
      }
      elseif ($resultado2 != "")
      {
        return $resultado2;
      }
      else
      {
        $producto = $this->mProducto->InsertarProducto($data);
        $data["IdProducto"] = $producto;
        return $data;
      }
    }

    function ActualizarProducto($data, $extra = false)
    {
      $data["NombreProducto"] = trim($data["NombreProducto"]);
      $resultado = $this->ValidarNombreProducto($data);
      
      $resultado2 = "";
      if($extra)
      {
        $parametroRubroRepuesto = $this->sConstanteSistema->ObtenerParametroRubroRepuesto();
        $resultado2 = ($parametroRubroRepuesto == 1) ? $this->ValidarNombreLargoProducto($data) : "";
      }

      if ($resultado != "")
      {
        return $resultado;
      }
      elseif ($resultado2 != "")
      {
        return $resultado2;
      }
      else
      {
        $this->mProducto->ActualizarProducto($data);
        return $data;
      }
    }

    function ActualizarProductoAlterno($data)
    {
      $resultado = $this->mProducto->ActualizarProducto($data);
      return $resultado;
    }

    function ValidarExistenciaProductoEnDetalleComprobanteVenta($data)
    {
      $resultado = $this->mProducto->ConsultarProductoEnDetalleComprobanteVenta($data);
      $contador = COUNT($resultado);
      if ($contador > 0)
      {
        return "No se puede eliminar porque tiene registros en Ventas";
      }
      else
      {
        return "";
      }
    }

    function ValidarExistenciaProductoEnDetalleComprobanteCompra($data)
    {
      $resultado = $this->mProducto->ConsultarProductoEnDetalleComprobanteCompra($data);
      $contador = COUNT($resultado);
      if ($contador > 0)
      {
        return "No se puede eliminar porque tiene registros en Compra";
      }
      else
      {
        return "";
      }
    }

    function ValidarExistenciaProductoEnInventarioInicial($data)
    {
      $resultado = $this->mProducto->ConsultarProductoEnInventarioInicial($data);
      $contador = COUNT($resultado);
      if ($contador > 0)
      {
        return "No se puede eliminar porque tiene registros en Inventario Inicial";
      }
      else
      {
        return "";
      }
    }

    function BorrarProducto($data)
    {
      $this->mProducto->BorrarProducto($data);
      return "";
    }

    function ObtenerProductoPorNombreONombreLargo($data) {
      $resultado=$this->mProducto->ObtenerProductoPorNombreONombreLargo($data);
      return $resultado;
    }
}
