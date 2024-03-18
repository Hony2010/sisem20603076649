<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sProveedor extends MY_Service {

        public $Proveedor = array();
        public $Persona = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('servicessearch');
              $this->load->library('consultadatosreniec');
              $this->load->library('herencia');
              $this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
              $this->load->service("Configuracion/General/sTipoPersona");
              $this->load->model('Catalogo/mProveedor');
              $this->load->model('Catalogo/mPersona');
              $this->load->service('Catalogo/sPersona');
              $this->load->service('Configuracion/General/sParametroSistema');
              $this->Persona = $this->mPersona->Persona;
              $this->Proveedor = $this->mProveedor->Proveedor;
              $this->Proveedor = $this->herencia->Heredar($this->Persona,$this->Proveedor);

        }
        function Cargar()
        {
          $this->Proveedor["Foto"] = "";
          $this->Proveedor["ApellidoCompleto"] = "";
          $this->Proveedor["NombreCompleto"] = "";
          $this->Proveedor["NombreAbreviado"] = "";
          $this->Proveedor["IdPersona"] = "";
          $this->Proveedor["NombreTipoPersona"] = "";
          $this->Proveedor["CodigoDocumentoIdentidad"] = "";
          $this->Proveedor["EstadoContribuyente"] = "";
          $this->Proveedor["CondicionContribuyente"] = "";
          $this->Proveedor["Celular"] = "";
          $this->Proveedor["TelefonoFijo"] = "";
          $this->Proveedor["IdRol"] = ID_ROL_PROVEEDOR;
          $this->Proveedor["IndicadorEstadoProveedor"] = true;

          $TiposDocumentoIdentidad = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();
          $TiposPersona = $this->sTipoPersona->ListarTiposPersona();
          $ImageURL = $this->ObtenerUrlCarpetaImagenes();

          $data = array(
                  'TiposDocumentoIdentidad'=>$TiposDocumentoIdentidad,
                  'TiposPersona'=>$TiposPersona,
                  'ImageURL' =>$ImageURL
                );

          $resultado = array_merge($this->Proveedor,$data);

          $resultado["ProveedorNuevo"] = $resultado;

          return $resultado;
        }


        function ObtenerNumeroFila()
        {
          $resultado=$this->mProveedor->ObtenerNumeroFila();
          $total=$resultado[0]['NumeroFila'];
          return $total;
        }

        function ObtenerNumeroFilaPorConsultaProveedor($data)
        {
          $resultado=$this->mProveedor->ObtenerNumeroFilaPorConsultaProveedor($data);
          $total=$resultado[0]['NumeroFila'];
          return $total;
        }

        function ObtenerNumeroPagina()
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_PROVEEDOR;
          $total = $this->ObtenerNumeroFila();
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            if (($total%$ValorParametroSistema)>0)
            {
              $numeropagina = ($total/$ValorParametroSistema)+1;
              return intval($numeropagina);
            }
            else
            {
              $numeropagina = ($total/$ValorParametroSistema);
              return intval($numeropagina);
            }
          }
        }

        function ObtenerNumeroPaginaPorConsultaProveedor($data)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_PROVEEDOR;
          $total = $this->ObtenerNumeroFilaPorConsultaProveedor($data);
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
            $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
            if (($total%$ValorParametroSistema)>0)
            {
              $numeropagina = ($total/$ValorParametroSistema)+1;
              return intval($numeropagina);
            }
            else
            {
              $numeropagina = ($total/$ValorParametroSistema);
              return intval($numeropagina);
            }
          }
        }

        function ListarProveedores($pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_PROVEEDOR;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
              $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
              $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
              $resultado = $this->mProveedor->ListarProveedores($inicio,$ValorParametroSistema);

              foreach ($resultado as $key => $value) {
                $resultado[$key]["IndicadorEstadoProveedor"] = ($value["EstadoProveedor"] == 0) ? false : true;
              }

              return($resultado);
          }
        }

        function ValidarRazonSocial($data)
        {
          $razonsocial = strpos($data['RazonSocial'], '"');
          if (is_numeric($razonsocial)) {
            return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples";
          }
          else {
            return "";
          }
        }

        function ValidarDireccion($data)
        {
          $direccion = strpos($data['Direccion'], '"');
          if (is_numeric($direccion)) {
            return "No se puedes utilizar comillas dobles, porfavor utilizar comillas simples";
          }
          else {
            return "";
          }
        }

        function ValidarDatosProveedor($data)
        {
          $razonsocial = $this->ValidarRazonSocial($data);
          $direccion = $this->ValidarDireccion($data);
          if ($razonsocial != "") {
            return $razonsocial;
          }
          else if ($direccion != "") {
            return $direccion;
          }
          else {
            return "";
          }
        }

        function ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data) {
          if ($data["IdTipoDocumentoIdentidad"] == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
            $resultado = array();
          } else {
            $resultado = $this->mProveedor->ObtenerNumeroDocumentoIdentidadParaInsertar($data);
          }
      
          if (Count($resultado) > 0) {
            return "Este número de documento ya fue registrado.";
          } else {
            return "";
          }
        }

        function ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data) {
          if ($data["IdTipoDocumentoIdentidad"] == ID_TIPO_DOCUMENTO_IDENTIDAD_OTROS) {
            $resultado = array();
          } else {
            $resultado = $this->mProveedor->ObtenerNumeroDocumentoIdentidadParaActualizar($data);
          }
          
          if (Count($resultado) > 0) {
            return "Este número de documento ya fue registrado.";
          } else {
            return "";
          }          
        }

        function InsertarProveedor($data)
        {
          $validaciondatos = $this->ValidarDatosProveedor($data);
          $validacion = $this->ValidarExistenciaNumeroDocumentoIdentidadParaInsertar($data);
          if ($validacion != "" ) {
            return $validacion;
          }
          else if ($validaciondatos != "") {
            return $validaciondatos;
          }
          else {
            switch($data["IdTipoPersona"]) {
              case ID_TIPO_PERSONA_NATURAL: $resultadoPersona=$this->sPersona->InsertarPersonaComoPersonaNatural($data); break;
              case ID_TIPO_PERSONA_JURIDICA: $resultadoPersona=$this->sPersona->InsertarPersonaComoPersonaJuridica($data); break;
              case ID_TIPO_PERSONA_NO_DOMICILIADO: $resultadoPersona=$this->sPersona->InsertarPersonaComoNoDomiciliado($data); break;
            }

            if (!is_array($resultadoPersona)) {
              return $resultadoPersona;
            }
            else {
              $resultadoPersona["EstadoProveedor"] = ($data["IndicadorEstadoProveedor"] == true) ? "1" : "0";
              $resultado = $this->mProveedor->InsertarProveedor($resultadoPersona);
              return $resultadoPersona;
            }
          }
        }

        function ActualizarProveedor($data) {

          $validaciondatos = $this->ValidarDatosProveedor($data);
          $validar = $this->ValidarExistenciaNumeroDocumentoIdentidadParaActualizar($data);
          if ($validar != "" ) {
            return $validar;
          }
          else if ($validaciondatos != "") {
            return $validaciondatos;
          }
          else {
            switch($data["IdTipoPersona"])
            {
              case ID_TIPO_PERSONA_NATURAL: $resultadoPersona=$this->sPersona->ActualizarPersonaComoPersonaNatural($data); break;
              case ID_TIPO_PERSONA_JURIDICA: $resultadoPersona=$this->sPersona->ActualizarPersonaComoPersonaJuridica($data); break;
              case ID_TIPO_PERSONA_NO_DOMICILIADO: $resultadoPersona=$this->sPersona->ActualizarPersonaComoNoDomiciliado($data); break;
            }
       
            if (!is_array($resultadoPersona))
              return $resultadoPersona;
            else
              return $data;
          }
        }

        function ValidarExistenciaPersonaEnComprobanteVenta($data)
        {
          $resultado = $this->mPersona->ConsultarProveedorEnComprobanteCompra($data);
          $contador = COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en comprobante de compra";
          }
          else
          {
            return "";
          }
        }

        function BorrarProveedor($data)
        {
          $existencia = $this->ValidarExistenciaPersonaEnComprobanteVenta($data);
          if ($existencia != "")
          {
            return $existencia;
          }
          else {
            $input["IdPersona"]=$data["IdPersona"];
            $resultado=$this->sPersona->BorrarPersona($input);
            return $resultado;
          }
        }

        function ConsultarProveedores($data,$pagina)
        {
          $data['IdParametroSistema']= ID_NUM_POR_PAGINA_PROVEEDOR;
          $resultado = $this->sParametroSistema->ObtenerParametroSistemaPorId($data);
          if (is_string($resultado))
          {
            return $resultado;
          }
          else
          {
              $ValorParametroSistema=$resultado[0]->ValorParametroSistema;
              $inicio = ($pagina*$ValorParametroSistema)-$ValorParametroSistema;
              $resultado=$this->mProveedor->ConsultarProveedores($inicio,$ValorParametroSistema,$data);
              
              foreach ($resultado as $key => $value) {
                $resultado[$key]["IndicadorEstadoProveedor"] = ($value["EstadoProveedor"] == 0) ? false : true;
              }

              return $resultado;
          }
        }

        function ObtenerProveedorPorIdPersona($data)
        {
          $resultado = $this->mProveedor->ObtenerProveedorPorIdPersona($data);
          return $resultado;
        }

        function ObtenerUrlCarpetaImagenes()
        {
          $data['IdParametroSistema']= ID_URL_CARPETA_IMAGENES_PROVEEDOR;
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

        function ConsultarSunat($data)
        {
          $number = $data["NumeroDocumentoIdentidad"];
          $resultado = $this->servicessearch->ruc($number);
          return $resultado;
        }
        function ConsultarReniec($data)
        {
          $resultado = $this->consultadatosreniec->BuscarPorNumeroDocumentoIdentidad($data);
          return $resultado;
        }

        function ObtenerNumeroFilasPorPagina()
        {
          $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_PROVEEDOR;
          $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
          $numerofilasporpagina=$parametro->ValorParametroSistema;
          return $numerofilasporpagina;
        }

        function ObtenerNumeroTotalProveedores($data)
        {
            $resultado = $this->mProveedor->ObtenerNumeroTotalProveedores($data);
            return $resultado;
        }

        function PrepararDataJSONProveedor()
        {
          $response = array();
          $proveedores = $this->mProveedor->ConsultarProveedorParaJSON();
          foreach ($proveedores as $key => $value) {
            $nueva_fila = Array (
    					"IdPersona" => $value["IdPersona"],
    					"NumeroDocumentoIdentidad" => $value["NumeroDocumentoIdentidad"],
    					"CodigoDocumentoIdentidad" => $value["CodigoDocumentoIdentidad"],
    					"RazonSocial" => $value["RazonSocial"],
              "Direccion" => $value["Direccion"],
              "EstadoProveedor" => $value["EstadoProveedor"]
    				);

            array_push($response, $nueva_fila);
          }

          return $response;
        }

        function CrearJSONProveedorTodos()
        {
          $url = DIR_ROOT_ASSETS.'/data/proveedor/proveedores.json';
    			$data_json = $this->PrepararDataJSONProveedor();

    			$resultado = $this->jsonconverter->CrearArchivoJSONData($url, $data_json);
          return $resultado;
        }

        //PARA EL TRADADO DEL JSONH
        function PreparaDataFilaProveedor($data)
        {
          $nueva_fila = Array (
            "IdPersona" => $data["IdPersona"],
  					"CodigoDocumentoIdentidad" => $data["CodigoDocumentoIdentidad"],
  					"NumeroDocumentoIdentidad" => $data["NumeroDocumentoIdentidad"],
  					"RazonSocial" => $data["RazonSocial"],
            "Direccion" => $data["Direccion"],
            "EstadoProveedor" => $data["EstadoProveedor"]
  				);

          return $nueva_fila;
        }

        function InsertarJSONDesdeProveedor($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/proveedor/proveedores.json';
          $nueva_fila = $this->PreparaDataFilaProveedor($data);
  				$resultado2 = $this->jsonconverter->InsertarNuevaFilaEnArchivoJSON($url, $nueva_fila);
          return $resultado2;
        }

        function ActualizarJSONDesdeProveedor($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/proveedor/proveedores.json';
          $nueva_fila = $this->PreparaDataFilaProveedor($data);
          $resultado2 = $this->jsonconverter->ActualizarFilaEnArchivoJSON($url, $nueva_fila, "IdPersona");

          return $resultado2;
        }

        function BorrarJSONDesdeProveedor($data)
        {
          $url = DIR_ROOT_ASSETS.'/data/proveedor/proveedores.json';
  				$resultado = $this->jsonconverter->EliminarFilaEnArchivoJSON($url, $data, "IdPersona");

          return $resultado;
        }

}
