<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mCostoServicio extends CI_Model {

        public $CostoServicio = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->CostoServicio = $this->Base->Construir("CostoServicio");
        }

        function ListarCostosServicio()
        {
          $query = $this->db->query("Select UM.AbreviaturaUnidadMedida,CS.*, TP.NombreTipoProducto, UM.NombreUnidadMedida, TE.NombreTipoExistencia, P.*
                                     From CostoServicio As CS
                                     Inner Join Producto As P on CS.IdProducto = P.IdProducto
                                     Inner Join TipoProducto As TP on TP.IdTipoProducto = CS.IdTipoProducto
                                     Inner Join UnidadMedida  As UM on UM.IdUnidadMedida = CS.IdUnidadMedida
                                     Inner Join TipoExistencia As TE on TE.IdTipoExistencia = CS.IdTipoExistencia
                                     Where P.IndicadorEstado = 'A'
                                     ORDER BY (CS.IdProducto)");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarCostoServicio($data)
        {
          $resultado = $this->mapper->map($data,$this->CostoServicio);
          $this->db->insert('CostoServicio', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarCostoServicio($data)
        {
          $id=$data["IdProducto"];
          $resultado = $this->mapper->map($data,$this->CostoServicio);
          $this->db->where('IdProducto', $id);
          $this->db->update('CostoServicio', $resultado);
        }

        function ConsultarCostosServicio($data)
        {
          $criterio=$data["textofiltro"];
          $this->db->select("CS.*, TP.NombreTipoProducto, UM.NombreUnidadMedida, TE.NomTipoExistencia, P.*")
          ->from('CostoServicio As CS')
          ->join('Producto As P','CS.IdProducto = P.IdProducto')
          ->join('TipoProducto As TP','TP.IdTipoProducto = CS.IdTipoProducto')
          ->join('UnidadMedida  As UM','UM.IdUnidadMedida = CS.IdUnidadMedida')
          ->join('TipoExistencia As TE','TE.IdTipoExistencia = CS.IdTipoExistencia')
          ->where('CS.IdProducto like "%'.$criterio.'%" or P.NombreProducto like "%'.$criterio.'%" or TP.NombreTipoProducto like "%'.$criterio.'%" or UM.NombreUnidadMedida like "%'.$criterio.'%" or TE.DescripcionTipoExistencia like "%'.$criterio.'%" AND P.IndicadorEstado="A" ' );
          $query = $this->db->get();
          $resultado = $query->result();
          return $resultado;
        }
}
