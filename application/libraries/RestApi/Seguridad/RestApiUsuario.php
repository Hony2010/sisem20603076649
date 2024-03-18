<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class RestApiUsuario {

	public $CI;

	function __construct()
	{
		if (!isset($this->CI))
		{
			$this->CI =& get_instance();
		}

		$this->CI->load->library('archivo');
		$this->CI->load->library('json');
		$this->CI->load->service("Seguridad/sUsuario");
		$this->CI->load->service("Seguridad/sMenu");
		$this->CI->load->service("Seguridad/sAccesoUsuario");
		$this->CI->load->service("Seguridad/sParametroSistema");

		$this->CI->load->service("Configuracion/General/sRol");
    	$this->CI->load->service("Catalogo/sEmpleado");
	}

	public function PreparaDataUsuarios()
	{
		$data_usuario =  $this->CI->sUsuario->Inicializar();
    	$data_empleado =  $this->CI->sEmpleado->Inicializar();
    	$Empleados =  $this->CI->sEmpleado->ListadoDeEmpleados();

    	$Roles = $this->CI->sRol->ListarRoles();
		$dataq["IdRol"] = $Roles[0]["IdRol"];
		$data_nueva = array(
			'AccesosRol' =>array(),
			'AccesoRol' => array(),
			'AccesosUsuario' => array(),
			'AccesoUsuario' => array(),
			'Roles' => $Roles,
			'Empleados' => $Empleados
		);

		$data = array_merge($data_nueva, $data_usuario["data"], $data_empleado["data"]);
		$data = array("data" => $data);
		$response['data'] = $data;
		return $response;
	}

	public function ListarUsuarios()
	{
    	$resultado = $this->CI->sUsuario->ListarUsuarios();
		return $resultado;
	}

	public function InsertarUsuario($data) {
		try {
		  $this->CI->db->trans_begin();
		  $opciones = $data["OpcionesSistema"];
		  $resultado = $this->CI->sUsuario->InsertarUsuario($data);
		//   print_r($resultado);exit;
		  if(is_array($resultado)) {
			$data["IdUsuario"] = $resultado["IdUsuario"];
			$datos = array();
			foreach ($opciones as $key) {
			  if(array_key_exists('OpcionesSistema', $key)){
				foreach ($key["OpcionesSistema"] as $value) {
				  $value["IdUsuario"] = $resultado["IdUsuario"];
				  $value["IdRol"] = $data["IdRol"];
				  // $value["EstadoOpcionUsuario"] = $value["EstadoOpcionUsuario"];
				  $value["EstadoOpcionUsuario"] = $value["EstadoOpcionRol"];
				  array_push($datos, $value);
				}
			  }
			}
	
			if(count($datos) > 0){
			  $this->CI->sAccesoUsuario->InsertarAccesosUsuario($datos);
			}

			$almacenes = $this->CI->sUsuario->InsertarAccesoUsuarioAlmacen($data);
			$data["Almacenes"] = $almacenes;
			$this->CI->sMenu->CrearMenuPorUsuario($data);
			
			$this->CI->db->trans_commit();
			return $data;
		  }
		  else {
			$this->CI->db->trans_rollback();
			return $resultado;
		  }
		} catch (Exception $e) {
		  $this->CI->db->trans_rollback();
		  return $e->getMessage();
		}
	}
	
	public function ActualizarUsuario($data) {
		try {
			$this->CI->db->trans_begin();
			$opciones = $data["OpcionesSistema"];
			$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
			$DatosCarpeta = $this->CI->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
			$nombre = "menu-".$data["NombreUsuario"];
			$resultado = $this->CI->sUsuario->ActualizarUsuario($data);
			if(is_array($resultado)) {
				if($opciones != "") {
					$datos = array();
						foreach ($opciones as $key) {
							if(array_key_exists('OpcionesSistema', $key)) {
								foreach ($key["OpcionesSistema"] as $value) {
									$value["IdRol"] = $data["IdRol"];
									$value["IdUsuario"] = $data["IdUsuario"];
									$value["EstadoOpcionRol"] = $value["EstadoOpcionUsuario"];
									array_push($datos, $value);
								}
							}
						}

					if(count($datos) > 0) {
						$accesos = $this->CI->sAccesoUsuario->ActualizarAccesosUsuario($datos);
						if($accesos == "")
						{
							$ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
							$resultado2=$this->CI->archivo->EliminarArchivo($ruta);
							$this->CI->sMenu->CrearMenuPorUsuario($data);
						}
						else {
							throw new Exception($accesos, 1);
						}
						// return $resultado;
					}
					else {
						if($data["IdPersona"] != $data["AnteriorIdPersona"])
						{
							$response = $this->CI->sMenu->CargarOpcionesPorUsuario($data);
							if(count($response) > 0)
							{
								$datos = array();
								foreach ($response as $key) {
									if(array_key_exists('OpcionesSistema', $key)) {
									foreach ($key["OpcionesSistema"] as $value) {
										$value["IdRol"] = $data["IdRol"];
										$value["IdUsuario"] = $data["IdUsuario"];
										$value["EstadoOpcionRol"] = $value["EstadoOpcionUsuario"];
										array_push($datos, $value);
									}
									}
								}

								$this->CI->sAccesoUsuario->ActualizarAccesosUsuario($datos);
							}
							else {
								$this->CI->sAccesoUsuario->BorrarAccesosPorUsuario($data);
							}
							$ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
							$resultado2=$this->CI->archivo->EliminarArchivo($ruta);
							$this->CI->sMenu->CrearMenuPorUsuario($data);
						}
					}
				}

				$this->CI->db->trans_commit();
				return $resultado;
			}
			else {
				$this->CI->db->trans_rollback();
				return $resultado;
			}

		} catch (Exception $e) {
			$this->CI->db->trans_rollback();
			return $e->getMessage();
		}
	}

	public function BorrarUsuario($data) {
		try {
			$this->CI->db->trans_begin();
			$resultado = $this->CI->sUsuario->BorrarUsuario($data);
			if($resultado == "") {
				$this->CI->sAccesoUsuario->BorrarAccesosPorUsuario($data);
				$data_carpeta['IdGrupoParametro']= ID_GRUPO_CARPETA_SUNAT;
				$DatosCarpeta = $this->CI->sParametroSistema->ObtenerParametroSistemaPorIdGrupoCarpeta($data_carpeta);
				$nombre = "menu-".$data["NombreUsuario"];
				$ruta = APP_PATH.$DatosCarpeta["RUTA_CARPETA_MENU"].$nombre.".json";
				$resultado2=$this->CI->archivo->EliminarArchivo($ruta);
				
				$this->CI->db->trans_commit();
				return $resultado;
			}
			else {
			  $this->CI->db->trans_rollback();
			  return $resultado;
			}
		  } catch (Exception $e) {
			$this->CI->db->trans_rollback();
			return $e->getMessage();
		  }
	}

}
