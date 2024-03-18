<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sCostoServicio extends MY_Service {

        public $CostoServicio = array();
        public $Producto = array();

        public function __construct()
        {
              parent::__construct();
              $this->load->database();
              $this->load->model("Base");
              $this->load->library('shared');
              $this->load->library('mapper');
              $this->load->library('herencia');
              $this->load->model('Catalogo/mCostoServicio');
              $this->load->service('Catalogo/sProducto');
              //$this->load->model('Catalogo/mProducto');
              $this->CostoServicio = $this->mCostoServicio->CostoServicio;
              $this->Producto = $this->sProducto->Producto;
              $this->CostoServicio = $this->herencia->Heredar($this->Producto,$this->CostoServicio);
        }

        function ListarCostosServicio()
        {
          $resultado = $this->mCostoServicio->ListarCostosServicio();
          return $resultado;
        }

        function ValidarNombreProducto($data)
        {
          $nombre=$data["NombreProducto"];
          if ($nombre == "")
          {
            return "Debe ingresar el nombre del costo del Producto";
          }
          else
          {
            return "";
          }
        }

        function ValidarCostoServicio($data)
        {
          $nombre = $this->ValidarNombreProducto($data);

          if ($nombre != "")
          {
            return $nombre;
          }
          else
          {
            return "";
          }
        }

        function InsertarCostoServicio($data)
        {
          //$data["NombreProducto"] = trim($data["NombreProducto"]);
          $resultado = $this->ValidarCostoServicio($data);

          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $producto = $this->sProducto->InsertarProducto($data);

            if(is_string($producto) && $producto != "")
            {
              return $producto;
            }
            else
            {
              $data["IdProducto"] = $producto["IdProducto"];
              $resultado = $this->mCostoServicio->InsertarCostoServicio($data);

              return $resultado;
            }
          }
        }

        function ActualizarCostoServicio($data)
        {
          $data["NombreProducto"] = trim($data["NombreProducto"]);
          $resultado = $this->ValidarCostoServicio($data);
          if ($resultado != "")
          {
            return $resultado;
          }
          else
          {
            $producto = $this->mProducto->ActualizarProducto($data);
            $resultado = $this->mCostoServicio->ActualizarCostoServicio($data);
            return "";
          }
        }

        function ValidarExistenciaCostoServicioEnVenta($data)
        {
          //$resultado = $this->mCostoServicio->ConsultarCostoServicioEnVenta($data);
          $contador =0;// COUNT($resultado);
          if ($contador > 0)
          {
            return "No se puede eliminar porque tiene registros en Venta";
          }
          else
          {
            return "";
          }
        }

        function BorrarCostoServicio($data)
        {
          $resultado = $this->ValidarExistenciaCostoServicioEnVenta($data);
          if ($resultado !="")
          {
            return $resultado;
          }
          else
          {
            $resultado= $this->sProducto->BorrarProducto($data);
            //$resultado= $this->mCostoServicio->BorrarCostoServicio($data);
            return $resultado;
          }
        }

        function ConsultarCostosServicio($data)
        {
          $resultado=$this->mCostoServicio->ConsultarCostosServicio($data);
          return $resultado;
        }
}
