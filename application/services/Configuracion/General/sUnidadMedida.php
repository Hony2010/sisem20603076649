<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sUnidadMedida extends MY_Service {

        public $UnidadMedida = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('sesionusuario');
              $this->load->model('Configuracion/General/mUnidadMedida');
              $this->load->service('Configuracion/General/sParametroSistema');
              $this->UnidadMedida = $this->mUnidadMedida->UnidadMedida;
        }

        function PreparaFiltroOtraUnidadMedida()
        {
          $input["textofiltro"]='';
      		$input["pagina"]=1;
      		$input["numerofilasporpagina"] = $this->ObtenerNumeroFilasPorPagina();
      		$input["paginadefecto"]=1;
      		$input["totalfilas"] =$this->ObtenerNumeroTotalOtraUnidadesMedida($input);

          return $input;
        }

        function ObtenerNumeroFilasPorPagina(){
          $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_OTRAUNIDADMEDIDA;
          $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);

          $numerofilasporpagina=$parametro->ValorParametroSistema;
          return $numerofilasporpagina;
        }

        function ConsultarOtraUnidadesMedida($data,$numeropagina,$numerofilasporpagina)
        {
            $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
            $resultado = $this->mUnidadMedida->ConsultarOtraUnidadesMedida($data,$numerofilainicio,$numerofilasporpagina);

            return $resultado;
        }

        function ObtenerNumeroTotalOtraUnidadesMedida($data){
          $resultado = $this->mUnidadMedida->ObtenerNumeroTotalOtraUnidadesMedida($data);
          return $resultado;
        }

        function ListarUnidadesMedida()
        {
          $resultado = $this->mUnidadMedida->ListarUnidadesMedida();
          return $resultado ;
        }

        function ListarOtraUnidadesMedida($pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_OTRAUNIDADMEDIDA;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
              $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
              $inicio = ($pagina * $ValorParametroSistema) - $ValorParametroSistema;
              $resultado = $this->mUnidadMedida->ListarOtraUnidadesMedida($inicio,$ValorParametroSistema);
              return $resultado ;
          }
        }

        function ValidarCodigoUnidadMedida($data)
        {
          $codigo=$data["CodigoUnidadMedidaSunat"];
          if ($codigo == "")
          {
            return "Debe ingresar el código";
          }
          else if (strlen($codigo)>10)
          {
            return "El código debe tener como máximo 10 caracteres";
          }
          else
          {
            return "";
          }
        }

        function ValidarNombreUnidadMedida($data)
        {
          $nombre=$data["NombreUnidadMedida"];
          if ($nombre == "")
          {
            return "Debe ingresar el nombre";
          }
          else
          {
            return "";
          }
        }

        function ValidarAbreviaturaUnidadMedida($data)
        {
          $abreviatura=$data["AbreviaturaUnidadMedida"];
          if ($abreviatura == "")
          {
            return "Debe ingresar la abreviatura";
          }
          else
          {
            return "";
          }
        }
        function ValidarUnidadMedida($data)
        {
          $codigo=$this->ValidarCodigoUnidadMedida($data);
          $nombre=$this->ValidarNombreUnidadMedida($data);
          $abreviatura=$this->ValidarAbreviaturaUnidadMedida($data);
          if ($codigo != "")
          {
            return $codigo;
          }
          else if ($nombre != "")
          {
            return $nombre;
          }
          else if ($abreviatura != "")
          {
            return $abreviatura;
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeCodigoUnidadMedidaParaInsertar($data)
        {
          $resultado = $this->mUnidadMedida->ObtenerDuplicadoDeCodigoUnidadMedidaParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeAbreviaturaUnidadMedidaParaInsertar($data)
        {
          $resultado = $this->mUnidadMedida->ObtenerDuplicadoDeAbreviaturaUnidadMedidaParaInsertar($data);
          if (Count($resultado)>0)
          {
            return "Este abreviatura ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarAbreviaturaUnidadMedidaParaInsertar($data)
        {
          $resultado = $this->mUnidadMedida->ObtenerDuplicadoDeAbreviaturaUnidadMedidaParaInsertar($data);
          if (count($resultado)>0)
          {
            return $resultado;
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeCodigoUnidadMedidaParaActualizar($data)
        {
          $resultado = $this->mUnidadMedida->ObtenerDuplicadoDeCodigoUnidadMedidaParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este código ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function ValidarDuplicadoDeAbreviaturaUnidadMedidaParaActualizar($data)
        {
          $resultado = $this->mUnidadMedida->ObtenerDuplicadoDeAbreviaturaUnidadMedidaParaActualizar($data);
          if (Count($resultado)>0)
          {
            return "Este abreviatura ya fue registrado";
          }
          else
          {
            return "";
          }
        }

        function InsertarUnidadMedida($data)
        {
          $data["CodigoUnidadMedidaSunat"] = trim($data["CodigoUnidadMedidaSunat"]);
          $resultado1 = $this->ValidarUnidadMedida($data);
          $resultado2 = $this->ValidarDuplicadoDeCodigoUnidadMedidaParaInsertar($data);
          $resultado3 = $this->ValidarDuplicadoDeAbreviaturaUnidadMedidaParaInsertar($data);

          if ($resultado1 != "")
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
            $resultado = $this->mUnidadMedida->InsertarUnidadMedida($data);
            return $resultado;
          }
        }

        function ActualizarUnidadMedida($data)
        {
          $data["CodigoUnidadMedidaSunat"] = trim($data["CodigoUnidadMedidaSunat"]);
          $resultado1 = $this->ValidarUnidadMedida($data);
          $resultado2 = $this->ValidarDuplicadoDeCodigoUnidadMedidaParaActualizar($data);
          $resultado3 = $this->ValidarDuplicadoDeAbreviaturaUnidadMedidaParaActualizar($data);

          if ($resultado1 != "")
          {
            return $resultado1;
          }
          else if ($resultado2!="")
          {
            return $resultado2;
          }
          else if ($resultado3!="")
          {
            return $resultado3;
          }
          else
          {
            $resultado = $this->mUnidadMedida->ActualizarUnidadMedida($data);
            return "";
          }
        }

        function ActualizarOtraUnidadMedida($data)
        {
          $data["CodigoUnidadMedidaSunat"] = trim($data["CodigoUnidadMedidaSunat"]);

          $resultado = $this->mUnidadMedida->ActualizarOtraUnidadMedida($data);
          return "";
        }

        function ValidarExistenciaUnidadMedidaEnMercaderia($data)
        {
          $resultado = $this->mUnidadMedida->ConsultarUnidadMedidaEnMercaderia($data);
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

        function ValidarExistenciaUnidadMedidaEnCostoServicio($data)
        {
          $resultado = $this->mUnidadMedida->ConsultarUnidadMedidaEnCostoServicio($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en costo de servicio";
          }
          else
          {
            return "";
          }
        }

        function BorrarUnidadMedida($data)
        {
          $resultado1= $this -> ValidarExistenciaUnidadMedidaEnMercaderia($data);
          $resultado2= $this -> ValidarExistenciaUnidadMedidaEnCostoServicio($data);
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
            $this->mUnidadMedida->BorrarUnidadMedida($data);
            return "";
          }
        }

        function ObtenerUnidadMedidaPorNombreOAbreviatura($data) {
          $resultado = $this->mUnidadMedida->ObtenerUnidadMedidaPorNombreOAbreviatura($data);          
          return $resultado;
        }

        function ActivarUnidadMedida($data) {          
            $data["UsuarioModificacion"]=$this->sesionusuario->obtener_alias_usuario();
            $data["IndicadorEstado"]=ESTADO_ACTIVO;
            $resultado = $this->mUnidadMedida->ActualizarUnidadMedida($data);
            return "";          
        }
}
