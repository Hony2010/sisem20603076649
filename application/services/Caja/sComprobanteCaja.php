<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sComprobanteCaja extends MY_Service {

  public $ComprobanteCaja = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->library('shared');
    $this->load->library('sesionusuario');
    $this->load->library('mapper');
    $this->load->library('herencia');
    $this->load->library('reporter');
    $this->load->library('imprimir');
    $this->load->helper("date");
    $this->load->model("Base");
    $this->load->model('Caja/mComprobanteCaja');
    $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
    $this->load->service('Seguridad/sAccesoCajaUsuario');
    $this->load->service("Seguridad/sUsuario");

    $this->ComprobanteCaja = $this->mComprobanteCaja->ComprobanteCaja;
  }

  function CargarComprobanteCaja($parametro)
  {
    $hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

    $this->ComprobanteCaja["IdCaja"] = "";
    $this->ComprobanteCaja["IdComprobanteCompra"] = "";
    $this->ComprobanteCaja["IdComprobanteVenta"] = "";
    $this->ComprobanteCaja["IdMoneda"] = "";
    $this->ComprobanteCaja["IdPersona"] = "";
    $this->ComprobanteCaja["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $this->ComprobanteCaja["ConceptoCaja"] = "";
    $this->ComprobanteCaja["NumeroSerie"] = "";
    $this->ComprobanteCaja["NumeroDocumento"] = "";
    $this->ComprobanteCaja["FechaComprobante"] = $hoy;
    $this->ComprobanteCaja["FechaTurno"] = "";//$hoy;
    $this->ComprobanteCaja["MontoComprobante"] = "";
    $this->ComprobanteCaja["IndicadorTipoComprobante"] = "";
    $this->ComprobanteCaja["Observacion"] = "";
    // $this->ComprobanteCaja["IdTipoDocumento"] = $parametro['IdTipoDocumento'];
    
    $this->ComprobanteCaja["AliasUsuarioVenta"] = $this->sesionusuario->obtener_alias_usuario();
    $this->ComprobanteCaja["UsuarioCobrador"] = $this->sesionusuario->obtener_alias_usuario();

    $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
    $this->ComprobanteCaja["SerieDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : "";
    $this->ComprobanteCaja["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : "";
    $this->ComprobanteCaja["IdTipoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdTipoDocumento"] : "";

    $Cajas = $this->sAccesoCajaUsuario->ListarAccesosCajaUsuarioPorIdUsuario(); //Se listaran las cajas por usuario
    $this->ComprobanteCaja["IdCaja"] = (count($Cajas) > 0) ? $Cajas[0]["IdCaja"] : "";
    $this->ComprobanteCaja["NombreCaja"] = (count($Cajas) > 0) ? $Cajas[0]["NombreCaja"] : "";

    $dataVendedor["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();
    $Cobradores = $this->sUsuario->ListarUsuariosPorSede($dataVendedor);

    $data = array(
      'SeriesDocumento' => $SeriesDocumento,
      'Cajas' => $Cajas,
      'Cobradores' => $Cobradores
    );

    $resultado = array_merge($this->ComprobanteCaja, $data);

    return $resultado;
  }

  /**CONSULTAS SOBRE COMPROBANTE DE CAJA */
  function ConsultarComprobanteCajaPorId($data)
  {
      $resultado = $this->mComprobanteCaja->ConsultarComprobanteCajaPorId($data);
      $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
      foreach ($resultado as $key => $item) {
        $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
        $parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
        $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
        $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      }

      return $resultado;
  }

  function ConsultarComprobantesCaja($data,$numeropagina,$numerofilasporpagina)
  {
      $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
      $resultado = $this->mComprobanteCaja->ConsultarComprobantesCaja($data,$numerofilainicio,$numerofilasporpagina);
      $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
      foreach ($resultado as $key => $item) {
        $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
        // $parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
        $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
        $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
        $resultado[$key]["FechaComprobante"] = convertirFechaES($item["FechaComprobante"]);
      }

      return $resultado;
  }

  function ObtenerNumeroFilasPorPagina()
  {
    $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_COMPROBANTEVENTA;
    $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
    $numerofilasporpagina=$parametro->ValorParametroSistema;
    return $numerofilasporpagina;
  }

  function ObtenerNumeroTotalComprobantesCaja($data)
  {
    $resultado = $this->mComprobanteCaja->ObtenerNumeroTotalComprobantesCaja($data);
    return $resultado;
  }
  /**FIN DE CONSULTAS */
  
  /**CONSULTAS PARA COBRANZA */
  function ConsultarCobranzasCliente($data,$numeropagina,$numerofilasporpagina)
  {
    $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mComprobanteCaja->ConsultarCobranzasCliente($data,$numerofilainicio,$numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      // $parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $resultado[$key]["FechaComprobante"] = convertirFechaES($item["FechaComprobante"]);
    }

    return $resultado;
  }

  function ObtenerNumeroTotalCobranzasCliente($data)
  {
    $resultado = $this->mComprobanteCaja->ObtenerNumeroTotalCobranzasCliente($data);
    return $resultado;
  }
  /**FIN CONSULTAS PARA COBRANZA */

  /**CONSULTAS PARA PAGANZA */
  function ConsultarPagosProveedor($data,$numeropagina,$numerofilasporpagina)
  {
    $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
    $resultado = $this->mComprobanteCaja->ConsultarPagosProveedor($data,$numerofilainicio,$numerofilasporpagina);
    $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
    foreach ($resultado as $key => $item) {
      $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
      // $parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
      $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
      $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;
      $resultado[$key]["FechaComprobante"] = convertirFechaES($item["FechaComprobante"]);
    }

    return $resultado;
  }

  function ObtenerNumeroTotalPagosProveedor($data)
  {
    $resultado = $this->mComprobanteCaja->ObtenerNumeroTotalPagosProveedor($data);
    return $resultado;
  }
  /**FIN CONSULTAS PARA PAGANZA */

  function ListarComprobantesCaja()
  {
    $resultado = $this->mComprobanteCaja->ListarComprobantesCaja();

    return $resultado;
  }

  function ObtenerFechaComprobanteMinimo()
  {
    $data['IdParametroSistema']= ID_PARAMETRO_FECHA_EMISION_MINIMO;
    $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);

    if(is_string($resultado))
    {
      return $resultado;
    }
    else
    {
      $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
      return $ValorParametroSistema;
    }
  }

  function InsertarComprobanteCaja($data)
  {
    try {
      $resultadoValidacion = "";

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];

        $resultado = $this->mComprobanteCaja->InsertarComprobanteCaja($data);

        if(strlen($data["IdComprobanteCaja"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
          $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $UltimoDocumento=$this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
          $input = $data;
          $input["NumeroDocumento"] = $UltimoDocumento;
          $resultadoValidacionCorrelativo = $this->ValidarCorrelativoDocumento($input);
          if ($resultadoValidacionCorrelativo!="") return $resultadoValidacionCorrelativo;
          $resultado["NumeroDocumento"] = $UltimoDocumento;

          $this->ActualizarSerieDocumentoComprobanteCaja($resultado);
          $resultado["NumeroDocumento"] =str_pad($UltimoDocumento, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        }
        else {
          $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);

          if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
            $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
            $input["UltimoDocumento"] = $data["NumeroDocumento"];
            $input["SerieDocumento"] = $data["SerieDocumento"];
            $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
            $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
          }
          //$resultado["NumeroDocumento"] =str_pad($data["NumeroDocumento"], CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
        }
        // $resultado["FechaComprobante"] =convertirFechaES($resultado["FechaComprobante"]);
        return $resultado;
      }
      else
      {
        $resultado = nl2br($resultadoValidacion); //throw new Exception(nl2br($resultadoValidacion));
        return $resultado;
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function ActualizarComprobanteCaja($data)
  {
    try {
      $data["FechaComprobante"]=$data["FechaComprobante"];
      $resultadoValidacion = "";

      if(!$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC))
      {
        return "Usted a cerrado sesión previamente, se necesita abrir la sesión para continuar con la operación.";
      }
      else if($resultadoValidacion == "")
      {
        $data["MontoComprobante"] = (is_string($data["MontoComprobante"])) ? str_replace(',',"",$data["MontoComprobante"]) : $data["MontoComprobante"];
        
        $resultado=$this->mComprobanteCaja->ActualizarComprobanteCaja($data);

        $resultadoCorrelativo = $this->sCorrelativoDocumento->ObtenerNuevoCorrelativoDocumento($data);
        if ($resultadoCorrelativo->UltimoDocumento < $data["NumeroDocumento"]) {
          $input["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
          $input["UltimoDocumento"] = $data["NumeroDocumento"];
          $input["SerieDocumento"] = $data["SerieDocumento"];
          $input["IdTipoDocumento"] = $data["IdTipoDocumento"];
          $this->sCorrelativoDocumento->ActualizarCorrelativoDocumento($input);
        }
        // $resultado["FechaComprobante"] =convertirFechaES($resultado["FechaComprobante"]);
        return $resultado;
      }
      else
      {
        throw new Exception(nl2br($resultadoValidacion));
      }
    }
    catch (Exception $e) {
      throw new Exception($e->getMessage(),$e->getCode(),$e);
    }
  }

  function BorrarComprobanteCaja($data) {
    $resultado =$this->mComprobanteCaja->BorrarComprobanteCaja($data);
    return $resultado;
  }

  function AnularComprobanteCaja($data) {
    $data["IndicadorEstado"] = ESTADO_DOCUMENTO_ANULADO;
    $resultado =$this->mComprobanteCaja->ActualizarComprobanteCaja($data);
    return $resultado;
  }

  function ActualizarEstadoComprobanteCaja($data)
  {
      $resultado=$this->mComprobanteCaja->ActualizarComprobanteCaja($data);
      return $resultado;
  }

  function ActualizarSerieDocumentoComprobanteCaja($data)
  {
      $this->mComprobanteCaja->ActualizarComprobanteCaja($data);
      return "";
  }

  function ObtenerComprobanteCaja($data)
  {
    $output = $this->mComprobanteCaja->ObtenerComprobanteCaja($data);
    $resultado=$output[0];

    return $resultado;
  }
  
  function ValidarCorrelativoDocumento($data)
  {
    $resultado="";

    if(strlen($data["IdComprobanteCaja"]) == 0 && strlen($data["NumeroDocumento"]) == 0) {
      return $resultado;
    }

    if(strlen($data["NumeroDocumento"]) > 0 && !is_numeric($data["NumeroDocumento"]) ) {
        $resultado = $resultado."El numero de documento debe ser mayor a cero y numérico."."\n";
    }
    else
    {
      $output = $this->mComprobanteCaja->ObtenerComprobanteCaja($data);

      if (count($output)>0) //existe y es modificacion
      {
        $resultado2 = $output[0];
        if($resultado2["IdTipoDocumento"] == $data["IdTipoDocumento"] && $resultado2["SerieDocumento"]==$data["SerieDocumento"]  && $resultado2["NumeroDocumento"]==$data["NumeroDocumento"]  && $resultado2["FechaComprobante"]==$data["FechaComprobante"])
        {
          //$resultado = $resultado."NO hay cambios \n";
          return $resultado;
        }
      }
      else
      {
        $resultado3 = $this->mComprobanteCaja->ObtenerComprobanteCajaPorSerieDocumento($data);
        if($resultado3!=null)
        {
          $resultado = $resultado."El número de documento ya existe en otro comprobante de venta"."\n";
          return $resultado;
        }
      }
    }

    $objeto1=$this->mComprobanteCaja->ObtenerFechaMayor($data);
    $objeto2=$this->mComprobanteCaja->ObtenerFechaMenor($data);
    $fechamayor = $objeto1->FechaComprobanteMayor;
    $fechamenor = $objeto2->FechaComprobanteMenor;

    if(strlen($fechamayor) != 0 && strlen($fechamenor) != 0)
    {
      if(!($data["FechaComprobante"]>=$fechamenor && $data["FechaComprobante"]<=$fechamayor))
        $resultado = $resultado."La fecha emisión debe ser entre ".$fechamenor." al ".$fechamayor." \n";
    }
    elseif(strlen($fechamayor)!=0)
    {
        if(!($data["FechaComprobante"]<=$fechamayor))
          $resultado = $resultado."La fecha emisión debe ser menor o igual al ".$fechamayor." \n";
    }
    elseif(strlen($fechamenor)!=0)
    {
        // if(!($data["FechaComprobante"]>=$fechamenor))
          // $resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    }
    else {
        //$resultado = $resultado."La fecha emisión debe ser mayor o igual al ".$fechamenor." \n";
    }

    return $resultado;
  }

  //PARA APERTURA
  function ObtenerComprobanteCajaApertura($data)
  {
    $resultado = $this->mComprobanteCaja->ObtenerComprobanteCajaApertura($data);
    return $resultado;
  }

  //NCV
  function ObtenerDocumentosPorIdComprobanteVentaReferencia($data)
  {
    $resultado = $this->mComprobanteCaja->ObtenerDocumentosPorIdComprobanteVentaReferencia($data);
    return $resultado;
  }

  function ObtenerDocumentosPorIdComprobanteCompraReferencia($data)
  {
    $resultado = $this->mComprobanteCaja->ObtenerDocumentosPorIdComprobanteCompraReferencia($data);
    return $resultado;
  }
}
