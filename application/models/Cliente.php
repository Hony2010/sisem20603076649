<?php

if (! defined ('BASEPATH')) exit ('No direct script access allowed');

class Cliente extends CI_Model {

        public $cliente = array();

         public function __construct()
         {
                parent::__construct();
                $this->load->database();
                $this->load->model("Base");
                $this->load->library('shared');
                $this->load->library('mapper');

                $this->cliente = $this->Base->Construir("Cliente");
         }


         function FiltrarClientes($spec)
         {
                $criterio=$spec["textofiltro"];

                $this->db->select("Cliente.*, TipoDocumento.NombreAbreviado")
                ->from('Cliente')
                ->join('TipoDocumento','Cliente.IdTipoDocumento=TipoDocumento.IdTipoDocumento')
                ->where('Cliente.RazonSocial like "%'.$criterio.'%" or
                  Cliente.NumeroDocumento like "%'.$criterio.'%" or Cliente.IdCliente like "%'.$criterio.'%"' );
                $query = $this->db->get();

                $resultado = $query->result();

                return $resultado;
         }

         function RegistrarUsuario($data)
         {
            $data["FechaRegistro"]=$this->Base->ObtenerFechaServidor();
            $resultado = $this->mapper->map($data,$this->usuario);

            $this->db->insert('Usuario', $resultado);
         }

         function ActualizarUsuario($data)
         {
            $id=$data["CodigoUsuario"];
            $resultado = $this->mapper->map($data,$this->usuario);

            $this->db->where('CodigoUsuario', $id);
            $this->db->update('Usuario', $resultado);
         }

         function ValidarCuenta($data)
         {
                $codigo=$data["NombreUsuario"];
                $clave=$data["Clave"];

                $this->db->select("Usuario.*")
                ->from('Usuario as Usuario')
                ->where('Usuario.NombreUsuario="'.$codigo.'" AND Usuario.Clave="'.$clave.'"');
                $query = $this->db->get();

                $objeto = $query->row();

                $resultado = json_decode(json_encode($objeto), True);
                return $resultado;
         }

         function MarcarSesionActivo($data)
         {
            $data["IndicadorSesionActivo"] = 'S';
            $this->ActualizarUsuario($data);
         }

         function DesmarcarSesionActivo($data)
         {
            $data["IndicadorSesionActivo"] = 'N';
            $this->ActualizarUsuario($data);
         }

         function ValidarCuentaAdministrador($data)
         {
                $resultado = $this->ValidarCuenta($data);

                if($resultado != null) //si existe cuenta
		            {
                  if ($resultado["IndicadorEstado"] == "A") //si la cuenta esta vigente
                  {
                    if($resultado["CodigoRol"] == 0) //si la cuenta vigente es de administrador
                    {
                        if ($resultado["IndicadorSesionActivo"] =="N") //si la cuenta esta vigente de administrador no tiene sesion activo
                        {
                          return $resultado;
                        }
                        else
                         return "La cuenta esta siendo usada en otra sesion.";
                    }
                    else
                      return "El tipo de cuenta no es de administrador.";
                  }
                  else
                   return "La cuenta no esta activa";
                }
                else
                  return "La cuenta no existe o la clave o nombre es incorrecto";
         }

         function ValidarCuentaUsuario($data)
         {
                $resultado = $this->ValidarCuenta($data);

                if($resultado != null) //si existe cuenta
		            {
                  if ($resultado["IndicadorEstado"] == "A") //si la cuenta esta vigente
                  {
                    if($resultado["CodigoRol"] == 1) //si la cuenta vigente es de usuario
                    {
                        if ($resultado["IndicadorSesionActivo"] == "N") //si la cuenta esta vigente de usuario no tiene sesion activo
                        {
                          return $resultado;
                        }
                        else
                         return "La cuenta esta siendo usada en otra sesion.";
                    }
                    else
                      return "El tipo de cuenta no es de usuario.";
                  }
                  else
                   return "La cuenta no esta activa";
                }
                else
                  return "La cuenta no existe o la clave o nombre es incorrecto";
         }

         function AutenticarCuentaAdministrador($data)
         {
           $resultado =$this->Usuario->ValidarCuentaAdministrador($data);

           if(is_array($resultado)) //si la cuenta es valida
            {
             $this->MarcarSesionActivo($resultado);
             return $resultado;
            }
            else
                throw new Exception($resultado);

         }
}
