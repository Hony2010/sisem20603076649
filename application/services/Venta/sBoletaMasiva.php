<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Venta\sComprobanteVenta.php');

class sBoletaMasiva extends sComprobanteVenta {

  public function __construct()
  {
    parent::__construct();
  }

  function CargarBoleta()
  {
    $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_BOLETA;
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    $resultado = parent::Cargar($parametro);
    $resultado['RutaPlantillaExcel'] = base_url().$this->ObtenerNombrePlantillaBoletaMasiva();
    $resultado["TipoCambio"] = 3.21;
    $resultado["IGV"] = 3.21;
    return $resultado;
  }

  function ObtenerNombrePlantillaBoletaMasiva()
  {
    $data['IdParametroSistema']= NOMBRE_PLANTILLA_EXCEL_BOLETA_MASIVA;
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
