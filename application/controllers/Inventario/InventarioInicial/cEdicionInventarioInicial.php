<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cEdicionInventarioInicial extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Inventario/sInventarioInicial");
		$this->load->service("Configuracion/General/sSede");
    $this->load->service("Configuracion/Catalogo/sTipoExistencia");
    $this->load->service("Configuracion/General/sUnidadMedida");
		$this->load->service("Configuracion/Catalogo/sFabricante");
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->service("Configuracion/General/sTipoPrecio");
		$this->load->service("Catalogo/sProductoProveedor");
		$this->load->service("Catalogo/sMercaderia");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("Configuracion/Inventario/sMotivoInventarioInicial");
		$this->load->service('Seguridad/sAccesoUsuarioAlmacen');
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
		$this->load->helper("date");
		$this->load->model("Base");
	}

	public function Index()
	{
		$hoy = $this->Base->ObtenerFechaServidor("d/m/Y");

		$Sedes=$this->sAccesoUsuarioAlmacen->ConsultarSedesTipoAlmacenPorUsuario();//$this->sSede->ListarSedesTipoAlmacen();

		$input["textofiltro"]='';
		$input["IdAsignacionSede"]=(count($Sedes)>0)? $Sedes[0]["IdAsignacionSede"] : '%';
		$input["pagina"]=1;
		$input["numerofilasporpagina"] = $this->sInventarioInicial->ObtenerNumeroFilasPorPagina();
		$input["paginadefecto"]=1;
		$input["FechaMovimiento"]=$this->Base->ObtenerFechaServidor("d/m/Y");
		$input["totalfilas"] = $this->sInventarioInicial->ObtenerNumeroTotalInventariosInicial($input);

		$InventarioInicial =  $this->sInventarioInicial->InventarioInicial;
		$InventarioInicial["IdSede"] = $this->sesionusuario->obtener_sesion_id_sede();
		$NuevaInventarioInicial =  $this->sInventarioInicial->InventarioInicial;
		$InventariosInicial = $this->sInventarioInicial->ConsultarInventariosInicial($input,$input["pagina"],$input["numerofilasporpagina"]);
		$TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();
		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
		$MotivosInventario = $this->sMotivoInventarioInicial->ListarMotivosInventarioInicial();

		$Mercaderia=$this->sMercaderia->Cargar();
		$Mercaderia["IdTipoExistencia"] = ID_TIPO_EXISTENCIA_MERCADERIA;
		$Mercaderia = array_merge($Mercaderia, $this->sInventarioInicial->Cargar());
		$Mercaderia["FechaVencimiento"] = $hoy;
		$Mercaderia["FechaEmisionDua"] = $hoy;
		$Mercaderia["FechaEmisionDocumentoSalidaZofra"] = $hoy;

		$ParametroLote = $this->sConstanteSistema->ObtenerParametroLote();
		$ParametroDua = $this->sConstanteSistema->ObtenerParametroDua();
		$ParametroDocumentoSalidaZofra = $this->sConstanteSistema->ObtenerParametroDocumentoSalidaZofra();
		$data = array("data" =>
					array(
						'Filtros' => $input,
						'InventarioInicial'=>$InventarioInicial,
						'NuevaInventarioInicial'=>$this->sInventarioInicial->Cargar(),
						'InventariosInicial'=>$InventariosInicial,
						'TiposExistencia'=>$TiposExistencia,
						'UnidadesMedida' =>$UnidadesMedida,
						'Mercaderia'  => $Mercaderia,
						'NuevaMercaderia'  => $Mercaderia,
						'Sedes'=>$Sedes,
						'MotivosInventario'=>$MotivosInventario,
						'FechaHoy' => $hoy,
						'ParametroLote' => $ParametroLote,
						'ParametroDua' => $ParametroDua,
						'ParametroDocumentoSalidaZofra' => $ParametroDocumentoSalidaZofra
					)
		 );

		$view_data['data'] = $data;
		$view_sub_subcontent['view_subcontent_buscador_inventarioinicial']=   $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_subcontent_buscador_inventarioinicial','',true);
		$view_sub_subcontent['view_subcontent_paginacion_inventarioinicial']=   $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_subcontent_paginacion_inventarioinicial','',true);
		$view_subcontent['view_subcontent_consulta_inventarioinicial'] =  $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_subcontent_consulta_inventarioinicial',$view_sub_subcontent,true);
		$view_subcontent['view_subcontent_form_inventarioinicial'] =  $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_subcontent_form_inventarioinicial','',true);
		$view_subcontent['view_subcontent_modal_form_mercaderia'] =  $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_modal_form_mercaderia','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_footer_inventarioinicial',$view_data,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Inventario/EdicionInventarioInicial/view_mainpanel_content_inventarioinicial',$view_subcontent,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function ConsultarInventariosInicial()
	{
		$input = $this->input->get("Data");
		$numerofilasporpagina = $this->sInventarioInicial->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sInventarioInicial->ObtenerNumeroTotalInventariosInicial($input);
		$output["resultado"] = $this->sInventarioInicial->ConsultarInventariosInicial($input,$input["pagina"],$numerofilasporpagina);
		$output["Filtros"] =array_merge($input, array(
			"numerofilasporpagina" => $numerofilasporpagina	,
			"totalfilas" => $TotalFilas,
			"paginadefecto" => 1)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarInventariosInicialPorPagina()
	{
		$input = $this->input->get("Data");
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$resultado = $this->sInventarioInicial->ConsultarInventariosInicial($input,$pagina,$numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}

	public function ActualizarFechaInventariosInicial()
	{
		$data = $this->input->post("Data");
		$resultado = $this->sInventarioInicial->ActualizarFechaInventariosInicial($data);
		echo $this->json->json_response($resultado);
	}

}
