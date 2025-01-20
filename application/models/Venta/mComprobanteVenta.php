<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mComprobanteVenta extends CI_Model {

  public $ComprobanteVenta = array();

  public function __construct()
  {
    parent::__construct();
    $this->load->database();
    $this->load->model("Base");
    $this->load->model("Configuracion/General/mSituacionComprobanteElectronico");
    $this->load->library('shared');
    $this->load->library('mapper');
    $this->load->library('sesionusuario');
    $this->ComprobanteVenta = $this->Base->Construir("ComprobanteVenta");
  }

  function ObtenerComprobanteVentaPorSerieDocumento($data)
  {
    $tipo=$data["IdTipoDocumento"];
    $serie=$data["SerieDocumento"];
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("Select cv.*
        from ComprobanteVenta As CV
        where CV.IndicadorEstado = 'A' and CV.SerieDocumento = '$serie' and cv.NumeroDocumento='$numero' and cv.IdTipoDocumento = '$tipo'");
    $resultado = $query->row();
    return $resultado;
  }


  function ObtenerDuplicadoDeComprobanteVenta($data)
  {
    $numero=$data["NumeroDocumento"];
    $query = $this->db->query("Select *
                                From ComprobanteVenta
                                Where IndicadorEstado = 'A' and NumeroDocumento = '$numero'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarComprobantesVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $query = $this->db->query("Select * from DetalleComprobanteVenta
                                where IdComprobanteVenta = '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ListarVentas()
  {
    $query = $this->db->query("Select CV.*, TV.NombreTipoVenta,
                                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                                FP.NombreFormaPago, U.AliasUsuarioVenta,
                                Per.RazonSocial,Per.NumeroDocumentoIdentidad
                                From ComprobanteVenta As CV
                                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                                left Join Cliente As C on C.IdPersona = CV.IdCliente
                                left Join Persona As Per on Per.IdPersona = C.IdPersona
                                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                                left Join Usuario As U on U.IdUsuario = CV.IdUsuario
                                Where CV.IndicadorEstado = 'A'
                                ORDER BY (CV.NumeroDocumento)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function InsertarComprobanteVenta($data)
  {
    $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
    $data["IndicadorEstado"]=ESTADO_ACTIVO;
    $data["IndicadorEstadoCPE"]=ESTADO_CPE_GENERADO;
    $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
    $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();

    if (array_key_exists("IdCaja", $data)){
      $data["IdCaja"] = ($data["IdCaja"] == '') ? null : $data["IdCaja"];
    }
    $data["SituacionCPE"] = $this->ObtenerSituacionCPE($data);
    $data["IdSubTipoDocumento"] = $data["IdSubTipoDocumento"] == "" ? null : $data["IdSubTipoDocumento"];
    $data["IdAlumno"] = $data["IdAlumno"] == "" ? null : $data["IdAlumno"];
    
    if (array_key_exists("IdReferenciaProforma", $data)) {
      $data["IdReferenciaProforma"] = $data["IdReferenciaProforma"] == "" ? null : $data["IdReferenciaProforma"];
    }

    if(array_key_exists("CodigoEstado", $data))
    {
      $data["CodigoEstado"] = $this->ObtenerCodigoEstado($data);
    }
    if (array_key_exists("IdMesa", $data)){
      $data["IdMesa"] = ($data["IdMesa"] == '') ? null : $data["IdMesa"];
    }

    if (array_key_exists("IdGenero", $data)) {
      $data["IdGenero"] = $data["IdGenero"] == "" ? null : $data["IdGenero"];
    }
    
    if (array_key_exists("IdCasillero", $data)) {
      $data["IdCasillero"] = $data["IdCasillero"] == "" ? null : $data["IdCasillero"];
    }

    if (array_key_exists("IdTipoListaPrecioEspecial", $data)) { 
      $data["IdTipoListaPrecioEspecial"] = $data["IdTipoListaPrecioEspecial"] == "" ? null : $data["IdTipoListaPrecioEspecial"];
    }

    $resultado = $this->mapper->map($data,$this->ComprobanteVenta);
    $this->db->insert('ComprobanteVenta', $resultado);
    $resultado["IdComprobanteVenta"] = $this->db->insert_id();
    $resultado["AbreviaturaSituacionCPE"] = $this->mSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($data["SituacionCPE"]);
    return($resultado);
  }


  function ActualizarComprobanteVenta($data)
  {
    $id=$data["IdComprobanteVenta"];
    $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();    
    $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();

    if(array_key_exists("IndicadorEstado", $data) && array_key_exists("IndicadorEstadoComunicacionBaja", $data)) {
      $data["SituacionCPE"] = $this->ObtenerSituacionCPE($data);
    }
    if (array_key_exists("IdCaja", $data)){
      $data["IdCaja"] = ($data["IdCaja"] == '') ? null : $data["IdCaja"];
    }

    if(array_key_exists("IdSubTipoDocumento", $data))
      if($data["IdSubTipoDocumento"]=="")
        $data["IdSubTipoDocumento"] = null;

    if(array_key_exists("IdAlumno", $data))
    {
      $data["IdAlumno"] = $data["IdAlumno"] == "" ? null : $data["IdAlumno"];
    }

    if (array_key_exists("IdMesa", $data)){
      $data["IdMesa"] = ($data["IdMesa"] == '') ? null : $data["IdMesa"];
    }

    if(array_key_exists("CodigoEstado", $data) && array_key_exists("IndicadorEstadoResumenDiario", $data)) {
      $data["CodigoEstado"] = $this->ObtenerCodigoEstado($data);
    }
    
    if (array_key_exists("IdReferenciaProforma", $data)) {
      $data["IdReferenciaProforma"] = $data["IdReferenciaProforma"] == "" ? null : $data["IdReferenciaProforma"];
    }
    
    if (array_key_exists("IdGenero", $data)) {
      $data["IdGenero"] = $data["IdGenero"] == "" ? null : $data["IdGenero"];
    }

    if (array_key_exists("IdCasillero", $data)) {
      $data["IdCasillero"] = $data["IdCasillero"] == "" ? null : $data["IdCasillero"];
    }

    if (array_key_exists("IdTipoListaPrecioEspecial", $data)) { 
      $data["IdTipoListaPrecioEspecial"] = $data["IdTipoListaPrecioEspecial"] == "" ? null : $data["IdTipoListaPrecioEspecial"];
    }
    
    $resultado = $this->mapper->map($data,$this->ComprobanteVenta);

    $this->db->where('IdComprobanteVenta', $id);
    $this->db->update('ComprobanteVenta', $resultado);

    if(array_key_exists("SituacionCPE", $data)) {
      $resultado["AbreviaturaSituacionCPE"] = $this->mSituacionComprobanteElectronico->ObtenerSituacionCPEPorCodigo($data["SituacionCPE"]);
    }

    return $resultado;
  }

  function BorrarComprobanteVenta($data)
  {
    $data["IndicadorEstado"]=ESTADO_ELIMINADO;
    $resultado = $this->ActualizarComprobanteVenta($data);
    return $resultado;
  }

  function ObtenerComprobanteVenta($data)
  {
    $IdComprobanteVenta=$data["IdComprobanteVenta"];

    $query = $this->db->query("select
        CONCAT(td.NombreAbreviado,'-',cv.SerieDocumento,'-',cv.NumeroDocumento) as Numero,
        CONCAT(m.SimboloMoneda,' ',CAST(cv.Total as char(10))) as TotalComprobante,
        pe.NumeroDocumentoIdentidad, td.NombreAbreviado, td.NombreTipoDocumento,
        pe.RazonSocial as RazonSocialCliente,        
        cv.*,
        m.CodigoMoneda,
        tdi.CodigoDocumentoIdentidad,
        td.CodigoTipoDocumento, top.CodigoSUNAT, sd.CodigoSedeSUNAT as CodigoSede,
        fp.NombreFormaPagoSUNAT
        from comprobanteventa as cv
        inner join tipodocumento td
        on td.IdTipoDocumento=cv.IdTipoDocumento
        inner join cliente cli
        on cli.IdPersona = cv.IdCliente
        inner join Persona pe
        on pe.IdPersona = cli.IdPersona
        inner join tipodocumentoidentidad tdi
        on tdi.IdTipoDocumentoIdentidad=pe.IdTipoDocumentoIdentidad
        inner join moneda m
        on m.IdMoneda=cv.IdMoneda
        left join tipooperacion top
        on top.IdTipoOperacion=cv.IdTipoOperacion
        left join asignacionsede ass
        on ass.IdAsignacionSede=cv.IdAsignacionSede
        left join sede sd
        on sd.IdSede=ass.IdSede
        inner join formapago fp
        on fp.idformapago=cv.idformapago
        where cv.IdComprobanteVenta='$IdComprobanteVenta'");

    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobanteVentaPorId($data)
  {
    $id=$data["IdComprobanteVenta"];

    $query = $this->db->query("Select
                                CV.*, TV.NombreTipoVenta,
                                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                                FP.NombreFormaPago, U.AliasUsuarioVenta,
                                Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                                MND.SimboloMoneda,CJ.NombreCaja
                                From ComprobanteVenta As CV
                                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                                left Join Cliente As C on C.IdPersona = CV.IdCliente
                                left Join Persona As Per on Per.IdPersona = C.IdPersona
                                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                                left Join Usuario As U on U.IdUsuario = CV.IdUsuario
                                left join Caja as CJ on CJ.IdCaja = CV.IdCaja
                                Where (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) and
                                CV.IdComprobanteVenta like '$id'");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesVenta($data,$numerofilainicio,$numerorfilasporpagina)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipoventa =$data["TipoVenta"];
    $tipodocumento=$data["TipoDocumento"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();

    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    $extensionConsulta2 ="";
    $extensionConsulta3 ="";

    if (array_key_exists("IdGenero", $data)) {
      $IdGenero=$data["IdGenero"];
      $NombreCasillero = $data["NombreCasillero"] == "" ? '%' : $data["NombreCasillero"];
      $extensionConsulta2 = " And G.IdGenero like '".$IdGenero."' And CS.NombreCasillero like '".$NombreCasillero."'";
    }

    if($vistaventa == 0) {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }
    else {
      $extensionConsulta3 = " OR (CV.AliasUsuarioVenta like '%".$criterio."%') ";      
    }

    $consulta = "Select CVT.FechaExpedicion, CVT.IdDestinatario, CVT.IdLugarDestino, CVT.IdLugarOrigen, CONCAT(DEST.NumeroDocumentoIdentidad,'  -  ',DEST.RazonSocial)  as RazonSocialDestinatario, CVT.CelularDestinatario, CVT.Destinatario, CVT.HoraPartida, CVT.IndicadorAmPm,
                CV.*, TV.NombreTipoVenta,
                if((SELECT count(dref.SerieDocumentoReferencia) from documentoreferencia as dref WHERE dref.IdComprobanteVenta = CV.IdComprobanteVenta and dref.IndicadorEstado='A' ) > 0, 1, 0) AS IndicadorDocumentoReferencia,
                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,MND.CodigoMoneda,
                FP.NombreFormaPago,FP.NombreFormaPagoSUNAT,
                Per.RazonSocial, Per.NumeroDocumentoIdentidad, tdi.CodigoDocumentoIdentidad,
                MND.SimboloMoneda, concat(ALUM.NombreCompleto,' ', ALUM.ApellidoCompleto)as NombreAlumno, ALUM.CodigoAlumno,
                NS.SerieNotaSalida, NS.NumeroNotaSalida,CS.NombreCasillero,G.NombreGenero,ASD.IdSede,
                date_format(CV.FechaRegistro, '%r') AS HoraOcupacionCasillero,
                DATE_FORMAT(CV.FechaRegistro, '%h:%i %p') AS HoraRegistro,
                (CASE WHEN cg.IndicadorCasilleroDisponible = 0 then '' 
                else date_format(cg.FechaModificacion, '%r') END) AS HoraLiberacionCasillero,
                cg.IndicadorCasilleroDisponible,
                DR.CodigoTipoDocumentoReferencia, DR.SerieDocumentoReferencia, DR.NumeroDocumentoReferencia,
                TD.NombreTipoDocumento,
                TD.CodigoTipoDocumento                               
                From ComprobanteVenta As CV
                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                left Join Cliente As C on C.IdPersona = CV.IdCliente
                left Join Persona As Per on Per.IdPersona = C.IdPersona
                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                left Join Usuario As U on U.IdUsuario = CV.IdUsuario
                left Join AsignacionSede As ASD on ASD.IdAsignacionSede = CV.IdAsignacionSede
                left Join Sede As S on S.IdSede = ASD.IdSede
                left join comprobanteventatransporte as CVT on CV.IdComprobanteVenta = CVT.IdComprobanteVenta
                left join persona as DEST on DEST.IdPersona = CVT.IdDestinatario
                left join Alumno as ALUM on ALUM.IdAlumno = CV.IdAlumno
                left join NotaSalida as NS on NS.IdNotaSalida = CV.IdNotaSalida
                left join Casillero CS on CS.IdCasillero = CV.IdCasillero
                left join Genero G on G.IdGenero = CV.IdGenero
                left join casillerogenero cg ON cg.IdCasillero=cs.idcasillero AND cg.IdGenero=g.IdGenero
                left join TipoDocumentoIdentidad tdi ON tdi.IdTipoDocumentoIdentidad = Per.IdTipoDocumentoIdentidad
                left join DocumentoReferencia As DR on DR.IdComprobanteNota = CV.IdComprobanteVenta And DR.IndicadorEstado='A'
                Where (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) and
                (CV.SerieDocumento like '%$criterio%' or
                CV.NumeroDocumento like '%$criterio%' or
                Per.RazonSocial like '%$criterio%' or
                Per.NumeroDocumentoIdentidad like '%$criterio%'
                $extensionConsulta3)
                $extensionConsulta
                And
                CV.IdTipoVenta like '$tipoventa' And
                CV.IdTipoDocumento like '$tipodocumento' And
                U.IndicadorEstado='A' And
                (CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin') $extensionConsulta2
                ORDER BY TD.NombreTipoDocumento, CV.FechaEmision DESC, TD.NombreAbreviado ASC, CV.SerieDocumento DESC,CV.NumeroDocumento DESC
                LIMIT $numerofilainicio,$numerorfilasporpagina";
    
                
    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesVentaCFC($data)
  {
    $fechaemision =$data["FechaInicio"];

    $query = $this->db->query("Select
              CV.*, TV.NombreTipoVenta,
              TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda, MND.CodigoMoneda,
              FP.NombreFormaPago, U.AliasUsuarioVenta,
              Per.RazonSocial,Per.NumeroDocumentoIdentidad,
              MND.SimboloMoneda, RCFC.IdMotivoComprobanteFisicoContingencia, TDI.NombreAbreviado, TDI.CodigoDocumentoIdentidad,
              DR.CodigoTipoDocumentoReferencia, DR.SerieDocumentoReferencia, DR.NumeroDocumentoReferencia, TOP.CodigoTipoOperacion
              From ComprobanteVenta As CV
              Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
              Inner Join TipoOperacion As TOP on TOP.IdTipoOperacion = CV.IdTipoOperacion
              Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
              Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
              Inner Join Cliente As C on C.IdPersona = CV.IdCliente
              Inner Join Persona As Per on Per.IdPersona = C.IdPersona
              Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
              Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
              Inner Join TipoDocumentoIdentidad As TDI on TDI.IdTipoDocumentoIdentidad = Per.IdTipoDocumentoIdentidad
              left join ResumenComprobanteFisicoContingencia As RCFC on CV.IdComprobanteVenta = RCFC.IdComprobanteVenta
              left join DocumentoReferencia As DR on DR.IdComprobanteNota = CV.IdComprobanteVenta
              Where (CV.SerieDocumento REGEXP '^[0-9]+$') and (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) and
              CV.FechaEmision = '$fechaemision'
              ORDER BY CV.FechaEmision DESC, TD.NombreAbreviado ASC,
              CV.SerieDocumento DESC,CV.NumeroDocumento DESC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalComprobantesVenta($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $tipoventa =$data["TipoVenta"];
    $tipodocumento=$data["TipoDocumento"];
    
    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    $extensionConsulta2 ="";
    $extensionConsulta3 ="";
    
    if (array_key_exists("IdGenero", $data)) {
      $IdGenero=$data["IdGenero"];
      $NombreCasillero = $data["NombreCasillero"] == "" ? '%' : $data["NombreCasillero"];
      $extensionConsulta2 = " And G.IdGenero like '".$IdGenero."' And CS.NombreCasillero like '".$NombreCasillero."'";
    }

    if($vistaventa == 0) {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }
    else {
      $extensionConsulta3 = " OR (CV.AliasUsuarioVenta like '%".$criterio."%') ";      
    }

    $consulta = "Select
                  CV.*, TV.NombreTipoVenta,
                  TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.AliasUsuarioVenta,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                  MND.SimboloMoneda
                  From ComprobanteVenta As CV
                  left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                  left Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                  left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                  left Join Cliente As C on C.IdPersona = CV.IdCliente
                  left Join Persona As Per on Per.IdPersona = C.IdPersona
                  left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                  left Join Usuario As U on U.IdUsuario = CV.IdUsuario
                  left Join AsignacionSede As ASD on ASD.IdAsignacionSede = CV.IdAsignacionSede
                  left Join Sede As S on S.IdSede = ASD.IdSede
                  left join Casillero CS on CS.IdCasillero = CV.IdCasillero
                  left join Genero G on G.IdGenero = CV.IdGenero
                  Where (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado='N' ) and
                  (CV.SerieDocumento like '%$criterio%' or
                  CV.NumeroDocumento like '%$criterio%' or
                  Per.RazonSocial like '%$criterio%' or
                  Per.NumeroDocumentoIdentidad like '%$criterio%'
                  $extensionConsulta3)
                  $extensionConsulta 
                  And
                  CV.IdTipoVenta like '$tipoventa' And
                  CV.IdTipoDocumento like '$tipodocumento' And
                  U.IndicadorEstado='A' And
                  (CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin')
                  $extensionConsulta2
                  ORDER BY CV.SerieDocumento,CV.NumeroDocumento";  

    $query = $this->db->query($consulta);
    $resultado = $query->num_rows();
    return $resultado;
  }

  function ConsultarComprobantesVentaPorCliente($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $cliente =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    $moneda = $data["IdMoneda"];
    $venta = $data["IdTipoVenta"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CV.*, TV.NombreTipoVenta,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, CV.AliasUsuarioVenta,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                  MND.SimboloMoneda, MND.CodigoMoneda
                  From ComprobanteVenta As CV
                  Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                  Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                  Inner Join Cliente As C on C.IdPersona = CV.IdCliente
                  Inner Join Persona As Per on Per.IdPersona = C.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CV.IdMoneda
                  Where C.IdPersona = '$cliente' AND CV.IdTipoVenta = '$venta' AND CV.IdTipoDocumento = '$documento' AND CV.IdMoneda = '$moneda' AND (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) and
                  (CV.SerieDocumento like '%$criterio%' or
                  CV.NumeroDocumento like '%$criterio%' or
                  Per.RazonSocial like '%$criterio%' or
                  Per.NumeroDocumentoIdentidad like '%$criterio%') And
                  CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin' AND SaldoNotaCredito > 0
                  ".$extensionConsulta."
                  ORDER BY CV.SerieDocumento,CV.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesVentaPorClienteParaDebito($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $cliente =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    $moneda = $data["IdMoneda"];
    $venta = $data["IdTipoVenta"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                CV.*, TV.NombreTipoVenta,
                TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                FP.NombreFormaPago, U.AliasUsuarioVenta,
                Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                MND.SimboloMoneda, MND.CodigoMoneda
                From ComprobanteVenta As CV
                Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                Inner Join Cliente As C on C.IdPersona = CV.IdCliente
                Inner Join Persona As Per on Per.IdPersona = C.IdPersona
                Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
                Inner Join Moneda As M on M.IdMoneda = CV.IdMoneda
                Where C.IdPersona = '$cliente' AND CV.IdTipoVenta = '$venta' AND CV.IdTipoDocumento = '$documento' AND CV.IdMoneda = '$moneda' AND (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) and
                (CV.SerieDocumento like '%$criterio%' or
                CV.NumeroDocumento like '%$criterio%' or
                Per.RazonSocial like '%$criterio%' or
                Per.NumeroDocumentoIdentidad like '%$criterio%') And
                CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                ".$extensionConsulta."
                ORDER BY CV.SerieDocumento,CV.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerDocumentosReferenciaPorComprobante($data)
  {
    $id=$data["IdComprobanteVenta"];
    $query = $this->db->query("Select CV.*, TD.NombreAbreviado
    from documentoreferencia as DR
    inner join comprobanteventa as CV on DR.IdComprobanteNota = CV.IdComprobanteVenta
    inner join tipodocumento as TD on TD.IdTipoDocumento = CV.IdTipoDocumento
    Where DR.IdComprobanteVenta = '$id' and (CV.IndicadorEstado = 'A' and DR.IndicadorEstado = 'A')");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesVentaPendienteNotaPorCliente($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $cliente =$data["IdPersona"];
    $documento = $data["IdTipoDocumento"];
    $moneda = $data["IdMoneda"];
    $nota = $data["TipoNota"];

    $vistaventa = $this->sesionusuario->obtener_sesion_vista_venta_usuario();
    $idusuario = $this->sesionusuario->obtener_sesion_id_usuario();
    $extensionConsulta = "";
    if($vistaventa == 0)
    {
      $extensionConsulta = " And U.IdUsuario = '$idusuario' ";
    }

    $consulta = "Select
                  CV.*, TV.NombreTipoVenta,
                  TD.NombreAbreviado, TD.CodigoTipoDocumento, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.AliasUsuarioVenta,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                  MND.SimboloMoneda, MND.CodigoMoneda
                  From ComprobanteVenta As CV
                  Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                  Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                  Inner Join Cliente As C on C.IdPersona = CV.IdCliente
                  Inner Join Persona As Per on Per.IdPersona = C.IdPersona
                  Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                  Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
                  Inner Join Moneda As M on M.IdMoneda = CV.IdMoneda
                  Where C.IdPersona = '$cliente' AND CV.IdTipoDocumento = '$documento' AND CV.IdMoneda = '$moneda' AND (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado = 'N' ) and
                  (CV.SerieDocumento like '%$criterio%' or
                  CV.NumeroDocumento like '%$criterio%' or
                  Per.RazonSocial like '%$criterio%' or
                  Per.NumeroDocumentoIdentidad like '%$criterio%') AND
                  CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin' AND CV.EstadoPendienteNota = '$nota'
                  ".$extensionConsulta."
                  ORDER BY CV.SerieDocumento,CV.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->result_array();
    return $resultado;
  }

  function ObtenerNumeroTotalComprobantesVentaPorCliente($data)
  {
    $criterio=$data["textofiltro"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $cliente =$data["IdPersona"];
    $query = $this->db->query("Select
                                CV.*, TV.NombreTipoVenta,
                                TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                                FP.NombreFormaPago, U.AliasUsuarioVenta,
                                Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                                MND.SimboloMoneda
                                From ComprobanteVenta As CV
                                Inner Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                                Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                                Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                                Inner Join Cliente As C on C.IdPersona = CV.IdCliente
                                Inner Join Persona As Per on Per.IdPersona = C.IdPersona
                                Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                                Inner Join Usuario As U on U.IdUsuario = CV.IdUsuario
                                Where C.IdPersona = '$cliente' AND (CV.IndicadorEstado = 'A' OR CV.IndicadorEstado='N' ) and
                                (CV.SerieDocumento like '%$criterio%' or
                                CV.NumeroDocumento like '%$criterio%' or
                                Per.RazonSocial like '%$criterio%' or
                                Per.NumeroDocumentoIdentidad like '%$criterio%') And
                                CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin'
                                ORDER BY CV.SerieDocumento,CV.NumeroDocumento");
    $resultado = $query->num_rows();
    return $resultado;
  }

  function ObtenerFechaMenor($data)
  {
    $tipodocumento=$data["IdTipoDocumento"];
    $numero=$data["NumeroDocumento"];
    $serie=$data["SerieDocumento"];
    $query = $this->db->query("select max(FechaEmision) as FechaEmisionMenor  from comprobanteventa
    where (IndicadorEstado='A' or IndicadorEstado='N') and NumeroDocumento<'$numero' and
    SerieDocumento='$serie' and IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerFechaMayor($data)
  {
    $tipodocumento=$data["IdTipoDocumento"];
    $numero=$data["NumeroDocumento"];
    $serie=$data["SerieDocumento"];
    $query = $this->db->query("select min(FechaEmision) as FechaEmisionMayor from comprobanteventa
    where (IndicadorEstado='A' or IndicadorEstado='N') and NumeroDocumento>'$numero' and
    SerieDocumento='$serie' and IdTipoDocumento='$tipodocumento'");
    $resultado = $query->row();
    return $resultado;
  }

  function ObtenerCodigoEstado($data) {

    $IndicadorEstado = $data["IndicadorEstado"];
    $IndicadorEstadoCPE = $data["IndicadorEstadoCPE"];
    $IndicadorEstadoResumenDiario = $data["IndicadorEstadoResumenDiario"];
    $IndicadorCodigoEstado = $data["CodigoEstado"];
    $codigo_serie_documento = substr($data["SerieDocumento"],0,1);

    if($IndicadorCodigoEstado == ESTADO_CPE_NINGUNO)
    {
      return CODIGO_ESTADO_EMITIDO;
    }

    if($codigo_serie_documento  == CODIGO_SERIE_BOLETA) {
      if($IndicadorEstado == ESTADO_DOCUMENTO_ACTIVO) {
        if($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO)  {
          if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)    {
            return CODIGO_ESTADO_EMITIDO;
          }
        }
        else if($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
          if($IndicadorCodigoEstado == CODIGO_ESTADO_EMITIDO){
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_ACEPTADO)    {
              return CODIGO_ESTADO_MODIFICADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_RECHAZADO)    {
              return CODIGO_ESTADO_EMITIDO;
            }
            return CODIGO_ESTADO_EMITIDO;
          }
        }
      }
      else if($IndicadorEstado == ESTADO_DOCUMENTO_ANULADO) {
        if($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO) {
          if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
            return CODIGO_ESTADO_EMITIDO;
          }
        }
        else if($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
          if($IndicadorEstadoResumenDiario == ESTADO_CPE_ACEPTADO)    {
            return CODIGO_ESTADO_ANULADO;
          }
          else {
            if($IndicadorCodigoEstado == CODIGO_ESTADO_MODIFICADO){
              return CODIGO_ESTADO_ANULADO;
            }
          }
        }
      }
    }
    else {
      return CODIGO_ESTADO_EMITIDO;
    }

  }

  function ObtenerSituacionCPE($data) {

    $IndicadorEstado = $data["IndicadorEstado"];
    $IndicadorEstadoCPE = $data["IndicadorEstadoCPE"];
    $IndicadorEstadoComunicacionBaja = $data["IndicadorEstadoComunicacionBaja"];
    $IndicadorEstadoResumenDiario = $data["IndicadorEstadoResumenDiario"];
    $codigo_serie_documento = substr($data["SerieDocumento"],0,1);

    if($IndicadorEstado  == ESTADO_DOCUMENTO_ELIMINADO) {
        return ESTADO_CPE_NINGUNO;
    }

    if($codigo_serie_documento  == CODIGO_SERIE_BOLETA) {
      if($IndicadorEstado == ESTADO_DOCUMENTO_ACTIVO) {
        if($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO)  {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)  {
              return ESTADO_CPE_NINGUNO;
            }
          }
        }
        else if($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)   {
              return ESTADO_CPE_GENERADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_GENERADO)   {
              return ESTADO_CPE_RESUMEN_DIARIO_GENERADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_EN_PROCESO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_EN_PROCESO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_ACEPTADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_ACEPTADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_RECHAZADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_RECHAZADO;
            }
          }
        }
        else if($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) //Se añade nueva regla
        {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_GENERADO)   {
              return ESTADO_CPE_RESUMEN_DIARIO_GENERADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_EN_PROCESO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_EN_PROCESO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_ACEPTADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_ACEPTADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_RECHAZADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_RECHAZADO;
            }
          }
        }
      }
      else if($IndicadorEstado == ESTADO_DOCUMENTO_ANULADO) {
        if($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_NINGUNO;
            }
          }
        }
        else if($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)   {
              return ESTADO_CPE_GENERADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_GENERADO)   {
              return ESTADO_CPE_RESUMEN_DIARIO_GENERADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_EN_PROCESO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_EN_PROCESO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_ACEPTADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_GENERADO;
            }
            else if($IndicadorEstadoResumenDiario == ESTADO_CPE_RECHAZADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_RECHAZADO;
            }
          }
        }
        else if($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) //Se añade nueva regla
        {
          if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_GENERADO)   {
              return ESTADO_CPE_RESUMEN_DIARIO_GENERADO;
            }
            else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_EN_PROCESO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_EN_PROCESO;
            }
            else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_ACEPTADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_ACEPTADO;
            }
            else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_RECHAZADO)    {
              return ESTADO_CPE_RESUMEN_DIARIO_RECHAZADO;
            }
          }
        }
      }
    }
    else {
      if($IndicadorEstado == ESTADO_DOCUMENTO_ACTIVO) {
        if ($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)    {
              return ESTADO_CPE_NINGUNO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)    {
              return ESTADO_CPE_GENERADO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_EN_PROCESO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_EN_PROCESO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_ACEPTADO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_RECHAZADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_RECHAZADO;
            }
          }
        }
      }
      else if($IndicadorEstado == ESTADO_DOCUMENTO_ANULADO) {
        if ($IndicadorEstadoCPE == ESTADO_CPE_NINGUNO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)    {
              return ESTADO_CPE_NINGUNO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_GENERADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO)    {
              return ESTADO_CPE_ANULADO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_EN_PROCESO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_ANULADO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_PENDIENTE_BAJA;
            }
          }
          else if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
            if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_GENERADO)   {
              return ESTADO_CPE_BAJA_GENERADA;
            }
            else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_EN_PROCESO)    {
              return ESTADO_CPE_BAJA_EN_PROCESO;
            }
            else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_ACEPTADO)    {
              return ESTADO_CPE_BAJA_ACEPTADA;
            }
            else if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_RECHAZADO)    {
              return ESTADO_CPE_BAJA_RECHAZADA;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_GENERADO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_BAJA_GENERADA;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_EN_PROCESO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_BAJA_EN_PROCESO;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_ACEPTADO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_BAJA_ACEPTADA;
            }
          }
        }
        else if ($IndicadorEstadoCPE == ESTADO_CPE_ACEPTADO) {
          if($IndicadorEstadoComunicacionBaja == ESTADO_CPE_RECHAZADO) {
            if($IndicadorEstadoResumenDiario == ESTADO_CPE_NINGUNO) {
              return ESTADO_CPE_BAJA_RECHAZADA;
            }
          }
        }
      }
    }

  }


  function ObtenerMinimoMaximoFechaEmisionComprobanteVenta() {
    $query = $this->db->query("Select min(fechaemision) as minimofechaemision,max(fechaemision) as maximofechaemision from ComprobanteVenta");
    $resultado = $query->row();
    return $resultado;
  }

  function ValidarComprobanteVentaJSONPorSerieYNumero($data) {
    $serie = $data['SerieDocumento'];
    $numero = $data['NumeroDocumento'];

    $query = $this->db->query("Select * FROM comprobanteventa cv
                                WHERE cv.SerieDocumento = '$serie'
                                AND cv.NumeroDocumento = '$numero' AND (IndicadorEstado = 'A' OR IndicadorEstado = 'N')");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ValidarComprobanteVentaEliminadoJSONPorSerieYNumero($data) {
    $serie = $data['SerieDocumento'];
    $numero = $data['NumeroDocumento'];

    $query = $this->db->query("Select * FROM comprobanteventa cv
                                WHERE cv.SerieDocumento = '$serie'
                                AND cv.NumeroDocumento = '$numero' AND (IndicadorEstado = 'A' OR IndicadorEstado = 'N' OR IndicadorEstado = 'E')
                                ORDER BY cv.FechaRegistro DESC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarReporteResumenVentasPorSerie($data) {
    $fechainicial = $data['FechaInicio'];
    $fechafinal = $data['FechaFin'];
    $aliasusuario = $data['AliasUsuarioVenta'];
    
    $query = $this->db->query("Select cv.FechaEmision,
                                (SELECT us.NumeroSerie FROM usuario us WHERE cv.AliasUsuarioVenta = us.AliasUsuarioVenta) AS NumeroSerieAlias,
                                CV.AliasUsuarioVenta AS AliasUsuarioVentaReporte,
                                CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento,
                                cv.Total,
                                pe.RazonSocial AS RazonSocialCliente,
                                pe.NumeroDocumentoIdentidad AS NumeroDocumentoIdentidad,
                                CASE WHEN cv.IndicadorEstado = 'N' THEN 'ANULADO' ELSE 'ACTIVO' END AS EstadoComprobante,
                                IF(TD.NombreAbreviado = 'FT', IF(ce.FechaEnvio = '0000-00-00',null,ce.FechaEnvio),rd.FechaGeneracionResumenDiario) AS FechaEnvioSunat,
                                cv.FechaRegistro, sce.NombreSituacionComprobanteElectronico
                                
                                FROM comprobanteventa cv
                                LEFT JOIN persona pe ON pe.IdPersona = cv.IdCliente
                                LEFT JOIN tipodocumento td ON td.IdTipoDocumento = cv.IdTipoDocumento
                                LEFT JOIN comprobanteelectronico ce ON ce.IdComprobanteVenta = cv.IdComprobanteVenta
                                LEFT JOIN situacioncomprobanteelectronico sce ON sce.CodigoSituacionComprobanteElectronico = cv.SituacionCPE
                                LEFT JOIN detalleresumendiario drd ON drd.IdComprobanteVenta = cv.IdComprobanteVenta
                                LEFT JOIN resumendiario rd ON rd.IdResumenDiario = drd.IdResumenDiario
                                
                                WHERE (cv.IndicadorEstado = 'A' OR cv.IndicadorEstado = 'N')
                                AND ce.IndicadorEstado = 'A'
                                AND CV.AliasUsuarioVenta LIKE '$aliasusuario'
                                AND (cv.FechaEmision BETWEEN '$fechainicial' AND '$fechafinal')
                                ORDER BY NumeroSerieAlias, cv.FechaEmision, Documento");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultaComprobantesVentaParaJSON() {
    $tipodocumentoexclusion = ID_TIPODOCUMENTO_NOTADEVOLUCION.", ".ID_TIPODOCUMENTO_NOTACREDITO.", ".ID_TIPODOCUMENTO_NOTADEBITO;
    $query = $this->db->query("SELECT CV.*, TD.NombreAbreviado, TD.CodigoTipoDocumento,cv.IdCorrelativoDocumento
                              FROM comprobanteventa CV
                              LEFT JOIN tipodocumento TD ON TD.IdTipoDocumento = CV.IdTipoDocumento
                              WHERE CV.IndicadorEstado = 'A'
                              AND CV.IdTipoDocumento NOT IN('$tipodocumentoexclusion')
                              ORDER BY CV.FechaEmision DESC, TD.NombreAbreviado ASC,
                              CV.SerieDocumento DESC,CV.NumeroDocumento DESC");
    $resultado = $query->result_array();
    return $resultado;
  }

  function TotalSaldosGuiaRemisionRemitenteEnDetalles($data) {
    $id = $data["IdComprobanteVenta"];
    $query = $this->db->query("SELECT SUM(DCV.SaldoPendienteGuiaRemision) 'TotalSaldoPendienteGuiaRemision'
                              FROM detallecomprobanteventa DCV
                              LEFT JOIN comprobanteventa CV ON CV.IdComprobanteVenta = DCV.IdComprobanteVenta
                              WHERE DCV.IndicadorEstado = 'A'
                              AND DCV.IdComprobanteVenta = '$id'");
    $resultado = $query->row_array();
    return $resultado;
  }

  //COMPROBANTES VENTA - CLINICA
  function ConsultarComprobantesVentaReferencia($data)
  {
    $tipoVenta = TIPO_VENTA_SERVICIOS;
    $tipoDocumento = ID_TIPO_DOCUMENTO_FACTURA;
    $tipoDocumento2 = ID_TIPO_DOCUMENTO_BOLETA;
    $query = $this->db->query("Select CV.*, TV.NombreTipoVenta,
                                CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento,
                                TD.NombreAbreviado, MND.NombreMoneda
                                From ComprobanteVenta As CV
                                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                                Where CV.IndicadorEstado = 'A' AND CV.IdTipoVenta = '$tipoVenta'
                                AND (CV.IdTipoDocumento = '$tipoDocumento' OR CV.IdTipoDocumento = '$tipoDocumento2')
                                ORDER BY (CV.NumeroDocumento)");
    $resultado = $query->result_array();
    return $resultado;
  }

  function ConsultarComprobantesVentaProforma($data)
  {
    // $tipoVenta = TIPO_VENTA_SERVICIOS;
    $tipoDocumento = ID_TIPO_DOCUMENTO_PROFORMA;
    // $tipoDocumento2 = ID_TIPO_DOCUMENTO_BOLETA;
    $query = $this->db->query("Select CV.*, TV.NombreTipoVenta,
                                CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento,
                                TD.NombreAbreviado, MND.NombreMoneda,TD.CodigoTipoDocumento
                                From ComprobanteVenta As CV
                                left Join TipoVenta As TV on TV.IdTipoVenta = CV.IdTipoVenta
                                left Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento
                                left Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                                Where CV.IndicadorEstado = 'A'
                                AND CV.IdTipoDocumento = '$tipoDocumento' 
                                ORDER BY (CV.NumeroDocumento)");
    $resultado = $query->result_array();
    return $resultado;
  }


  function ObtenerNumeroTotalVentasProforma($data) {
    $IdCliente = $data["IdCliente"];//$criterio=$data["TextoFiltro"] == "%" ? "%" : "%".$data["TextoFiltro"]."%";
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $IdUsuarioVendedor =$data["IdUsuarioVendedor"];
    $IdTipoDocumento = ID_TIPO_DOCUMENTO_PROFORMA;    
  
    $consulta = "Select
    CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento,
    CV.*, 
    TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
    FP.NombreFormaPago, U.AliasUsuarioVenta,
    Per.RazonSocial,Per.NumeroDocumentoIdentidad,
    MND.SimboloMoneda
    From ComprobanteVenta As CV                  
    Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
    Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento                  
    Inner Join Persona As Per on Per.IdPersona = CV.IdCliente
    Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
    Inner Join Usuario As U on U.AliasUsuarioVenta = CV.AliasUsuarioVenta
    LEFT JOIN proformacomprobanteventa pcv
    ON cv.IdComprobanteVenta = pcv.IdProforma
    Where (CV.IndicadorEstado = 'A' and
    CV.IdCliente like '$IdCliente' and
    U.IdUsuario like '$IdUsuarioVendedor') And
    CV.IdTipoDocumento like '$IdTipoDocumento' And
    U.IndicadorEstado='A' and
    (CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin' )           
    and pcv.IdProformaComprobanteVenta is null
    ORDER BY CV.SerieDocumento,CV.NumeroDocumento";

    $query = $this->db->query($consulta);
    $resultado = $query->num_rows();
    return $resultado;
  }

  function ConsultarVentasProformas($data) {
    //$criterio=$data["TextoFiltro"] == "%" ? "%" : "%".$data["TextoFiltro"]."%";
    $IdCliente = $data["IdCliente"];
    //$numeroDocumento = $data["NumeroDocumento"];
    $fechainicio =$data["FechaInicio"];
    $fechafin =$data["FechaFin"];
    $IdUsuarioVendedor =$data["IdUsuarioVendedor"];
    $IdTipoDocumento = ID_TIPO_DOCUMENTO_PROFORMA;        
    //            (Per.RazonSocial like '$criterio' or
    //Per.NumeroDocumentoIdentidad like '$criterio' and
    $consulta = "Select
                  CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento,
                  CV.*, 
                  TD.NombreAbreviado, Per.IdPersona, MND.NombreMoneda,
                  FP.NombreFormaPago, U.AliasUsuarioVenta,
                  Per.RazonSocial,Per.NumeroDocumentoIdentidad,
                  MND.SimboloMoneda
                  From ComprobanteVenta As CV                  
                  Inner Join FormaPago As FP on FP.IdFormaPago = CV.IdFormaPago
                  Inner Join TipoDocumento As TD on TD.IdTipoDocumento = CV.IdTipoDocumento                  
                  Inner Join Persona As Per on Per.IdPersona = CV.IdCliente
                  Inner Join Moneda As MND on MND.IdMoneda = CV.IdMoneda
                  Inner Join Usuario As U on U.AliasUsuarioVenta = CV.AliasUsuarioVenta
                  LEFT JOIN proformacomprobanteventa pcv
                  ON cv.IdComprobanteVenta = pcv.IdProforma
                  Where (CV.IndicadorEstado = 'A' and
                  CV.IdCliente like '$IdCliente' and
                  U.IdUsuario like '$IdUsuarioVendedor') And
                  CV.IdTipoDocumento like '$IdTipoDocumento' And
                  U.IndicadorEstado='A' and
                  (CV.FechaEmision BETWEEN '$fechainicio' And '$fechafin' )           
                  and pcv.IdProformaComprobanteVenta is null
                  ORDER BY CV.SerieDocumento,CV.NumeroDocumento";

      $query = $this->db->query($consulta);      
      $resultado = $query->result_array();      
      return $resultado;
  }



  function ConsultarComprobantesGuia($data) {
    $fi=$data["FechaInicio"];
    $ff=$data["FechaFin"];
    $v = $data["Vendedores"];

    $query = $this->db->query("SELECT cv.*,'0' as EstadoSelector,
                    CONCAT(TD.NombreAbreviado, ' ', CV.SerieDocumento, '-', CV.NumeroDocumento) AS Documento
                    FROM comprobanteventa cv
                    LEFT JOIN tipodocumento td ON td.IdTipoDocumento = cv.IdTipoDocumento
                    WHERE cv.IndicadorEstado='A' 
                    AND ( cv.FechaEmision BETWEEN '$fi' AND '$ff') 
                    AND cv.AliasUsuarioVenta IN ($v)");
    $resultado = $query->result_array();
    return $resultado;
  }
}
