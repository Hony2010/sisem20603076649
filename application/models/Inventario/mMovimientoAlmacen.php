<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMovimientoAlmacen extends CI_Model {

        public $MovimientoAlmacen = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->MovimientoAlmacen = $this->Base->Construir("MovimientoAlmacen");
        }


        function InsertarMovimientoAlmacen($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoAlmacen);
          $this->db->insert('MovimientoAlmacen', $resultado);
          $resultado["IdMovimientoAlmacen"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarMovimientoAlmacen($data) {
          $id=$data["IdMovimientoAlmacen"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoAlmacen);
          $this->db->where('IdMovimientoAlmacen', $id);
          $this->db->update('MovimientoAlmacen', $resultado);

          return $resultado;
        }

        function BorrarMovimientoAlmacen($data) {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMovimientoAlmacen($data);
        }

        function ObtenerMovimientoAlmacenPorIdMovimientoAlmacen($data)
        {
          $id=$data["IdMovimientoAlmacen"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacen MA
            WHERE MA.IdMovimientoAlmacen = '$id' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->row();//$query->result_array();
          return (array) $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaEntradaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacen MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaEntradaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacen MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaSalidaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacen MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaSalidaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacen MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerUltimoRegistroPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select * from MovimientoAlmacen
                                     WHERE IdProducto = '$id' AND IndicadorEstado = 'A'
                                     ORDER BY IdMovimientoAlmacen DESC LIMIT 1");
          $resultado = $query->result_array();
          return $resultado[0];
        }

        function ObtenerMovimientosPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select *
              from MovimientoAlmacen
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
              from MovimientoAlmacen
              where IdInventarioInicial IS NULL AND IdAsignacionSede = '$sede' AND IdProducto = '$id' AND IndicadorEstado = 'A'
              ORDER BY FechaMovimiento");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnMovimientoAlmacen()
        {
          $query = $this->db->query("Select distinct IdProducto
              from MovimientoAlmacen
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoAlmacen($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from MovimientoAlmacen
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorProductoSede($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $extraFecha = "";

          if(array_key_exists('FechaInicial', $data) && array_key_exists('FechaFinal', $data))
          {
            $fechaInicial =$data['FechaInicial'];
            $fechaFinal =$data['FechaFinal'];
            $extraFecha = " MA.FechaMovimiento between '$fechaInicial' and '$fechaFinal' and ";
          }

          $consulta = "select MA.*, MA.IdAsignacionSede, M.CodigoMercaderia,P.NombreProducto,MA.FechaMovimiento,
              MA.CostoUnitarioTotal as CostoUnitarioTotal,
              MA.EntradaValorado as EntradaValorado,
              MA.SalidaValorado as SalidaValorado,
              MA.SaldoValorado as SaldoValorado,
              MA.CostoUnitarioPromedio as CostoUnitarioPromedio
              from movimientoalmacen as MA
              left join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
              left join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
              left join InventarioInicial II on II.IdInventarioInicial=MA.IdInventarioInicial
              inner join Producto as P on P.IdProducto=MA.IdProducto
              inner join Mercaderia as M on M.IdProducto=P.IdProducto
              where
              ".$extraFecha."
              MA.IdAsignacionSede like '$sede' and M.IdProducto like '$id'
              and MA.IndicadorEstado='A' and P.IndicadorEstado='A'
              and MA.IndicadorDocumentoIngresoZofra = '0'
              group by MA.IdMovimientoAlmacen
              Order by MA.IdAsignacionSede,CodigoMercaderia, FechaMovimiento, field(CodigoTipoDocumento,'SI','NE','NS'), II.SerieInventarioInicial, NE.SerieNotaEntrada, NS.SerieNotaSalida, II.NumeroInventarioInicial,
              NE.NumeroNotaEntrada, NS.NumeroNotaSalida";
          $query = $this->db->query($consulta);
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorProductoSedeFiltrado($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $extraFecha = "";

          if(array_key_exists('FechaInicial', $data) && array_key_exists('FechaFinal', $data))
          {
            $fechaInicial =$data['FechaInicial'];
            $fechaFinal =$data['FechaFinal'];
            $extraFecha = " (MA.FechaMovimiento between '$fechaInicial' and '$fechaFinal') and ";
          }

          $consulta = "select MA.*, MA.IdAsignacionSede, M.CodigoMercaderia,P.NombreProducto,MA.FechaMovimiento,
              MA.CostoUnitarioTotal as CostoUnitarioTotal,
              MA.EntradaValorado as EntradaValorado,
              MA.SalidaValorado as SalidaValorado,
              MA.SaldoValorado as SaldoValorado,
              MA.CostoUnitarioPromedio as CostoUnitarioPromedio
              from movimientoalmacen as MA
              left join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
              left join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
              left join InventarioInicial II on II.IdInventarioInicial=MA.IdInventarioInicial
              left join DocumentoReferenciaNotaEntrada DRNE on DRNE.IdNotaEntrada=NE.IdNotaEntrada
              left join DocumentoReferenciaNotaSalida DRNS on DRNS.IdNotaSalida=NS.IdNotaSalida
              inner join Producto as P on P.IdProducto=MA.IdProducto
              inner join Mercaderia as M on M.IdProducto=P.IdProducto
              where
              ".$extraFecha."
              MA.IdAsignacionSede like '$sede' and M.IdProducto like '$id'
              and MA.IndicadorEstado='A' and MA.IndicadorDocumentoIngresoZofra='0'
              Order by MA.IdAsignacionSede,CodigoMercaderia, FechaMovimiento, field(CodigoTipoDocumento,'SI','NE','NS',''),
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
                  from MovimientoAlmacen
                  where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarProductoAlmacenInventarioInicial($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("Select *
                  from MovimientoAlmacen
                  where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IdAsignacionSede = '$sede' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaEntrada($data)
        {
          $id=$data["IdNotaEntrada"];
          $query = $this->db->query("Select * from MovimientoAlmacen
                  where IdNotaEntrada = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaSalida($data)
        {
          $id=$data["IdNotaSalida"];
          $query = $this->db->query("Select * from MovimientoAlmacen
                  where IdNotaSalida = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorInventarioInicial($data)
        {
          $id=$data["IdInventarioInicial"];
          $query = $this->db->query("Select * from MovimientoAlmacen
                  where IdInventarioInicial = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaEntrada($data)
        {
          $id=$data["IdMovimientoAlmacen"];
          $query = $this->db->query("Select
                  IF(DRNE.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRNE.IdComprobanteCompra, DRNE.IdComprobanteVenta) as IdComprobante,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                  DRNE.*,
                  MA.*
                  from MovimientoAlmacen MA
                  inner join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
                  inner join DocumentoReferenciaNotaEntrada DRNE on DRNE.IdNotaEntrada=NE.IdNotaEntrada
                  left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNE.IdComprobanteVenta
                  left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNE.IdComprobanteCompra
                  left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                  left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                  where MA.IdMovimientoAlmacen = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorNotaSalida($data)
        {
          $id=$data["IdMovimientoAlmacen"];
          $query = $this->db->query("Select
                IF(DRNS.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                IF(DRNS.IdComprobanteVenta IS NULL, DRNS.IdComprobanteCompra, DRNS.IdComprobanteVenta) as IdComprobante,
                IF(DRNS.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                DRNS.*,
                MA.*
                from MovimientoAlmacen MA
                inner join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
                inner join DocumentoReferenciaNotaSalida DRNS on DRNS.IdNotaSalida=NS.IdNotaSalida
                left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNS.IdComprobanteVenta
                left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNS.IdComprobanteCompra
                left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                where MA.IdMovimientoAlmacen = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ActualizarFechaParaInventariosInicial($data)
        {
          $fecha=$data["FechaMovimiento"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("UPDATE MovimientoAlmacen MA
              SET MA.FechaMovimiento = '$fecha'
              WHERE MA.IdAsignacionSede = '$sede' AND MA.IndicadorEstado = 'A' AND MA.IdInventarioInicial IS NOT NULL
              ");
          $resultado = $this->db->affected_rows();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenPorIdTransferenciaAlmacenYIdProducto($data) {
          $IdTransferenciaAlmacen=$data["IdTransferenciaAlmacen"];
          $IdProducto=$data["IdProducto"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacen MA
            WHERE MA.IdTransferenciaAlmacen = '$IdTransferenciaAlmacen' 
            AND MA.IdProducto = '$IdProducto' 
            AND MA.IndicadorEstado = 'A'");
          
          $resultado = $query->result_array(); 
          return $resultado;
        }


 }
