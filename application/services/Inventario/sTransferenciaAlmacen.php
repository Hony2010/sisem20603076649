<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sTransferenciaAlmacen extends MY_Service {

        public $TransferenciaAlmacen = array();
        public $DetalleTransferenciaAlmacen = array();

        public function __construct() {
          parent::__construct();
          $this->load->database();
          $this->load->library('shared');
          $this->load->library('sesionusuario');
          $this->load->library('mapper');
          //$this->load->library('reporter');
          //$this->load->library('imprimir');
          $this->load->helper("date");          
          
          $this->load->service('Configuracion/General/sParametroSistema');
          $this->load->service('Configuracion/General/sConstanteSistema');
          $this->load->service('Configuracion/Venta/sCorrelativoDocumento');
          //$this->load->service("Configuracion/General/sMoneda");
          $this->load->service("Configuracion/General/sTipoDocumento");          
      		$this->load->service('Configuracion/General/sAsignacionSede');          
          
          $this->load->model("Base");
          $this->load->service('Catalogo/sProducto');
          $this->load->service('Catalogo/sMercaderia');          
          //$this->load->service("Configuracion/General/sUnidadMedida");          
          $this->load->model('Inventario/mTransferenciaAlmacen');
          $this->load->service('Inventario/sDetalleTransferenciaAlmacen');          

          
          $this->TransferenciaAlmacen = $this->mTransferenciaAlmacen->TransferenciaAlmacen;         
        }

        public function Cargar() {
          $hoy =convertirFechaES($this->Base->ObtenerFechaServidor());

          $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_TRANSFERENCIA_ALMACEN;
          $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);            
          $resultadoSerie = $this->sCorrelativoDocumento->ObtenerCorrelativoDocumentoEnSeriesPorUsuarioYTipoDocumento($parametro,$SeriesDocumento);
          
          if($resultadoSerie != false) {
            $this->TransferenciaAlmacen["IdCorrelativoDocumento"] = $resultadoSerie["IdCorrelativoDocumento"];
            $this->TransferenciaAlmacen["SerieTransferencia"] = $resultadoSerie["SerieDocumento"];
            //$this->TransferenciaAlmacen["NumeroTransferencia"] =$resultadoSerie['UltimoDocumento'];
          }
          else {
            $this->TransferenciaAlmacen["IdCorrelativoDocumento"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["IdCorrelativoDocumento"] : "";
            $this->TransferenciaAlmacen["SerieTransferencia"] = (count($SeriesDocumento) > 0) ? $SeriesDocumento[0]["SerieDocumento"] : "";            
            //$this->TransferenciaAlmacen["NumeroTransferencia"] =(count($SeriesDocumento) > 0) ? $SeriesDocumento[0]['UltimoDocumento'] : "";
          }
                  
          $this->TransferenciaAlmacen["FechaTraslado"] = $hoy;
          $this->TransferenciaAlmacen["IdTipoDocumento"] = ID_TIPO_DOCUMENTO_TRANSFERENCIA_ALMACEN;
          $tipodocumento=$this->sTipoDocumento->ObtenerTipoDocumentoPorId($this->TransferenciaAlmacen);
          $this->TransferenciaAlmacen["CodigoTipoDocumento"] = $tipodocumento[0]["CodigoTipoDocumento"];
          $this->TransferenciaAlmacen["NombreTipoDocumento"] = $tipodocumento[0]["NombreTipoDocumento"];
          $this->TransferenciaAlmacen["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();                    
          $Sedes = $this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario();
          
          if(count($Sedes) > 0) {
            $this->TransferenciaAlmacen["IdAsignacionSedeOrigen"] = $Sedes[0]["IdAsignacionSede"];
            $this->TransferenciaAlmacen["CodigoSedeOrigen"] = $Sedes[0]["CodigoSede"];
            $this->TransferenciaAlmacen["NombreSedeOrigen"] = $Sedes[0]["NombreSede"];            

            $this->TransferenciaAlmacen["IdAsignacionSedeDestino"] = $Sedes[0]["IdAsignacionSede"];
            $this->TransferenciaAlmacen["CodigoSedeDestino"] = $Sedes[0]["CodigoSede"];
            $this->TransferenciaAlmacen["NombreSedeDestino"] = $Sedes[0]["NombreSede"];
            $this->TransferenciaAlmacen["NombreAlmacen"] = $Sedes[0]["NombreSede"];
          }
          
          $this->TransferenciaAlmacen["GuiaRemisionEmisor"] = "";
          $this->TransferenciaAlmacen["Observacion"] = "";

          $this->TransferenciaAlmacen["ParametroLote"] = $this->sConstanteSistema->ObtenerParametroLote();
          $this->TransferenciaAlmacen["ParametroDua"] = $this->sConstanteSistema->ObtenerParametroDua();
          $this->TransferenciaAlmacen["ParametroDocumentoSalidaZofra"] = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
          $this->TransferenciaAlmacen["DetallesTransferenciaAlmacen"][] = $this->sDetalleTransferenciaAlmacen->Cargar();
          $data =array(            
            'SeriesDocumento' => $SeriesDocumento,
            'Sedes' => $Sedes,
            'NuevoDetalleTransferenciaAlmacen' =>$this->TransferenciaAlmacen["DetallesTransferenciaAlmacen"][0]
          );

          $resultado = array_merge($this->TransferenciaAlmacen,$data);
          
          return $resultado;
        }

        function ObtenerNumeroTotalTransferenciaAlmacen($data) {
            $resultado = $this->mTransferenciaAlmacen->ObtenerNumeroTotalTransferenciaAlmacen($data);
            return $resultado;          
        }

        function ConsultarTransferenciasAlmacen($data,$numeropagina,$numerofilasporpagina) {
          $numerofilainicio=$numerofilasporpagina * ($numeropagina - 1);
          $resultado = $this->mTransferenciaAlmacen->ConsultarTransferenciasAlmacen($data,$numerofilainicio,$numerofilasporpagina);
          
          $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          
          foreach ($resultado as $key => $item) {
            $parametro['IdTipoDocumento'] = $item["IdTipoDocumento"];
            //$parametro['IdSubTipoDocumento'] = $item["IdSubTipoDocumento"];
            $resultado[$key]["FechaTraslado"] =convertirFechaES($resultado[$key]["FechaTraslado"]);
            $resultado[$key]["DetallesTransferenciaAlmacen"] =[];
            $SeriesDocumento = $this->sCorrelativoDocumento->ListarSeriesDocumento($parametro);
            $resultado[$key]["SeriesDocumento"] = $SeriesDocumento;                                                      
          }
      
          return $resultado;
        }
        
        function ObtenerNumeroFilasPorPagina() {
          $input["IdParametroSistema"] = ID_NUM_POR_PAGINA_MERCADERIA;
          $parametro=$this->sParametroSistema->ObtenerParametroSistemaPorIdParametroSistema($input);
          $numerofilasporpagina=$parametro->ValorParametroSistema;
          return $numerofilasporpagina;
        }
      
        
        function InsertarTransferenciaAlmacen($data) {
          try {                        
            $data["FechaTraslado"]=convertToDate($data["FechaTraslado"]);
            $resultado = $this->mTransferenciaAlmacen->InsertarTransferenciaAlmacen($data);
            $resultado=$this->sDetalleTransferenciaAlmacen->InsertarDetallesTransferenciaAlmacen($resultado);
            $dataCorrelativo["IdCorrelativoDocumento"] = $data["IdCorrelativoDocumento"];
            $UltimoDocumento=$this->sCorrelativoDocumento->IncrementarCorrelativoDocumento($dataCorrelativo);
            $resultado["NumeroTransferencia"] =$UltimoDocumento;
            $this->mTransferenciaAlmacen->ActualizarTransferenciaAlmacen($resultado);

            $resultado["NumeroTransferencia"] =str_pad($UltimoDocumento, CANTIDAD_LETRA_NUMERO_DOCUMENTO, '0', STR_PAD_LEFT);
            $resultado["FechaTraslado"] =convertirFechaES($resultado["FechaTraslado"]);

            return $resultado;
          
          } catch (Exception $e) {            
            throw new Exception($e->getMessage(),$e->getCode(),$e);            
          }
        }
       
        function ActualizarTransferenciaAlmacen($data) {          
          $data["FechaTraslado"]=convertToDate($data["FechaTraslado"]);
          $resultado = $this->sDetalleTransferenciaAlmacen->ActualizarDetallesTransferenciaAlmacen($data);          
          $this->mTransferenciaAlmacen->ActualizarTransferenciaAlmacen($data);
          $resultado["FechaTraslado"] = convertirFechaES($resultado["FechaTraslado"]);
          return $resultado;          
        }

        function AnularTransferenciaAlmacen($data) {
          $data["FechaTraslado"] = convertToDate($data["FechaTraslado"]);
          $data["IndicadorEstado"] = ESTADO_ANULADO;
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();          
          $resultadoTransferenciaAlmacen = $this->sDetalleTransferenciaAlmacen->AnularDetallesTransferenciaAlmacen($data);
          $resultado=$this->mTransferenciaAlmacen->ActualizarTransferenciaAlmacen($data);
          $resultado["IdProductos"]=$resultadoTransferenciaAlmacen["IdProductos"];
          $resultado["FechaTraslado"] = convertirFechaES($resultado["FechaTraslado"]);          
          return $resultado;
        }

        function BorrarTransferenciaAlmacen($data) {           
          $data["FechaTraslado"] = convertToDate($data["FechaTraslado"]);
          $data["IndicadorEstado"] = ESTADO_ELIMINADO;
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();          
          $resultadoTransferenciaAlmacen = $this->sDetalleTransferenciaAlmacen->BorrarDetallesTransferenciaAlmacen($data);
          $resultado=$this->mTransferenciaAlmacen->ActualizarTransferenciaAlmacen($data);
          $resultado["IdProductos"]=$resultadoTransferenciaAlmacen["IdProductos"];
          $resultado["FechaTraslado"] = convertirFechaES($resultado["FechaTraslado"]);          
          return $resultado;        
        }
                       
}
