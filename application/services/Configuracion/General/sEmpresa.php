<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sEmpresa extends MY_Service {

  public $Empresa = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->model('Configuracion/General/mEmpresa');
    $this->load->service('Configuracion/General/sParametroSistema');
    $this->load->service("FacturacionElectronica/sComprobanteElectronico");
    $this->load->service("Configuracion/General/sConstanteSistema");
    $this->Empresa = $this->mEmpresa->Empresa;
  }

  function ListarEmpresas($data) {
    $resultado = $this->mEmpresa->ListarEmpresas($data);
    return $resultado;
  }

  function ObtenerDatosEmpresa() {
    $input["IdEmpresa"]=LICENCIA_EMPRESA_ID;
    $resultado = $this->ListarEmpresas($input)[0];
    return $resultado;
  }

  function ValidarCodigoEmpresa($data) {
    $codigo = $data['CodigoEmpresa'];
    if ($codigo == "")
    {
      return "Debe ingrsar el RUC de la empresa";
    }
    else if (!is_numeric($codigo))
    {
      return "Debe ingresar un valor numeríco en el RUC";
    }
    else if (strlen($codigo) != 11)
    {
      return "El RUC debe tener 11 caracteres";
    }
    else
    {
      return "";
    }
  }

  function ValidarRazonSocial($data) {
    $razon = $data['RazonSocial'];
    if ($razon == "")
    {
      return "Debe completar la razon social de la empresa";
    }
    else
    {
      return "";
    }
  }

  function ValidarUbigeo($data) {
    $ubigeo = $data['Ubigeo'];
    if ($ubigeo == "")
    {
      return "Debe completar el ubigeo de la empresa";
    }
    else
    {
      return "";
    }
  }

  function ValidarNombreComercial($data) {
    if ($data['NombreComercial'] == "")
    {
      return "Debe completar el nombre comercial de la empresa";
    }
    else
    {
      return "";
    }
  }

  function ValidarFechaCertificadoDigital($data) {
    $fechaInicioCertificado = $data['FechaInicioCertificadoDigital'];
    $fechaFinCertificado = $data['FechaFinCertificadoDigital'];
    if ($fechaFinCertificado < $fechaInicioCertificado)
    {
      return "La Fecha Fin del Certificado no puede ser menor a la Fecha de Inicio del Certificado.";
    }
    else
    {
      return "";
    }
  }

  function ValidarEmpresa($data) {
    $resultado1= $this->ValidarCodigoEmpresa($data);
    $resultado2= $this->ValidarRazonSocial($data);
    $resultado3= $this->ValidarUbigeo($data);
    $facturacionElectronica = $this->sConstanteSistema->ObtenerParametroFacturacionElectronica();
    $resultado4 = ($facturacionElectronica == 1) ? $this->ValidarFechaCertificadoDigital($data) : "";
    $resultado5="";
    if ($resultado1!="")
    {
      return $resultado1;
    }
    else if ($resultado2 !="")
    {
      return $resultado2;
    }
    elseif ($resultado3 !="")
    {
      return $resultado3;
    }
    elseif ($resultado4 !="")
    {
      return $resultado4;
    }
    elseif ($resultado5 !="")
    {
      return $resultado5;
    }
    else
    {
      return "";
    }
  }

  function InsertarEmpresa($data) {
    $data["CodigoEmpresa"] = trim($data["CodigoEmpresa"]);
    $data['RazonSocial'] = trim($data['RazonSocial']);
    $data['Ubigeo'] = trim($data['Ubigeo']);
    $data['NombreComercial'] = trim($data['NombreComercial']);
    $data['FechaInicioCertificadoDigital'] = convertToDate($data['FechaInicioCertificadoDigital']);
    $data['FechaFinCertificadoDigital'] = convertToDate($data['FechaFinCertificadoDigital']);
    $resultado = $this->ValidarEmpresa($data);

    if ($resultado!="") {
      return $resultado;
    }
    else {
      $resultado = $this->mEmpresa->InsertarEmpresa($data);
      return $resultado;
    }
  }

  function ActualizarEmpresa($data)
  {
    $data["CodigoEmpresa"] = trim($data["CodigoEmpresa"]);
    $data['RazonSocial'] = trim($data['RazonSocial']);
    $data['Ubigeo'] = trim($data['Ubigeo']);
    $data['NombreComercial'] = trim($data['NombreComercial']);
    $data['FechaInicioCertificadoDigital'] = convertToDate($data['FechaInicioCertificadoDigital']);
    $data['FechaFinCertificadoDigital'] = convertToDate($data['FechaFinCertificadoDigital']);
    $resultado = $this->ValidarEmpresa($data);

    if ($resultado!="")
    {
      return $resultado;
    }
    else
    {
      $resultado = $this->mEmpresa->ActualizarEmpresa($data);

      $this->sComprobanteElectronico->GenerarKeyCertificadoDigital();
      return "";
    }
  }

  function ObtenerIdEmpresa() {
    $data['IdParametroSistema']= ID_EMPRESA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado)) {
      return $resultado;
    }
    else {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerUrlCarpetaImagenes() {
    $data['IdParametroSistema']= ID_URL_CARPETA_IMAGENES_EMPRESA;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function ObtenerRutaCarpetaCertificado()
  {
    $data['IdParametroSistema']= RUTA_CERTIFICADO_DIGITAL;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
    if (is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }
}
