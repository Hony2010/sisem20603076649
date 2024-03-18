<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sControlMesa extends MY_Service {

  public function __construct()
  {
    parent::__construct();
    $this->load->library('sesionusuario');
    $this->load->service('Configuracion/Venta/sMesa');
    $this->load->model("Base");
  }

  function Cargar()
  {
    $mesas = $this->sMesa->ListarMesas();

    $resultado['IdTipoDocumento'] = "%";
    $resultado['IdRolUsuario'] = $this->sesionusuario->obtener_sesion_id_rol();
    $resultado['FechaInicio'] = $this->Base->ObtenerFechaServidor("d/m/Y");
    $resultado['Mesas'] = $mesas;
    return $resultado;
  }


}
