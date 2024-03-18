<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCorrelativoDocumento extends MY_Service {

  public $CorrelativoDocumento = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->service('Seguridad/sUsuario');
    $this->load->model('Configuracion/Venta/mCorrelativoDocumento');
    $this->CorrelativoDocumento = $this->mCorrelativoDocumento->CorrelativoDocumento;
  }

  function ListarCorrelativosDocumento()
  {
    $resultado = $this->mCorrelativoDocumento->ListarCorrelativosDocumento();
    return $resultado;
  }

  function ValidarUltimoDocumentoEnCorrelativoDocumeto($data)
  {
    $documeto=$data["UltimoDocumento"];
    if ($documeto == "")
    {
      return "Debe completar el campo Ultimo Documento";
    }
    else if (!is_numeric($documeto)) {
      return "El Ultimo Documento debe ser de tipo numérico";
    }
    else
    {
      return "";
    }
  }

  function ValidarSerieDocumentoEnCorrelativoDocumeto($data)
  {
    $serie = $data['SerieDocumento'];
    if ($serie == "")
    {
      return "Debe completar el campo Serie Documento";
    }
    else if (strlen($serie)!=4)
    {
      return "El Serie de Documento debe tener 4 dígitos";
    }
    else
    {
      return "";
    }
  }

  function ValidarCorrelativoDocumento($data)
  {
    // $documeto = $this->ValidarUltimoDocumentoEnCorrelativoDocumeto($data);
    $serie = $this->ValidarSerieDocumentoEnCorrelativoDocumeto($data);
    if ($serie != "")
    {
      return $serie;
    }
    // else if ($documeto !="")
    // {
    //   return $documeto;
    // }
    else
    {
      return "";
    }
  }

  //VALIDACIONES PARA FISICOS
  function ValidarSerieYTipoDocumentoEnCorrelativoDocumento($data)
  {
    $response = $this->mCorrelativoDocumento->ValidarSerieYTipoDocumentoEnCorrelativoDocumento($data);
    if(count($response) > 0)
    {
      return "No se puede usar la misma serie para el tipo de documento asignado.";
    }
    else
    {
      return "";
    }
  }

  function ValidarActualizarSerieYTipoDocumentoEnCorrelativoDocumento($data)
  {
    $response = $this->mCorrelativoDocumento->ValidarActualizarSerieYTipoDocumentoEnCorrelativoDocumento($data);
    if(count($response) > 0)
    {
      return "No se puede usar la misma serie para el tipo de documento asignado.";
    }
    else
    {
      return "";
    }
  }

  //VALIDACIONES PARA ELECTRONICOS
  function ValidarSerieEnCorrelativoDocumento($data)
  {
    $response = $this->mCorrelativoDocumento->ValidarSerieEnCorrelativoDocumento($data);
    if(count($response) > 0)
    {
      return "No se puede usar la misma serie electronica.";
    }
    else
    {
      return "";
    }
  }

  function ValidarActualizarSerieEnCorrelativoDocumento($data)
  {
    $response = $this->mCorrelativoDocumento->ValidarActualizarSerieEnCorrelativoDocumento($data);
    if(count($response) > 0)
    {
      return "No se puede usar la misma serie electronica.";
    }
    else
    {
      return "";
    }
  }

  function ValidarSerieDuplicadaParaInsertar($data)
  {
    $SerieDocumento=(string) $data["SerieDocumento"];
    $indicador = substr($SerieDocumento, 0, 1);
    if(is_numeric($indicador))
    {
      $response = $this->ValidarSerieYTipoDocumentoEnCorrelativoDocumento($data);
      return $response;
    }
    else
    {
      $response = $this->ValidarSerieEnCorrelativoDocumento($data);
      return $response;
    }
  }
  
  function ValidarSerieDuplicadaParaActualizar($data)
  {
    $SerieDocumento=(string) $data["SerieDocumento"];
    $indicador = substr($SerieDocumento, 0, 1);
    if(is_numeric($indicador))
    {
      $response = $this->ValidarActualizarSerieYTipoDocumentoEnCorrelativoDocumento($data);
      return $response;
    }
    else
    {
      $response = $this->ValidarActualizarSerieEnCorrelativoDocumento($data);
      return $response;
    }
  }

  function InsertarCorrelativoDocumento($data)
  {
    $resultado = $this->ValidarCorrelativoDocumento($data);
    $validacion2 = $this->ValidarSerieYTipoDocumentoEnCorrelativoDocumento($data);//$this->ValidarSerieDuplicadaParaInsertar($data);
    if ($resultado!="")
    {
      return $resultado;
    }
    else if($validacion2 != "")
    {
      return $validacion2;
    }
    else
    {
      $resultado = $this->mCorrelativoDocumento->InsertarCorrelativoDocumento($data);
      return $resultado ;
    }
  }

  function ActualizarCorrelativoDocumento($data, $extra = false)
  {
    // $resultado = $this->ValidarCorrelativoDocumento($data);
    $validacion2 = ""; 
    if($extra == false)
    {
      $validacion2 = $this->ValidarActualizarSerieYTipoDocumentoEnCorrelativoDocumento($data);//$this->ValidarSerieDuplicadaParaActualizar($data);
    }

    // if ($resultado!="")
    // {
    //   return $resultado;
    // }
    // else 
    if($validacion2 != "")
    {
      return $validacion2;
    }
    else
    {
      $data["IndicadorEstado"]=ESTADO_ACTIVO;
      $resultado = $this->mCorrelativoDocumento->ActualizarCorrelativoDocumento($data);
      return "";
    }
  }

  function BorrarCorrelativoDocumento($data)
  {
    $this->mCorrelativoDocumento->BorrarCorrelativoDocumento($data);
    return "";
  }

  function ListarSeriesDocumento($data)
  {
    $resultado = $this->mCorrelativoDocumento->ListarSeriesDocumento($data);
    
    return $resultado;
  }

  function ObtenerCorrelativoDocumento($data)
  {
    $resultado = $this->mCorrelativoDocumento->ObtenerCorrelativoDocumento($data);
    return $resultado;
  }

  function ObtenerNuevoCorrelativoDocumento($data)
  {
    $resultado = $this->mCorrelativoDocumento->ObtenerCorrelativoDocumento($data);
    return $resultado;
  }

  function ObtenerCorrelativoDocumentoPorTipoDocumentoYSede($data)
  {
    $resultado = $this->mCorrelativoDocumento->ObtenerCorrelativoDocumentoPorTipoDocumentoYSede($data);
    return $resultado;
  }

  function IncrementarCorrelativoDocumento($data)
  {
    $data["UltimoDocumento"] = 'i';
    $resultado = $this->ActualizarCorrelativoDocumento($data, true);
    $resultado = $this->ObtenerNuevoCorrelativoDocumento($data);
    // $resultado->UltimoDocumento = $resultado->UltimoDocumento + 1;
    // $input["IdCorrelativoDocumento"] = $resultado->IdCorrelativoDocumento;
    $input["UltimoDocumento"] = $resultado->UltimoDocumento;
    // $input["SerieDocumento"] = $resultado->SerieDocumento;
    // $input["IdTipoDocumento"] = $resultado->IdTipoDocumento;
    // $resultado = $this->ActualizarCorrelativoDocumento($input);
    return $input["UltimoDocumento"];
  }

  function ValidarCorrelativoDocumentoJSONPorSerieYTipo($data)
  {
    $resultado = $this->mCorrelativoDocumento->ValidarCorrelativoDocumentoJSONPorSerieYTipo($data);
    return $resultado;
  }

  function CosultarCorrelativoDocumentoParaJSON($data)
  {
    $resultado = $this->mCorrelativoDocumento->ObtenerCorrelativoDocumentoParaJSON($data);
    if(!empty($resultado))
    {
      return $resultado;
    }
    else
    {
      return "La serie de documento con el tipo de documento no existen en el servidor.";
    }
  }

  function ObtenerCorrelativoDocumentoEnSeriesPorUsuarioYTipoDocumento($data,$series = null) {
    if ($series != null)
      $SeriesDocumento = $this->ListarSeriesDocumento($data);
    else
      $SeriesDocumento = $series;

    $response = null;
    $dataConfigCorrelativo = $this->json->ObtenerConfigCorrelativo($this->sesionusuario->obtener_sesion_id_usuario(), $data);
    if($dataConfigCorrelativo != false) {
      $response = false;
      foreach ($SeriesDocumento as $key => $value) {
        if($dataConfigCorrelativo["IdCorrelativoDocumento"] == $value["IdCorrelativoDocumento"]) {
          $response = $value;
        }
      }
    }     

    return $response;
  }

  function ListarCorrelativosDocumentoPorNumeroSerie($data) {
    $resultado = $this->mCorrelativoDocumento->ListarCorrelativosDocumentoPorNumeroSerie($data);
    return $resultado;
  }  

  function ObtenerSeriesDocumentoPorIdUsuarioYIdTipoDocumentoYIdSubTipoDocumento($data) {
    $resultado = $this->mCorrelativoDocumento->ObtenerSeriesDocumentoPorIdUsuarioYIdTipoDocumentoYIdSubTipoDocumento($data);
    return $resultado;
  }
}
