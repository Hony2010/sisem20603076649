<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mDocumentoSalidaZofraProducto extends CI_Model {

        public $DocumentoSalidaZofraProducto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');
               $this->DocumentoSalidaZofraProducto = $this->Base->Construir("DocumentoSalidaZofraProducto");
        }


        function InsertarDocumentoSalidaZofraProducto($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
          $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoSalidaZofraProducto);
          $this->db->insert('DocumentoSalidaZofraProducto', $resultado);
          $resultado["IdDocumentoSalidaZofraProducto"] = $this->db->insert_id();
          return($resultado);
        }


        function ActualizarDocumentoSalidaZofraProducto($data)
        {
          $id=$data["IdDocumentoSalidaZofraProducto"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
          $resultado = $this->mapper->map($data,$this->DocumentoSalidaZofraProducto);
          $this->db->where('IdDocumentoSalidaZofraProducto', $id);
          $this->db->update('DocumentoSalidaZofraProducto', $resultado);

          return $resultado;
        }

        function BorrarDocumentoSalidaZofraProducto($data)
        {
          $data["IndicadorEstado"]=ESTADO_ELIMINADO;
          $this->ActualizarDocumentoSalidaZofraProducto($data);
        }

        function ObtenerDocumentoSalidaZofraProductoPorProductoAlmacen($data)
        {
          $id=$data["IdProducto"];
          $almacen=$data["IdAsignacionSede"];
          $documentozofra=$data["IdDocumentoSalidaZofra"];
          $query = $this->db->query("Select * from DocumentoSalidaZofraProducto
                                     WHERE IdDocumentoSalidaZofra = '$documentozofra' AND IdProducto = '$id' AND IdAsignacionSede = '$almacen'
                                     AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerDocumentoSalidaZofraProductoPorId($data)
        {
          $id=$data["IdDocumentoSalidaZofraProducto"];
          $query = $this->db->query("Select * from DocumentoSalidaZofraProducto
                                     WHERE IdDocumentoSalidaZofraProducto = '$id' AND IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarListasDocumentoSalidaZofraPorIdProducto($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("select DSZP.IdDocumentoSalidaZofraProducto, DSZP.IdAsignacionSede, DSZP.IdProducto, DSZP.IdDocumentoSalidaZofra,
            DSZP.StockDocumentoSalidaZofra, DSZ.NumeroDocumentoSalidaZofra
            from DocumentoSalidaZofraProducto DSZP
            inner join DocumentoSalidaZofra DSZ on DSZ.IdDocumentoSalidaZofra = DSZP.IdDocumentoSalidaZofra
            where DSZP.IdProducto = '$id' and DSZP.StockDocumentoSalidaZofra > 0 and DSZP.IndicadorEstado = 'A'
            order by DSZP.StockDocumentoSalidaZofra asc");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ProductosEnDocumentoSalidaZofraProducto()
        {
          $query = $this->db->query("Select distinct IdProducto
              from DocumentoSalidaZofraProducto
              where IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function SedesPorProductoEnDocumentoSalidaZofraProducto($data)
        {
          $id = $data["IdProducto"];
          $query = $this->db->query("Select distinct IdAsignacionSede
                from DocumentoSalidaZofraProducto
                where IdProducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ValidarProductoEnDocumentoSalidaZofraProductoParaMercaderia($data)
        {
          $id=$data["IdProducto"];
          $query = $this->db->query("select * from documentosalidazofraproducto
          where idproducto = '$id' and IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

 }
