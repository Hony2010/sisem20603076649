<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class sMigracionInventario extends MY_Service {

    public $MigracionInventario = array();
        
    public function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->library('shared');
        $this->load->library('sesionusuario');
        $this->load->library('mapper');
        $this->load->library('herencia');
        $this->load->library('reporter');
        $this->load->library('imprimir');        
        $this->load->model('Inventario/mMigracionInventario');            
        $this->load->service('Inventario/sInventarioInicial');
        $this->load->service('Configuracion/General/sAsignacionSede'); 
        $this->load->service('Configuracion/General/sUnidadMedida'); 
        $this->load->service('Configuracion/Catalogo/sMarca'); 
        $this->load->service('Configuracion/Catalogo/sModelo'); 
        $this->load->service('Configuracion/Catalogo/sFamiliaProducto'); 
        $this->load->service('Configuracion/Catalogo/sSubFamiliaProducto'); 
        $this->load->service('Configuracion/Catalogo/sLineaProducto'); 
        $this->load->service('Configuracion/General/sParametroSistema');
    }

    function ListarMigracionProductosInventario() {
        $resultado = $this->mMigracionInventario->ListarMigracionInventario();
        return $resultado;
    }   

    function MigrarUnidadesMedidasInventario() {
        $mensaje = "";
        $dataMigracionUnidadesMedida =$this->mMigracionInventario->ListarMigracionUnidadesMedidaInventario();

        foreach ($dataMigracionUnidadesMedida as $key => $value) {            
          $value["IndicadorEstado"]="%";
          $resultadoUnidadMedida = $this->sUnidadMedida->ObtenerUnidadMedidaPorNombreOAbreviatura($value);
    
          if(count($resultadoUnidadMedida) > 0 ) {
            $dataMigracionUnidadesMedida[$key]["IdUnidadMedida"]=$resultadoUnidadMedida[0]["IdUnidadMedida"];

            if ($resultadoUnidadMedida[0]["IndicadorEstado"]==ESTADO_ELIMINADO) {               
              $this->sUnidadMedida->ActivarUnidadMedida($dataMigracionUnidadesMedida[$key]);              
            }              
          }
          else{
            $mensaje =$mensaje."La unidad de medida migrada : ".$value["NombreUnidadMedida"]." no se encuentra en el catalogo de unidades de medidas.<br>";
          }
        }

        if ($mensaje != "") {
          return $mensaje;
        }
        else {
          return $dataMigracionUnidadesMedida;
        }
    }
 
    function MigrarModeloMarcaInventario($data) {
        $mensaje="";
        $dataModelo = $this->sModelo->ObtenerModeloMarcaPorNombreModelo($data);
              
        if(count($dataModelo) > 0) {
          $data["IdModelo"] = $dataModelo[0]["IdModelo"];
        }
        else {                  
          $resultadoModelo = $this->sModelo->InsertarModelo($data);
          if (is_string($resultadoModelo)) {
            $mensaje =  "El modelo migrado ".$data["NombreModelo"] ." tiene la siguiente observacion : " .$resultadoModelo."<br>"; 
            return $mensaje;
          }
          else {
            $data["IdModelo"] = $resultadoModelo["IdModelo"];
          }
        }

        return $data;
    }

    function MigrarMarcasModelosInventario() {
        $mensaje="";
        $dataMigracionMarcasModelos =$this->mMigracionInventario->ListarMigracionMarcasModelosInventario();
        
        foreach ($dataMigracionMarcasModelos as $key => $value) {
          $value["NombreMarca"] = $value["NombreMarca"] == "" ? NOMBRE_NO_ESPECIFICADO : $value["NombreMarca"];
          $value["NombreModelo"] = $value["NombreModelo"] == "" ? NOMBRE_NO_ESPECIFICADO : $value["NombreModelo"];

          $dataMarca = $this->sMarca->ObtenerMarcaPorNombreMarca($value);
          
          if(count($dataMarca) > 0 ) { 
            $value["IdMarca"] = $dataMarca[0]["IdMarca"];
            $value["NoEspecificado"] = $value["NombreModelo"] == NOMBRE_NO_ESPECIFICADO ? "S" : "";
            $resultadoModelo = $this->MigrarModeloMarcaInventario($value);
            if (is_string($resultadoModelo)) {
              $mensaje = $mensaje.$resultadoModelo;
            }
            else {
              $value["IdModelo"]=$resultadoModelo["IdModelo"];
            }
          }
          else {
            $resultadoMarca = $this->sMarca->InsertarMarca($value);
            if (is_string($resultadoMarca)) {
              $mensaje =$mensaje."La marca migrada ".$value["NombreMarca"] ." tiene la siguiente observacion : " .$resultadoMarca."<br>"; 
            }
            else {
              $value["IdMarca"] = $resultadoMarca["IdMarca"];
              $resultadoModelo = $this->MigrarModeloMarcaInventario($value);
              if (is_string($resultadoModelo)) {
                $mensaje =$mensaje.$resultadoModelo;
              }
              else{
                $value["IdModelo"]=$resultadoModelo["IdModelo"];
              }
            }
          }

          $dataMigracionMarcasModelos[$key] = $value;
        }

        if ($mensaje != "") {
          return $mensaje;
        }
        else {
          return $dataMigracionMarcasModelos;
        }

    }

    function MigrarLineasProductoInventario() {
        $mensaje="";
        $dataMigracionLineas =$this->mMigracionInventario->ListarMigracionLineasProductoInventario();
        
        foreach ($dataMigracionLineas as $key => $value) {                    
          $dataLineaProducto = $this->sLineaProducto->ObtenerLineaProductoPorNombreLineaProducto($value);
          if(count($dataLineaProducto) > 0 ) {
            $value["IdLineaProducto"]=$dataLineaProducto[0]["IdLineaProducto"];              
          }
          else{              
            $value["IdLineaProducto"]="";
            $resultadoLineaProducto=$this->sLineaProducto->InsertarLineaProducto($value);
            if(is_string($resultadoLineaProducto)){
              $mensaje=$mensaje."La linea de producto migrada ".$value["NombreLineaProducto"]." tiene una observacion : ".$resultadoLineaProducto."<br>";
            }
            else{
              $value["IdLineaProducto"]=$resultadoLineaProducto["IdLineaProducto"];
            }
          }

          $dataMigracionLineas[$key]=$value;
        }

        if ($mensaje != "") {
          return $mensaje;
        }
        else {
          return $dataMigracionLineas;
        }
    }

    function MigrarSubFamiliaProductoFamiliaProductoInventario($data) {
        $mensaje="";
        $dataSubFamiliaProducto = $this->sSubFamiliaProducto->ObtenerSubFamiliaProductoPorNombreSubFamilia($data);
                
        if(count($dataSubFamiliaProducto) > 0) {
            $data["IdSubFamiliaProducto"] = $dataSubFamiliaProducto[0]["IdSubFamiliaProducto"];
        }
        else {                  
            $resultadoSubFamiliaProducto = $this->sSubFamiliaProducto->InsertarSubFamiliaProducto($data);
            if (is_string($resultadoSubFamiliaProducto)) {
                $mensaje =$mensaje."La subfamilia migrada ".$data["NombreSubFamiliaProducto"] ." tiene la siguiente observacion : " .$resultadoSubFamiliaProducto; 
                return $mensaje;
            }
            else {
                $data["IdSubFamiliaProducto"] = $resultadoSubFamiliaProducto["IdSubFamiliaProducto"];
            }
        }

        return $data;
    }

    function MigrarFamiliasSubFamiliasProductoInventario() {
        $mensaje="";
        $dataMigracionFamiliasSubFamiliasProducto = $this->mMigracionInventario->ListarMigracionFamiliasSubFamiliasProductoInventario();
        
        foreach ($dataMigracionFamiliasSubFamiliasProducto as $key => $value) {
            $value["NombreFamiliaProducto"] = $value["NombreFamiliaProducto"] == "" ? NOMBRE_NO_ESPECIFICADO : $value["NombreFamiliaProducto"];
            $value["NombreSubFamiliaProducto"] = $value["NombreSubFamiliaProducto"] == "" ? NOMBRE_NO_ESPECIFICADO : $value["NombreSubFamiliaProducto"];

            $dataFamiliaProducto = $this->sFamiliaProducto->ObtenerFamiliaProductoPorNombreFamilia($value);
            
            if(count($dataFamiliaProducto) > 0 ) { 
                $value["IdFamiliaProducto"] = $dataFamiliaProducto[0]["IdFamiliaProducto"];
                $value["NoEspecificado"] = $value["NombreSubFamiliaProducto"] == NOMBRE_NO_ESPECIFICADO ? "S" : "";
                $resultadoSubFamiliaProducto = $this->MigrarSubFamiliaProductoFamiliaProductoInventario($value);
                
                if (is_string($resultadoSubFamiliaProducto)) {
                    $mensaje =$mensaje.$resultadoSubFamiliaProducto;
                }
                else {
                    $value["IdSubFamiliaProducto"]=$resultadoSubFamiliaProducto["IdSubFamiliaProducto"];
                }
            }
            else {
                $value["IdFamiliaProducto"]="";
                $resultadoFamiliaProducto = $this->sFamiliaProducto->InsertarFamiliaProducto($value);
                
                if (is_string($resultadoFamiliaProducto)) {
                    $mensaje=$mensaje."La familia producto migrada ".$value["NombreFamiliaProducto"] ." tiene la siguiente observacion : " .$resultadoFamiliaProducto."<br>"; 
                }
                else {
                    $value["IdFamiliaProducto"] = $resultadoFamiliaProducto["IdFamiliaProducto"];
                    $resultadoSubFamiliaProducto = $this->MigrarSubFamiliaProductoFamiliaProductoInventario($value);
                    
                    if (is_string($resultadoSubFamiliaProducto)) {
                        $mensaje=$mensaje.$resultadoSubFamiliaProducto;
                    }
                    else {
                        $value["IdSubFamiliaProducto"]=$resultadoSubFamiliaProducto["IdSubFamiliaProducto"];
                    }
                }
            }

            $dataMigracionFamiliasSubFamiliasProducto[$key] = $value;
        }

        if ($mensaje != "") {
            return $mensaje;
        }
        else {
            return $dataMigracionFamiliasSubFamiliasProducto;
        }
    }
    
    function MigrarProductosInventario() {
        $resultadoMigracionUnidadesMedida=$this->MigrarUnidadesMedidasInventario();
        echo "resultadoMigracionUnidadesMedida<br>";

        if (is_string($resultadoMigracionUnidadesMedida)) {
            return $resultadoMigracionUnidadesMedida;
        }

        $resultadoMigracionMarcasModelo=$this->MigrarMarcasModelosInventario();
        echo "resultadoMigracionMarcasModelo<br>";
        if (is_string($resultadoMigracionMarcasModelo)) {
            return $resultadoMigracionMarcasModelo;
        }

        $resultadoMigracionLineasProducto=$this->MigrarLineasProductoInventario();
        echo "resultadoMigracionLineasProducto<br>";
        if (is_string($resultadoMigracionLineasProducto)) {
          return $resultadoMigracionLineasProducto;
        }

        $resultadoMigracionFamiliasSubFamiliasProducto=$this->MigrarFamiliasSubFamiliasProductoInventario();
        echo "resultadoMigracionFamiliasSubFamiliasProducto<br>";
        if (is_string($resultadoMigracionFamiliasSubFamiliasProducto)) {
          return $resultadoMigracionFamiliasSubFamiliasProducto;
        }

        $dataMigracionProductos=$this->ListarMigracionProductosInventario();          
        $mensaje = "";
                  
        echo "dataMigracionProductos<br>";
        foreach ($dataMigracionProductos as $key => $value) {
          
          $dataProducto = $this->sProducto->ObtenerProductoPorNombreONombreLargo($value);

          if (count($dataProducto) > 0)  {
            $value["IdProducto"] = $dataProducto[0]["IdProducto"];
            
            foreach($resultadoMigracionUnidadesMedida as $keyUnidadMedida => $valueUnidadMedida) {
              if($value["NombreUnidadMedida"] == $valueUnidadMedida["NombreUnidadMedida"]) {
                $value["IdUnidadMedida"] = $valueUnidadMedida["IdUnidadMedida"];
                break;
              }
            }

            foreach($resultadoMigracionMarcasModelo as $keyMarcaModelo=>$valueMarcaModelo) {
              if($value["NombreMarca"] == $valueMarcaModelo["NombreMarca"] && $value["NombreModelo"] == $valueMarcaModelo["NombreModelo"] ) {
                $value["IdModelo"] = $valueMarcaModelo["IdModelo"];
                break;
              }
            }

            foreach($resultadoMigracionLineasProducto as $keyLineaProducto=>$valueLineaProducto) {
              if($value["NombreLineaProducto"] == $valueLineaProducto["NombreLineaProducto"]) {
                $value["IdLineaProducto"] = $valueLineaProducto["IdLineaProducto"];
                break;
              }
            }
            
            foreach($resultadoMigracionFamiliasSubFamiliasProducto as $keyFamiliaSubFamiliaProducto=>$valueFamiliaSubFamiliaProducto) {
              if($value["NombreFamiliaProducto"] == $valueFamiliaSubFamiliaProducto["NombreFamiliaProducto"] && $value["NombreSubFamiliaProducto"] == $valueFamiliaSubFamiliaProducto["NombreSubFamiliaProducto"] ) {
                $value["IdSubFamiliaProducto"] = $valueFamiliaSubFamiliaProducto["IdSubFamiliaProducto"];
                break;
              }
            }

            $value["PrecioUnitarioCompra"]=$value["PrecioUnitario1"];
            $value["PrecioUnitario"]=$value["PrecioUnitario2"];
            
            $dataMercaderia =(array)$this->sMercaderia->ObtenerMercaderiaPorIdProducto($value);
            
            $dataMercaderia2 = array_merge($dataMercaderia,$value);              
            $CodigoTipoAfectacionIGV = array_key_exists("CodigoTipoAfectacionIGV",$value) == false ?  $dataMercaderia2["CodigoTipoAfectacionIGV"] : $value["CodigoTipoAfectacionIGV"];
            $dataMercaderia2["CodigoTipoAfectacionIGV"] = $CodigoTipoAfectacionIGV == "" ? CODIGO_AFECTACION_IGV_GRAVADO : $CodigoTipoAfectacionIGV;
            
            $resultadoMercaderia = $this->sMercaderia->ActualizarMercaderia($dataMercaderia2);

            if (is_string($resultadoMercaderia)) {
              $mensaje =$mensaje."El producto existente ".$value["NombreProducto"]." migrado tiene una observacion : ".$resultadoMercaderia."<br>";
            }
          }
          else {
            $value["IdProducto"]="";
            $value["PrecioUnitarioCompra"]=$value["PrecioUnitario1"];
            $value["PrecioUnitario"]=$value["PrecioUnitario2"];
            $dataMercaderia = $this->sMercaderia->Nuevo();     
            $dataMercaderia["CodigoCorrelativoAutomatico"] = ($value["CodigoMercaderia"] == "" ? 1 : 0);
            $dataMercaderia = array_merge($dataMercaderia,$value);
            $CodigoTipoAfectacionIGV = array_key_exists("CodigoTipoAfectacionIGV",$value) == false ?  CODIGO_AFECTACION_IGV_GRAVADO : $value["CodigoTipoAfectacionIGV"];
            $dataMercaderia["CodigoTipoAfectacionIGV"] = $CodigoTipoAfectacionIGV == "" ? CODIGO_AFECTACION_IGV_GRAVADO : $CodigoTipoAfectacionIGV;
                          
            $resultadoMercaderia = $this->sMercaderia->InsertarMercaderia($dataMercaderia);
            if(is_string($resultadoMercaderia)){
              $mensaje =$mensaje."El producto nuevo ".$value["NombreProducto"]." migrado tiene una observacion : ".$resultadoMercaderia."<br>";
            }
            else{
              $value["IdProducto"]=$resultadoMercaderia["IdProducto"];
            }
          }            

          $value["CantidadInicial"]=$value["StockAlmacen"];
          $value["ValorUnitario"]=$value["PrecioUnitario"];
          $value["FechaVencimiento"]="";
          $value["FechaEmisionDua"]="";
          $value["FechaEmisionDocumentoSalidaZofra"]="";

          $dataMigracionProductos[$key] = $value;            
        }
        

        if ($mensaje != "") {
         return $mensaje;           
        }
        else {
            return $dataMigracionProductos;          
        }
    }

    function MigrarInventarioInicial($data) {
        $filtroAsignacionSede["NombreSede"] = $data[0]["NombreAlmacen"];
        $dataAsignacionSede=$this->sAsignacionSede->ObtenerAsignacionSedeTipoAlmacenPorNombreSede($filtroAsignacionSede);

        $dataInventarioInicial = $this->sInventarioInicial->Cargar();
        $dataInventarioInicial["DetallesInventarioInicial"] = array_merge($dataInventarioInicial["DetallesInventarioInicial"],$data);
        $dataInventarioInicial["IdMotivoInventarioInicial"] = ID_MOTIVO_INVENTARIO_INICIAL_SALDO_INICIAL;
        $dataInventarioInicial["Observacion"] ="MIGRACION DE SALDO INICIAL DE OTRA SISTEMA";
        $dataInventarioInicial["IdAsignacionSede"] = $dataAsignacionSede[0]["IdAsignacionSede"];
        $dataInventarioInicial["NombreSede"] = $dataAsignacionSede[0]["NombreSede"];
        $dataInventarioInicial["IdSede"] = $dataAsignacionSede[0]["IdSede"];
        $dataInventarioInicial["NombreAlmacen"] = $dataAsignacionSede[0]["NombreSede"];
        //$dataInventarioInicial["CodigoSede"] = $dataAsignacionSede[0]["CodigoSede"];
        //$dataInventarioInicial["FechaInventario"] =  $this->Base->ObtenerFechaServidor();
        $resultado = $this->sInventarioInicial->InsertarInventarioInicial($dataInventarioInicial);
         
        return $resultado;
    }

    function MigrarInventario() {          
        $dataProductosInventario=$this->MigrarProductosInventario();
        echo "dataProductosInventario<br>";
        if(is_string($dataProductosInventario)) {
            return $dataProductosInventario;
        }
        
        $resultado = $this->MigrarInventarioInicial($dataProductosInventario);
        
        return $resultado;
    }

}