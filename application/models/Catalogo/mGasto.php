<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mGasto extends CI_Model {

        public $Gasto = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Gasto = $this->Base->Construir("Gasto");
        }

        function ObtenerNumeroFila()
        {
          $query = $this->db->query("Select Count(IdProducto) As NumeroFila From Gasto");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarGastos($inicio, $ValorParametroSistema)
        {
          $query = $this->db->query("Select G.*,  P.*
                                     From Gasto As G
                                     Inner Join Producto As P on G.IdProducto = P.IdProducto
                                     Where P.IndicadorEstado = 'A'
                                     ORDER BY (G.IdProducto)
                                     LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerGastoPorIdProducto($data)
        {
          $criterio=$data["IdProducto"];
          $query = $this->db->query("Select G.*, P.*
              From Gasto As G
              Inner Join Producto as P on G.IdProducto = P.IdProducto
              Where P.IndicadorEstado= 'A' AND G.IdProducto = '$criterio'
              ORDER BY(G.IdProducto)");
          $resultado = $query->row();
          return $resultado;
        }

        function InsertarGasto($data)
        {
          if(array_key_exists("IdTipoTributo", $data)) {
            $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
          }  
          $resultado = $this->mapper->map($data,$this->Gasto);
          $this->db->insert('Gasto', $resultado);
          // $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarGasto($data)
        {
          $id=$data["IdProducto"];
          if(array_key_exists("IdTipoTributo", $data)) {
            $data["IdTipoTributo"] = $data["IdTipoTributo"] == "" ? null : $data["IdTipoTributo"];
          }
          $resultado = $this->mapper->map($data,$this->Gasto);
          $this->db->where('IdProducto', $id);
          $this->db->update('Gasto', $resultado);
        }

        function ConsultarGastos($inicio,$ValorParametroSistema,$data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select G.*,  P.*
                                     From Gasto As G
                                     Inner Join Producto As P on G.IdProducto = P.IdProducto
                                     Where P.IndicadorEstado = 'A' AND G.IdProducto like '%$criterio%' or  P.NombreProducto like '%$criterio%'
                                     ORDER BY(P.IdProducto)
                                     LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroFilaPorConsultaGasto($data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select Count(P.IdProducto) as NumeroFila
                                     From Gasto As G
                                     Inner Join Producto As P on G.IdProducto = P.IdProducto
                                     Where P.IndicadorEstado = 'A' AND G.IdProducto like '%$criterio%' or  P.NombreProducto like '%$criterio%'
                                     ORDER BY(P.IdProducto)");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarGastoParaJSON()
        {
          $query = $this->db->query("Select G.*,  P.*
                             From Gasto As G
                             Inner Join Producto As P on G.IdProducto = P.IdProducto
                             Where P.IndicadorEstado = 'A'
                             ORDER BY(P.IdProducto)");
          $resultado = $query->result_array();
          return $resultado;
        }
}
