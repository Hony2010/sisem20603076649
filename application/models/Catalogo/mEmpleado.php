<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class mEmpleado extends CI_Model {

        public $Empleado = array();
        // public $Persona = array();

        public function __construct()
        {
               parent::__construct();
               $this->load->database();
               $this->load->model("Base");
               $this->load->library('shared');
               $this->load->library('mapper');
               $this->Empleado = $this->Base->Construir("Empleado");
               // $this->Persona = $this->Base->Construir("Persona");
        }

        function ObtenerNumeroFila()
        {
          $query = $this->db->query("Select Count(IdEmpleado) As NumeroFila From Empleado");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListadoDeEmpleados()
        {
            $query = $this->db->query("Select P.*,E.*, TDI.NombreAbreviado, CE.NombreRol, S.NombreSede
                                       From Empleado As E
                                       Inner Join Persona As P On E.IdPersona = P.IdPersona
                                       Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                       Inner Join Rol As CE on P.IdRol = CE.IdRol
                                       Inner Join Sede As S on E.IdSede = S.IdSede
                                       WHERE  E.IndicadorEstado= 'A'  or E.IndicadorEstado= 'I'
                                       ORDER  BY (E.IdEmpleado) ASC");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarEmpleados($inicio, $ValorParametroSistema)
        {
            $query = $this->db->query("Select P.*,E.*, TDI.NombreAbreviado, CE.NombreRol, S.NombreSede
                                       From Empleado As E
                                       Inner Join Persona As P On E.IdPersona = P.IdPersona
                                       Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                       Inner Join Rol As CE on P.IdRol = CE.IdRol
                                       Inner Join Sede As S on E.IdSede = S.IdSede
                                       WHERE  E.IndicadorEstado= 'A'  or E.IndicadorEstado= 'I'
                                       ORDER  BY (E.IdEmpleado) ASC
                                       LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ListarEmpleadosPorId($data)
        {
          $id=$data["IdEmpleado"];
          $query = $this->db->query("Select E.*, P.NombreCompleto , P.ApellidoCompleto, CE.NombreRol, P.Foto,P.IdRol
                                    From Empleado as E
                                    Inner Join Persona as P on P.IdPersona = E.IdPersona
                                    Inner Join Rol as CE on CE.IdRol = P.IdRol
                                    Where E.IdEmpleado  = '$id' and E.IndicadorEstado = 'A' ");
          $resultado = $query->result_array();
          return $resultado;
        }

        function InsertarEmpleado($data)
        {
          $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
          $data["IndicadorEstado"]=ESTADO_ACTIVO;
          $resultado = $this->mapper->map($data,$this->Empleado);
          $this->db->insert('Empleado', $resultado);
          $resultado = $this->db->insert_id();
          return($resultado);
        }

        function ActualizarEmpleado($data)
        {
          $id=$data["IdEmpleado"];
          $data["FechaModificacion"]=$this->Base->ObtenerFechaServidor();
          $resultado = $this->mapper->map($data,$this->Empleado);
          $this->db->where('IdEmpleado', $id);
          $this->db->update('Empleado', $resultado);
        }
        
        function ObtenerNumeroTotalEmpleados($data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select E.*, TDI.NombreAbreviado, P.*, CE.NombreRol, S.NombreSede
                                    From Empleado As E
                                    Inner Join Persona As P On E.IdPersona = P.IdPersona
                                    Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                    Inner Join Rol As CE on P.IdRol = CE.IdRol
                                    Inner Join Sede As S on E.IdSede = S.IdSede
                                    Where (P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%') AND (P.IndicadorEstado= 'A' or P.IndicadorEstado= 'I')
                                    ORDER  BY (E.IdEmpleado)");
          $resultado = $query->num_rows();
          return $resultado;
        }

        function ConsultarEmpleados($inicio,$ValorParametroSistema,$data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select E.*, TDI.NombreAbreviado, P.*, CE.NombreRol, S.NombreSede
                                    From Empleado As E
                                    Inner Join Persona As P On E.IdPersona = P.IdPersona
                                    Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                    Inner Join Rol As CE on P.IdRol = CE.IdRol
                                    Inner Join Sede As S on E.IdSede = S.IdSede
                                    Where(P.RazonSocial like '%$criterio%' or P.NumeroDocumentoIdentidad like '%$criterio%') AND (P.IndicadorEstado= 'A' or P.IndicadorEstado= 'I')
                                    ORDER  BY (E.IdEmpleado)
                                    LIMIT $inicio,$ValorParametroSistema");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroFilaPorConsultaEmpleado($data)
        {
          $criterio=$data["textofiltro"];
          $query = $this->db->query("Select Count(IdEmpleado) As NumeroFila
                                     From Empleado As E
                                     Inner Join Persona As P On E.IdPersona = P.IdPersona
                                     Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                                     Inner Join Rol As CE on P.IdRol = CE.IdRol
                                     Inner Join Sede As S on E.IdSede = S.IdSede
                                     Where P.IndicadorEstado= 'A' AND (P.RazonSocial like '%$criterio%' or P.IndicadorEstado= 'I' or P.NumeroDocumento like '%$criterio')
                                     ORDER  BY (P.RazonSocial) ASC");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroDocumentoIdentidadParaInsertar($data)
        {
          $numero=$data["NumeroDocumentoIdentidad"];
          $query = $this->db->query("Select E.*
                                    From Empleado As E
                                    Inner Join Persona As P On E.IdPersona = P.IdPersona
                                    Where P.NumeroDocumentoIdentidad ='$numero' and P.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ObtenerNumeroDocumentoIdentidadParaActualizar($data)
        {
          $id=$data["IdPersona"];
          $numero=$data["NumeroDocumentoIdentidad"];
          $query = $this->db->query("Select E.*
                                    From Empleado As E
                                    Inner Join Persona As P On E.IdPersona = P.IdPersona
                                    Where (P.IdPersona > '$id' Or P.IdPersona < '$id' ) and P.NumeroDocumentoIdentidad = '$numero' and P.IndicadorEstado = 'A'");
          $resultado = $query->result_array();
          return $resultado;
        }

        function ConsultarEmpleadoParaJSON()
        {
          $query = $this->db->query("Select E.*, TDI.NombreAbreviado, P.*, CE.NombreRol, S.NombreSede
                    From Empleado As E
                    Inner Join Persona As P On E.IdPersona = P.IdPersona
                    Inner Join TipoDocumentoIdentidad As TDI on P.IdTipoDocumentoIdentidad = TDI.IdTipoDocumentoIdentidad
                    Inner Join Rol As CE on P.IdRol = CE.IdRol
                    Inner Join Sede As S on E.IdSede = S.IdSede
                    Where (P.IndicadorEstado= 'A' or P.IndicadorEstado= 'I')
                    ORDER  BY (E.IdEmpleado)");
          $resultado = $query->result_array();
          return $resultado;
        }
}
