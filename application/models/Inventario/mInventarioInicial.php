<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mInventarioInicial extends CI_Model {

        public $InventarioInicial = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->InventarioInicial = $this->Base->Construir("InventarioInicial");
        }

        function ListarInventarioInicial()
        {
          $query = $this->db->query("Select II.*, M.CodigoMercaderia, P.NombreProducto
                FROM InventarioInicial II
                Inner Join Mercaderia M on M.IdProducto = II.IdProducto
                Inner Join Producto P on P.IdProducto = M.IdProducto
                Inner Join TipoExistencia TE on TE.IdTipoExistencia = II.IdTipoExistencia
                Inner Join MotivoInventarioInicial MII on MII.IdMotivoInventarioInicial = II.IdMotivoInventarioInicial
                Inner Join AsignacionSede ASS on ASS.IdAsignacionSede = II.IdAsignacionSede
                Inner Join Sede S on S.IdSede = ASS.IdSede
                WHERE II.IndicadorEstado = 'A' limit 5");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarInventarioInicial($data, $numerofilainicio,$numerorfilasporpagina)
        {
          $criterio=$data["textofiltro"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("Select II.*, M.CodigoMercaderia, UM.NombreUnidadMedida, P.NombreProducto, TE.NombreTipoExistencia, S.NombreSede
                                    FROM InventarioInicial II
                                    Inner Join Mercaderia M on M.IdProducto = II.IdProducto
                                    Inner Join Producto P on P.IdProducto = M.IdProducto
                                    Inner Join UnidadMedida UM on M.IdUnidadMedida = UM.IdUnidadMedida
                                    Inner Join TipoExistencia TE on TE.IdTipoExistencia = II.IdTipoExistencia
                                    Inner Join MotivoInventarioInicial MII on MII.IdMotivoInventarioInicial = II.IdMotivoInventarioInicial
                                    Inner Join AsignacionSede ASS on ASS.IdAsignacionSede = II.IdAsignacionSede
                                    Inner Join Sede S on S.IdSede = ASS.IdSede
                                    WHERE II.IdAsignacionSede = '$sede' AND (P.NombreProducto like '%$criterio%' or M.CodigoMercaderia like '%$criterio%')
                                    AND II.IndicadorEstado = 'A' AND P.IndicadorEstado = 'A'
                                    LIMIT $numerofilainicio,$numerorfilasporpagina");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroTotalInventariosInicial($data)
        {
          $criterio=$data["textofiltro"];
          $sede=$data["IdAsignacionSede"];

          $query = $this->db->query("Select count(II.IdInventarioInicial) as Cantidad
                                    FROM InventarioInicial II
                                    Inner Join Mercaderia M on M.IdProducto = II.IdProducto
                                    Inner Join Producto P on P.IdProducto = M.IdProducto
                                    Inner Join TipoExistencia TE on TE.IdTipoExistencia = II.IdTipoExistencia
                                    Inner Join MotivoInventarioInicial MII on MII.IdMotivoInventarioInicial = II.IdMotivoInventarioInicial
                                    Inner Join AsignacionSede ASS on ASS.IdAsignacionSede = II.IdAsignacionSede
                                    Inner Join Sede S on S.IdSede = ASS.IdSede
                                    WHERE II.IdAsignacionSede = '$sede' AND (P.NombreProducto like '%$criterio%' or M.CodigoMercaderia like '%$criterio%')
                                    AND II.IndicadorEstado = 'A' AND P.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarInventarioInicial($data)
        {
          $data["FechaRegistro"]=$this->shared->now();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->InventarioInicial);
          if( array_key_exists("FechaVencimiento",$data )){
            $resultado["FechaVencimiento"] = $data["FechaVencimiento"] == "" ? null : $data["FechaVencimiento"];
          }
          $this->db->insert('InventarioInicial', $resultado);
          $resultado["IdInventarioInicial"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarInventarioInicial($data)
        {
          $id=$data["IdInventarioInicial"];
          $data["FechaModificacion"]=$this->shared->now();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->InventarioInicial);
          if( array_key_exists("FechaVencimiento",$data )){
            $resultado["FechaVencimiento"] = $data["FechaVencimiento"] == "" ? null : $data["FechaVencimiento"];
          }
          $this->db->where('IdInventarioInicial', $id);
          $this->db->update('InventarioInicial', $resultado);

          return $resultado;
        }

        function BorrarInventarioInicial($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarInventarioInicial($data);
        }

        function ConsultarInventarioInicialPorIdProductoSede($data)
        {
          $producto=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("Select * FROM InventarioInicial
                        WHERE IdProducto = '$producto' AND IdAsignacionSede = '$sede'
                        AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarInventarioInicialPorIdProductoSedeZofra($data)
        {
          $producto=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $zofra=$data["NumeroDocumentoSalidaZofra"];
          $query = $this->db->query("Select * FROM InventarioInicial
                        WHERE IdProducto = '$producto' AND IdAsignacionSede = '$sede'
                        AND NumeroDocumentoSalidaZofra = '$zofra'
                        AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarInventarioInicialPorIdProductoSedeDua($data)
        {
          $producto=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $dua=$data["NumeroDua"];
          $query = $this->db->query("Select * FROM InventarioInicial
                        WHERE IdProducto = '$producto' AND IdAsignacionSede = '$sede'
                        AND NumeroDua = '$dua'
                        AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ActualizarFechaInventariosInicial($data)
        {
          $fecha=$data["FechaMovimiento"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("UPDATE InventarioInicial II
              SET II.FechaInicial = '$fecha'
              WHERE II.IdAsignacionSede = '$sede' AND II.IndicadorEstado = 'A'");
          $resultado = $this->db->affected_rows();
          return $resultado;
        }

        //SE HACE LA BUSQUEDA DE LOS PRODUCTOS PARA SUMARLOS EN PARTE DE ALMACEN GENERAL
        function ObtenerProductoZofraPorProductoYAlmacen($data)
        {
          $producto=$data["IdProducto"];
          $sede=$data["IdAsignacionSede"];
          $query = $this->db->query("SELECT *
          FROM inventarioinicial
          WHERE idproducto = '$producto' AND idasignacionsede = '$sede' 
          AND NumeroDocumentoSalidaZofra != '' AND indicadorestado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
