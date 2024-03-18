<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mMovimientoDocumentoDua extends CI_Model {

        public $MovimientoDocumentoDua = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->MovimientoDocumentoDua = $this->Base->Construir("MovimientoDocumentoDua");
        }


        function InsertarMovimientoDocumentoDua($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoDocumentoDua);
          $this->db->insert('MovimientoDocumentoDua', $resultado);
          $resultado["IdMovimientoDocumentoDua"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarMovimientoDocumentoDua($data)
        {
          $id=$data["IdMovimientoDocumentoDua"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->MovimientoDocumentoDua);
          $this->db->where('IdMovimientoDocumentoDua', $id);
          $this->db->update('MovimientoDocumentoDua', $resultado);

          return $resultado;
        }

        function BorrarMovimientoDocumentoDua($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarMovimientoDocumentoDua($data);
        }

        //VALIDADO HASTA AQUI
        function ObtenerMovimientoDocumentoDuaPorNotaEntradaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoDua MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoDuaPorNotaEntradaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoDua MA
            inner join notaentrada NE ON NE.IdNotaEntrada = MA.IdNotaEntrada
            inner join documentoreferencianotaentrada DRNE on DRNE.IdNotaEntrada = MA.IdNotaEntrada
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNE.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoDuaPorNotaSalidaComprobanteVenta($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteVenta"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoDua MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteVenta = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoDuaPorNotaSalidaComprobanteCompra($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $comprobante=$data["IdComprobanteCompra"];
          $query = $this->db->query("select MA.*
            FROM MovimientoDocumentoDua MA
            inner join notasalida NS ON NS.IdNotaSalida = MA.IdNotaSalida
            inner join documentoreferencianotasalida DRNS on DRNS.IdNotaSalida = MA.IdNotaSalida
            WHERE MA.IdProducto = '$id' AND MA.IdAsignacionSede = '$sede' AND DRNS.IdComprobanteCompra = '$comprobante' AND MA.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerUltimoRegistroPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select * from MovimientoDocumentoDua
                                     WHERE IdProducto = '$id' AND IndicadorEstado = 'A'
                                     ORDER BY IdMovimientoDocumentoDua DESC LIMIT 1");
          $resultado = $query->result_array();
          return $resultado[0];
        }

        function ObtenerMovimientosPorProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select *
              from MovimientoDocumentoDua
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
              from MovimientoDocumentoDua
              where IdInventarioInicial IS NULL AND IdAsignacionSede = '$sede' AND IdProducto = '$id' AND IndicadorEstado = 'A'
              ORDER BY FechaMovimiento");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnMovimientoDocumentoDua()
        {
          $query = $this->db->query("Select distinct IdProducto
              from MovimientoDocumentoDua
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnMovimientoDocumentoDua($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from MovimientoDocumentoDua
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function DocumentosPorProductoSedeEnMovimientoDocumentoDua($data)
        {
          $id = $data["IdProducto"];
          $sede = $data["IdAsignacionSede"];
          $query = $this->db->query("Select distinct IdDua
              from MovimientoDocumentoDua
              where IdProducto = '$id' and IdAsignacionSede = '$sede' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorProductoSedeDocumento($data)
        {
          $id=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $documento=$data["IdDua"];
          $extraFecha = "";
          $extraFecha2 = "";

          if(array_key_exists('FechaInicial', $data) && array_key_exists('FechaFinal', $data))
          {
            $fechaInicial =$data['FechaInicial'];
            $fechaFinal =$data['FechaFinal'];
            $extraFecha = "and (D.FechaEmisionDua between '$fechaInicial' and '$fechaFinal')";
          }

          if(array_key_exists('FechaMovimientoInicial', $data) && array_key_exists('FechaMovimientoFinal', $data))
          {
            $fechaInicialMovimiento =$data['FechaMovimientoInicial'];
            $fechaFinalMovimiento =$data['FechaMovimientoFinal'];
            $extraFecha2 = "and (MD.FechaMovimiento between '$fechaInicialMovimiento' and '$fechaFinalMovimiento')";
          }

          $consulta = "select MD.*, MD.IdMovimientoDocumentoDua, MD.IdAsignacionSede, MD.IdDua,D.NumeroDua,
            date_format(D.FechaEmisionDua,'%d/%m/%Y') as FechaEmisionDua,MD.IdProducto,
            M.CodigoMercaderia,P.NombreProducto, MD.FechaMovimiento

            from movimientoDocumentoDua as MD
            inner join Dua as D on D.IdDua=MD.IdDua
            left join NotaSalida NS on NS.IdNotaSalida=MD.IdNotaSalida
            left join NotaEntrada NE on NE.IdNotaEntrada=MD.IdNotaEntrada
            left join InventarioInicial II on II.IdInventarioInicial=MD.IdInventarioInicial
            inner join Producto as P on P.IdProducto=MD.IdProducto
            inner join Mercaderia as M on M.IdProducto=P.IdProducto
            inner join asignacionsede as ASE on ASE.IdAsignacionSede =MD.IdAsignacionSede
            where MD.IdAsignacionSede like '$sede'
            ".$extraFecha."
            and MD.IdDua like '$documento'
            ".$extraFecha2."
            and MD.IdProducto like '$id' and MD.IndicadorEstado='A' and P.IndicadorEstado='A'
            Order by MD.IdAsignacionSede,MD.IdDua,MD.IdProducto,P.NombreProducto, MD.FechaMovimiento,
            field(MD.CodigoTipoDocumento,'SI','NE','NS'),
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
                  from MovimientoDocumentoDua
                  where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarProductoDuaInventarioInicial($data)
        {
          $id=$data["IdProducto"];
          $dua=$data["IdDua"];
          $query = $this->db->query("Select *
                  from MovimientoDocumentoDua
                  where IdInventarioInicial IS NOT NULL AND IdProducto = '$id' AND IdDua = '$dua' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorInventarioInicial($data)
        {
          $id=$data["IdInventarioInicial"];
          $query = $this->db->query("Select * from MovimientoDocumentoDua
                  where IdInventarioInicial = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaEntrada($data)
        {
          $id=$data["IdNotaEntrada"];
          $query = $this->db->query("Select * from MovimientoDocumentoDua
                  where IdNotaEntrada = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientosPorNotaSalida($data)
        {
          $id=$data["IdNotaSalida"];
          $query = $this->db->query("Select * from MovimientoDocumentoDua
                  where IdNotaSalida = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoDuaPorNotaEntrada($data)
        {
          $id=$data["IdMovimientoDocumentoDua"];
          $query = $this->db->query("Select
                  IF(DRNE.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRNE.IdComprobanteCompra, DRNE.IdComprobanteVenta) as IdComprobante,
                  IF(DRNE.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                  IF(DRNE.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                  DRNE.*,
                  MA.*
                  from MovimientoDocumentoDua MA
                  inner join NotaEntrada NE on NE.IdNotaEntrada=MA.IdNotaEntrada
                  inner join DocumentoReferenciaNotaEntrada DRNE on DRNE.IdNotaEntrada=NE.IdNotaEntrada
                  left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNE.IdComprobanteVenta
                  left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNE.IdComprobanteCompra
                  left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                  left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                  where MA.IdMovimientoDocumentoDua = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerMovimientoDocumentoDuaPorNotaSalida($data)
        {
          $id=$data["IdMovimientoDocumentoDua"];
          $query = $this->db->query("Select
                IF(DRNS.IdComprobanteVenta IS NULL, 'C', 'V') as DescripcionTipoOperacion,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdTipoDocumento, CV.IdTipoDocumento) as IdTipoDocumento,
                IF(DRNS.IdComprobanteVenta IS NULL, DRNS.IdComprobanteCompra, DRNS.IdComprobanteVenta) as IdComprobante,
                IF(DRNS.IdComprobanteVenta IS NULL, DRC.IdComprobanteCompra, DRV.IdComprobanteVenta) as IdDocumentoReferencia,
                IF(DRNS.IdComprobanteVenta IS NULL, CC.IdAsignacionSede, CV.IdAsignacionSede) as SedeDescripcion,
                DRNS.*,
                MA.*
                from MovimientoDocumentoDua MA
                inner join NotaSalida NS on NS.IdNotaSalida=MA.IdNotaSalida
                inner join DocumentoReferenciaNotaSalida DRNS on DRNS.IdNotaSalida=NS.IdNotaSalida
                left join ComprobanteVenta CV on CV.IdComprobanteVenta = DRNS.IdComprobanteVenta
                left join ComprobanteCompra CC on CC.IdComprobanteCompra = DRNS.IdComprobanteCompra
                left join DocumentoReferenciaCompra DRC on DRC.IdComprobanteNota = CC.IdComprobanteCompra
                left join DocumentoReferencia DRV on DRV.IdComprobanteNota = CV.IdComprobanteVenta
                where MA.IdMovimientoDocumentoDua = '$id' AND MA.IndicadorEstado = 'A'
                  ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ActualizarFechaParaInventariosInicial($data)
        {
          $fecha=$data["FechaMovimiento"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("UPDATE MovimientoDocumentoDua MDD
              SET MDD.FechaMovimiento = '$fecha'
              WHERE MDD.IdAsignacionSede = '$sede' AND MDD.IndicadorEstado = 'A' AND MDD.IdInventarioInicial IS NOT NULL
              ");
          $resultado = $this->db->affected_rows();
          return $resultado;
        }
 }
