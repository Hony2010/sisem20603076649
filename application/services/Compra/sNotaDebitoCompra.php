<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Compra\sComprobanteCompra.php');

class sNotaDebitoCompra extends sComprobanteCompra {

        public $DetalleNotaDebitoCompra = array();

        public function __construct()
        {
          parent::__construct();

      		$this->load->service("Compra/sDocumentoReferenciaCompra");
      		$this->load->service("Compra/sDetalleNotaDebitoCompra");
      		$this->load->service("Configuracion/Venta/sMotivoNotaDebito");
      		$this->load->service("Configuracion/Venta/sConceptoNotaCreditoDebito");
          $DetalleNotaDebitoCompra = [];
          $DetalleNotaDebitoCompra[] = $this->sDetalleNotaDebitoCompra->Cargar();
          $this->ComprobanteCompra["DetallesNotaDebitoCompra"] = $DetalleNotaDebitoCompra;
        }

        function CargarNotaDebitoCompra()
        {
          $parametro['IdTipoDocumento'] = ID_TIPODOCUMENTO_NOTADEBITO;
          $parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          $resultado = parent::Cargar($parametro);

          $resultado["ActualizarDetalle"] = "0";
          $resultado["TotalProporcional"] = "0";
          $resultado["NombreAlmacen"] = $this->sSede->ListarSedesTipoAlmacen()[0]["NombreSede"];
          $resultado["MotivoMovimiento"] = "DESDE NOTA CREDITO";
          $resultado["EstadoPendienteNota"] = '0';

          $fechaservidor=$this->Base->ObtenerFechaServidor("d/m/Y");

          $resultado["TotalSaldo"] = "00.00";
          $resultado["Concepto"] = "";
      		$resultado["MontoLetra"] = "";
      		$resultado["Porcentaje"] = "0.00";
      		$resultado["Importe"] = "0.00";
          $resultado["IdSede"] = $parametro['IdSedeAgencia']; 
          $resultado["IdPersona"] = 0;
      		$resultado["FechaIngreso"] = $fechaservidor;
      		$MotivosNotaDebitoCompra = $this->sMotivoNotaDebito->ListarMotivosNotaDebitoCompra();
      		$ConceptosNotaDebitoCompra = $this->sConceptoNotaCreditoDebito->ListarConceptosNotaDebito();

      		$resultado["MotivosNotaDebitoCompra"] = $MotivosNotaDebitoCompra;
      		$resultado["ConceptosNotaDebitoCompra"] = $ConceptosNotaDebitoCompra;
      		$resultado["BusquedaComprobantesCompraND"] = array();
      		$resultado["BusquedaComprobanteCompraND"] = array();
      		$resultado["MiniComprobantesCompraND"] = array();//$this->ComprobanteCompra;//array();
      		$resultado["GrupoDetalleComprobanteCompra"] = array();
          $resultado["DocumentosReferencia"] = array();

          $resultado['NuevoDetalleNotaDebitoCompra']=$this->sDetalleNotaDebitoCompra->Cargar();

          return $resultado;
        }


        /*****/
        function InsertarNotaDebitoCompra($data)
        {
          $data["DetallesComprobanteCompra"] = $data["DetallesNotaDebitoCompra"];
          $resultado = parent::InsertarComprobanteCompra($data);

          $idMotivoNota = $data["MotivoNotaDebitoCompra"]["Data"]["IdMotivoNotaDebito"];

    			if(is_array($resultado))
    			{
            $data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];
    				if(array_key_exists("MiniComprobantesCompraND", $data))
    				{
              $this->InsertarDocumentosReferencia($data);
    				}

            if($idMotivoNota == ID_MOTIVO_NOTA_DEBITO_AUMENTO_VALOR)
            {
              $this->sMovimientoAlmacen->ActualizarMovimientosAlmacenNotaDebitoCompra($resultado["DetallesComprobanteCompra"]);
            }
    				$resultado["DocumentosReferencia"] = $data["MiniComprobantesCompraND"];
    				return $resultado;
    			}
    			else{
    				$data_error["error"]["msg"] = $resultado;
    				return $data_error;
    			}
        }

        function InsertarDocumentosReferencia($data)
        {
          foreach ($data["MiniComprobantesCompraND"] as $key => $value) {
						$value["IdComprobanteNota"] = $data["IdComprobanteCompra"];
						$documentoreferencia = $this->sDocumentoReferenciaCompra->InsertarDocumentoReferenciaCompra($value);
					}
          return "";
        }

        function ActualizarNotaDebitoCompra($data)
        {
          $data["DetallesComprobanteCompra"] = $data["DetallesNotaDebitoCompra"];

          $idMotivoNota = $data["MotivoNotaDebitoCompra"]["Data"]["IdMotivoNotaDebito"];

          $this->sMovimientoAlmacen->DescontarParaActualizarMovimientosAlmacenNotaDebitoCompra($data);
          //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
          $this->sDocumentoReferenciaCompra->BorrarDocumentoReferenciaCompra($data);
          $otra_data = $data;
    			foreach ($otra_data["DetallesComprobanteCompra"] as $key => $value) {
    				$otra_data["DetallesComprobanteCompra"][$key]["SaldoPendienteNotaCredito"] = 0;
            $otra_data["DetallesComprobanteCompra"][$key]["SaldoPendienteEntrada"] = $value["Cantidad"];
    			}
          $resultado = parent::ActualizarComprobanteCompra($otra_data);

          if(is_array($resultado))
    			{
            $data["IdComprobanteCompra"] = $resultado["IdComprobanteCompra"];

    				if(array_key_exists("MiniComprobantesCompraND", $data))
    				{
              $this->InsertarDocumentosReferencia($data);
    				}

            if($idMotivoNota == ID_MOTIVO_NOTA_DEBITO_AUMENTO_VALOR)
            {
              $this->sMovimientoAlmacen->ActualizarMovimientosAlmacenNotaDebitoCompra($data["DetallesComprobanteCompra"]);
            }

    				$resultado["DocumentosReferencia"] = $data["MiniComprobantesCompraND"];
    				return $resultado;
    			}
    			else{
    				$data_error["error"]["msg"] = $resultado;
    				return $data_error;
    			}

        }

        function BorrarNotaDebitoCompraDesdeServicioCompra($data)
        {
          $this->sMovimientoAlmacen->DescontarParaActualizarMovimientosAlmacenNotaDebitoCompra($data);
          //BORRAR REFERENCIAS EN TABLA DOCUMENTO REFERENCIAS
          $this->sDocumentoReferenciaCompra->BorrarDocumentoReferenciaCompra($data);
        }

}
