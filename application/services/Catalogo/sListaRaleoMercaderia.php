<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sListaRaleoMercaderia extends MY_Service {

  public $ListaRaleoMercaderia = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Catalogo/mListaRaleoMercaderia');
    $this->load->service("Configuracion/Catalogo/sTipoListaRaleo");
    $this->load->service("Configuracion/Catalogo/sFamiliaProducto");
    $this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
    $this->load->service("Configuracion/Catalogo/sMarca");
    $this->load->service("Configuracion/Catalogo/sModelo");
    $this->load->service("Configuracion/Catalogo/sLineaProducto");

    $this->ListaRaleoMercaderia = $this->mListaRaleoMercaderia->ListaRaleoMercaderia;
  }

  function Inicializar()
  {
    $this->ListaRaleoMercaderia["IdFamiliaProducto"] = "";
    $this->ListaRaleoMercaderia["IdSubFamiliaProducto"] = "";
    $this->ListaRaleoMercaderia["IdLineaProducto"] = "";
    $this->ListaRaleoMercaderia["IdTipoListaRaleo"] = "";
    $this->ListaRaleoMercaderia["IdMarca"] = "";
    $this->ListaRaleoMercaderia["IdModelo"] = "";
    $this->ListaRaleoMercaderia["Descripcion"] = "";

    $this->ListaRaleoMercaderia["TiposListaRaleo"] = $this->sTipoListaRaleo->ListarTiposListaRaleo();
    $this->ListaRaleoMercaderia["FamiliasProducto"] = $this->sFamiliaProducto->ListarFamiliasProducto();
    $this->ListaRaleoMercaderia["SubFamiliasProducto"] = $this->sSubFamiliaProducto->ListarTodosSubFamiliasProducto();
    $this->ListaRaleoMercaderia["Modelos"] = $this->sModelo->ListarTodosModelos();
    $this->ListaRaleoMercaderia["Marcas"] = $this->sMarca->ListarMarcas();
    $this->ListaRaleoMercaderia["LineasProducto"] = $this->sLineaProducto->ListarLineasProducto();
    $this->ListaRaleoMercaderia["DetallesListaRaleo"] = array();
    $this->ListaRaleoMercaderia["CopiaIdProductosDetalle"] = array();

    return $this->ListaRaleoMercaderia;
  }

  function ConsultarListasRaleoMercaderia($data)
  {
    $resultado = $this->mListaRaleoMercaderia->ConsultarListasRaleoMercaderia($data);
    return $resultado;
  }

  function ConsultarListasRaleoMercaderiaPorIdProducto($data)
  {
    $resultado = $this->mListaRaleoMercaderia->ConsultarListasRaleoMercaderiaPorIdProducto($data);
    $response = array();
    if(count($resultado) > 0)
    {
      foreach ($resultado as $key => $value) {
        $lista["NombreTipoListaRaleo"] = $value["NombreTipoListaRaleo"];
        $lista["Precio"] = $value["Precio"];
        array_push($response, $lista);
      }

    }
    return $response;
  }

  function ActualizarListaRaleoMercaderias($data) {

    foreach ($data["DetallesListaRaleo"] as $key => $value) {
      $value["IdTipoListaRaleo"] = $data["IdTipoListaRaleo"];
      if(is_string($value["Precio"])){$value["Precio"] = str_replace(',',"",$value["Precio"]);}

      $response = $this->mListaRaleoMercaderia->ObtenerListaRaleoMercaderiaPorProductoYTipoListaRaleo($value);
      if(count($response) > 0)
      {
        $value["IdListaRaleoMercaderia"] = $response[0]["IdListaRaleoMercaderia"];
        $resultado = $this->ActualizarListaRaleoMercaderia($value);
      }
      else
      {
        $value["IdListaRaleoMercaderia"] = "";
        $resultado = $this->InsertarListaRaleoMercaderia($value);
      }
    }
    return $data;
  }

  function InsertarListaRaleoMercaderia($data) {
    $resultado = $this->mListaRaleoMercaderia->InsertarListaRaleoMercaderia($data);
    return $resultado;
  }

  function ActualizarListaRaleoMercaderia($data) {
    $resultado = $this->mListaRaleoMercaderia->ActualizarListaRaleoMercaderia($data);
    return $resultado;
  }

}
