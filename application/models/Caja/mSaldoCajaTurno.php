<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mSaldoCajaTurno extends CI_Model {

    public $SaldoCajaTurno = array();

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("Base");
        $this->load->library('shared');
        $this->load->library('mapper');
        $this->load->library('sesionusuario');
        $this->SaldoCajaTurno = $this->Base->Construir("SaldoCajaTurno");
    }

    function InsertarSaldoCajaTurno($data)
    {
        $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
        $data["IndicadorEstado"]=ESTADO_ACTIVO;
        $data["IdUsuario"] = $this->sesionusuario->obtener_sesion_id_usuario();
        $data["UsuarioRegistro"] = $this->sesionusuario->obtener_sesion_nombre_usuario();
        $data["NombreUsuario"] = $data["UsuarioRegistro"];
        $resultado = $this->mapper->map($data,$this->SaldoCajaTurno);
        $this->db->insert('SaldoCajaTurno', $resultado);
        $resultado["IdSaldoCajaTurno"] = $this->db->insert_id();
        return($resultado);
    }


    function ActualizarSaldoCajaTurno($data)
    {
        $id=$data["IdSaldoCajaTurno"];
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data,$this->SaldoCajaTurno);
        $this->db->where('IdSaldoCajaTurno', $id);
        $this->db->update('SaldoCajaTurno', $resultado);

        return $resultado;
    }

    function BorrarSaldoCajaTurno($data)
    {
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $this->ActualizarSaldoCajaTurno($data);
    }

    function BorrarSaldoCajaTurnoPorIdCaja($data)
    {
        $id=$data["IdCaja"];
        $data["IndicadorEstado"]=ESTADO_ELIMINADO;
        $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
        $data["UsuarioModificacion"]=$this->sesionusuario->obtener_sesion_nombre_usuario();
        $resultado = $this->mapper->map($data,$this->SaldoCajaTurno);
        $this->db->where('IdCaja', $id);
        $this->db->update('SaldoCajaTurno', $resultado);

        return $resultado;
    }

    /**FUNCIONES PARA APERTURA CAJA */
    function ValidarAperturaSaldoCajaTurnoPorTurnoYCaja($data) {
        $caja = $data["IdCaja"];
        $pendiente = INDICADOR_ESTADO_CAJA_PENDIENTE;
        $turno = $data["IdTurno"];
        $fecha = $data["FechaTurno"];
        
        $sql = "Select * FROM SaldoCajaTurno
        WHERE IdTurno = '$turno' AND IdCaja = '$caja'
        AND EstadoCaja = '$pendiente' AND FechaCaja != '$fecha'
        AND IndicadorEstado = 'A'";
        
        $query = $this->db->query($sql);
        $resultado = $query->result_array();
        return $resultado;
    }

    function ValidarDuplicadoDeAperturaSaldoCajaTurnoParaInsertar($data)
    {
        $caja = $data["IdCaja"];
        $turno = $data["IdTurno"];
        $fecha = $data["FechaTurno"];

        $query = $this->db->query("Select * FROM SaldoCajaTurno
                                    WHERE IdCaja = '$caja' AND IdTurno = '$turno'
                                    AND FechaCaja = '$fecha'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    /**FIN FUNCIONES PARA APERTURA CAJA */
    
    /**FUNCIONES PARA CIERRE CAJA */
    function ObtenerSaldoCajaTurnoParaInsertarOActualizar($data)
    {
        $caja=$data["IdCaja"];
        $usuario=$data["IdUsuario"];
        $turno=$data["IdTurno"];
        $fecha=$data["FechaTurno"];

        $query = $this->db->query("Select * from SaldoCajaTurno
                                WHERE IdCaja = '$caja'
                                AND IdUsuario = '$usuario'
                                AND IdTurno = '$turno'
                                AND FechaCaja = '$fecha'
                                AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    
    function ObtenerUltimaAperturaPorUsuarioYCaja($data) {
        $usuario = $data["IdUsuario"];
        $caja = $data["IdCaja"];
        $pendiente = INDICADOR_ESTADO_CAJA_PENDIENTE;
        // IdUsuario = '$usuario' 
        $sql="Select * FROM SaldoCajaTurno
                                    WHERE
                                    IdCaja = '$caja'
                                    AND EstadoCaja = '$pendiente'
                                    AND IndicadorEstado = 'A'";        
        $query = $this->db->query($sql);
        $resultado = $query->result_array();
        return $resultado;
    }
    /**FIN FUNCIONES PARA CIERRE CAJA */

    function ValidarExistenciaDeCierreSaldoCajaTurnoParaInsertar($data)
    {
        $caja = $data["IdCaja"];
        $pendiente = INDICADOR_ESTADO_CAJA_PENDIENTE;

        $query = $this->db->query("Select * FROM SaldoCajaTurno
                                    WHERE IdCaja = '$caja' 
                                    AND EstadoCaja = '$pendiente'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ValidarExistenciaDeAperturaSaldoCajaTurnoParaInsertar($data)
    {
        $caja = $data["IdCaja"];
        $pendiente = INDICADOR_ESTADO_CAJA_PENDIENTE;
        $fecha = $data["FechaCaja"];

        $query = $this->db->query("Select * FROM SaldoCajaTurno
                                    WHERE FechaCaja = '$fecha' AND IdCaja = '$caja'
                                    AND EstadoCaja = '$pendiente'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ValidarDuplicadoDeCierreSaldoCajaTurnoParaInsertar($data)
    {
        $caja = $data["IdCaja"];
        $turno = $data["IdTurno"];
        $fecha = $data["FechaCaja"];

        $query = $this->db->query("Select * FROM SaldoCajaTurno
                                    WHERE IdCaja = '$caja' AND IdTurno = '$turno'
                                    AND FechaCaja = '$fecha'
                                    AND IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }
    
    function ObtenerCajasAperturadasParaTransferencias($data)
    {
        $fecha = $data["FechaCaja"];
        $query = $this->db->query("Select sct.*, c.*, u.NombreUsuario, u.AliasUsuarioVenta, m.NombreMoneda 
                                    FROM saldocajaturno sct
                                    INNER JOIN caja c ON c.IdCaja = sct.IdCaja
                                    INNER JOIN usuario u ON u.IdUsuario = sct.IdUsuario
                                    INNER JOIN moneda m ON m.IdMoneda = c.IdMoneda
                                    WHERE sct.EstadoCaja = 'O' 
                                     AND sct.IndicadorEstado = 'A'
                                     AND c.IndicadorEstado = 'A'
                                     AND sct.FechaCaja = '$fecha'");
        $resultado = $query->result_array();
        return $resultado;
    }

    function ObtenerSaldoCajaTurnoPorUsuario($data)
    {
        $caja=$data["IdCaja"];
        $usuario=$data["IdUsuario"];
        $turno=$data["IdTurno"];
        $fecha=$data["FechaCaja"];
        $pendiente = INDICADOR_ESTADO_CAJA_PENDIENTE;

        $query = $this->db->query("Select SCT.*, U.NombreUsuario, U.AliasUsuarioVenta from SaldoCajaTurno SCT
                                INNER JOIN Usuario U ON U.IdUsuario = SCT.IdUsuario
                                WHERE SCT.IdCaja = '$caja'
                                AND SCT.IdUsuario = '$usuario'
                                AND SCT.IdTurno = '$turno'
                                AND SCT.FechaCaja = '$fecha'
                                AND SCT.EstadoCaja = '$pendiente'
                                AND SCT.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

    //OBTENCION DE ULTIMA CAJA APERTURADA
    function ObtenerUltimaCajaAperturadaPorTurnoYCaja($data)
    {
        $caja=$data["IdCaja"];
        $usuario=$data["IdUsuario"];
        $turno=$data["IdTurno"];
        $pendiente = INDICADOR_ESTADO_CAJA_PENDIENTE;
        //AND SCT.IdUsuario = '$usuario'
        $query = $this->db->query("Select SCT.*, U.NombreUsuario, U.AliasUsuarioVenta from SaldoCajaTurno SCT
                                INNER JOIN Usuario U ON U.IdUsuario = SCT.IdUsuario
                                WHERE SCT.IdCaja = '$caja'                            
                                AND SCT.IdTurno = '$turno'
                                AND SCT.EstadoCaja = '$pendiente'
                                AND SCT.IndicadorEstado = 'A'");
        $resultado = $query->result_array();
        return $resultado;
    }

 }
