<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloGeneral extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->helper("date");
		$this->load->library('form_validation');
		$this->load->library('json');
    	$this->load->service("Configuracion/General/sEmpresa");
		$this->load->service("Configuracion/General/sFormaPago");
		$this->load->service("Configuracion/General/sGiroNegocio");
		$this->load->service("Configuracion/General/sGrupoParametro");
		$this->load->service("Configuracion/General/sMedioPago");
		$this->load->service("Configuracion/General/sMoneda");
		$this->load->service("Configuracion/General/sRegimenTributario");
		$this->load->service("Configuracion/General/sSede");
		$this->load->service("Configuracion/General/sTipoCambio");
		$this->load->service("Configuracion/General/sTipoDocumento");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service("Seguridad/sModuloSistema");
		$this->load->service("Configuracion/General/sAsignacionSede");
		$this->load->service("Configuracion/General/sTipoSede");
		$this->load->service("Configuracion/General/sUnidadMedida");
		$this->load->service("Configuracion/General/sRol");
		$this->load->service("Configuracion/Venta/sCorrelativoDocumento");
		$this->load->service("Configuracion/General/sConstanteSistema");
	}

	private function CargarEmpresa()
	{
		$data["IdEmpresa"] = $this->sEmpresa->ObtenerIdEmpresa();
		$Empresas = $this->sEmpresa->ListarEmpresas($data);
		$Empresa = $Empresas[0];
		$Empresa["RutaCertificado"] = BASE_PATH.$this->sEmpresa->ObtenerRutaCarpetaCertificado();
		$Empresa["FechaInicioCertificadoDigital"] = ($Empresa["FechaInicioCertificadoDigital"] == "") ? "" : convertirFechaES($Empresa["FechaInicioCertificadoDigital"]);
		$Empresa["FechaFinCertificadoDigital"] = ($Empresa["FechaFinCertificadoDigital"] == "") ? "" : convertirFechaES($Empresa["FechaFinCertificadoDigital"]);
		$Empresa["ParametroFacturacionElectronica"] = $this->sConstanteSistema->ObtenerParametroFacturacionElectronica();
		$GirosNegocio = $this->sGiroNegocio->ListarGirosNegocio();
		$RegimenesTributario = $this->sRegimenTributario->ListarRegimenesTributario();

		$ImageURL = $this->sEmpresa->ObtenerUrlCarpetaImagenes();

		$dataEmpresa = array("dataEmpresa" =>
			array(
				'Empresa'=>$Empresa,
				'GirosNegocio' =>$GirosNegocio,
				'RegimenesTributario' =>$RegimenesTributario,
				'ImageURL' =>$ImageURL
			)
		);
		return $dataEmpresa;
	}

	private function CargarFormaPago()
	{
		$FormaPago =  $this->sFormaPago->FormaPago;

			$dataFormaPago = array("dataFormaPago" =>
					array(
						'FormasPago' => array(),
						'FormaPago' => $FormaPago
					)
				);
		return $dataFormaPago;
	}

	private function CargarFormaPagoData()
	{
		$FormasPago = $this->sFormaPago->ListarFormasPago();

		$dataFormaPago__ = array("dataFormaPago" =>
					array(
						'FormasPago' => $FormasPago
					)
				);
		return $dataFormaPago__;
	}

	private function CargarGiroNegocio()
	{
		$GiroNegocio =  $this->sGiroNegocio->GiroNegocio;

		$dataGiroNegocio = array("dataGiroNegocio" =>
					array(
						'GirosNegocio' => array(),
						'GiroNegocio' => $GiroNegocio
					)
		 );
		 return $dataGiroNegocio;
	}

	private function CargarGiroNegocioData()
	{
		$GirosNegocio = $this->sGiroNegocio->ListarGirosNegocio();

		$dataGiroNegocio__ = array("dataGiroNegocio" =>
					array(
						'GirosNegocio' => $GirosNegocio
					)
		 );
		 return $dataGiroNegocio__;
	}

	private function CargarGrupoParametro()
	{
		$GrupoParametro =  $this->sGrupoParametro->GrupoParametro;

		$dataGrupoParametro = array("dataGrupoParametro" =>
					array(
						'GruposParametro' => array(),
						'GrupoParametro' => $GrupoParametro
					)
		 );
		 return $dataGrupoParametro;
	}

	private function CargarGrupoParametroData()
	{
		$GruposParametro =  $this->sGrupoParametro->ListarGruposParametro();

		$dataGrupoParametro__ = array("dataGrupoParametro" =>
					array(
						'GruposParametro' => $GruposParametro
					)
		 );
		 return $dataGrupoParametro__;
	}

	private function CargarMedioPago()
	{
		$MedioPago =  $this->sMedioPago->MedioPago;

		$dataMedioPago = array("dataMedioPago" =>
					array(
						'MediosPago' => array(),
						'MedioPago' => $MedioPago
					)
		 );
		 return $dataMedioPago;
	}

	private function CargarMedioPagoData()
	{
		$MediosPago = $this->sMedioPago->ListarMediosPago();

		$dataMedioPago__ = array("dataMedioPago" =>
					array(
						'MediosPago' => $MediosPago
					)
		 );
		 return $dataMedioPago__;
	}

	private function CargarMoneda()
	{
		$Moneda =  $this->sMoneda->Moneda;

		$dataMoneda = array("dataMoneda" =>
					array(
						'Monedas' => array(),
						'Moneda' => $Moneda
					)
		 );
		 return $dataMoneda;
	}

	private function CargarMonedaData()
	{
		$Monedas = $this->sMoneda->ListarMonedas();

		$dataMoneda__ = array("dataMoneda" =>
					array(
						'Monedas' => $Monedas
					)
		 );
		 return $dataMoneda__;
	}

	private function CargarRegimenTributario()
	{
		$RegimenTributario =  $this->sRegimenTributario->RegimenTributario;

		$dataRegimenTributario = array("dataRegimenTributario" =>
					array(
						'RegimenesTributario' => array(),
						'RegimenTributario' => $RegimenTributario
					)
		 );
		 return $dataRegimenTributario;
	}

	private function CargarRegimenTributarioData()
	{
		$RegimenesTributario = $this->sRegimenTributario->ListarRegimenesTributario();

		$dataRegimenTributario__ = array("dataRegimenTributario" =>
					array(
						'RegimenesTributario' => $RegimenesTributario
					)
		 );
		 return $dataRegimenTributario__;
	}

	private function CargarSede()
	{
		$Sede =  $this->sSede->Sede;
		$Sede['NumeroItemsSeleccionadas'] = 0;
		$Sede['SeleccionarTodos'] = false;
		$Sede['TiposSede'] =  array();
		$Sede['_TiposSede'] =  array();

		$dataSede = array("dataSede" =>
					array(
						'Sedes' => array(),
						'Sede' => $Sede
					)
		 );
		 return $dataSede;
	}

	private function CargarSedeData()
	{
		$Sedes = $this->sSede->ListarSedes();
		$TiposSede = $this->sTipoSede->ListarTipoSedeParaSede();

		$data_TiposSede = $TiposSede;

		foreach ($Sedes as $key=>$value) {
			$Sedes[$key]["TiposSede"] = $TiposSede;
			$AsignacionSede = $this->sAsignacionSede->ConsultarTipoSede($Sedes[$key]["IdSede"]);

			$TiposSede = $Sedes[$key]["TiposSede"];

			$Sedes[$key]["SeleccionarTodos"] = false;
			$Sedes[$key]["NumeroItemsSeleccionadas"] = 0;
			$totalfilas = 0;
			foreach($TiposSede  as $key1 =>$value1)
			{
				$TiposSede[$key1]["Seleccionado"] = false;
				$TiposSede[$key1]["IdAsignacionSede"] = "";

				foreach($AsignacionSede  as $key2 =>$value2)
				{
					if($value2["IdTipoSede"] == $value1["IdTipoSede"] )
					{
						$TiposSede[$key1]["IdAsignacionSede"] = $value2["IdAsignacionSede"];
						$TiposSede[$key1]["Seleccionado"] = true;
						$totalfilas++;
					}
				}
			}

			if(count($TiposSede) == $totalfilas){
				$Sedes[$key]["SeleccionarTodos"] = true;
			}
			$Sedes[$key]["NumeroItemsSeleccionadas"] = $totalfilas;

			$Sedes[$key]["TiposSede"] =$TiposSede;
			$Sedes[$key]["_TiposSede"] =$TiposSede;
		}

		foreach($data_TiposSede  as $key1 =>$value1)
		{
			$data_TiposSede[$key1]["IdAsignacionSede"] = "";
			$data_TiposSede[$key1]["Seleccionado"] = false;
		}

		$dataSede__ = array("dataSede" =>
					array(
						'Sedes' => $Sedes,
						'TiposSede' => $data_TiposSede

					)
		 );
		 return $dataSede__;
	}

	private function CargarTipoCambio()
	{
		$TipoCambio =  $this->sTipoCambio->TipoCambio;
		$EnteroValue = $this->sTipoCambio->ObtenerEnteroTipoCambio();
		$DecimalValue = $this->sTipoCambio->ObtenerDecimalTipoCambio();
		$Filtros =$this->sTipoCambio->PaginadorTipoCambio();
		$ParametroPesoChileno = $this->sConstanteSistema->ObtenerParametroPesoChileno();
		$dataTipoCambio = array("dataTipoCambio" =>
					array(
						'Filtros' =>$Filtros,
						'TiposCambio' =>array(),
						'TipoCambio' => $TipoCambio,
						'EnteroValue' => $EnteroValue,
						'DecimalValue' => $DecimalValue,
						'ParametroPesoChileno' => $ParametroPesoChileno
					)
		 );
		 return $dataTipoCambio;
	}

	private function CargarTipoCambioData()
	{
		$TiposCambio = array_reverse($this->sTipoCambio->ListarTiposCambio(1));

		$dataTipoCambio__ = array("dataTipoCambio" =>
					array(
						'TiposCambio' => $TiposCambio
					)
		 );
		 return $dataTipoCambio__;
	}

	private function CargarTipoDocumento()
	{
		$TipoDocumento =  $this->sTipoDocumento->TipoDocumento;
		$TipoDocumento['NumeroItemsSeleccionadas'] = 0;
		$TipoDocumento['SeleccionarTodos'] = false;
		$TipoDocumento['ModulosSistema'] =  array();
		$TipoDocumento['_ModulosSistema'] =  array();

		$dataTipoDocumento = array("dataTipoDocumento" =>
					array(
						'TiposDocumento' => array(),
						'TipoDocumento' => $TipoDocumento
					)
		 );
		 return $dataTipoDocumento;
	}

	private function CargarTipoDocumentoData()
	{
		$TiposDocumento = $this->sTipoDocumento->ListarTiposDocumento();
		$ModulosSistema = $this->sModuloSistema->ListarModulosSistemaParaTipoDocumento();
		$data_ModulosSistema = $ModulosSistema;

		foreach ($TiposDocumento as $key=>$value) {
			$TiposDocumento[$key]["ModulosSistema"] = $ModulosSistema;
			$TipoDocumentoModuloSistema = $this->sTipoDocumentoModuloSistema->ConsultarModuloSistema($TiposDocumento[$key]["IdTipoDocumento"]);

			$ModulosSistema = $TiposDocumento[$key]["ModulosSistema"];

			$TiposDocumento[$key]["SeleccionarTodos"] = false;
			$TiposDocumento[$key]["NumeroItemsSeleccionadas"] = 0;
			$totalfilas = 0;
			foreach($ModulosSistema  as $key1 =>$value1)
			{
				$ModulosSistema[$key1]["Seleccionado"] = false;
				$ModulosSistema[$key1]["IdTipoDocumentoModuloSistema"] = "";

				foreach($TipoDocumentoModuloSistema  as $key2 =>$value2)
				{
					if($value2["IdModuloSistema"] == $value1["IdModuloSistema"] )
					{
						$ModulosSistema[$key1]["IdTipoDocumentoModuloSistema"] = $value2["IdTipoDocumentoModuloSistema"];
						$ModulosSistema[$key1]["Seleccionado"] = true;
						$totalfilas++;
					}
				}
			}

			if(count($ModulosSistema) == $totalfilas){
				$TiposDocumento[$key]["SeleccionarTodos"] = true;
			}
			$TiposDocumento[$key]["NumeroItemsSeleccionadas"] = $totalfilas;

			$TiposDocumento[$key]["ModulosSistema"] =$ModulosSistema;
			$TiposDocumento[$key]["_ModulosSistema"] =$ModulosSistema;
		}

		foreach($data_ModulosSistema  as $key1 =>$value1)
		{
			$data_ModulosSistema[$key1]["IdTipoDocumentoModuloSistema"] = "";
			$data_ModulosSistema[$key1]["Seleccionado"] = false;
		}

		$dataTipoDocumento__ = array("dataTipoDocumento" =>
					array(
						'TiposDocumento' => $TiposDocumento,
						'ModulosSistema' => $data_ModulosSistema
					)
		 );

		 return $dataTipoDocumento__;
	}

	private function CargarTipoSede()
	{
		$TipoSede =  $this->sTipoSede->TipoSede;

		$dataTipoSede = array("dataTipoSede" =>
					array(
						'TiposSede' => array(),
						'TipoSede' => $TipoSede
					)
		 );
		 return $dataTipoSede;
	}

	private function CargarTipoSedeData()
	{
		$TiposSede = $this->sTipoSede->ListarTiposSede();

		$dataTipoSede__ = array("dataTipoSede" =>
					array(
						'TiposSede' => $TiposSede
					)
		 );
		 return $dataTipoSede__;
	}

	private function CargarUnidadMedida()
	{
		$UnidadMedida =  $this->sUnidadMedida->UnidadMedida;
		$Filtros = $this->sUnidadMedida->PreparaFiltroOtraUnidadMedida();

		$dataUnidadMedida = array("dataUnidadMedida" =>
		array(
			'Filtros' => $Filtros,
			'UnidadesMedida' => array(),
			'UnidadMedida' => $UnidadMedida,
			'OtraUnidadesMedida' => array(),
			'OtraUnidadMedida' => array()
			)
		);
		return $dataUnidadMedida;
	}

	private function CargarUnidadMedidaData()
	{
		$UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
		// $OtraUnidadesMedida = $this->sUnidadMedida->ListarOtraUnidadesMedida();

		$dataUnidadMedida__ = array("dataUnidadMedida" =>
			array(
				'UnidadesMedida' => $UnidadesMedida
				// 'OtraUnidadesMedida' => $OtraUnidadesMedida
				)
			);
		return $dataUnidadMedida__;
	}

	private function CargarCorrelativoDocumento()
	{
		$CorrelativoDocumento =  $this->sCorrelativoDocumento->CorrelativoDocumento;
		$CorrelativoDocumento['NombreSede'] = "";
		$CorrelativoDocumento['NombreTipoDocumento'] = "";
		$CorrelativoDocumento['NombreAbreviado'] = "";

		$dataCorrelativoDocumento = array("dataCorrelativoDocumento" =>
					array(
						'CorrelativosDocumento' => array(),
						'CorrelativoDocumento' => $CorrelativoDocumento,
						'Sedes' => array(),
						"TiposDocumento" => array()
					)
		 );
		 return $dataCorrelativoDocumento;
	}

	private function CargarCorrelativoDocumentoData()
	{
		$CorrelativosDocumento = $this->sCorrelativoDocumento->ListarCorrelativosDocumento();
		$Sedes = $this->sSede->ListarSedes();
		$TiposDocumento = $this->sTipoDocumento->ListarTiposDocumento();

		$dataCorrelativoDocumento__ = array("dataCorrelativoDocumento" =>
					array(
						'CorrelativosDocumento' => $CorrelativosDocumento,
						'Sedes' => $Sedes,
						"TiposDocumento" => $TiposDocumento
					)
		 );
		 return $dataCorrelativoDocumento__;
	}

	public function Index()
	{
		$dataEmpresa = $this->CargarEmpresa();
		$dataFormaPago = $this->CargarFormaPago();
		$dataGiroNegocio = $this->CargarGiroNegocio();
		$dataGrupoParametro = $this->CargarGrupoParametro();
		$dataMedioPago = $this->CargarMedioPago();
		$dataMoneda = $this->CargarMoneda();
		$dataRegimenTributario = $this->CargarRegimenTributario();
		$dataSede = $this->CargarSede();
		$dataTipoCambio = $this->CargarTipoCambio();
		$dataTipoDocumento = $this->CargarTipoDocumento();
		$dataTipoSede = $this->CargarTipoSede();
		$dataUnidadMedida = $this->CargarUnidadMedida();
		$dataCorrelativoDocumento = $this->CargarCorrelativoDocumento();

		// Empresa
    	$view_sub_subcontent['view_subcontent_buscador_empresa']=   $this->load->View('Configuracion/General/Empresa/view_mainpanel_subcontent_buscador_empresa','',true);
		$view_subcontent['view_subcontent_consulta_empresas'] =  $this->load->View('Configuracion/General/Empresa/view_mainpanel_subcontent_consulta_empresas',$view_sub_subcontent,true);
    	$view_['view_content_empresa'] =  $this->load->View('Configuracion/General/Empresa/view_mainpanel_content_empresa',$view_subcontent,true);
		// forma_pago
		$view_['view_content_formapago'] =  $this->load->View('Configuracion/General/FormaPago/view_mainpanel_content_formapago','',true);
		// GiroNegocio
		$view_['view_content_gironegocio'] =  $this->load->View('Configuracion/General/GiroNegocio/view_mainpanel_content_gironegocio','',true);
		// GrupoParametro
		$view_['view_content_grupoparametro'] =  $this->load->View('Configuracion/General/GrupoParametro/view_mainpanel_content_grupoparametro','',true);
		// MedioPago
		$view_['view_content_mediopago'] =  $this->load->View('Configuracion/General/MedioPago/view_mainpanel_content_mediopago','',true);
		// Moneda
		$view_['view_content_moneda'] =  $this->load->View('Configuracion/General/Moneda/view_mainpanel_content_moneda','',true);
		// dataRegimenTributario
		$view_['view_content_regimentributario'] =  $this->load->View('Configuracion/General/RegimenTributario/view_mainpanel_content_regimentributario','',true);
		// Sede
		$view_['view_content_sede'] =  $this->load->View('Configuracion/General/Sede/view_mainpanel_content_sede','',true);
		// TipoCambio
		$view_sub_subcontent['view_subcontent_buscador_tipocambio']=   $this->load->View('Configuracion/General/TipoCambio/view_mainpanel_subcontent_buscador_tipocambio','',true);
		$view_sub_subcontent['view_subcontent_paginacion_tipocambio']=   $this->load->View('Configuracion/General/TipoCambio/view_mainpanel_subcontent_paginacion_tipocambio','',true);
		$view_['view_content_tipocambio'] =  $this->load->View('Configuracion/General/TipoCambio/view_mainpanel_content_tipocambio',$view_sub_subcontent,true);
		// TipoDocumento
		$view_['view_content_tipodocumento'] =  $this->load->View('Configuracion/General/TipoDocumento/view_mainpanel_content_tipodocumento','',true);
		// TipoSede
		$view_['view_content_tiposede'] =  $this->load->View('Configuracion/General/TipoSede/view_mainpanel_content_tiposede','',true);
		// UnidadMedida
		$view_buscador["view_subcontent_buscador_otraunidadmedida"] = $this->load->View('Configuracion/General/UnidadMedida/view_mainpanel_subcontent_buscador_otraunidadmedida','',true);
		$view_buscador["view_subcontent_paginacion_otraunidadmedida"] = $this->load->View('Configuracion/General/UnidadMedida/view_mainpanel_subcontent_paginacion_otraunidadmedida','',true);
		$view_['view_content_unidadmedida'] =  $this->load->View('Configuracion/General/UnidadMedida/view_mainpanel_content_unidadmedida','',true);
		$view_['view_subcontent_otraunidadmedida'] =  $this->load->View('Configuracion/General/UnidadMedida/view_mainpanel_subcontent_otraunidadmedida',$view_buscador,true);
		// CorrelativoDocumento
		$view_['view_content_correlativodocumento'] =  $this->load->View('Configuracion/General/CorrelativoDocumento/view_mainpanel_content_correlativodocumento','',true);

		$data['dataEmpresa'] = $dataEmpresa;
		$data['dataFormaPago'] = $dataFormaPago;
		$data['dataGiroNegocio'] = $dataGiroNegocio;
		$data['dataGrupoParametro'] = $dataGrupoParametro;
		$data['dataMedioPago'] = $dataMedioPago;
		$data['dataMoneda'] = $dataMoneda;
		$data['dataRegimenTributario'] = $dataRegimenTributario;
		$data['dataSede'] = $dataSede;
		$data['dataTipoCambio'] = $dataTipoCambio;
		$data['dataTipoDocumento'] = $dataTipoDocumento;
		$data['dataTipoSede'] = $dataTipoSede;
		$data['dataUnidadMedida'] = $dataUnidadMedida;
		$data['dataCorrelativoDocumento'] = $dataCorrelativoDocumento;
		$view_data_vistamodelogeneral['data']= $data;
		$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Configuracion/General/.VistaModeloGeneral/view_main_vistamodelogeneral','',true);
		$view_ext['view_footer_extension'] = $this->load->View('Configuracion/General/.VistaModeloGeneral/view_mainpanel_footer_vistamodelogeneral',$view_data_vistamodelogeneral,true);

		$view['view_header'] = $this->load->view('.Master/view_mainpanel_header','',true);
		$view['view_navigationbar'] = $this->load->view('.Master/view_mainpanel_navigationbar','',true);
		$view['view_menu'] = $this->load->view('.Master/view_mainpanel_menu','',true);
		$view['view_demo_theme'] = $this->load->view('.Master/view_mainpanel_demo_theme','',true);
		$view['view_option_mobile'] = $this->load->view('.Master/view_modal_option_mobile','',true);
		$view['view_content'] =  $this->load->View('Configuracion/General/.VistaModeloGeneral/view_main_vistamodelogeneral',$view_,true);
		$view['view_footer'] = $this->load->view('.Master/view_mainpanel_footer',$view_ext,true);

    	$this->load->View('.Master/master_view_mainpanel',$view);
	}

	public function FormaPago()
	{
		$dataFormaPago = $this->CargarFormaPagoData();
		echo $this->json->json_response($dataFormaPago);
	}

	public function GiroNegocio()
	{
		$dataGiroNegocio = $this->CargarGiroNegocioData();
		echo $this->json->json_response($dataGiroNegocio);
	}

	public function GrupoParametro()
	{
		$dataGrupoParametro = $this->CargarGrupoParametroData();
		echo $this->json->json_response($dataGrupoParametro);
	}

	public function MedioPago()
	{
		$dataMedioPago = $this->CargarMedioPagoData();
		echo $this->json->json_response($dataMedioPago);
	}

	public function Moneda()
	{
		$dataMoneda = $this->CargarMonedaData();
		echo $this->json->json_response($dataMoneda);
	}

	public function RegimenTributario()
	{
		$dataRegimenTributario = $this->CargarRegimenTributarioData();
		echo $this->json->json_response($dataRegimenTributario);
	}

	public function Sede()
	{
		$dataSede = $this->CargarSedeData();
		echo $this->json->json_response($dataSede);
	}

	public function TipoCambio()
	{
		$dataTipoCambio = $this->CargarTipoCambioData();
		echo $this->json->json_response($dataTipoCambio);
	}

	public function TipoDocumento()
	{
		$dataTipoDocumento = $this->CargarTipoDocumentoData();
		echo $this->json->json_response($dataTipoDocumento);
	}

	public function TipoSede()
	{
		$dataTipoSede = $this->CargarTipoSedeData();
		echo $this->json->json_response($dataTipoSede);
	}

	public function UnidadMedida()
	{
		$dataUnidadMedida = $this->CargarUnidadMedidaData();
		echo $this->json->json_response($dataUnidadMedida);
	}

	public function CorrelativoDocumento()
	{
		$dataCorrelativoDocumento = $this->CargarCorrelativoDocumentoData();
		echo $this->json->json_response($dataCorrelativoDocumento);
	}

}
