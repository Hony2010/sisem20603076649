<?php
defined('BASEPATH') or exit('No direct script access allowed');

class cVistaModeloReporteVenta extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->service("Reporte/Venta/sVentaDetallado");
		$this->load->service("Reporte/Venta/sVentaGeneral");
		$this->load->service("Reporte/Venta/sVentaGeneralLubricante");
		$this->load->service("Reporte/Venta/sGananciaPorProducto");
		$this->load->service("Reporte/Venta/sGananciaPorPrecioBaseProducto");
		$this->load->service("Reporte/Venta/sGananciaPorVendedor");
		$this->load->service("Reporte/Venta/sProductosMasVendidos");
		$this->load->service("Reporte/Venta/sFamiliasMasVendidos");
		$this->load->service("Reporte/Venta/sMarcasMasVendidos");
		$this->load->service("Reporte/Venta/sVentaDiaria");
		$this->load->service("Reporte/Venta/sVentasPorVendedor");
		$this->load->service("Reporte/Venta/sProductosPorFamilia");
		$this->load->service("Reporte/Venta/sVentasMensuales");
		$this->load->service("Reporte/Venta/sVentasPorMercaderia");
		$this->load->service("Reporte/Venta/sReporteSaldoCliente");
		$this->load->service("Reporte/Venta/sReporteFormato14_1Venta");
		$this->load->service("Reporte/Venta/sResumenVentas");
		$this->load->service("Reporte/Venta/sRemuneracionEmpleadosMetaMensual");
		$this->load->service("Reporte/Venta/sListaPrecios");
		$this->load->service("Configuracion/General/sSede");
		$this->load->service("Configuracion/Catalogo/sMarca");
		$this->load->service("Seguridad/sUsuario");
		$this->load->service("Configuracion/General/sTipoDocumentoModuloSistema");
		$this->load->service('Configuracion/General/sConstanteSistema');
		$this->load->service("Reporte/Venta/sProductosPorFamiliaConsolidado");
		$this->load->helper('url');
		$this->load->helper('form');
		$this->load->library('sesionusuario');
		$this->load->library('form_validation');
		$this->load->library('json');
	}

	private function CargarReporteVentaDetallado()
	{
		$Buscador["FechaInicio_D"] = $this->sVentaDetallado->obtener_primer_dia_mes();
		$Buscador["FechaFinal_D"] = $this->sVentaDetallado->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_D"] = $this->sVentaDetallado->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_D"] = $this->sVentaDetallado->ObtenerNombreArchivoJasper();
		$Buscador["FormaPago_D"] = '0';
		$Buscador["NumeroDocumentoIdentidad_D"] = '0';
		$Buscador["Orden_D"] = '0';
		$Buscador["IdPersona"] = '';
		$Buscador["OpcionVistaVenta_D"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
		$Buscador["IdUsuario_D"] = $this->sesionusuario->obtener_sesion_id_usuario();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$input["IdModuloSistema"] = ID_MODULO_VENTA;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input, 0);
		$Buscador["TiposDocumento"] = array();
		$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		$Buscador["NumeroDocumentosSeleccionados"] = 0;

		$dataReporteVentaDetallado = array(
			"dataReporteVentaDetallado" =>
			array(
				'Buscador' => $Buscador
			)
		);
		return $dataReporteVentaDetallado;
	}

	private function CargarReporteVentaGeneral()
	{
		$Buscador["FechaInicio_R"] = $this->sVentaGeneral->obtener_primer_dia_mes();
		$Buscador["FechaFinal_R"] = $this->sVentaGeneral->obtener_ultimo_dia_mes();
		$Buscador["HoraInicio_R"] = '00:00';
		$Buscador["HoraFinal_R"] = '23:59';
		$Buscador["ParametroHoraReporte_R"] = $this->sConstanteSistema->ObtenerParametroHoraReporte();
		$Buscador["NombreArchivoReporte_R"] = $this->sVentaGeneral->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_R"] = $this->sVentaGeneral->ObtenerNombreArchivoJasper();
		$Buscador["NumeroDocumentoIdentidad_R"] = '0';
		$Buscador["IdPersona"] = '';
		$Buscador["OpcionVistaVenta_R"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
		$Buscador["IdUsuario_R"] = $this->sesionusuario->obtener_sesion_id_usuario();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$input["IdModuloSistema"] = ID_MODULO_VENTA;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input, 0);
		$Buscador["TiposDocumento"] = array();
		$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		$Buscador["NumeroDocumentosSeleccionados"] = 0;
		$Buscador["SerieDocumento"] = '';
		
		$dataReporteVentaGeneral = array(
			"dataReporteVentaGeneral" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteVentaGeneral;
	}

	private function CargarReporteVentaGeneralLubricante() {

		$Buscador["FechaInicio_R"] = $this->sVentaGeneral->obtener_primer_dia_mes();
		$Buscador["FechaFinal_R"] = $this->sVentaGeneral->obtener_ultimo_dia_mes();
		$Buscador["HoraInicio_R"] = '00:00';
		$Buscador["HoraFinal_R"] = '23:59';
		$Buscador["ParametroHoraReporte_R"] = $this->sConstanteSistema->ObtenerParametroHoraReporte();
		$Buscador["NombreArchivoReporte_R"] = $this->sVentaGeneralLubricante->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_R"] = $this->sVentaGeneralLubricante->ObtenerNombreArchivoJasper();
		$Buscador["NumeroDocumentoIdentidad_R"] = '0';
		$Buscador["IdPersona"] = '';
		$Buscador["Placa"] = '';
		$Buscador["RadioTaxi"] = '';
		$Buscador["OpcionVistaVenta_R"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
		$Buscador["IdUsuario_R"] = $this->sesionusuario->obtener_sesion_id_usuario();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$input["IdModuloSistema"] = ID_MODULO_VENTA;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input, 0);
		$Buscador["TiposDocumento"] = array();
		$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		$Buscador["NumeroDocumentosSeleccionados"] = 0;

		$dataReporteVentaGeneralLubricante = array(
			"dataReporteVentaGeneral" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteVentaGeneralLubricante;
	}

	private function CargarProductosMasVendidos()
	{
		$Buscador["FechaInicio_MAS"] = $this->sProductosMasVendidos->obtener_primer_dia_mes();
		$Buscador["FechaFinal_MAS"] = $this->sProductosMasVendidos->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_MAS"] = $this->sProductosMasVendidos->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_MAS"] = $this->sProductosMasVendidos->ObtenerNombreArchivoJasper();
		$Buscador["CantidadFilas_MAS"] = '0';
		$Buscador["OrdenadoPor"] = '1';
		$Buscador["Texto_MAS"] = '';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataProductosMasVendidos = array(
			"dataProductosMasVendidos" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataProductosMasVendidos;
	}
	private function CargarFamiliasMasVendidos()
	{
		$Buscador["FechaInicio_Familia"] = $this->sFamiliasMasVendidos->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Familia"] = $this->sFamiliasMasVendidos->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Familia"] = $this->sFamiliasMasVendidos->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Familia"] = $this->sFamiliasMasVendidos->ObtenerNombreArchivoJasper();
		$Buscador["CantidadFilas_Familia"] = '0';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataFamiliasMasVendidos = array(
			"dataFamiliasMasVendidos" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataFamiliasMasVendidos;
	}

	private function CargarMarcasMasVendidos()
	{
		$Buscador["FechaInicio_Marca"] = $this->sMarcasMasVendidos->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Marca"] = $this->sMarcasMasVendidos->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Marca"] = $this->sMarcasMasVendidos->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Marca"] = $this->sMarcasMasVendidos->ObtenerNombreArchivoJasper();
		$Buscador["CantidadFilas_Marca"] = '0';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataMarcasMasVendidos = array(
			"dataMarcasMasVendidos" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataMarcasMasVendidos;
	}

	private function CargarVentaDiaria()
	{
		$Buscador["FechaInicio_Diario"] = $this->sVentaDiaria->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Diario"] = $this->sVentaDiaria->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Diario"] = $this->sVentaDiaria->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Diario"] = $this->sVentaDiaria->ObtenerNombreArchivoJasper();
		$Buscador["OpcionVistaVenta_Diario"] = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
		$Buscador["IdUsuario_Diario"] = $this->sesionusuario->obtener_sesion_id_usuario();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataVentaDiaria = array(
			"dataVentaDiaria" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataVentaDiaria;
	}

	private function CargarReporteVentasPorVendedor()
	{
		$Buscador["FechaInicio_Vendedor"] = $this->sVentasPorVendedor->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Vendedor"] = $this->sVentasPorVendedor->obtener_ultimo_dia_mes();
		$Buscador["HoraInicio_Vendedor"] = '00:00';
		$Buscador["HoraFinal_Vendedor"] = '23:59';
		$Buscador["ParametroHoraReporte_Vendedor"] = $this->sConstanteSistema->ObtenerParametroHoraReporte();
		$Buscador["NombreArchivoReporte_Vendedor"] = $this->sVentasPorVendedor->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Vendedor"] = $this->sVentasPorVendedor->ObtenerNombreArchivoJasper();
		$Buscador["Vendedores"] = $this->sUsuario->ListarUsuarios();
		$Buscador["VendedoresSeleccionados"] = array();
		$Buscador["TotalVendedores"] = count($Buscador["Vendedores"]);
		$Buscador["NumeroVendedoresSeleccionados"] = 0;
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataReporteVentasPorVendedor = array(
			"dataReporteVentasPorVendedor" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteVentasPorVendedor;
	}

	private function CargarReporteResumenVentas()
	{
		$Buscador["FechaInicio_ResumenVentas"] = $this->sResumenVentas->obtener_primer_dia_mes();
		$Buscador["FechaFinal_ResumenVentas"] = $this->sResumenVentas->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_ResumenVentas"] = $this->sResumenVentas->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_ResumenVentas"] = $this->sResumenVentas->ObtenerNombreArchivoJasper();
		$Buscador["Vendedor"] = $this->sUsuario->ListarUsuarios();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataReporteResumenVentas = array(
			"dataReporteResumenVentas" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteResumenVentas;
	}

	private function CargarProductosPorFamilia()
	{
		$Buscador["FechaInicio_PF"] = $this->sProductosPorFamilia->obtener_primer_dia_mes();
		$Buscador["FechaFinal_PF"] = $this->sProductosPorFamilia->obtener_ultimo_dia_mes();
		$Buscador["HoraInicio_PF"] = '00:00';
		$Buscador["HoraFinal_PF"] = '23:59';
		$Buscador["ParametroHoraReporte_PF"] = $this->sConstanteSistema->ObtenerParametroHoraReporte();
		$Buscador["NombreArchivoReporte_PF"] = $this->sProductosPorFamilia->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_PF"] = $this->sProductosPorFamilia->ObtenerNombreArchivoJasper();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";
		$Buscador["FamiliasProducto"]=$this->sFamiliaProducto->ListarFamiliasProducto();
		$Buscador["IdFamiliaProducto"]="%";

		$dataProductosPorFamilia = array(
			"dataProductosPorFamilia" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataProductosPorFamilia;
	}

	private function CargarProductosPorFamiliaConsolidado()
	{
		$Buscador["FechaInicio"] = $this->sProductosPorFamiliaConsolidado->obtener_primer_dia_mes();
		$Buscador["FechaFinal"] = $this->sProductosPorFamiliaConsolidado->obtener_ultimo_dia_mes();
		$Buscador["HoraInicio"] = '00:00';
		$Buscador["HoraFinal"] = '23:59';
		$Buscador["ParametroHoraReporte"] = $this->sConstanteSistema->ObtenerParametroHoraReporte();
		$Buscador["NombreArchivoReporte"] = $this->sProductosPorFamiliaConsolidado->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper"] = $this->sProductosPorFamiliaConsolidado->ObtenerNombreArchivoJasper();

		$Buscador["Usuarios"] = $this->sUsuario->ListarUsuarios();
		$Buscador["UsuariosSeleccionados"] = array();
		$Buscador["TotalUsuarios"] = count($Buscador["Usuarios"]);
		$Buscador["NumeroUsuariosSeleccionados"] = 0;
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$data = array('Buscador' => $Buscador );

		return $data;
	}

	private function CargarVentasMensuales()
	{
		$Buscador["FechaInicio_Mensual"] = "";
		$Buscador["FechaFinal_Mensual"] = "";
		$Buscador["NombreArchivoReporte_Mensual"] = $this->sVentasMensuales->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Mensual"] = $this->sVentasMensuales->ObtenerNombreArchivoJasper();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataVentasMensuales = array(
			"dataVentasMensuales" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataVentasMensuales;
	}

	private function CargarReporteVentasPorMercaderia()
	{
		$Buscador["FechaInicio_Mercaderia"] = $this->sVentasPorMercaderia->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Mercaderia"] = $this->sVentasPorMercaderia->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Mercaderia"] = $this->sVentasPorMercaderia->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Mercaderia"] = $this->sVentasPorMercaderia->ObtenerNombreArchivoJasper();
		$Buscador["IdProducto_Mercaderia"] = '0';
		$Buscador["TextoMercaderia_Mercaderia"] = '';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataReporteVentasPorMercaderia = array(
			"dataReporteVentasPorMercaderia" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteVentasPorMercaderia;
	}

	private function CargarReporteGananciaPorProducto()
	{
		$Buscador["FechaInicio_Gananciaporproducto"] = $this->sGananciaPorProducto->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Gananciaporproducto"] = $this->sGananciaPorProducto->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Gananciaporproducto"] = $this->sGananciaPorProducto->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Gananciaporproducto"] = $this->sGananciaPorProducto->ObtenerNombreArchivoJasper();
		$Buscador["IdProducto_Gananciaporproducto"] = '0';
		$Buscador["TextoMercaderia_Gananciaporproducto"] = '';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";

		$dataReporteGananciaPorProducto = array(
			"dataReporteGananciaPorProducto" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteGananciaPorProducto;
	}

	private function CargarReporteGananciaPorPrecioBaseProducto()
	{
		$Buscador["FechaInicio_GananciaPorPrecioBaseProducto"] = $this->sGananciaPorPrecioBaseProducto->obtener_primer_dia_mes();
		$Buscador["FechaFinal_GananciaPorPrecioBaseProducto"] = $this->sGananciaPorPrecioBaseProducto->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_GananciaPorPrecioBaseProducto"] = $this->sGananciaPorPrecioBaseProducto->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_GananciaPorPrecioBaseProducto"] = $this->sGananciaPorPrecioBaseProducto->ObtenerNombreArchivoJasper();
		$Buscador["IdProducto_GananciaPorPrecioBaseProducto"] = '0';
		$Buscador["TextoMercaderia_GananciaPorPrecioBaseProducto"] = '';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["Marcas"] = $this->sMarca->ListarMarcas();
		$Buscador["IdAsignacionSede"]="%";

		$dataReporteGananciaPorPrecioBaseProducto = array(
			"dataReporteGananciaPorPrecioBaseProducto" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteGananciaPorPrecioBaseProducto;
	}

	private function CargarReporteGananciaPorVendedor()
	{
		$Buscador["FechaInicio_Gananciaporvendedor"] = $this->sGananciaPorVendedor->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Gananciaporvendedor"] = $this->sGananciaPorVendedor->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Gananciaporvendedor"] = $this->sGananciaPorVendedor->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Gananciaporvendedor"] = $this->sGananciaPorVendedor->ObtenerNombreArchivoJasper();
		$Buscador["IdProducto_Gananciaporvendedor"] = '0';
		$Buscador["TextoMercaderia_Gananciaporvendedor"] = '';
		$Buscador["Agencia"] = $this->sSede->ListarSedes();
		$Buscador["Vendedor"] = $this->sUsuario->ListarUsuarios();
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";
		$dataReporteGananciaPorVendedor = array(
			"dataReporteGananciaPorVendedor" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteGananciaPorVendedor;
	}

	private function CargarReporteSaldoCliente()
	{
		$Buscador["FechaInicio_SaldoCliente"] = $this->sReporteSaldoCliente->obtener_primer_dia_mes();
		$Buscador["FechaFinal_SaldoCliente"] = $this->sReporteSaldoCliente->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_SaldoCliente"] = $this->sReporteSaldoCliente->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_SaldoCliente"] = $this->sReporteSaldoCliente->ObtenerNombreArchivoJasper();
		$Buscador["IdPersona_SaldoCliente"] = '0';
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";
		
		$dataReporteSaldoCliente = array(
			"dataReporteSaldoCliente" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteSaldoCliente;
	}

	private function CargarReporteFormato14_1Venta()
	{
		$Buscador["FechaInicio_Formato14"] = $this->sReporteFormato14_1Venta->obtener_primer_dia_mes();
		$Buscador["FechaFinal_Formato14"] = $this->sReporteFormato14_1Venta->obtener_ultimo_dia_mes();
		$Buscador["NombreArchivoReporte_Formato14"] = $this->sReporteFormato14_1Venta->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper_Formato14"] = $this->sReporteFormato14_1Venta->ObtenerNombreArchivoJasper();

		$input["IdModuloSistema"] = ID_MODULO_VENTA;
		$input["IdTipoDocumento"] = FILTRO_TODOS;
		$Buscador["TiposDocumentoVenta"] = $this->sTipoDocumentoModuloSistema->ListarTiposDocumentoModuloSistemaPorIdModulo($input, 0);
		$Buscador["TiposDocumento"] = array();
		$Buscador["TotalTipoDocumentos"] = count($Buscador["TiposDocumentoVenta"]);
		$Buscador["NumeroDocumentosSeleccionados"] = 0;
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";
		$dataReporteFormato14_1Venta = array(
			"dataReporteFormato14_1Venta" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteFormato14_1Venta;
	}


	private function CargarReporteRemuneracionEmpleadosMetaMensual()
	{
		$Buscador["Mes"] = '';
		$Buscador["Anio"] = '';
		$Buscador["NombreArchivoReporte"] = $this->sRemuneracionEmpleadosMetaMensual->ObtenerNombreArchivoReporte();
		$Buscador["NombreArchivoJasper"] = $this->sRemuneracionEmpleadosMetaMensual->ObtenerNombreArchivoJasper();
		$Buscador["Vendedores"] = $this->sUsuario->ListarUsuarios();
		$Buscador["TotalVendedores"] = count($Buscador["Vendedores"]);
		$Buscador["VendedoresSeleccionados"] = array();
		$Buscador["NumeroVendedoresSeleccionados"] = 0;
		$Buscador["Almacenes"] = $this->sSede->ListarSedesTipoAlmacen();
		$Buscador["IdAsignacionSede"]="%";
		
		$dataReporteRemuneracionEmpleadosMetaMensual = array(
			"dataReporteRemuneracionEmpleadosMetaMensual" =>
			array(
				'Buscador' => $Buscador
			)
		);

		return $dataReporteRemuneracionEmpleadosMetaMensual;
	}

	private function CargarParametros() {
			$dataParametros["ParametroLubricante"] = $this->sConstanteSistema->ObtenerParametroRubroLubricante();
			return $dataParametros;
	 }

	public function Index()
	{
		// VentaDetallado
		$view_['view_content_ventadetallado'] =  $this->load->View('Reporte/Venta/VentaDetallado/view_mainpanel_content_ventadetallado', '', true);
		// VentaGeneral
		$view_['view_content_ventageneral'] =  $this->load->View('Reporte/Venta/VentaGeneral/view_mainpanel_content_ventageneral', '', true);
		// VentaGeneralLubricante
		$view_['view_content_ventagenerallubricante'] =  $this->load->View('Reporte/Venta/VentaGeneralLubricante/view_mainpanel_content_ventageneral', '', true);
		// ProductosMasVendidos
		$view_['view_content_productosmasvendidos'] =  $this->load->View('Reporte/Venta/ProductosMasVendidos/view_mainpanel_content_productosmasvendidos', '', true);
		// FamiliasMasVendidos
		$view_['view_content_familiasmasvendidos'] =  $this->load->View('Reporte/Venta/FamiliasMasVendidos/view_mainpanel_content_familiasmasvendidos', '', true);
		// MarcasMasVendidos
		$view_['view_content_marcasmasvendidos'] =  $this->load->View('Reporte/Venta/MarcasMasVendidos/view_mainpanel_content_marcasmasvendidos', '', true);
		// VentaDiaria
		$view_['view_content_ventadiaria'] =  $this->load->View('Reporte/Venta/VentaDiaria/view_mainpanel_content_ventadiaria', '', true);
		// VentasPorVendedor
		$view_['view_content_ventasporvendedor'] =  $this->load->View('Reporte/Venta/VentasPorVendedor/view_mainpanel_content_ventasporvendedor', '', true);
		// ProductosPorFamilia
		$view_['view_content_productosporfamilia'] =  $this->load->View('Reporte/Venta/ProductosPorFamilia/view_mainpanel_content_productosporfamilia', '', true);
		// ProductosPorFamiliaConsolidado
		$view_['view_content_productosporfamiliaconsolidado'] =  $this->load->View('Reporte/Venta/ProductosPorFamiliaConsolidado/view_mainpanel_content_productosporfamiliaconsolidado', '', true);
		// VentasMensuales
		$view_['view_content_ventasmensuales'] =  $this->load->View('Reporte/Venta/VentasMensuales/view_mainpanel_content_ventasmensuales', '', true);
		// VentasPorMercaderia
		$view_['view_content_ventaspormercaderia'] =  $this->load->View('Reporte/Venta/VentasPorMercaderia/view_mainpanel_content_ventaspormercaderia', '', true);
		// GananciaPorProducto
		$view_['view_content_gananciaporproducto'] =  $this->load->View('Reporte/Venta/GananciaPorProducto/view_mainpanel_content_gananciaporproducto', '', true);
		// GananciaPorVendedor
		$view_['view_content_gananciaporvendedor'] =  $this->load->View('Reporte/Venta/GananciaPorVendedor/view_mainpanel_content_gananciaporvendedor', '', true);
		// GananciaPorPrecioBaseProducto
		$view_['view_content_gananciaporpreciobaseproducto'] =  $this->load->View('Reporte/Venta/GananciaPorPrecioBaseProducto/view_mainpanel_content_gananciaporpreciobaseproducto', '', true);
		// ReportesaldoCliente
		$view_['view_content_reportesaldocliente'] =  $this->load->View('Reporte/Venta/ReporteSaldoCliente/view_mainpanel_content_reportesaldocliente', '', true);
		// ReporteFormato14_1Venta
		$view_['view_content_Reporteformato14_1venta'] =  $this->load->View('Reporte/Venta/ReporteFormato14_1Venta/view_mainpanel_content_reporteformato14_1venta', '', true);
		// ResumenVentas
		$view_['view_content_resumen_ventas'] =  $this->load->View('Reporte/Venta/ResumenVentas/view_mainpanel_content_resumenventas', '', true);
		// ReporteRemuneracionEmpleadosMetaMensual
		$view_['view_content_reporteremuneracionempleadosmetamensual'] =  $this->load->View('Reporte/Venta/ReporteRemuneracionEmpleadosMetaMensual/view_content_reporteremuneracionempleadosmetamensual', '', true);
		// ReporteListadoPrecios
		$view_['view_content_reportelistaprecios'] =  $this->load->View('Reporte/Venta/ReporteListaPrecios/view_content_reportelistaprecios', '', true);

		$data['dataReporteVentaDetallado'] =  $this->CargarReporteVentaDetallado();
		$data['dataReporteVentaGeneral'] = $this->CargarReporteVentaGeneral();
		$data['dataReporteVentaGeneralLubricante'] = $this->CargarReporteVentaGeneralLubricante();
		$data['dataProductosMasVendidos'] = $this->CargarProductosMasVendidos();
		$data['dataFamiliasMasVendidos'] = $this->CargarFamiliasMasVendidos();
		$data['dataMarcasMasVendidos'] = $this->CargarMarcasMasVendidos();
		$data['dataVentaDiaria'] = $this->CargarVentaDiaria();
		$data['dataReporteVentasPorVendedor'] = $this->CargarReporteVentasPorVendedor();
		$data['dataProductosPorFamilia'] = $this->CargarProductosPorFamilia();
		$data['dataReporteProductosPorFamiliaConsolidado'] = $this->CargarProductosPorFamiliaConsolidado();
		$data['dataVentasMensuales'] = $this->CargarVentasMensuales();
		$data['dataReporteVentasPorMercaderia'] = $this->CargarReporteVentasPorMercaderia();
		$data['dataReporteGananciaPorProducto'] = $this->CargarReporteGananciaPorProducto();
		$data['dataReporteGananciaPorVendedor'] = $this->CargarReporteGananciaPorVendedor();
		$data['dataReporteGananciaPorPrecioBaseProducto'] = $this->CargarReporteGananciaPorPrecioBaseProducto();
		$data['dataReporteSaldoCliente'] = $this->CargarReporteSaldoCliente();
		$data['dataReporteFormato14_1Venta'] = $this->CargarReporteFormato14_1Venta();
		$data['dataReporteResumenVentas'] = $this->CargarReporteResumenVentas();
		$data['dataReporteRemuneracionEmpleadosMetaMensual'] = $this->CargarReporteRemuneracionEmpleadosMetaMensual();
		$data['dataReporteListaPrecios'] = $this->sListaPrecios->Cargar();
		$data['parametros'] = $this->CargarParametros();

		$view_data_vistamodeloreporte['data'] = $data;
		$view_subcontent['view_mainpanel_subcontent_modal_reportevistaprevia'] =  $this->load->View('Reporte/VistaModeloReporteVenta/view_mainpanel_subcontent_modal_reportevistaprevia', '', true);
		$view_vistamodelogeneral['view_main_vistamodelogeneral'] = $this->load->View('Reporte/VistaModeloReporteVenta/view_main_vistamodeloreporteventa', $view_subcontent, true);

		$view['view_footer_extension'] = $this->load->View('Reporte/VistaModeloReporteVenta/view_mainpanel_footer_vistamodeloreporteventa', $view_data_vistamodeloreporte, true);
		$view['view_content_min'] =  $this->load->View('Reporte/VistaModeloReporteVenta/view_main_vistamodeloreporteventa', $view_, true);

		$this->load->View('.Master/master_view_mainpanel_min', $view);
	}
}
