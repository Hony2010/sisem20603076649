<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class cVistaModeloReporteCatalogo extends CI_Controller  {

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Catalogo/sListaClientes");
		$this->load->service("Reporte/Catalogo/sClientesPorZona");
		$this->load->service("Reporte/Catalogo/sListaMercaderias");
		$this->load->service("Reporte/Catalogo/sListaActivosFijos");
		$this->load->service("Reporte/Catalogo/sListaGastos");
		$this->load->service("Reporte/Catalogo/sListaEmpleados");
		$this->load->service("Reporte/Catalogo/sListaProveedores");
		$this->load->service("Reporte/Catalogo/sListaCostosAgregados");
		$this->load->service("Reporte/Catalogo/sListaOtrasVentas");
		$this->load->service("Reporte/Catalogo/sListaFamiliasSubFamilias");
		$this->load->service("Reporte/Catalogo/sListaMarcasModelos");
    	$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	private function CargarReporteClientesPorZona() {
		$Buscador["NombreArchivoReporte_Z"] = $this->sClientesPorZona->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Z"] = $this->sClientesPorZona->ObtenerNombreArchivoJasper();
		$Buscador["NombreZona_Z"] = "";

		$dataReporte = array("dataReporteClientesPorZona" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporte;
	 }


	private function CargarReporteListaClientes() {
		$Buscador["NombreArchivoReporte_D"] = $this->sListaClientes->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_D"] = $this->sListaClientes->ObtenerNombreArchivoJasper();

		$dataReporte = array("dataReporteListaClientes" =>
					array(
						'Buscador'=>$Buscador
					)
		 );
		return $dataReporte;
	 }

	 private function CargarReporteListaMercaderias() {
		 $Buscador["NombreArchivoReporte_M"] = $this->sListaMercaderias->ObtenerNombreArchivoReporte();
		 $Buscador["NombreArchivoJasper_M"] = $this->sListaMercaderias->ObtenerNombreArchivoJasper();

		 $dataReporte = array("dataReporteListaMercaderias" =>
					 array(
						 'Buscador'=>$Buscador
					 )
			);
		 return $dataReporte;
		}

		private function CargarReporteListaActivosFijos() {
			$Buscador["NombreArchivoReporte_ActivosFijos"] = $this->sListaActivosFijos->ObtenerNombreArchivoReporte();
			$Buscador["NombreArchivoJasper_ActivosFijos"] = $this->sListaActivosFijos->ObtenerNombreArchivoJasper();

			$dataReporte = array("dataReporteListaActivosFijos" =>
						array(
							'Buscador'=>$Buscador
						)
			 );
			return $dataReporte;
		 }

		 private function CargarReporteListaGastos() {
			 $Buscador["NombreArchivoReporte_Gastos"] = $this->sListaGastos->ObtenerNombreArchivoReporte();
			 $Buscador["NombreArchivoJasper_Gastos"] = $this->sListaGastos->ObtenerNombreArchivoJasper();

			 $dataReporte = array("dataReporteListaGastos" =>
						 array(
							 'Buscador'=>$Buscador
						 )
				);
			 return $dataReporte;
			}

			private function CargarReporteListaEmpleados() {
				$Buscador["NombreArchivoReporte_Empleados"] = $this->sListaEmpleados->ObtenerNombreArchivoReporte();
				$Buscador["NombreArchivoJasper_Empleados"] = $this->sListaEmpleados->ObtenerNombreArchivoJasper();

				$dataReporte = array("dataReporteListaEmpleados" =>
							array(
								'Buscador'=>$Buscador
							)
				 );
				return $dataReporte;
			 }

			 private function CargarReporteListaProveedores() {
				 $Buscador["NombreArchivoReporte_Proveedores"] = $this->sListaProveedores->ObtenerNombreArchivoReporte();
				 $Buscador["NombreArchivoJasper_Proveedores"] = $this->sListaProveedores->ObtenerNombreArchivoJasper();

				 $dataReporte = array("dataReporteListaProveedores" =>
							 array(
								 'Buscador'=>$Buscador
							 )
					);
				 return $dataReporte;
				}

				private function CargarReporteListaCostosAgregados() {
					$Buscador["NombreArchivoReporte_CostosAgregados"] = $this->sListaCostosAgregados->ObtenerNombreArchivoReporte();
					$Buscador["NombreArchivoJasper_CostosAgregados"] = $this->sListaCostosAgregados->ObtenerNombreArchivoJasper();

					$dataReporte = array("dataReporteListaCostosAgregados" =>
								array(
									'Buscador'=>$Buscador
								)
					 );
					return $dataReporte;
				 }

				 private function CargarReporteListaOtrasVentas() {
					 $Buscador["NombreArchivoReporte_OtrasVentas"] = $this->sListaOtrasVentas->ObtenerNombreArchivoReporte();
					 $Buscador["NombreArchivoJasper_OtrasVentas"] = $this->sListaOtrasVentas->ObtenerNombreArchivoJasper();

					 $dataReporte = array("dataReporteListaOtrasVentas" =>
								 array(
									 'Buscador'=>$Buscador
								 )
						);
					 return $dataReporte;
					}

					private function CargarReporteListaFamiliasSubFamilias() {
						$Buscador["NombreArchivoReporte_FamiliasSubFamilias"] = $this->sListaFamiliasSubFamilias->ObtenerNombreArchivoReporte();
						$Buscador["NombreArchivoJasper_FamiliasSubFamilias"] = $this->sListaFamiliasSubFamilias->ObtenerNombreArchivoJasper();

						$dataReporte = array("dataReporteListaFamiliasSubFamilias" =>
									array(
										'Buscador'=>$Buscador
									)
						 );
						return $dataReporte;
					 }
					 private function CargarReporteListaMarcasModelos() {
						 $Buscador["NombreArchivoReporte_MarcasModelos"] = $this->sListaMarcasModelos->ObtenerNombreArchivoReporte();
						 $Buscador["NombreArchivoJasper_MarcasModelos"] = $this->sListaMarcasModelos->ObtenerNombreArchivoJasper();

						 $dataReporte = array("dataReporteListaMarcasModelos" =>
									 array(
										 'Buscador'=>$Buscador
									 )
							);
						 return $dataReporte;
						}

	 public function Index() {

		// ListaClientes
		$view_['view_content_listaclientes'] =  $this->load->View('Reporte/Catalogo/ListaClientes/view_mainpanel_content_listaclientes','',true);
		// ListaMercaderias
 		$view_['view_content_listamercaderias'] =  $this->load->View('Reporte/Catalogo/ListaMercaderias/view_mainpanel_content_listamercaderias','',true);
		// ListaActivosFijos
		$view_['view_content_listaactivosfijos'] =  $this->load->View('Reporte/Catalogo/ListaActivosFijos/view_mainpanel_content_listaactivosfijos','',true);
		// ListaGastos
		$view_['view_content_listagastos'] =  $this->load->View('Reporte/Catalogo/ListaGastos/view_mainpanel_content_listagastos','',true);
		// ListaEmpleados
		$view_['view_content_listaempleados'] =  $this->load->View('Reporte/Catalogo/ListaEmpleados/view_mainpanel_content_listaempleados','',true);
		// ListaProveedores
		$view_['view_content_listaproveedores'] =  $this->load->View('Reporte/Catalogo/ListaProveedores/view_mainpanel_content_listaproveedores','',true);
		// ListaCostosAgregados
		$view_['view_content_listacostosagregados'] =  $this->load->View('Reporte/Catalogo/ListaCostosAgregados/view_mainpanel_content_listacostosagregados','',true);
		// ListaOtrasVentas
		$view_['view_content_listaotrasventas'] =  $this->load->View('Reporte/Catalogo/ListaOtrasVentas/view_mainpanel_content_listaotrasventas','',true);
		// ListaFamiliasSubFamilias
		$view_['view_content_listafamiliassubfamilias'] =  $this->load->View('Reporte/Catalogo/ListaFamiliasSubFamilias/view_mainpanel_content_listafamiliassubfamilias','',true);
		// ListaMarcasModelos
		$view_['view_content_listamarcasmodelos'] =  $this->load->View('Reporte/Catalogo/ListaMarcasModelos/view_mainpanel_content_listamarcasmodelos','',true);
		// ListaMarcasModelos
		$view_['view_content_clientesporzona'] =  $this->load->View('Reporte/Catalogo/ClientesPorZona/view_mainpanel_content_clientesporzona','',true);


		$data['dataReporteListaClientes'] =  $this->CargarReporteListaClientes();
		$data['dataReporteListaMercaderias'] = $this->CargarReporteListaMercaderias();
		$data['dataReporteListaActivosFijos'] = $this->CargarReporteListaActivosFijos();
		$data['dataReporteListaGastos'] = $this->CargarReporteListaGastos();
		$data['dataReporteListaEmpleados'] = $this->CargarReporteListaEmpleados();
		$data['dataReporteListaProveedores'] = $this->CargarReporteListaProveedores();
		$data['dataReporteListaCostosAgregados'] = $this->CargarReporteListaCostosAgregados();
		$data['dataReporteListaOtrasVentas'] = $this->CargarReporteListaOtrasVentas();
		$data['dataReporteListaFamiliasSubFamilias'] = $this->CargarReporteListaFamiliasSubFamilias();
 		$data['dataReporteListaMarcasModelos'] = $this->CargarReporteListaMarcasModelos();
 		$data['dataReporteClientesPorZona'] = $this->CargarReporteClientesPorZona();

 		$view_data_vistamodeloreporte['data']= $data;
		 //$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteCatalogo/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
		$view_['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteCatalogo/view_mainpanel_subcontent_modal_reportevistaprevia','',true);
 		//$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Reporte/VistaModeloReporteCatalogo/view_main_vistamodeloreportecatalogo',$view_subcontent,true);
 		$view['view_content_min'] =  $this->load->View('Reporte/VistaModeloReporteCatalogo/view_main_vistamodeloreportecatalogo',$view_,true);
		$view['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteCatalogo/view_mainpanel_footer_vistamodeloreportecatalogo',$view_data_vistamodeloreporte,true);

     	$this->load->View('.Master/master_view_mainpanel_min',$view);
 	}
}
