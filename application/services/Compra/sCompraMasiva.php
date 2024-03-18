<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

require_once(APPPATH.'services\Compra\sComprobanteCompra.php');

class sCompraMasiva extends sComprobanteCompra {

        public function __construct()
        {

          parent::__construct();
        }

        function CargarCompraMasiva()
        {
          $parametro['IdTipoDocumento'] = ID_TIPO_DOCUMENTO_FACTURA;
      		$parametro['IdSedeAgencia'] = $this->sesionusuario->obtener_sesion_id_sede();
          $resultado = parent::Cargar($parametro);
          $resultado["IdMoneda"] = ID_MONEDA_SOLES;

          return $resultado;
        }


}
