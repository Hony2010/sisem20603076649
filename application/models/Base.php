<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class Base extends CI_Model {

         public function __construct()
         {
                parent::__construct();
                $this->load->database();
                $this->load->helper("date");
         }

         function Construir($tabla)
         {
            $data = $this->db->list_fields($tabla);

            $resultado = [];

            foreach($data as $key)
            {
                $resultado[$key]=null;
            }

            return $resultado;
         }

        function ObtenerFechaServidor($format = "") {
          $query = $this->db->query("select NOW() as FechaServidor");
          $resultado = $query->row();

          if($format != "") {
            $fecha =convertToDate($resultado->FechaServidor,$format);
          }
          else {
            $fecha = $resultado->FechaServidor;
          }
          return $fecha;
        }

        function ObtenerHoraServidor($format = "") {
          $query = $this->db->query("select DATE_FORMAT(NOW(),'%H:%i:%s') as HoraServidor");
          $resultado = $query->row();
          $hora = $resultado->HoraServidor;
          return $hora;
        }

        function ObtenerFechaHoraServidor($format = "") {
          $query = $this->db->query("select NOW() as FechaHoraServidor");
          $resultado = $query->row();

          if($format != "") {
            $fechahora =convertToDateTime($resultado->FechaHoraServidor,$format);
          }
          else {
            $fechahora = $resultado->FechaHoraServidor;
          }
          return $fechahora;
        }
}
