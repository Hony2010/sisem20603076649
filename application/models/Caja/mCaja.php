<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCaja extends CI_Model {

        public $Caja = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->Caja = $this->Base->Construir("Caja");
        }


        function InsertarCaja($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->Caja);
          $this->db->insert('Caja', $resultado);
          $resultado["IdCaja"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarCaja($data)
        {
          $id=$data["IdCaja"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->Caja);
          $this->db->where('IdCaja', $id);
          $this->db->update('Caja', $resultado);

          return $resultado;
        }

        function BorrarCaja($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $resultado = $this->ActualizarCaja($data);
          return $resultado;
        }

        function ObtenerNombreCajaParaInsertar($data)
        {
          $nombrecaja=$data["NombreCaja"];
          $query = $this->db->query("Select * from Caja
                                     WHERE NombreCaja = '$nombrecaja'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNombreCajaParaActualizar($data)
        {
            $id=$data["IdCaja"];
            $nombrecaja=$data["NombreCaja"];
            $query = $this->db->query("Select *
                            from Caja
                            where IdCaja != '$id'
                            and NombreCaja = '$nombrecaja'
                            and IndicadorEstado = 'A'");
            $resultado = $query->result_array();
            return $resultado;
        }

        function ListarCajas()
        {
          $query = $this->db->query("Select C.*, M.NombreMoneda from Caja C
                                      INNER JOIN Moneda M on M.IdMoneda = C.IdMoneda
                                      WHERE C.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerCajaPorIdCaja($data)
        {
          $id=$data["IdCaja"];
          $query = $this->db->query("Select * from Caja
                                     WHERE IdCaja = '$id'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroCajaParaInsertar($data)
        {
          $idmoneda=$data["IdMoneda"];
          $numerocaja=$data["NumeroCaja"];
          $query = $this->db->query("SELECT c.*
                                    FROM caja c
                                    WHERE c.IdMoneda = '$idmoneda'
                                    AND c.NumeroCaja = '$numerocaja'
                                    AND c.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroCajaParaActualizar($data)
        {
          $idmoneda=$data["IdMoneda"];
          $numerocaja=$data["NumeroCaja"];
          $id=$data["IdCaja"];
          $query = $this->db->query("SELECT c.*
                                    FROM caja c
                                    WHERE c.IdMoneda = '$idmoneda'
                                    AND c.NumeroCaja = '$numerocaja'
                                    AND c.IdCaja != '$id'
                                    AND c.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarCajasPorNumeroCaja($data)
        {
            $numerocaja=$data["NumeroCaja"];
            $rolusuario = $this->sesionusuario->obtener_sesion_id_rol();
            $extensionConsulta = "";
            if($rolusuario != ID_ROL_ADMINISTRADOR)
            {
              $extensionConsulta = " AND c.NumeroCaja = '$numerocaja'";
            }

            $query = $this->db->query("select c.*
                                        FROM caja c
                                        WHERE IndicadorEstado = 'A'
                                        ".$extensionConsulta);
            $resultado = $query->result_array();
            return $resultado;
        }
        
}
