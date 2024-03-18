<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mAccesoUsuario extends CI_Model {

        public $AccesoUsuario = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->AccesoUsuario = $this->Base->Construir("AccesoUsuario");
        }

        function ListarAccesosUsuario()
        {
          $id = $data["IdUsuario"];
          $query = $this->db->query("select U.NombreUsuario, OS.NombreOpcionSistema, AU.EstadoOpcionUsuario, M.NombreModuloSistema
                                      from accesousuario as AU
                                      inner join Usuario as U on U.IdUsuario=AU.IdUsuario
                                      inner join AccesoRol as AR on AR.IdAccesoRol=AU.IdAccesoRol
                                      inner join OpcionSistema as OS on OS.IdOpcionSistema=AR.IdOpcionSistema
                                      inner join ModuloSistema as M on M.IdModuloSistema=OS.IdModuloSistema
                                      where AU.IdUsuario='$Id'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function AccesosUsuarioMenu($data)
        {
          $usuario = $data['Usuario'];
          $query = $this->db->query("Select AU.IdAccesoUsuario, U.NombreUsuario, R.NombreRol, TR.NombreTipoRol, MS.NombreModuloSistema, OS.NombreOpcionSistema, AU.EstadoOpcionUsuario, AR.EstadoOpcionRol, AU.IndicadorEstado
                                    From AccesoUsuario As AU
                                    Inner Join Usuario As U on AU.IdUsuario = U.IdUsuario
                                    Inner Join AccesoRol As AR on AU.IdAccesoRol = AR.IDAccesoRol
                                    Inner Join Rol As R on AR.IdRol = R.IdRol
                                    Inner Join TipoRol As TR on R.IdTipoRol = TR.IdTipoRol
                                    Inner Join OpcionSistema As OS on AR.IdOpcionSistema = OS.IdOpcionSistema
                                    Inner Join ModuloSistema As MS on OS.IdModuloSistema = MS.IdModuloSistema
                                    Where U.NombreUsuario = '$usuario' and AU.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarAccesoUsuario($data) {
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;

          $resultado = $this->mapper->map($data,$this->AccesoUsuario);
          $this->db->insert('AccesoUsuario', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarAccesoUsuario($data) {
          $id=$data["IdAccesoUsuario"];
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->AccesoUsuario);
          $this->db->where('IdAccesoUsuario', $id);
          $this->db->update('AccesoUsuario', $resultado);
        }

        function ObtenerAccesoOpcionPorUsuario($data) {
          $usuario = $data['NombreUsuario'];
          $idopcionsistema = $data['IdOpcionSistema'];
          $query = $this->db->query("Select AU.*
                                    from AccesoUsuario As AU
                                    inner join Usuario As U on AU.IdUsuario = U.IdUsuario
                                    inner join AccesoRol As AR on AU.IdAccesoRol = AR.IDAccesoRol
                                    inner join OpcionSistema As OS on AR.IdOpcionSistema = OS.IdOpcionSistema
                                    where U.NombreUsuario = '$usuario'
                                    and OS.IdOpcionSistema = '$idopcionsistema'
                                    and AU.IndicadorEstado = 'A'");

          $resultado = $query->row();
          return $resultado;
        }


        function ObtenerAccesosUsuarioPorIdOpcionSistemaPorIdUsuarioPorIdRol($data) {
          $idopcionsistema = $data['IdOpcionSistema'];
          $idusuario = $data['IdUsuario'];
          $idrol = $data['IdRol'];

          $query = $this->db->query("Select AR.IdAccesoRol,AU.IdAccesoUsuario,AU.EstadoOpcionUsuario,OS.*
                                    from AccesoRol As AR
                                    inner join OpcionSistema As OS on AR.IdOpcionSistema = OS.IdOpcionSistema
                                    left join AccesoUsuario As AU on AU.IdAccesoRol = AR.IdAccesoRol
                                    and AU.IdUsuario = '$idusuario'
                                    where OS.IdOpcionSistema = '$idopcionsistema'
                                    and AR.IdRol = '$idrol'
                                    ");

          $resultado = $query->result_array();
          return $resultado;
        }

        function BorrarAccesosPorUsuario($data)
        {
          $id = $data["IdUsuario"];
          $this->db->where("IdUsuario",$id);
          $this->db->delete("AccesoUsuario");
          return "";
        }

        function ValidarAccesoUsuario($data)
        {
          $id = $data["IdUsuario"];
          $idopcion = $data["IdOpcionSistema"];
          $query = $this->db->query("select AU.*
                  from accesousuario as AU
                  inner join accesorol as AR on AR.IdAccesoRol = AU.IdAccesoRol
                  where AU.IdUsuario='$id' and AR.IdOpcionSistema ='$idopcion' AND AU.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
