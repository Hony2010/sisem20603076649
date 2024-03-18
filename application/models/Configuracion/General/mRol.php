<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mRol extends CI_Model {

        public $Rol = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Rol = $this->Base->Construir("Rol");
        }

        function ListarRoles()
        {
          $query = $this->db->query("select
              r.*,
              tr.NombreTipoRol as NombreTipoRol
              from rol as r
              inner join tiporol tr
              on tr.IdTipoRol=r.IdTipoRol
              where r.IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarRol($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Rol);
          $this->db->insert('Rol', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarRol($data)
        {
          $id=$data["IdRol"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Rol);
          $this->db->where('IdRol', $id);
          $this->db->update('Rol', $resultado);
        }

        function BorrarRol($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarRol($data);
        }

        function ConsultarRolEnEmpleado($data)
        {
          $id=$data["IdRol"];
          
          $query = $this->db->query("Select E.*
          From Empleado As E
          Inner Join Persona As P On E.IdPersona = P.IdPersona
          Where P.IdRol='$id' and P.IndicadorEstado = 'A'");
          
          //$query = $this->db->get();
          //$resultado = $query->result();
          $resultado = $query->result_array();
          return $resultado;
        }
 }
