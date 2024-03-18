<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mListaPrecioMercaderia extends CI_Model {

  public $ListaPrecioMercaderia = array();

  public function __construct() {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->ListaPrecioMercaderia = $this->Base->Construir("ListaPrecioMercaderia");
  }

  function ListarPreciosMercaderiasPorIdTipoPrecio($data) {
    $IdSede = $data["IdSede"];
    $IdTipoListaPrecio = $data["IdTipoListaPrecio"];
    $query = $this->db->query("Select M.*,LPM.IdListaPrecioMercaderia
                              From Mercaderia As M
                              Left Join ListaPrecioMercaderia As LPM on S.IdProducto = LPM.IdProducto
                              Inner Join TipoListaPrecio as TLP on TLP.IdTipoListaPrecio = LPM.IdTipoListaPrecio
                              Where M.IndicadorEstado = 'A'
                              And LPM.IndicadorEstado = 'A'
                              And LPM.IdTipoListaPrecio='$IdTipoListaPrecio'
                              And LPM.IdSede = $IdSede
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarListasPrecioMercaderia($data) {
    $IdSede = $data["IdSede"];
    $IdTipoListaPrecio = $data["IdTipoListaPrecio"];
    $SubFamiliaProducto = $data["IdSubFamiliaProducto"];
    $Modelo = $data["IdModelo"];
    $Marca = $data["IdMarca"];
    $FamiliaProducto = $data["IdFamiliaProducto"];
    $LineaProducto = $data["IdLineaProducto"];
    $Descripcion = $data["Descripcion"];

    if(array_key_exists("EstadoProducto",$data)) {
      $estadoProducto = $data["EstadoProducto"];
      $filtro = " And (P.EstadoProducto like '".$estadoProducto."')";
    }
    else {
      $filtro = " And (P.EstadoProducto = '1') ";
    }

    $query = $this->db->query("Select SFP.NombreSubFamiliaProducto, FP.NombreFamiliaProducto, MC.NombreMarca, ML.NombreModelo, 
                                P.NombreProducto, UM.AbreviaturaUnidadMedida,
                              M.*, LPM.IdListaPrecioMercaderia, LPM.Precio, LPM.CostoPromedioPonderado,
                              LPM.MargenPorcentaje,LPM.MargenUtilidad,LPM.ValorVenta,LPM.ValorIGV,  
                              LPM.PrecioPromedioCompra,LPM.UltimoPrecio,LPM.FechaUltimoPrecio,  
                              TLP.NombreTipoListaPrecio, TLP.IdTipoListaPrecio,'' as IndicadorProducto
                              From mercaderia As M
                              Inner Join unidadmedida as UM on UM.IdUnidadMedida = M.IdUnidadMedida
                              Inner Join producto As P on M.IdProducto = P.IdProducto
                              Left Join listapreciomercaderia As LPM on M.IdProducto = LPM.IdProducto
                              And LPM.IdTipoListaPrecio like '$IdTipoListaPrecio' And LPM.IndicadorEstado = 'A'
                              Left Join TipoListaPrecio as TLP on TLP.IdTipoListaPrecio = LPM.IdTipoListaPrecio
                              left join SubFamiliaProducto SFP on SFP.IdSubFamiliaProducto = M.IdSubFamiliaProducto
                              left join FamiliaProducto FP on FP.IdFamiliaProducto = SFP.IdFamiliaProducto
                              left join Modelo ML on ML.IdModelo = M.IdModelo
                              left join Marca MC on MC.IdMarca = ML.IdMarca
                              Where P.IndicadorEstado = 'A'
                              And LPM.IdSede = $IdSede
                              And M.IdSubFamiliaProducto like '$SubFamiliaProducto'
                              And M.IdModelo like '$Modelo'
                              And MC.IdMarca like '$Marca'
                              And FP.IdFamiliaProducto like '$FamiliaProducto'
                              And M.IdLineaProducto like '$LineaProducto'
                              And P.NombreProducto like '$Descripcion'
                              $filtro
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarMercaderiasParaPrecio($data) {    
    $SubFamiliaProducto = $data["IdSubFamiliaProducto"];
    $Modelo = $data["IdModelo"];
    $Marca = $data["IdMarca"];
    $FamiliaProducto = $data["IdFamiliaProducto"];
    $LineaProducto = $data["IdLineaProducto"];
    $Descripcion = $data["Descripcion"];
    $query = $this->db->query("Select SFP.NombreSubFamiliaProducto, FP.NombreFamiliaProducto, MC.NombreMarca, ML.NombreModelo, 
                                P.NombreProducto, UM.AbreviaturaUnidadMedida,
                              M.*, M.IdProducto IdListaPrecioMercaderia, M.PrecioUnitario Precio,
                              '' as IndicadorProducto
                              From mercaderia As M
                              Inner Join unidadmedida as UM on UM.IdUnidadMedida = M.IdUnidadMedida
                              Inner Join producto As P on M.IdProducto = P.IdProducto
                              left join SubFamiliaProducto SFP on SFP.IdSubFamiliaProducto = M.IdSubFamiliaProducto
                              left join FamiliaProducto FP on FP.IdFamiliaProducto = SFP.IdFamiliaProducto
                              left join Modelo ML on ML.IdModelo = M.IdModelo
                              left join Marca MC on MC.IdMarca = ML.IdMarca
                              Where P.IndicadorEstado = 'A'                              
                              And M.IdSubFamiliaProducto like '$SubFamiliaProducto'
                              And M.IdModelo like '$Modelo'
                              And MC.IdMarca like '$Marca'
                              And FP.IdFamiliaProducto like '$FamiliaProducto'
                              And M.IdLineaProducto like '$LineaProducto'
                              And P.NombreProducto like '$Descripcion'
                              ORDER BY(M.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarListasPrecioMercaderiaPorIdProducto($data) {
    $IdProducto = $data["IdProducto"];
    if (array_key_exists("IdSede",$data)) {
      $IdSede = $data["IdSede"];
    }
    else 
      $IdSede = "'%'";      

    $sql="Select M.*, P.NombreProducto, UM.AbreviaturaUnidadMedida,LPM.*, 
    TLP.NombreTipoListaPrecio,TLP.IdTipoListaPrecio,TLP.IndicadorPrecioMinimo,'' as IndicadorProducto
    ,TLP.IndicadorPrecioVentaPorDefecto
    From mercaderia As M
    Inner Join unidadmedida as UM on UM.IdUnidadMedida = M.IdUnidadMedida
    Inner Join producto As P on M.IdProducto = P.IdProducto
    Left Join listapreciomercaderia As LPM on M.IdProducto = LPM.IdProducto And LPM.IndicadorEstado = 'A'
    Left Join tipolistaprecio as TLP on TLP.IdTipoListaPrecio = LPM.IdTipoListaPrecio
    Where P.IndicadorEstado = 'A'        
    And M.IdProducto like '$IdProducto'
    And LPM.Precio > 0
    And LPM.IdSede like $IdSede
    ORDER BY TLP.OrdenListaPrecio";
    
    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarListaPreciosMercaderiaPorIdProducto($data) {
    $IdProducto = $data["IdProducto"]; 
    $IdSede=$data["IdSede"];
     $sql="Select LPM.*,'' as IndicadorProducto
     From listapreciomercaderia As LPM     
     Left Join tipolistaprecio as TLP on TLP.IdTipoListaPrecio = LPM.IdTipoListaPrecio
     Where LPM.IdProducto like '$IdProducto'  
     AND LPM.IndicadorEstado = 'A'  And LPM.IdSede = $IdSede 
     ORDER BY TLP.OrdenListaPrecio";    
    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarListaPrecioMercaderia($data) {
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();    
    $data["IndicadorEstado"] = ESTADO_ACTIVO;
    $resultado = $this->mapper->map($data,$this->ListaPrecioMercaderia);
    $this->db->insert('ListaPrecioMercaderia', $resultado);
    $resultado["IdListaPrecioMercaderia"] = $this->db->insert_id();
    return $resultado;
  }

  function ActualizarListaPrecioMercaderia($data)
  {
    $id=$data["IdListaPrecioMercaderia"];
    $data["UsuarioModificacion"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
    $data["FechaModificacion"] = $this->Base->ObtenerFechaServidor();    
    $resultado = $this->mapper->map($data,$this->ListaPrecioMercaderia);
    $this->db->where('IdListaPrecioMercaderia', $id);
    $this->db->update('ListaPrecioMercaderia', $resultado);
  }

  //PRECIOS POR PROMEDIO EN COMPRA
  function ConsultarPromedioDePreciosUnitarioEnCompra($data) {
    $IdProducto = $data["IdProducto"];
    $query = $this->db->query("select DCC.IdProducto,
                M.CodigoMercaderia,P.NombreProducto, DCC.AbreviaturaUnidadMedida,
                DCC.Cantidad,
                (SELECT (SUM(DCCO.PrecioUnitario) / (SELECT COUNT(DCCO.IdProducto) FROM DetalleComprobanteCompra AS DCCO WHERE DCCO.IdProducto = DCC.IdProducto))
                FROM DetalleComprobanteCompra AS DCCO WHERE DCCO.IdProducto = DCC.IdProducto)  AS PrecioCompraPromedio
                
                from detallecomprobantecompra as DCC
                inner join comprobantecompra CC on CC.IdComprobanteCompra=DCC.IdComprobanteCompra
                inner join producto P on DCC.IdProducto=P.IdProducto
                inner join mercaderia M on P.IdProducto=M.IdProducto
                
                inner join TipoDocumento TD on TD.IdTipodocumento=CC.IdTipoDocumento
                
                where P.IdProducto like '$IdProducto' and CC.IndicadorEstado='A'
                group by DCC.IdProducto
                order by M.CodigoMercaderia, FechaEmision");
    $resultado = $query->result_array();
    return $resultado;
  }

  //ULTIMO PRECIO A LA FECHA
  function ConsultarUltimoPrecioUnitarioEnCompra($data) {
    $IdProducto = $data["IdProducto"];
    $query = $this->db->query("SELECT DCC.IdProducto,
                  M.CodigoMercaderia,P.NombreProducto, DCC.AbreviaturaUnidadMedida,
                  (SELECT DDCC.PrecioUnitario
                  FROM detallecomprobantecompra AS DDCC
                  INNER JOIN comprobantecompra AS CCOO ON CCOO.IdComprobanteCompra = DDCC.IdComprobanteCompra
                  WHERE DDCC.IdProducto = DCC.IdProducto and DDCC.IndicadorEstado='A' 
                  ORDER BY CCOO.FechaEmision DESC, DDCC.FechaRegistro DESC
                  LIMIT 1  ) AS PrecioUnitario,
                  (SELECT CCOO.FechaEmision
                  FROM detallecomprobantecompra AS DDCC
                  INNER JOIN comprobantecompra AS CCOO ON CCOO.IdComprobanteCompra = DDCC.IdComprobanteCompra
                  WHERE DDCC.IdProducto = DCC.IdProducto and DDCC.IndicadorEstado='A' 
                  ORDER BY CCOO.FechaEmision DESC, DDCC.FechaRegistro DESC
                  LIMIT 1  ) AS FechaEmision
                  FROM detallecomprobantecompra as DCC
                  left join comprobantecompra CC on CC.IdComprobanteCompra=DCC.IdComprobanteCompra
                  left join producto P on DCC.IdProducto=P.IdProducto
                  left join mercaderia M on P.IdProducto=M.IdProducto
                  left join TipoDocumento TD on TD.IdTipodocumento=CC.IdTipoDocumento
                  
                  WHERE DCC.IndicadorEstado='A' and DCC.IdProducto like '$IdProducto'
                  
                  GROUP BY (DCC.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  //ULTIMO COSTO A LA FECHA
  function ConsultarUltimoCostoUnitarioCalculadoEnCompra($data) {

    $IdProducto = $data["IdProducto"];
    $query = $this->db->query("SELECT DCC.IdProducto,
                  M.CodigoMercaderia,P.NombreProducto, DCC.AbreviaturaUnidadMedida,
                  
                  (SELECT DDCC.CostoUnitarioCalculado
                  FROM detallecomprobantecompra AS DDCC
                  INNER JOIN comprobantecompra AS CCOO ON CCOO.IdComprobanteCompra = DDCC.IdComprobanteCompra
                  WHERE DDCC.IdProducto = DCC.IdProducto and DDCC.IndicadorEstado='A' 
                  ORDER BY CCOO.FechaEmision DESC, DDCC.FechaRegistro DESC
                  LIMIT 1  ) AS CostoUnitarioCalculado,
                  
                  (SELECT CCOO.FechaEmision
                  FROM detallecomprobantecompra AS DDCC
                  INNER JOIN comprobantecompra AS CCOO ON CCOO.IdComprobanteCompra = DDCC.IdComprobanteCompra
                  WHERE DDCC.IdProducto = DCC.IdProducto and DDCC.IndicadorEstado='A' 
                  ORDER BY CCOO.FechaEmision DESC, DDCC.FechaRegistro DESC
                  LIMIT 1  ) AS FechaEmision
                  
                  FROM detallecomprobantecompra as DCC
                  left join comprobantecompra CC on CC.IdComprobanteCompra=DCC.IdComprobanteCompra
                  left join producto P on DCC.IdProducto=P.IdProducto
                  left join mercaderia M on P.IdProducto=M.IdProducto
                  left join TipoDocumento TD on TD.IdTipodocumento=CC.IdTipoDocumento
                  
                  WHERE DCC.IndicadorEstado='A' and DCC.IdProducto like '$IdProducto'
                  
                  GROUP BY (DCC.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  //OBTENEMOS LA LISTA YA INSERTADA
  function ObtenerListaPrecioMercaderiaPorIdProductoYIdTipoListaPrecio($data) {
    $IdProducto = $data["IdProducto"];
    $IdSede=$data["IdSede"];
    $IdTipoListaPrecio = $data["IdTipoListaPrecio"];
    $query = $this->db->query("SELECT * FROM ListaPrecioMercaderia
                WHERE IdProducto = '$IdProducto' AND IdTipoListaPrecio = '$IdTipoListaPrecio'  And IdSede = $IdSede 
                AND IndicadorEstado = 'A'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerPrecioMinimoPorIdProducto($data) {
    $IdProducto = $data["IdProducto"];    
    $IdSede=$data["IdSede"];
    $Sql="SELECT lpm.* FROM ListaPrecioMercaderia lpm
    INNER JOIN tipolistaprecio tlp
    ON lpm.idtipolistaprecio=tlp.idtipolistaprecio    
    WHERE IdProducto = '$IdProducto' 
    AND tlp.IndicadorPrecioMinimo = '1' AND tlp.IndicadorEstado='A' 
    AND lpm.IndicadorEstado = 'A'
    And LPM.IdSede = $IdSede ";
    $query = $this->db->query($Sql);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarServicioParaPrecioBase($data) {

    $SubFamiliaProducto = $data["IdSubFamiliaProducto"];
    //$Modelo = $data["IdModelo"];
    //$Marca = $data["IdMarca"];
    $FamiliaProducto = $data["IdFamiliaProducto"];
    $LineaProducto = $data["IdLineaProducto"];
    $Descripcion = $data["Descripcion"];
    $query = $this->db->query("Select SFP.NombreSubFamiliaProducto, FP.NombreFamiliaProducto, 'NO ESPECIFICADO' AS NombreMarca , 'NO ESPECIFICADO' AS NombreModelo, 
                                P.NombreProducto, UM.AbreviaturaUnidadMedida,
                                0 AS PrecioPromedioCompra,
                                0 AS MargenPorcentaje,
                                0 AS MargenUtilidad,
                                0 AS UltimoPrecio,
                                0 AS CostoPromedioPonderado,
                                0 AS ValorVenta,
                                0 AS ValorIGV,
                                S.CodigoServicio as CodigoMercaderia,
                                S.*, S.IdProducto IdListaPrecioMercaderia, S.PrecioUnitario Precio,'S' as IndicadorProducto
                              From Servicio As S
                              Inner Join unidadmedida as UM on UM.IdUnidadMedida = S.IdUnidadMedida
                              Inner Join producto As P on S.IdProducto = P.IdProducto
                              left join SubFamiliaProducto SFP on SFP.IdSubFamiliaProducto = S.IdSubFamiliaProducto
                              left join FamiliaProducto FP on FP.IdFamiliaProducto = SFP.IdFamiliaProducto                              
                              Where P.IndicadorEstado = 'A'
                              And S.IdSubFamiliaProducto like '$SubFamiliaProducto'                              
                              And FP.IdFamiliaProducto like '$FamiliaProducto'
                              And S.IdLineaProducto like '$LineaProducto'
                              And P.NombreProducto like '$Descripcion'
                              ORDER BY(S.IdProducto)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarTodosListasPrecioMercaderia($data) {
    $IdSede = $data["IdSede"];
    $sql ="SELECT 
      M.CodigoMercaderia,
      P.NombreProducto, 
      LPM.*
      From mercaderia As M
      INNER JOIN producto As P on M.IdProducto = P.IdProducto
      INNER JOIN listapreciomercaderia As LPM on M.IdProducto = LPM.IdProducto
      INNER JOIN tipolistaprecio tlp ON tlp.IdTipoListaPrecio= LPM.IdTipoListaPrecio
      And LPM.IndicadorEstado = 'A'
      Where P.IndicadorEstado = 'A'
      And LPM.IdSede like '$IdSede'";
    $query = $this->db->query($sql);
    $resultado = $query->result_array();
    return $resultado;
  }

}
