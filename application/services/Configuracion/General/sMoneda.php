<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMoneda extends MY_Service {

        public $Moneda = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mMoneda');
              $this->Moneda = $this->mMoneda->Moneda;
        }

        function ListarMonedas()
        {
          $resultado = $this->mMoneda->ListarMonedas();
          return $resultado;
        }

        function ValidarCodigoMoneda($data)
        {
          $codigo=$data['CodigoMoneda'];
          if ($codigo == "")
          {
            return "Debe completar el código de la Moneda";
          }
          else if (strlen($codigo)!=3)
          {
            return "El código debe tener 3 caracteres";
          }
          else
          {
            return "";
          }
        }

        function ValidarNombreMoneda($data)
        {
          $nombre=$data['NombreMoneda'];
          if ($nombre == "")
          {
            return "Debe completar el nombre de la Moneda";
          }
          else
          {
            return "";
          }
        }

        function ValidarSimboloMoneda($data)
        {
          $simbolo=$data['SimboloMoneda'];
          if ($simbolo == "")
          {
            return "Debe completar el símbolo de la Moneda";
          }
          else
          {
            return "";
          }
        }

        function ValidarMoneda($data)
        {
          $resultado1= $this->ValidarCodigoMoneda($data);
          $resultado2= $this->ValidarNombreMoneda($data);
          $resultado3= $this->ValidarSimboloMoneda($data);

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
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeCodigoMonedaParaInsertar($data)
        {
          $resultado = $this->mMoneda->ObtenerDuplicadoDeCodigoMonedaParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeCodigoMonedaParaActualizar($data)
        {
          $resultado = $this->mMoneda->ObtenerDuplicadoDeCodigoMonedaParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarMoneda($data)
        {
          $data["CodigoMoneda"] = trim($data["CodigoMoneda"]);
          $data['NombreMoneda'] = trim($data['NombreMoneda']);
          $data['SimboloMoneda'] = trim($data['SimboloMoneda']);
          $resultado1 = $this->ValidarMoneda($data);
          $resultado2 = $this->ValidarDuplicadoDeCodigoMonedaParaInsertar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2 !="")
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mMoneda->InsertarMoneda($data);
            return $resultado;
          }
        }

        function ActualizarMoneda($data)
        {
          $data["CodigoMoneda"] = trim($data["CodigoMoneda"]);
          $data['NombreMoneda'] = trim($data['NombreMoneda']);
          $data['SimboloMoneda'] = trim($data['SimboloMoneda']);
          $resultado1 = $this->ValidarMoneda($data);
          $resultado2 = $this->ValidarDuplicadoDeCodigoMonedaParaActualizar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2!="")
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mMoneda->ActualizarMoneda($data);
            return "";
          }
        }

        function ValidarExistenciaMonedaEnMercaderia($data)
        {
          $resultado = $this->mMoneda->ConsultarMonedaEnMercaderia($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Mercadería";
          }
          else
          {
            return "";
          }
        }

        function BorrarMoneda($data)
        {
          $resultado1= $this -> ValidarExistenciaMonedaEnMercaderia($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else
          {
            $this->mMoneda->BorrarMoneda($data);
            return "";
          }
        }

        function ObtenerMonedaPorId($data)
        {
          $resultado = $this->mMoneda->ObtenerMonedaPorId($data);
          return $resultado;
        }
}
