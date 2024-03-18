<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAccesoRol extends CI_Model {

        public $AccesoRol = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->AccesoRol = $this->Base->Construir("AccesoRol");
        }


        function ObtenerAccesosRolPorIdOpcionSistemaPorIdRol($data)
        {
          $id = $data["IdRol"];
          $idopcion = $data["IdOpcionSistema"];
          $query = $this->db->query("select OS.IdModuloSistema, OS.NombreOpcionSistema, AR.*
                                    from accesorol as AR
                                    inner join Rol as R on R.IdRol=AR.IdRol
                                    inner join OpcionSistema as OS on OS.IdOpcionSistema=AR.IdOpcionSistema
                                    inner join ModuloSistema as M on M.IdModuloSistema=OS.IdModuloSistema
                                    where R.IdRol='$id' and AR.IdOpcionSistema='$idopcion'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarAccesoRol($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->AccesoRol);
          $this->db->insert('AccesoRol', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarAccesoRol($data)
        {
          $id=$data["IdAccesoRol"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->AccesoRol);
          $this->db->where('IdAccesoRol', $id);
          $this->db->update('AccesoRol', $resultado);
        }

        function ValidarAccesoRol($data)
        {
          $id = $data["IdRol"];
          $idopcion = $data["IdOpcionSistema"];
          $query = $this->db->query("select AR.*
                                    from accesorol as AR
                                    where AR.IdRol='$id' and AR.IdOpcionSistema='$idopcion' AND AR.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
