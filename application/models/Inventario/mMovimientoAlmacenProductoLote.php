<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMovimientoAlmacenProductoLote extends CI_Model {

        public $MovimientoAlmacenProductoLote = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->MovimientoAlmacenProductoLote = $this->Base->Construir("MovimientoAlmacenProductoLote");
        }


        function InsertarMovimientoAlmacenProductoLote($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoAlmacenProductoLote);
          $this->db->insert('MovimientoAlmacenProductoLote', $resultado);
          $resultado["IdMovimientoAlmacenProductoLote"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarMovimientoAlmacenProductoLote($data)
        {
          $id=$data["IdMovimientoAlmacenProductoLote"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoAlmacenProductoLote);
          $this->db->where('IdMovimientoAlmacenProductoLote', $id);
          $this->db->update('MovimientoAlmacenProductoLote', $resultado);

          return $resultado;
        }

        function BorrarMovimientoAlmacenProductoLote($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMovimientoAlmacenProductoLote($data);
        }

        function ObtenerMovimientoAlmacenProductoLotePorNotaEntradaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacenProductoLote MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenProductoLotePorNotaEntradaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacenProductoLote MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenProductoLotePorNotaSalidaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacenProductoLote MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenProductoLotePorNotaSalidaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoAlmacenProductoLote MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerUltimoRegistroPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select * from MovimientoAlmacenProductoLote
                                     WHERE IdProducto = '$id' AND IndicadorEstado = 'A'
                                     ORDER BY IdMovimientoAlmacenProductoLote DESC LIMIT 1");
          $resultado = $query->result_array();
          return $resultado[0];
        }

        function ObtenerMovimientosPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select *
              from MovimientoAlmacenProductoLote
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
              from MovimientoAlmacenProductoLote
              where IdInventarioInicial IS NULL AND IdAsignacionSede = '$sede' AND IdProducto = '$id' AND IndicadorEstado = 'A'
              ORDER BY FechaMovimiento");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnMovimientoAlmacenProductoLote()
        {
          $query = $this->db->query("Select distinct IdProducto
              from MovimientoAlmacenProductoLote
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoAlmacenProductoLote($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from MovimientoAlmacenProductoLote
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function DocumentosPorProductoSedeEnMovimientoAlmacenProductoLote($data)
        {
          $id = $data["IdProducto"];
          $sede = $data["IdAsignacionSede"];
          $query = $this->db->query("Select distinct IdLoteProducto
              from MovimientoAlmacenProductoLote
              where IdProducto = '$id' and IdAsignacionSede = '$sede' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorProductoSede($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("select MA.*, MA.IdAsignacionSede, MA.NombreAlmacen,M.CodigoMercaderia,P.NombreProducto,UM.AbreviaturaUnidadMedida, MA.FechaMovimiento,
              case MA.CodigoTipoDocumento
              	when 'SI' then Concat(MA.CodigoTipoDocumento,' ',II.SerieInventarioInicial,'-',II.NumeroInventarioInicial)
              	when 'NE' then Concat(MA.CodigoTipoDocumento,' ',NE.SerieNotaEntrada,'-',NE.NumeroNotaEntrada)
              	when 'NS' then concat(MA.CodigoTipoDocumento,' ',NS.SerieNotaSalida,'-',NS.NumeroNotaSalida)
              	else '' end as Documento,
              MA.RazonSocial, MA.MotivoMovimiento, MA.CantidadEntrada,MA.CantidadSalida, MA.SaldoFisico,

              Case MA.CodigoTipoDocumento
              when 'NE' then concat(DRNE.NombreAbreviadoDocumentoReferencia,' ',DRNE.SerieDocumentoReferencia,'-',DRNE.NumeroDocumentoReferencia)
              when 'NS' then concat(DRNS.NombreAbreviadoDocumentoReferencia,' ',DRNS.SerieDocumentoReferencia,'-',DRNS.NumeroDocumentoReferencia)
              else '' end as ComprobantePago,

              MA.CostoUnitarioTotal, MA.EntradaValorado, MA.SalidaValorado,
              MA.SaldoValorado, CostoUnitarioPromedio,

              case MA.CodigoTipoDocumento
              	when 'SI' then II.Observacion
              	when 'NE' then NE.Observacion
              	else NS.Observacion end as Observacion,
              EMP.CodigoEmpresa, EMP.RazonSocial as NombreEmpresa
              from movimientoalmacen as MA
              left join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
              left join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
              left join InventarioInicial II on II.IdInventarioInicial=MA.IdInventarioInicial
              left join DocumentoReferenciaNotaEntrada DRNE on DRNE.IdNotaEntrada=NE.IdNotaEntrada
              left join DocumentoReferenciaNotaSalida DRNS on DRNS.IdNotaSalida=NS.IdNotaSalida
              inner join Producto as P on P.IdProducto=MA.IdProducto
              inner join Mercaderia as M on M.IdProducto=P.IdProducto
              inner join UnidadMedida as UM on UM.IdUnidadMedida=M.IdUnidadMedida
              Cross join Empresa as EMP

              where MA.IdAsignacionSede like '$sede' and M.IdProducto like '$id'
              and MA.IndicadorEstado='A'
              Order by MA.IdAsignacionSede,CodigoMercaderia, FechaMovimiento, field(CodigoTipoDocumento,'SI','NE','NS'), II.SerieInventarioInicial, NE.SerieNotaEntrada, NS.SerieNotaSalida, II.NumeroInventarioInicial,
              NE.NumeroNotaEntrada, NS.NumeroNotaSalida");
          $resultado = $query->result_array();
          return $resultado;
        }


        // function ConsultarProductoInventarioInicial($data)
        // {
        //   $id=$data["IdProducto"];
        //   $query = $this->db->query("Select *
        //           from MovimientoAlmacenProductoLote
        //           where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IndicadorEstado = 'A'");
        //   $resultado = $query->result_array();
        //   return $resultado;
        // }

        function ConsultarProductoAlmacenInventarioInicial($data)
        {
          $id=$data["IdLoteProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("Select *
                  from MovimientoAlmacenProductoLote
                  where IdInventarioInicial IS NOT NULL AND IdLoteProducto = '$id' AND IdAsignacionSede = '$sede' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorInventarioInicial($data)
        {
          $id=$data["IdInventarioInicial"];
          $query = $this->db->query("Select * from MovimientoAlmacenProductoLote
                  where IdInventarioInicial = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaEntrada($data)
        {
          $id=$data["IdNotaEntrada"];
          $query = $this->db->query("Select * from MovimientoAlmacenProductoLote
                  where IdNotaEntrada = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaSalida($data)
        {
          $id=$data["IdNotaSalida"];
          $query = $this->db->query("Select * from MovimientoAlmacenProductoLote
                  where IdNotaSalida = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenProductoLotePorNotaEntrada($data)
        {
          $id=$data["IdMovimientoAlmacenProductoLote"];
          $query = $this->db->query("Select
                  IF(DRNE.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRNE.IdComprobanteCompra, DRNE.IdComprobanteVenta) as IdComprobante,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                  DRNE.*,
                  MA.*
                  from MovimientoAlmacenProductoLote MA
                  inner join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
                  inner join DocumentoReferenciaNotaEntrada DRNE on DRNE.IdNotaEntrada=NE.IdNotaEntrada
                  left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNE.IdComprobanteVenta
                  left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNE.IdComprobanteCompra
                  left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                  left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                  where MA.IdMovimientoAlmacenProductoLote = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoAlmacenProductoLotePorNotaSalida($data)
        {
          $id=$data["IdMovimientoAlmacenProductoLote"];
          $query = $this->db->query("Select
                IF(DRNS.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                IF(DRNS.IdComprobanteVenta IS NULL, DRNS.IdComprobanteCompra, DRNS.IdComprobanteVenta) as IdComprobante,
                IF(DRNS.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                DRNS.*,
                MA.*
                from MovimientoAlmacenProductoLote MA
                inner join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
                inner join DocumentoReferenciaNotaSalida DRNS on DRNS.IdNotaSalida=NS.IdNotaSalida
                left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNS.IdComprobanteVenta
                left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNS.IdComprobanteCompra
                left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                where MA.IdMovimientoAlmacenProductoLote = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ActualizarFechaParaInventariosInicial($data)
        {
          $fecha=$data["FechaMovimiento"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("UPDATE MovimientoAlmacenProductoLote MAPL
                  SET MAPL.FechaMovimiento = '$fecha'
                  WHERE MAPL.IdAsignacionSede = '$sede' AND MAPL.IndicadorEstado = 'A' AND MAPL.IdInventarioInicial IS NOT NULL
                  ");
          $resultado = $this->db->affected_rows();
          return $resultado;
        }
 }
