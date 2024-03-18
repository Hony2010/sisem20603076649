<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloCatalogo extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Configuracion/Catalogo/sFamiliaProducto");
		$this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Configuracion/Catalogo/sModelo");
		$this->load->service("Configuracion/Catalogo/sLineaProducto");
		$this->load->service("Configuracion/Catalogo/sTipoExistencia");
		$this->load->service("Configuracion/Catalogo/sFabricante");
		$this->load->service("Configuracion/Catalogo/sTipoServicio");
		$this->load->service("Configuracion/Catalogo/sTipoDocumentoIdentidad");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	private function CargarFamiliaProducto()
	{
		$NuevaFamiliaProducto =  $this->sFamiliaProducto->FamiliaProducto;
		$FamiliaProducto =  $this->sFamiliaProducto->FamiliaProducto;
		$FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();
		$data["IdFamiliaProducto"]=-1;
 	  $SubFamiliasProducto = $this->sSubFamiliaProducto->ListarSubFamiliasProducto($data);
		$SubFamiliaProducto = $this->sSubFamiliaProducto->SubFamiliaProducto;
		$NuevaSubFamiliaProducto = $this->sSubFamiliaProducto->SubFamiliaProducto;
		$NuevaFamiliaProducto["IdFamiliaProducto"] = -1;
		$NuevaSubFamiliaProducto["IdSubFamiliaProducto"] = -1;

		$dataFamiliaProducto = array("dataFamiliaProducto" =>
					array(
						'FamiliasProducto' => $FamiliasProducto,
						'SubFamiliasProducto' => $SubFamiliasProducto,
						'FamiliaProducto' => $FamiliaProducto,
						'NuevaFamiliaProducto' => $NuevaFamiliaProducto,
						'SubFamiliaProducto' =>$SubFamiliaProducto,
						'NuevaSubFamiliaProducto' =>$NuevaSubFamiliaProducto
					)
		 );

		return $dataFamiliaProducto;
	}
	private function CargarLineaProducto()
	{
		$LineaProducto =  $this->sLineaProducto->LineaProducto;
		$dataLineaProducto = array("dataLineaProducto" =>
						array(
							'LineasProducto' => array(),
							'LineaProducto' => $LineaProducto
					)
		 );
		return $dataLineaProducto;
	}
	private function CargarLineaProductoData()
	{
		$LineasProducto = $this->sLineaProducto->ListarLineasProducto();

		$dataLineaProducto__ = array("dataLineaProducto" =>
						array(
						'LineasProducto' => $LineasProducto
					)
		 );
		 return $dataLineaProducto__;
	}
	private function CargarMarca()
	{
		$NuevaMarca =  $this->sMarca->Marca;
		$Marca =  $this->sMarca->Marca;
		$Modelo = $this->sModelo->Modelo;
		$NuevoModelo =  $this->sModelo->Modelo;
		$NuevaMarca["IdMarca"] = -1;
		$NuevoModelo["IdModelo"] = -1;

		$dataMarca = array("dataMarca" =>
					array(
						'Marcas' => array(),
						'Modelos' => array(),
						'Marca' => $Marca,
						'NuevaMarca' => $NuevaMarca,
						'Modelo' =>$Modelo,
						'NuevoModelo' => $NuevoModelo
					)
		 );

		return $dataMarca;
	}
	private function CargarMarcaData()
	{
		$Marcas = $this->sMarca->ListarMarcas();
		$data["IdMarca"]=-1;
		$Modelos = array();//$this->sModelo->ListarModelos($data);

		$dataMarca__ = array("dataMarca" =>
					array(
						'Marcas' => $Marcas,
						'Modelos' => $Modelos
					)
		 );
		 return $dataMarca__;
	}
	private function CargarTipoExistencia()
	{
		$TipoExistencia =  $this->sTipoExistencia->TipoExistencia;

		$dataTipoExistencia = array("dataTipoExistencia" =>
					array(
						'TiposExistencia' => array(),
						'TipoExistencia' => $TipoExistencia
					)
		 );
		 return $dataTipoExistencia;
	}
	private function CargarTipoExistenciaData()
	{
		$TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();

		$dataTipoExistencia__ = array("dataTipoExistencia" =>
					array(
						'TiposExistencia' => $TiposExistencia
					)
		 );
		 return $dataTipoExistencia__;
	}
	private function CargarFabricante()
	{
		$Fabricante =  $this->sFabricante->Fabricante;
		$dataFabricante = array("dataFabricante" =>
					array(
						'Fabricantes' => array(),
						'Fabricante' => $Fabricante
					)
		 );
		 return $dataFabricante;
	}
	private function CargarFabricanteData()
	{
		$Fabricantes = $this->sFabricante->ListarFabricantes();

		$dataFabricante__ = array("dataFabricante" =>
					array(
						'Fabricantes' => $Fabricantes,
					)
		 );
		 return $dataFabricante__;
	}
	private function CargarTipoServicio()
	{
		$TipoServicio =  $this->sTipoServicio->TipoServicio;

		$dataTipoServicio = array("dataTipoServicio" =>
					array(
						'TiposServicio' => array(),
						'TipoServicio' => $TipoServicio
					)
		 );
		 return $dataTipoServicio;
	}
	private function CargarTipoServicioData()
	{
		$TiposServicio = $this->sTipoServicio->ListarTiposServicio();

		$dataTipoServicio__ = array("dataTipoServicio" =>
					array(
						'TiposServicio' => $TiposServicio
					)
		 );
		 return $dataTipoServicio__;
	}
	private function CargarTipoDocumentoIdentidad()
	{
		$TipoDocumentoIdentidad =  $this->sTipoDocumentoIdentidad->TipoDocumentoIdentidad;

		$dataTipoDocumentoIdentidad = array("dataTipoDocumentoIdentidad" =>
					array(
						'TiposDocumentoIdentidad' => array(),
						'TipoDocumentoIdentidad' => $TipoDocumentoIdentidad
					)
		 );
		 return $dataTipoDocumentoIdentidad;
	}
	private function CargarTipoDocumentoIdentidadData()
	{
		$TipoDocumentoIdentidad =  $this->sTipoDocumentoIdentidad->TipoDocumentoIdentidad;
		$TiposDocumentoIdentidad = $this->sTipoDocumentoIdentidad->ListarTiposDocumentoIdentidad();

		$dataTipoDocumentoIdentidad__ = array("dataTipoDocumentoIdentidad" =>
					array(
						'TiposDocumentoIdentidad' => $TiposDocumentoIdentidad
					)
		 );
		 return $dataTipoDocumentoIdentidad__;
	}

	public function Index()
	{
		$dataFamiliaProducto = $this->CargarFamiliaProducto();
		$dataLineaProducto = $this->CargarLineaProducto();
		$dataMarca = $this->CargarMarca();
		$dataTipoExistencia = $this->CargarTipoExistencia();
		$dataFabricante = $this->CargarFabricante();
		$dataTipoServicio = $this->CargarTipoServicio();
		$dataTipoDocumentoIdentidad = $this->CargarTipoDocumentoIdentidad();

		//FamiliaProducto
		$view_sub_subcontent['view_subcontent_buscador_familiaproducto']=   $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_buscador_familiaproducto','',true);
		$view_subcontent_familia['view_subcontent_familiaproducto'] =  $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_familiaproducto',$view_sub_subcontent,true);
		$view_subcontent_familia['view_subcontent_subfamiliaproducto'] =  $this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_subcontent_subfamiliaproducto','',true);
		$view_['view_content_familia'] =$this->load->View('Configuracion/Catalogo/FamiliaProducto/view_mainpanel_content_familiaproducto',$view_subcontent_familia,true);
		//Marca
		$view_subcontent_marca['view_subcontent_marca'] =  $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_subcontent_marca','',true);
		$view_subcontent_marca['view_subcontent_modelo'] =  $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_subcontent_modelo','',true);
		$view_['view_content_marca'] =  $this->load->View('Configuracion/Catalogo/Marca/view_mainpanel_content_marca',$view_subcontent_marca,true);
		//LineasProducto
		$view_['view_content_linea_producto'] =  $this->load->View('Configuracion/Catalogo/LineaProducto/view_mainpanel_content_lineaproducto','',true);
		// TipoExistencia
		$view_['view_content_tipo_existencia'] =  $this->load->View('Configuracion/Catalogo/TipoExistencia/view_mainpanel_content_tipoexistencia','',true);
		// Fabricante
		$view_['view_content_fabricante'] =  $this->load->View('Configuracion/Catalogo/Fabricante/view_mainpanel_content_fabricante','',true);
		// TipoServicio
		$view_['view_content_tipo_servicio'] =  $this->load->View('Configuracion/Catalogo/TipoServicio/view_mainpanel_content_tiposervicio','',true);
		// TipoDocumentoIdentidad
		$view_['view_content_tipo_documento_identidad'] =  $this->load->View('Configuracion/Catalogo/TipoDocumentoIdentidad/view_mainpanel_content_tipodocumentoidentidad','',true);

		$data['dataFamiliaProducto'] = $dataFamiliaProducto;
		$data['dataLineaProducto'] = $dataLineaProducto;
		$data['dataMarca'] = $dataMarca;
		$data['dataTipoExistencia'] = $dataTipoExistencia;
		$data['dataFabricante'] = $dataFabricante;
		$data['dataTipoServicio'] = $dataTipoServicio;
		$data['dataTipoDocumentoIdentidad'] = $dataTipoDocumentoIdentidad;
		$view_data_vistamodelocatalogo['data']= $data;
		$view_vistamodelocatalogo['view_main_vistamodelocatalogo'] = $this->load->View('Configuracion/Catalogo/.VistaModeloCatalogo/view_main_vistamodelocatalogo','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/Catalogo/.VistaModeloCatalogo/view_mainpanel_footer_vistamodelocatalogo',$view_data_vistamodelocatalogo,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Configuracion/Catalogo/.VistaModeloCatalogo/view_main_vistamodelocatalogo',$view_,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    $this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function Marca()
	{
		$dataMarca = $this->CargarMarcaData();
		echo $this->json->json_response($dataMarca);
	}
	public function LineaProducto()
	{
		$dataLineaProducto = $this->CargarLineaProductoData();
		echo $this->json->json_response($dataLineaProducto);
	}
	public function TipoExistencia()
	{
		$dataTipoExistencia = $this->CargarTipoExistenciaData();
		echo $this->json->json_response($dataTipoExistencia);
	}
	public function Fabricante()
	{
		$dataFabricante = $this->CargarFabricanteData();
		echo $this->json->json_response($dataFabricante);
	}
	public function TipoServicio()
	{
		$dataTipoServicio = $this->CargarTipoServicioData();
		echo $this->json->json_response($dataTipoServicio);
	}

	public function TipoDocumentoIdentidad()
	{
		$dataTipoDocumentoIdentidad = $this->CargarTipoDocumentoIdentidadData();
		echo $this->json->json_response($dataTipoDocumentoIdentidad);
	}
}
