<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class sListaPrecios extends MY_Service
{

  public $ListaPrecios = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->helper("date");
    $this->load->model('Reporte/mReporteBasePDF');
    $this->load->model('Reporte/mReporteBaseEXCEL');
    $this->load->model('Reporte/mReporteBasePANTALLA');
    $this->load->model('Reporte/mDescargarArchivo');
    $this->load->service("Configuracion/General/sEmpresa");
    $this->load->service('Configuracion/General/sParametroSistema');

    $this->load->service("Configuracion/Catalogo/sFamiliaProducto");
    $this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
    $this->load->service("Configuracion/Catalogo/sLineaProducto");
    $this->load->service("Configuracion/Catalogo/sMarca");
    $this->load->service("Configuracion/Catalogo/sModelo");
    $this->load->service("Configuracion/General/sSede");
  }

  function Cargar()
  {
    $Buscador["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();
    $Buscador["TextoFiltro"] = "";
    $Buscador["IdFamilia"] = "";
    $Buscador["IdSubFamilia"] = "";
    $Buscador["IdMarca"] = "";
    $Buscador["IdModelo"] = "";
    $Buscador["IdLineaProducto"] = "";
    $Buscador["Sedes"]=$this->sSede->ListarSedes();
    $Buscador["Familias"] = $this->sFamiliaProducto->ListarFamiliasProducto();
    $Buscador["SubFamilias"] = $this->sSubFamiliaProducto->ListarTodosSubFamiliasProducto();
    $Buscador["LineasProducto"] = $this->sLineaProducto->ListarLineasProducto();
    $Buscador["Marcas"] = $this->sMarca->ListarMarcas();
    $Buscador["Modelos"] = $this->sModelo->ListarTodosModelos();

    $Buscador["NombreArchivoReporte"] = $this->ObtenerNombreArchivoReporte();
    $Buscador["NombreArchivoJasper"] = $this->ObtenerNombreArchivoJasper();

    $Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
    $Buscador["IdAsignacionSede"]="%";
    
    return array("Buscador" => $Buscador);
  }

  function GenerarReportePDF($data)
  {
    $resultado = $this->mReporteBasePDF->ReporteBasePDF($data);
    return $resultado;
  }

  function GenerarReporteEXCEL($data)
  {
    $resultado = $this->mReporteBaseEXCEL->ReporteBaseEXCEL($data);
    return $resultado;
  }

  function GenerarReportePANTALLA($data)
  {
    $resultado = $this->mReporteBasePANTALLA->ReporteBasePANTALLA($data);
    return $resultado;
  }

  function DescargarArchivo($data)
  {
    $resultado = $this->mDescargarArchivo->DescargarArchivo($data);
    return $resultado;
  }

  function obtener_primer_dia_mes()
  {
    $resultado = $this->shared->obtener_primer_dia_mes();
    return $resultado;
  }

  function obtener_ultimo_dia_mes()
  {
    $resultado = $this->shared->obtener_ultimo_dia_mes();
    return $resultado;
  }

  private function ObtenerRucEmpresa()
  {
    $data["IdEmpresa"] = $this->sEmpresa->ObtenerIdEmpresa();
    $Empresas = $this->sEmpresa->ListarEmpresas($data);
    $ruc = $Empresas[0]['CodigoEmpresa'];
    return $ruc;
  }

  function ObtenerNombreArchivoReporte()
  {
    $rucempresa = $this->ObtenerRucEmpresa();

    $data['IdParametroSistema'] = NOMBRE_ARCHIVO_REPORTE_LISTA_PRECIOS;

    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $rucempresa . '-' . $ValorParametroSistema;
    }
  }

  function ObtenerNombreArchivoJasper()
  {
    $data['IdParametroSistema'] = NOMBRE_ARCHIVO_JASPER_LISTA_PRECIOS;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    } else {
      $ValorParametroSistema = $resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
}
