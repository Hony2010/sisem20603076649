<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mInventario extends CI_Model {

        //public $AlmacenMercaderia = array();

        public function __construct() {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->load->library('sesionusuario');               
        }

        function ObtenerTotalInventarioMercaderiasPorSede($data) {
                $textofiltro = $data["textofiltro"];
                $IdSede = $data["IdSede"];
                $IdFamiliaProducto = $data["IdFamiliaProducto"];
                $IdMarca = $data["IdMarca"];
                $VerSoloStock=$data["CheckConSinStock"] == '1' ? " And StockMercaderia > 0 " : "";
    
                $sql ="Select M.IdProducto                
                From Mercaderia As M                
                left Join Producto as P on M.IdProducto = P.IdProducto
                left join AlmacenMercaderia as AM on AM.IdProducto = P.IdProducto
                left join AsignacionSede as AG on AG.IdAsignacionSede = AM.IdAsignacionSede
                left join Sede S on S.IdSede = AG.IdSede
                left join SubFamiliaProducto AS SFP ON SFP.IdSubFamiliaProducto = M.IdSubFamiliaProducto
                left Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto                
                left Join Modelo AS MO on MO.IdModelo = M.IdModelo
                left Join Marca As MRC on MO.IdMarca = MRC.IdMarca                
                Where P.IndicadorEstado= 'A' and P.EstadoProducto='1'                            
                and FP.IdFamiliaProducto like '$IdFamiliaProducto'
                and MRC.IdMarca like '$IdMarca'    
                and P.NombreProducto like '%$textofiltro%'
                and AG.IdSede = $IdSede and AG.IndicadorEstado='A' and AM.IndicadorEstado='A' and S.IndicadorEstado='A'           
                $VerSoloStock";
         

                $query = $this->db->query($sql);
                    
                $resultado = $query->num_rows();
                //$query->free_result();  
                return $resultado;
        }

        function ListarInventarioMercaderiasPorSede($data, $numerofilainicio,$numerorfilasporpagina) {
            $textofiltro = $data["textofiltro"];
            $IdSede = $data["IdSede"];
            $IdFamiliaProducto = $data["IdFamiliaProducto"];
            $IdMarca = $data["IdMarca"];
            $VerSoloStock=$data["CheckConSinStock"] == '1' ? " And StockMercaderia > 0 " : "";

            $sql ="Select M.*, 
            MND.NombreMoneda, FP.NombreFamiliaProducto, FP.IdFamiliaProducto, 
            MRC.NombreMarca, MRC.IdMarca,
            P.*, '1' As Cantidad,
            AM.StockMercaderia AS StockProducto,
            M.PrecioUnitario as SubTotal,
            UM.AbreviaturaUnidadMedida,UM.NombreUnidadMedida,
            TAI.CodigoTipoAfectacionIGV
            From Mercaderia As M
            left Join Producto as P on M.IdProducto = P.IdProducto
            left join AlmacenMercaderia as AM on AM.IdProducto = P.IdProducto
            left join AsignacionSede as AG on AG.IdAsignacionSede = AM.IdAsignacionSede
            left join Sede S on S.IdSede = AG.IdSede 
            left join SubFamiliaProducto AS SFP ON SFP.IdSubFamiliaProducto = M.IdSubFamiliaProducto
            left Join FamiliaProducto As FP on SFP.IdFamiliaProducto = FP.IdFamiliaProducto
            left Join Modelo AS MO on MO.IdModelo = M.IdModelo
            left Join Marca As MRC on MO.IdMarca = MRC.IdMarca            
            left join UnidadMedida As UM on UM.IdUnidadMedida = M.IdUnidadMedida
            left Join Moneda As MND on M.IdMoneda = MND.IdMoneda
            left join tipoafectacionigv as TAI on TAI.IdTipoAfectacionIGV = M.IdTipoAfectacionIGV
            Where P.IndicadorEstado= 'A' and P.EstadoProducto='1'                                    
            and FP.IdFamiliaProducto like '$IdFamiliaProducto'
            and MRC.IdMarca like '$IdMarca'
            and P.NombreProducto like '%$textofiltro%' 
            and AG.IdSede = $IdSede and AG.IndicadorEstado='A' and AM.IndicadorEstado='A' and S.IndicadorEstado='A'           
            $VerSoloStock
            ORDER BY P.NombreProducto
            LIMIT $numerofilainicio,$numerorfilasporpagina";
            
            $query = $this->db->query($sql);

            $resultado = $query->result_array();            
            //$query->free_result();  
            return $resultado;
        }


 }
