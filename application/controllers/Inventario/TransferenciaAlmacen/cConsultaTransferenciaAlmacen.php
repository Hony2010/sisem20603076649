<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cConsultaTransferenciaAlmacen extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model("Base");
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('date');
        $this->load->library('json');
        $this->load->library('shared');
        $this->load->service("Inventario/sTransferenciaAlmacen");
    }


    public function Index()
    {
        $fechaservidor = $this->Base->ObtenerFechaServidor("Y-m-d");
        $input["TextoFiltro"] = '';
        $input["FechaInicio"] = $fechaservidor;
        $input["FechaFin"] = $fechaservidor;        
        $input["pagina"] = 1;
        $input["numerofilasporpagina"] = $this->sTransferenciaAlmacen->ObtenerNumeroFilasPorPagina();
        $input["paginadefecto"] = 1;
        $input["totalfilas"] = $this->sTransferenciaAlmacen->ObtenerNumeroTotalTransferenciaAlmacen($input);

        $TransferenciaAlmacen = $this->sTransferenciaAlmacen->Cargar();
        $TransferenciasAlmacen = $this->sTransferenciaAlmacen->ConsultarTransferenciasAlmacen($input, $input["pagina"], $input["numerofilasporpagina"]); 

        $input["FechaInicio"] = date("d/m/Y", strtotime($input["FechaInicio"]));
        $input["FechaFin"] = date("d/m/Y", strtotime($input["FechaFin"]));

        $data = array(
            "data" => array(
                'Filtros' => $input,
                'TransferenciaAlmacen' => $TransferenciaAlmacen,
                'TransferenciasAlmacen' => $TransferenciasAlmacen,
            )
        );

        $view_data['data'] = $data;

        $views_tc['view_form_transferenciaalmacen'] = $this->load->View('Inventario/TransferenciaAlmacen/view_form_transferenciaalmacen', '', true);
        $views['view_modal_transferenciaalmacen'] = $this->load->View('Inventario/TransferenciaAlmacen/view_modal_transferenciaalmacen', $views_tc, true);

        $views['view_buscador_consultatransferenciaalmacen'] = $this->load->View('Inventario/ConsultaTransferenciaAlmacen/view_buscador_consultatransferenciaalmacen', '', true);
        $views['view_tabla_consultatransferenciaalmacen'] = $this->load->View('Inventario/ConsultaTransferenciaAlmacen/view_tabla_consultatransferenciaalmacen', '', true);
        $views['view_paginacion_consultatransferenciaalmacen'] = $this->load->View('Inventario/ConsultaTransferenciaAlmacen/view_paginacion_consultatransferenciaalmacen', "", true);

        $view_footer['view_footer_extension'] = $this->load->View('Inventario/ConsultaTransferenciaAlmacen/view_footer_consultatransferenciaalmacen', $view_data, true);
        $view['view_header'] = $this->load->view('.Master/view_mainpanel_header', '', true);
        $view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar', '', true);
        $view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu', '', true);
        $view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme', '', true);
        $view['view_content'] =  $this->load->View('Inventario/ConsultaTransferenciaAlmacen/view_content_consultatransferenciaalmacen', $views, true);
        $view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer', $view_footer, true);

        $this->load->View('.Master/master_view_mainpanel', $view);
    }

    public function ConsultarTransferenciasAlmacen() {
		$input = json_decode($this->input->post("Data"), true);
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$numerofilasporpagina = $this->sTransferenciaAlmacen->ObtenerNumeroFilasPorPagina();
		$TotalFilas = $this->sTransferenciaAlmacen->ObtenerNumeroTotalTransferenciaAlmacen($input);
		$output["resultado"] = $this->sTransferenciaAlmacen->ConsultarTransferenciasAlmacen($input, $input["pagina"], $numerofilasporpagina);
		$output["Filtros"] = array_merge(
			$input,
			array(
				"numerofilasporpagina" => $numerofilasporpagina,
				"totalfilas" => $TotalFilas,
				"paginadefecto" => 1
			)
		);
		echo $this->json->json_response($output);
	}

	public function ConsultarTransferenciasAlmacenPorPagina()
	{
		$input = json_decode($this->input->post("Data"), true);
		$pagina = $input["pagina"];
		$numerofilasporpagina = $input["numerofilasporpagina"];
		$input["FechaInicio"] = convertToDate($input["FechaInicio"]);
		$input["FechaFin"] = convertToDate($input["FechaFin"]);
		$resultado = $this->sTransferenciaAlmacen->ConsultarTransferenciasAlmacen($input, $pagina, $numerofilasporpagina);
		echo $this->json->json_response($resultado);
	}
}
