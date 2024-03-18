<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAccesoCajaUsuario extends CI_Model {

        public $AccesoCajaUsuario = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->AccesoCajaUsuario = $this->Base->Construir("AccesoCajaUsuario");
        }

        function InsertarAccesoCajaUsuario($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          // $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->AccesoCajaUsuario);
          $this->db->insert('AccesoCajaUsuario', $resultado);
          $data["IdAccesoCajaUsuario"] = $this->db->insert_id();
          return $data;
        }

        function ActualizarAccesoCajaUsuario($data)
        {
          $id=$data["IdAccesoCajaUsuario"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->AccesoCajaUsuario);
          $this->db->where('IdAccesoCajaUsuario', $id);
          $this->db->update('AccesoCajaUsuario', $resultado);
          return $data;
        }

        function BorrarAccesoCajaUsuario($data)
        {
          $id=$data["IdAccesoCajaUsuario"];
          $this->db->where("IdAccesoCajaUsuario",$id);
          $this->db->delete("AccesoCajaUsuario");
        }

        function BorrarAccesosCajaUsuarioPorUsuario($data)
        {
          $id=$data["IdUsuario"];
          $this->db->where("IdUsuario",$id);
          $this->db->delete("AccesoCajaUsuario");
        }

        function ConsultarAccesosCajaUsuarioPorIdUsuario($data)
        {
          $id = $data["IdUsuario"];
          $query = $this->db->query("select ACU.*, C.IdMoneda, C.NombreCaja, M.CodigoMoneda, M.NombreMoneda
                              from AccesoCajaUsuario as ACU
                              INNER JOIN Caja C ON C.IdCaja = ACU.IdCaja
                              INNER JOIN Moneda M ON M.IdMoneda = C.IdMoneda
                              where ACU.IdUsuario='$id' AND ACU.EstadoCajaUsuario = 1
                              AND ACU.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerAccesoCajaUsuarioPorUsuarioYCaja($data)
        {
          $usuario = $data["IdUsuario"];
          $caja = $data["IdCaja"];
          $query = $this->db->query("Select *
                                    FROM accesoCajaUsuario as ACU
                                    WHERE ACU.IdUsuario='$usuario' and ACU.IdCaja = '$caja'
                                    AND ACU.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
