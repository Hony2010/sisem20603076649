<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTipoCambio extends MY_Service {

        public $TipoCambio = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->helper("date");
              $this->load->library('mapper');
              $this->load->model('Configuracion/General/mTipoCambio');
              $this->load->service('Configuracion/General/sParametroSistema');
              $this->TipoCambio = $this->mTipoCambio->TipoCambio;
        }

        function PaginadorTipoCambio()
        {
          $input["textofiltro"]='';
          $input["pagina"]=1;
          $input["numerofilasporpagina"] = $this->ObtenerNumeroFilasPorPagina();
          $input["paginadefecto"]=1;
          $input["totalfilas"] =$this->ObtenerNumeroTotalTipoCambio($input);

          return $input;
        }

        function ListarTiposCambio($pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_TIPO_CAMBIO;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
              $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
              $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
              $resultado = $this->mTipoCambio->ListarTiposCambio($inicio,$ValorParametroSistema);
              return $resultado;
          }
        }

        function ObtenerEnteroTipoCambio()
        {
          $data['IdParametroSistema']= ID_NUM_ENTERO_TIPO_CAMBIO;
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

        function ObtenerDecimalTipoCambio()
        {
          $data['IdParametroSistema']= ID_NUM_DECIMAL_TIPO_CAMBIO;
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

        function IsDate($data)
        {
          $fecha = $data['FechaCambio'];
          $tempdate = explode('-',$fecha);
          return checkdate($tempdate[1],$tempdate[2],$tempdate[0]);
        }

        function ValidarFechaTipoCambio($data)
        {
          $isdate= $this->IsDate($data);
          $fecha=$data["FechaCambio"];
          if ($fecha == "")
          {
            return "Debe completar el registro";
          }
          else if ($isdate==false)
          {
            return "Debe ingresar una fecha valida";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoCambioCompra($data)
        {
          $entero = $this->ObtenerEnteroTipoCambio();
          $decimal = $this->ObtenerDecimalTipoCambio();
          $compra=$data["TipoCambioCompra"];
          $explodecimal = explode('.',$compra);
          if ($compra == "")
          {
            return "Debe completar el tipo de cambio compra";
          }
          else if (strlen((int)$compra)>$entero)
          {
            return "el número de enteros en compra no debe ser mayor a  $entero dígitos";
          }
          else if (strlen($explodecimal[1])>$decimal)
          {
            return "el número de decimales en compra no debe ser mayor a  $decimal dígitos";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoCambioVenta($data)
        {
          $entero = $this->ObtenerEnteroTipoCambio();
          $decimal = $this->ObtenerDecimalTipoCambio();
          $venta=$data["TipoCambioVenta"];
          $explodecimal = explode('.',$venta);
          if ($venta == "")
          {
            return "Debe completar el tipo de cambio venta";
          }
          else if (strlen((int)$venta)>$entero)
          {
            return "el número de enteros en venta no debe ser mayor a  $entero dígitos";
          }
          else if (strlen($explodecimal[1])>$decimal)
          {
            return "el número de decimales en venta no debe ser mayor a  $decimal dígitos";
          }
          else
          {
            return "";
          }
        }

        function ValidarTipoCambio($data)
        {
          $resultado1= $this->ValidarFechaTipoCambio($data);
          $resultado2= $this->ValidarTipoCambioCompra($data);
          $resultado3= $this->ValidarTipoCambioVenta($data);

          if ($resultado1!="")
          {
            return $resultado1;
          }
          else if ($resultado2 !="")
          {
            return $resultado2;
          }
          else if ($resultado3 !="")
          {
            return $resultado3;
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeFechaCambioParaInsertar($data)
        {
          $resultado = $this->mTipoCambio->ObtenerDuplicadoDeFechaCambioParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Esta fecha ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeFechaCambioParaActualizar($data)
        {
          $resultado = $this->mTipoCambio->ObtenerDuplicadoDeFechaCambioParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Esta fecha ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarDecimalCompra($data)
        {
          $decimal = $this->ObtenerDecimalTipoCambio();
          $compra = trim($data["TipoCambioCompra"]);
          $valor = (float)($compra) - (int)($compra);
          if ($compra == "")
          {
            return $compra;
          }
          else
          {
            if (($valor != 0))
            {
              return $compra;
            }
            else
            {
              $data["TipoCambioCompra"] = number_format($compra,$decimal,'.','');
              return($data["TipoCambioCompra"]);
            }
          }
        }

        function ValidarDecimalVenta($data)
        {
          $decimal = $this->ObtenerDecimalTipoCambio();
          $venta = trim($data["TipoCambioVenta"]);
          $valor = (float)($venta) - (int)($venta);
          if ($venta == "")
          {
            return $venta;
          }
          else
          {
            if ($valor != 0)
            {
              return $venta;
            }
            else
            {
              $data["TipoCambioVenta"] = number_format($venta,$decimal,'.','');
              return($data["TipoCambioVenta"]);
            }
          }
        }

        function InsertarTipoCambio($data)
        {
          $decimal = $this->ObtenerDecimalTipoCambio();
          $compra = $this->ValidarDecimalCompra($data);
          $venta = $this->ValidarDecimalVenta($data);
          $data["TipoCambioCompra"] = $compra;
          $data["TipoCambioVenta"] = $venta;
          $data["FechaCambio"] = substr($data["FechaCambio"], 0, 10);
          $data["FechaCambio"] = trim($data["FechaCambio"]);
          $resultado1 = $this->ValidarTipoCambio($data);
          $resultado2 = $this->ValidarDuplicadoDeFechaCambioParaInsertar($data);

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
            $resultado = $this->mTipoCambio->InsertarTipoCambio($data);
            return $resultado;
          }
        }

        function ActualizarTipoCambio($data)
        {
          $decimal = $this->ObtenerDecimalTipoCambio();
          $compra = $this->ValidarDecimalCompra($data);
          $venta = $this->ValidarDecimalVenta($data);
          $data["TipoCambioCompra"] = $compra;
          $data["TipoCambioVenta"] = $venta;
          $data["FechaCambio"] = trim($data["FechaCambio"]);
          $data["FechaCambio"] = substr($data["FechaCambio"], 0, 10);

          $resultado1 = $this->ValidarTipoCambio($data);
          $resultado2 = $this->ValidarDuplicadoDeFechaCambioParaActualizar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2 != "")
          {
            return $resultado2;
          }
          else
          {
            $resultado = $this->mTipoCambio->ActualizarTipoCambio($data);
            return "";
          }
        }

        function BorrarTipoCambio($data)
        {
            $this->mTipoCambio->BorrarTipoCambio($data);
            return "";
        }

        function ConsultarTiposCambio($data,$pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_TIPO_CAMBIO;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;

            $data['textofiltro']=convertToDate($data['textofiltro']);

            $resultado = $this->mTipoCambio->ConsultarTiposCambio($inicio,$ValorParametroSistema,$data);
            if (count($resultado) != 0) {
              if (Count($resultado) == 1) {
                $resultado = $this->BuscarRangoDeFechas($inicio,$ValorParametroSistema,$data);
                return array_reverse($resultado);
              }
              else {
                return array_reverse($resultado);
              }
            }
            else {
              $resultado = $this->BuscarRangoDeFechas($inicio,$ValorParametroSistema,$data);
              return array_reverse($resultado);
            }
          }
        }

        function ConsultarTiposCambioPorPagina($data,$pagina)
        {
          $resultado = $this->mTipoCambio->ConsultarTiposCambio($inicio,$ValorParametroSistema,$data);
          return $resultado;
        }

        function BuscarRangoDeFechas($inicio,$ValorParametroSistema,$data)
        {
          $restarFecha=strtotime('-5 days',strtotime($data['textofiltro']));
          $data['FechaInicio']=date("Y-m-d",$restarFecha);

          $sumarFecha=strtotime('+4 days',strtotime($data['textofiltro']));
          $data['FechaFin']=date("Y-m-d",$sumarFecha);
          $resultado = $this->mTipoCambio->ConsultarRangoFechasTiposCambio($inicio,$ValorParametroSistema,$data);
          return $resultado;
        }

        function ObtenerTipoCambio($data)
        {
          $resultado = $this->mTipoCambio->ObtenerTipoCambio($data);
          return $resultado;
        }

        function ObtenerNumeroFilasPorPagina()
        {
          $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_TIPO_CAMBIO;
          $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
          $numerofilasporpagina=$parametro->ValorParametroSistema;
          return $numerofilasporpagina;
        }

        function ObtenerNumeroTotalTipoCambio($data)
        {
          if ($data['textofiltro']!="%") {
            $data['textofiltro']=convertToDate($data['textofiltro']);
          }
          $resultado = $this->mTipoCambio->ObtenerNumeroTotalTipoCambio($data);
          return $resultado;
        }

}
