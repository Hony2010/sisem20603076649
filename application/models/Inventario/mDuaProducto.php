<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDuaProducto extends CI_Model {

        public $DuaProducto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DuaProducto = $this->Base->Construir("DuaProducto");
        }

        function ListarDuasPruductoPorFiltros($data)
        {
          $FechaInicio = $data['FechaInicio'];
          $FechaFinal = $data['FechaFinal'];

          $query = $this->db->query("select D.NumeroDua, D.FechaEmisionDua, DP.IdDuaProducto, D.IdDua, DP.NumeroItemDua
                                    from duaproducto as DP
                                    inner join dua as D on DP.IdDua = D.IdDua
                                    where (DP.IndicadorEstado = 'A' and D.IndicadorEstado = 'A') and
                                    D.FechaEmisionDua BETWEEN '$FechaInicio' and '$FechaFinal'
                                    group by D.IdDua
                                    order by D.FechaEmisionDua asc");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarDuaProducto($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DuaProducto);
          $this->db->insert('DuaProducto', $resultado);
          $resultado["IdDuaProducto"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDuaProducto($data)
        {
          $id=$data["IdDuaProducto"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DuaProducto);
          $this->db->where('IdDuaProducto', $id);
          $this->db->update('DuaProducto', $resultado);

          return $resultado;
        }

        function BorrarDuaProducto($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDuaProducto($data);
        }

        //VALIDADO HASTA AQUI
        function ObtenerDuaProductoPorProductoDua($data)
        {
          $id=$data["IdProducto"];
          $dua=$data["IdDua"];
          $query = $this->db->query("Select * from DuaProducto
                                     WHERE IdDua = '$dua' AND IdProducto = '$id'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDuaProductoPorId($data)
        {
          $id=$data["IdDuaProducto"];
          $query = $this->db->query("Select * from DuaProducto
                                     WHERE IdDuaProducto = '$id'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
      
        function ProductosEnDuaProducto()
        {
          $query = $this->db->query("Select distinct IdProducto
              from DuaProducto
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnDuaProducto($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from DuaProducto
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarListasDuaPorIdProducto($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("Select DP.IdDuaProducto, DP.IdProducto, DP.NumeroItemDua, DP.StockDua, DD.NumeroDua, DP.IdAsignacionSede
             from DuaProducto DP
             inner join Dua DD on DD.IdDua = DP.IdDua
             WHERE DP.IdProducto = '$id'
             AND DP.IndicadorEstado = 'A' AND DP.StockDua > 0");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ValidarProductoEnDuaProductoParaMercaderia($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("select * from duaproducto
          where idproducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }
 }
