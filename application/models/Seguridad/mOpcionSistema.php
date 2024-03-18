<?php
if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mOpcionSistema extends CI_Model {

        public $OpcionSistema = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Usuario = $this->Base->Construir("OpcionSistema");
        }

        function ListarOpcionesSistema()
        {
          $id = $data["IdOpcionSistema"];
          $query = $this->db->query("select * from OpcionSistema where IndicadorEstado='A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerOpcionPorNombreControlador($data)  {
          $nombrecontrolador = $data["NombreControlador"];
          $query = $this->db->query("select OS.*, MS.NameModulo, MS.IdModulo from OpcionSistema as OS
                                    inner join ModuloSistema  as MS on OS.IdModuloSistema = MS.IdModuloSistema
                                    where OS.NombreControlador = '$nombrecontrolador' and (OS.IndicadorEstado = 'A' and MS.IndicadorEstado = 'A' )");
          $resultado = $query->row();
          return $resultado;
        }

        function ObtenerOpcionesSistemaPorModuloSistema($data) {
          $idmodulosistema = $data["IdModuloSistema"];
          $query = $this->db->query("select * from OpcionSistema
                                     where IdModuloSistema='$idmodulosistema' and IndicadorEstado='A'
                                     order by IdModuloSistema, OrdenOpcion");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerOpcionesPorIdUsuarioPorIdModuloSistema($data) {
          $idusuario = $data['IdUsuario'];
          $idmodulosistema = $data['IdModuloSistema'];

          $query = $this->db->query("Select OS.*
                                    from AccesoUsuario As AU
                                    inner join AccesoRol As AR on AU.IdAccesoRol = AR.IdAccesoRol
                                    inner join OpcionSistema As OS on AR.IdOpcionSistema = OS.IdOpcionSistema
                                    where AU.IdUsuario = '$idusuario'
                                    and OS.IdModuloSistema = '$idmodulosistema'
                                    and OS.IndicadorEstado = 'A'
                                    and AR.IndicadorEstado = 'A'
                                    and AR.EstadoOpcionRol = '1'
                                    and AU.IndicadorEstado = 'A'
                                    and AU.EstadoOpcionUsuario = '1'
                                    order by OS.IdModuloSistema, OS.OrdenOpcion
                                    ");

          $resultado = $query->result_array();
          return $resultado;
        }
 }
