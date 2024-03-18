<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMovimientoDocumentoSalidaZofra extends CI_Model {

        public $MovimientoDocumentoSalidaZofra = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->MovimientoDocumentoSalidaZofra = $this->Base->Construir("MovimientoDocumentoSalidaZofra");
        }


        function InsertarMovimientoDocumentoSalidaZofra($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoDocumentoSalidaZofra);
          $this->db->insert('MovimientoDocumentoSalidaZofra', $resultado);
          $resultado["IdMovimientoDocumentoSalidaZofra"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarMovimientoDocumentoSalidaZofra($data)
        {
          $id=$data["IdMovimientoDocumentoSalidaZofra"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoDocumentoSalidaZofra);
          $this->db->where('IdMovimientoDocumentoSalidaZofra', $id);
          $this->db->update('MovimientoDocumentoSalidaZofra', $resultado);

          return $resultado;
        }

        function BorrarMovimientoDocumentoSalidaZofra($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMovimientoDocumentoSalidaZofra($data);
        }

        function ObtenerMovimientoDocumentoSalidaZofraPorNotaEntradaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoSalidaZofra MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoSalidaZofraPorNotaEntradaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoSalidaZofra MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoSalidaZofraPorNotaSalidaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoSalidaZofra MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoSalidaZofraPorNotaSalidaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoSalidaZofra MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerUltimoRegistroPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select * from MovimientoDocumentoSalidaZofra
                                     WHERE IdProducto = '$id' AND IndicadorEstado = 'A'
                                     ORDER BY IdMovimientoDocumentoSalidaZofra DESC LIMIT 1");
          $resultado = $query->result_array();
          return $resultado[0];
        }

        function ObtenerMovimientosPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select *
              from MovimientoDocumentoSalidaZofra
              where IdInventarioInicial IS NULL AND IdProducto = '$id' AND IndicadorEstado = 'A'
              ORDER BY FechaMovimiento");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorProductoAlmacen($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("Select *
              from MovimientoDocumentoSalidaZofra
              where IdInventarioInicial IS NULL AND IdAsignacionSede = '$sede' AND IdProducto = '$id' AND IndicadorEstado = 'A'
              ORDER BY FechaMovimiento");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnMovimientoDocumentoSalidaZofra()
        {
          $query = $this->db->query("Select distinct IdProducto
              from MovimientoDocumentoSalidaZofra
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoDocumentoSalidaZofra($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from MovimientoDocumentoSalidaZofra
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function DocumentosPorProductoSedeEnMovimientoDocumentoSalidaZofra($data)
        {
          $id = $data["IdProducto"];
          $sede = $data["IdAsignacionSede"];
          $query = $this->db->query("Select distinct IdDocumentoSalidaZofra
              from MovimientoDocumentoSalidaZofra
              where IdProducto = '$id' and IdAsignacionSede = '$sede' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorProductoSedeDocumento($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $documento=$data["IdDocumentoSalidaZofra"];
          $extraFecha = "";
          $extraFecha2 = "";

          if(array_key_exists('FechaInicial', $data) && array_key_exists('FechaFinal', $data))
          {
            $fechaInicial =$data['FechaInicial'];
            $fechaFinal =$data['FechaFinal'];
            $extraFecha = "and (DZ.FechaEmisionDocumentoSalidaZofra between '$fechaInicial' and '$fechaFinal')";
          }

          if(array_key_exists('FechaMovimientoInicial', $data) && array_key_exists('FechaMovimientoFinal', $data))
          {
            $fechaInicialMovimiento =$data['FechaMovimientoInicial'];
            $fechaFinalMovimiento =$data['FechaMovimientoFinal'];
            $extraFecha2 = "and (MDZ.FechaMovimiento between '$fechaInicialMovimiento' and '$fechaFinalMovimiento')";
          }

          $consulta = "select MDZ.*, MDZ.IdMovimientoDocumentoSalidaZofra, MDZ.IdAsignacionSede, MDZ.IdDocumentoSalidaZofra,DZ.NumeroDocumentoSalidaZofra,
              date_format(DZ.FechaEmisionDocumentoSalidaZofra,'%d/%m/%Y') as FechaEmisionDocumentoSalidaZofra,
              MDZ.IdProducto,M.CodigoMercaderia,P.NombreProducto, MDZ.FechaMovimiento
              from MovimientoDocumentoSalidaZofra as MDZ
              inner join DocumentoSalidaZofra as DZ on DZ.IdDocumentoSalidaZofra=MDZ.IdDocumentoSalidaZofra
              left join NotaSalida NS on NS.IdNotaSalida=MDZ.IdNotaSalida
              left join NotaEntrada NE on NE.IdNotaEntrada=MDZ.IdNotaEntrada
              left join InventarioInicial II on II.IdInventarioInicial=MDZ.IdInventarioInicial
              inner join Producto as P on P.IdProducto=MDZ.IdProducto
              inner join Mercaderia as M on M.IdProducto=P.IdProducto

              where MDZ.IdAsignacionSede like '$sede'
              ".$extraFecha."
              and MDZ.IdDocumentoSalidaZofra like '$documento'
              ".$extraFecha2."
              and MDZ.IdProducto like '$id' and MDZ.IndicadorEstado='A' and P.IndicadorEstado='A'

              Order by MDZ.IdAsignacionSede,MDZ.IdDocumentoSalidaZofra,MDZ.IdProducto,P.NombreProducto, FechaMovimiento,
              field(MDZ.CodigoTipoDocumento,'SI','NE','NS'),
              II.SerieInventarioInicial, NE.SerieNotaEntrada, NS.SerieNotaSalida, II.NumeroInventarioInicial,
              NE.NumeroNotaEntrada, NS.NumeroNotaSalida";
          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }


        function ConsultarProductoInventarioInicial($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select *
                  from MovimientoDocumentoSalidaZofra
                  where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarProductoAlmacenInventarioInicial($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $documento=$data["IdDocumentoSalidaZofra"];
          $query = $this->db->query("Select *
                  from MovimientoDocumentoSalidaZofra
                  where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IdAsignacionSede = '$sede' AND IdDocumentoSalidaZofra like '$documento' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorInventarioInicial($data)
        {
          $id=$data["IdInventarioInicial"];
          $query = $this->db->query("Select * from MovimientoDocumentoSalidaZofra
                  where IdInventarioInicial = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaEntrada($data)
        {
          $id=$data["IdNotaEntrada"];
          $query = $this->db->query("Select * from MovimientoDocumentoSalidaZofra
                  where IdNotaEntrada = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaSalida($data)
        {
          $id=$data["IdNotaSalida"];
          $query = $this->db->query("Select * from MovimientoDocumentoSalidaZofra
                  where IdNotaSalida = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoSalidaZofraPorNotaEntrada($data)
        {
          $id=$data["IdMovimientoDocumentoSalidaZofra"];
          $query = $this->db->query("Select
                  IF(DRNE.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRNE.IdComprobanteCompra, DRNE.IdComprobanteVenta) as IdComprobante,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                  DRNE.*,
                  MA.*
                  from MovimientoDocumentoSalidaZofra MA
                  inner join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
                  inner join DocumentoReferenciaNotaEntrada DRNE on DRNE.IdNotaEntrada=NE.IdNotaEntrada
                  left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNE.IdComprobanteVenta
                  left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNE.IdComprobanteCompra
                  left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                  left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                  where MA.IdMovimientoDocumentoSalidaZofra = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoSalidaZofraPorNotaSalida($data)
        {
          $id=$data["IdMovimientoDocumentoSalidaZofra"];
          $query = $this->db->query("Select
                IF(DRNS.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                IF(DRNS.IdComprobanteVenta IS NULL, DRNS.IdComprobanteCompra, DRNS.IdComprobanteVenta) as IdComprobante,
                IF(DRNS.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                DRNS.*,
                MA.*
                from MovimientoDocumentoSalidaZofra MA
                inner join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
                inner join DocumentoReferenciaNotaSalida DRNS on DRNS.IdNotaSalida=NS.IdNotaSalida
                left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNS.IdComprobanteVenta
                left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNS.IdComprobanteCompra
                left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                where MA.IdMovimientoDocumentoSalidaZofra = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ActualizarFechaParaInventariosInicial($data)
        {
          $fecha=$data["FechaMovimiento"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("UPDATE MovimientoDocumentoSalidaZofra MDSZ
              SET MDSZ.FechaMovimiento = '$fecha'
              WHERE MDSZ.IdAsignacionSede = '$sede' AND MDSZ.IndicadorEstado = 'A' AND MDSZ.IdInventarioInicial IS NOT NULL");
          $resultado = $this->db->affected_rows();
          return $resultado;
        }

        //SE HACE LA BUSQUEDA DE LOS PRODUCTOS PARA SUMARLOS EN PARTE DE ALMACEN GENERAL
        function ObtenerMovimientoDocumentoSalidaZofraPorProductoYAlmacen($data)
        {
          $producto=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("SELECT *
            FROM movimientodocumentosalidazofra
            WHERE idproducto = '$producto' AND idasignacionsede = '$sede' AND indicadorestado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
