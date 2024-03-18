<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cImportacionMasiva extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();

		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
		$this->load->service("Configuracion/Catalogo/sModelo");
		$this->load->service("Catalogo/sProveedor");
		$this->load->service("Catalogo/sCliente");
		$this->load->service("Catalogo/sImportacionMasiva");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service("Configuracion/General/sTipoAfectacionIGV");
		$this->load->library('sesionusuario');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->library('RestApi/Catalogo/RestApiMercaderia');
	}

	public function Index()
	{
		$ImportacionMasiva =$this->sImportacionMasiva->CargarImportacionMasiva();
		$Proveedor=$this->sProveedor->Cargar();

		$data = array(
			"data" => array(
				'ImportacionMasiva' => $ImportacionMasiva,
				'Clientes' => array()
				)
		);

    $view_data['data'] = $data;
		$view_subsubcontent_extra['view_subcontent_form_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_form_proveedor','',true);
		$view_subcontent['view_modal_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_proveedor',$view_subsubcontent_extra,true);
		$view_subcontent['view_modal_preview_foto_proveedor'] = $this->load->View('Catalogo/Proveedor/view_mainpanel_subcontent_modal_preview_foto_proveedor','',true);
		$view_subcontent_panel['view_form_importacionmasiva'] = $this->load->View('Catalogo/ImportacionMasiva/view_form_importacionmasiva','',true);
		$view_subcontent_panel['view_panel_header_importacionmasiva'] = $this->load->View('Catalogo/ImportacionMasiva/view_panel_header_importacionmasiva','',true);
		$view_subcontent['view_panel_importacionregistro'] = $this->load->View('Catalogo/ImportacionRegistro/view_panel_importacionregistro',$view_subcontent_panel,true);

		$view_subcontent['view_template_cliente'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_cliente','',true);
		$view_subcontent['view_template_mercaderia'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_mercaderia','',true);
		$view_subcontent['view_template_proveedor'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_proveedor','',true);
		$view_subcontent['view_template_rol'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_rol','',true);
		$view_subcontent['view_template_familiaproducto'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_familiaproducto','',true);
		$view_subcontent['view_template_subfamiliaproducto'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_subfamiliaproducto','',true);
		$view_subcontent['view_template_marca'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_marca','',true);
		$view_subcontent['view_template_modelo'] = $this->load->View('Catalogo/ImportacionMasiva/templates/view_template_modelo','',true);

		$view['view_footer_extension'] = $this->load->View('Catalogo/ImportacionRegistro/view_footer_importacionregistro',$view_data,true);
		$view['view_content_min'] =  $this->load->View('Catalogo/ImportacionRegistro/view_content_importacionregistro',$view_subcontent,true);

    $this->load->View('.Master/master_view_mainpanel_min',$view);
	}

	function RegistrarImportacionMasiva()
	{
		try {
			$data = $_POST["Data"];
			$data1 = json_decode($data, true);
			$resultado = "";
			if($data1["Opcion"] == 1)
			{
				$resultado = $this->InsertarFamiliaProducto($data1["DetallesImportacionMasiva"]);
			}
			else if($data1["Opcion"] == 2)
			{
				$resultado = $this->InsertarSubFamiliaProducto($data1["DetallesImportacionMasiva"]);
			}
			else if($data1["Opcion"] == 3)
			{
				$resultado = $this->InsertarMarca($data1["DetallesImportacionMasiva"]);
			}
			else if($data1["Opcion"] == 4)
			{
				$resultado = $this->InsertarModelo($data1["DetallesImportacionMasiva"]);
			}
			else if($data1["Opcion"] == 5)
			{
				$resultado = $this->InsertarClientes($data1["DetallesImportacionMasiva"]);
			}
			else if($data1["Opcion"] == 6){
				$resultado = $this->InsertarProveedores($data1["DetallesImportacionMasiva"]);
			}
			else if($data1["Opcion"] == 7){
				$resultado = $this->InsertarMercaderias($data1["DetallesImportacionMasiva"]);

			}

			if(!is_array($resultado))
			{
				echo $this->json->json_response_error($resultado);
			}
			else {
				echo $this->json->json_response($resultado);
			}
		} catch (Exception $e) {
			echo $this->json->json_response_error($e);
		}

	}

	function InsertarClientes($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$texto = false;

			foreach ($data as $key => $value) {
				$resultado = $this->sCliente->InsertarCliente($value);
				if(!is_array($resultado))
				{
					$validaciones .= "<br/> El registro n° ".$key." (".$value["RazonSocial"].")".$resultado;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				$url = DIR_ROOT_ASSETS.'/data/cliente/clientes.json';
				$data_json = $this->sCliente->PrepararDataJSONCliente();

				$resultado1 = $this->json->CrearArchivoJSONData($url, $data_json);
				return $resultado1;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}

	function InsertarProveedores($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$texto = false;
			foreach ($data as $key => $value) {
				$resultado = $this->sProveedor->InsertarProveedor($value);
				if(!is_array($resultado))
				{
					$validaciones .= "<br/> El registro n° ".$key." (".$value["RazonSocial"].")".$resultado;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				$url = DIR_ROOT_ASSETS.'/data/proveedor/proveedores.json';
				$data_json = $this->sProveedor->PrepararDataJSONProveedor();

				$resultado1 = $this->json->CrearArchivoJSONData($url, $data_json);
				return $resultado1;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}

	function InsertarFamiliaProducto($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$texto = false;
			foreach ($data as $key => $value) {
				$resultado = $this->sFamiliaProducto->InsertarFamiliaProducto($value);
				if(!is_array($resultado))
				{
					$validaciones .= "<br/>".$resultado;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				return $data;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}

	function InsertarSubFamiliaProducto($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$texto = false;
			foreach ($data as $key => $value) {
				$resultado = $this->sSubFamiliaProducto->InsertarSubFamiliaProducto($value);
				if(!is_numeric($resultado))
				{
					$validaciones .= "<br/>".$resultado;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				return $data;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}

	function InsertarMarca($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$texto = false;
			foreach ($data as $key => $value) {
				$resultado = $this->sMarca->InsertarMarca($value);
				if(!is_array($resultado))
				{
					print_r($resultado);exit();
					$validaciones .= "<br/>".$resultado;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				return $data;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}

	function InsertarModelo($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$texto = false;
			foreach ($data as $key => $value) {
				$resultado = $this->sModelo->InsertarModelo($value);
				if(!is_numeric($resultado))
				{
					$validaciones .= "<br/>".$resultado;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				return $data;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}

	function InsertarMercaderias($data)
	{
		try {
			$this->db->trans_begin();
			$validaciones = "";
			$dataforJson = array();
			$texto = false; $i=1;
			foreach ($data as $key => $value) {
				$afectoigv = $this->sTipoAfectacionIGV->ObtenerTipoAfectacionIGVPorId($value);
				if(is_array($afectoigv))
				{					
					$value["CodigoTipoAfectacionIGV"] = $afectoigv["CodigoTipoAfectacionIGV"];					
					//$noEspecificadoMarca = $this->sModelo->ConsultarModeloNoEspefificadoPorMarca($value);
					//$noEspecificadoFamiliaProducto = $this->sSubFamiliaProducto->ConsultarSubFamiliaNoEspefificadoPorFamilia($value);
					//if(empty($noEspecificadoMarca))
					$value["Contador"] = $i;
					$resultado = $this->sMercaderia->InsertarMercaderia($value);
					$i++;
					if(!is_array($resultado))
					{
						$validaciones .= "<br/>".$resultado;
						//$validaciones .= "<br/>"."NO SE ENCONTRO MODELO.";
						$texto = true;
					}
					array_push($dataforJson,$resultado);
					/*elseif(empty($noEspecificadoFamiliaProducto))
					{
						$validaciones .= "<br/>"."NO SE ENCONTRO SUBFAMILIAPRODUCTO.";
						$texto = true;
					}
					else{
						$value["IdSubFamiliaProducto"] = $noEspecificadoFamiliaProducto["IdSubFamiliaProducto"];
						$value["IdModelo"] = $noEspecificadoMarca["IdModelo"];
						$resultado = $this->sMercaderia->InsertarMercaderia($value);
						if(!is_array($resultado))
						{
							$validaciones .= "<br/>".$resultado;
							$texto = true;
						}
						array_push($dataforJson,$resultado);
					}*/
				}
				else
				{
					$validaciones .= "<br/>".$afectoigv;
					$texto = true;
				}
			}

			if($texto == false)
			{
				$this->db->trans_commit();
				foreach ($dataforJson as $key => $value) {
					$resultado1 = $this->restapimercaderia->InsertarJSONDesdeMercaderia($value);
				}
				return $resultado1;
			}
			else {
				$this->db->trans_rollback();
				return $validaciones;
			}
		} catch (Exception $e) {
			$this->db->trans_rollback();
			return $e;
		}
	}
}
