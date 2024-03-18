<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sImportacionMasiva extends MY_Service {

        public function __construct()
        {
          parent::__construct();
      		$this->load->library('json');
          $this->load->service("Configuracion/Catalogo/sFamiliaProducto");
          $this->load->service("Configuracion/Catalogo/sMarca");
          $this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
          $this->load->service("Configuracion/Catalogo/sModelo");
          $this->load->service("Catalogo/sCliente");
          $this->load->service("Catalogo/sProveedor");
          $this->load->service("Catalogo/sMercaderia");
          $this->load->service("Configuracion/General/sRol");
          $this->load->service('Configuracion/General/sConstanteSistema');
        }

        function CargarImportacionMasiva()
        {
          $estructura["FamiliaProducto"] = $this->sFamiliaProducto->FamiliaProducto;
          $estructura["Marca"] = $this->sMarca->Marca;
          $estructura["SubFamiliaProducto"] = $this->sSubFamiliaProducto->SubFamiliaProducto;
          $estructura["Modelo"] = $this->sModelo->Modelo;

          $estructura["Rol"] = $this->sRol->Rol;
          $estructura["Cliente"] = $this->sCliente->Cliente;
          $estructura["Cliente"]["IdRol"] = ID_ROL_CLIENTE;
          $estructura["Cliente"]["IdTipoPersona"] = 2;
          $estructura["Cliente"]["IdTipoDocumentoIdentidad"] = 2;
          $estructura["Cliente"]["IndicadorEstadoCliente"] = true;
          $estructura["Proveedor"] = $this->sProveedor->Proveedor;
          $estructura["Proveedor"]["IdRol"] = ID_ROL_PROVEEDOR;
          $estructura["Proveedor"]["IdTipoPersona"] = 2;
          $estructura["Proveedor"]["IdTipoDocumentoIdentidad"] = 2;
          $estructura["Proveedor"]["IndicadorEstadoProveedor"] = true;
          $estructura["Mercaderia"] = $this->sMercaderia->Mercaderia;
          $estructura["Mercaderia"]["IdTipoExistencia"] = 1;
          $estructura["Mercaderia"]["IdSubFamiliaProducto"] = 0;
          $estructura["Mercaderia"]["IdModelo"] = 0;
          //$estructura["Mercaderia"]["IdFamiliaProducto"] = 0;
          //$estructura["Mercaderia"]["IdMarca"] = 0;
          $estructura["Mercaderia"]["IdLineaProducto"] = LINEA_PRODUCTO;
          $estructura["Mercaderia"]["IdUnidadMedida"] = ID_UNIDAD_MEDIDA_UNIDAD;
          $estructura["Mercaderia"]["IdTipoSistemaISC"] = 0;
          $estructura["Mercaderia"]["IdTipoAfectacionIGV"] = 1;
          $estructura["Mercaderia"]["IdTipoPrecio"] = 1;
          $estructura["Mercaderia"]["IdFabricante"] = ID_FABRICANTE_NO_ESPECIFICADO;
          $estructura["Mercaderia"]["IdMoneda"] = 1;
          $estructura["Mercaderia"]["IdTipoTributo"] = ID_TIPO_TRIBUTO_IGV;
          $estructura["Mercaderia"]["IdOrigenMercaderia"] = ID_ORIGEN_MERCADERIA_GENERAL;
          $estructura["Mercaderia"]["IndicadorCodigoPropio"]=0;
          $parametroCodigoBarraAutomatico = $this->sConstanteSistema->ObtenerParametroCodigoBarraAutomatico();
    
          if($parametroCodigoBarraAutomatico == 1) {
            $estructura["Mercaderia"]["CodigoAutomatico"] = 0;            
          }
          else {
            $estructura["Mercaderia"]["CodigoAutomatico"] = 1;
          }

          $estructura["Mercaderia"]["CodigoTipoAfectacionIGV"] = CODIGO_AFECTACION_IGV_GRAVADO;
          $url = DIR_ROOT_ASSETS.'/data/importacion/plantillaimportacion.json';
          $resultado["Opciones"] = $this->json->MapearJSONEstructuraDesdePlantilla($url, $estructura);

          $resultado["Opcion"] = 1;
          $resultado["DetallesImportacionMasiva"] = array();

          return $resultado;
        }

}
