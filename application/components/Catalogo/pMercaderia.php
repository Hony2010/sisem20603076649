<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class pMercaderia extends MY_Component {

        public $Mercaderia = array();
        public $Producto = array();

        public function __construct() {
            parent::__construct();
            $this->load->database();              
            $this->load->service("Catalogo/sMercaderia");
            $this->load->service("Configuracion/Catalogo/sFamiliaProducto");
            $this->load->service("Configuracion/Catalogo/sSubFamiliaProducto");
            $this->load->service("Configuracion/Catalogo/sTipoExistencia");
            $this->load->service("Configuracion/Catalogo/sLineaProducto");
            $this->load->service("Configuracion/Catalogo/sMarca");
            $this->load->service("Configuracion/Catalogo/sModelo");
            $this->load->service("Configuracion/General/sUnidadMedida");
            $this->load->service("Configuracion/Catalogo/sFabricante");
            $this->load->service("Configuracion/General/sMoneda");
            $this->load->service("Configuracion/General/sTipoSistemaISC");
            $this->load->service("Configuracion/General/sTipoAfectacionIGV");
            $this->load->service("Configuracion/General/sTipoPrecio");
            $this->load->service("Configuracion/General/sOrigenMercaderia");
            $this->load->service("Catalogo/sListaPrecioMercaderia");
            $this->load->service("Catalogo/sListaRaleoMercaderia");
            $this->load->service("Catalogo/sProductoProveedor");
            $this->load->helper('url');
            $this->load->helper('form');
            $this->load->library('form_validation');
            $this->load->library('json');
            $this->load->library('RestApi/Catalogo/RestApiMercaderia');      
        }

        public function Iniciar() {

            $input["textofiltro"]='';
            $input["pagina"]=1;
            $input["numerofilasporpagina"] = $this->sMercaderia->ObtenerNumeroFilasPorPagina();
            $input["paginadefecto"]=1;
            $input["totalfilas"] =$this->sMercaderia->ObtenerNumeroTotalMercaderias($input);
    
            $Mercaderia =  $this->sMercaderia->Cargar();
            $Mercaderias = $this->sMercaderia->ListarMercaderias(1);
            $FamiliasProducto = $this->sFamiliaProducto->ListarFamiliasProducto();
            $SubFamiliasProducto = $this->sSubFamiliaProducto->ListarTodosSubFamiliasProducto();
            $TiposExistencia = $this->sTipoExistencia->ListarTiposExistencia();
            $Marcas = $this->sMarca->ListarMarcas();
            $Modelos = $this->sModelo->ListarTodosModelos();
            $LineasProducto = $this->sLineaProducto->ListarLineasProducto();
            $UnidadesMedida = $this->sUnidadMedida->ListarUnidadesMedida();
            $Fabricantes = $this->sFabricante->ListarFabricantes();
            $Monedas = $this->sMoneda->ListarMonedas();
            $OrigenMercaderia = $this->sOrigenMercaderia->ListarOrigenMercaderia();
    
            $ImageURL = $this->sMercaderia->ObtenerUrlCarpetaImagenes();
            $LinkInicio = $this->sMercaderia->ObtenerLinkDeBusqueda()[0]->ValorParametroSistema;
            $LinkFin = $this->sMercaderia->ObtenerLinkDeBusqueda()[1]->ValorParametroSistema;
    
            $TiposSistemaISC = $this->sTipoSistemaISC->ListarTiposSistemaISC();
            $TiposAfectacionIGV = $this->sTipoAfectacionIGV->ListarTiposAfectacionIGV();
            $TiposPrecio = $this->sTipoPrecio->ListarTiposPrecio();
    
            $data = array("data" =>
                        array(
                            'Filtros' => $input,
                            'Mercaderia'=>$Mercaderia,
                            'NuevaMercaderia'=>$Mercaderia,
                            'Mercaderias'=>$Mercaderias,
                            'FamiliasProducto'=>$FamiliasProducto,
                            'SubFamiliasProducto'=>$SubFamiliasProducto,
                            'TiposExistencia'=>$TiposExistencia,
                            'Marcas'=>$Marcas,
                            'Modelos'=>$Modelos,
                            'LineasProducto' =>$LineasProducto,
                            'UnidadesMedida' =>$UnidadesMedida,
                            'Fabricantes' =>$Fabricantes,
                            'Monedas' =>$Monedas,
                            'ImageURL' =>$ImageURL,
                            'LinkInicio' =>$LinkInicio,
                            'LinkFin' =>$LinkFin,
                            'TiposSistemaISC' =>$TiposSistemaISC,
                            'TiposPrecio'=>$TiposPrecio,
                            'OrigenMercaderia'=>$OrigenMercaderia,
                            'TiposAfectacionIGV'=>$TiposAfectacionIGV
                        )
             );
             
            return $data;
        }

        public function ConsultarMercaderias($data) {
            $input = $data;//this->input->get("Data")
            $numerofilasporpagina = $this->sMercaderia->ObtenerNumeroFilasPorPagina();
            $TotalFilas = $this->sMercaderia->ObtenerNumeroTotalMercaderias($input);
            $output["resultado"] = $this->sMercaderia->ConsultarMercaderias($input,$input["pagina"],$numerofilasporpagina);
            $output["Filtros"] =array_merge($input, array(
                "numerofilasporpagina" => $numerofilasporpagina	,
                "totalfilas" => $TotalFilas,
                "paginadefecto" => 1)
            );

            return $output;// $this->json->json_response($output);
        }

}
