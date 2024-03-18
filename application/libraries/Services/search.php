<?php

namespace Search;

class Search {
    public function ruc($number)
    {
        $result = $this->get($number, 'ruc');

        if (isset($result->nombre)) {
            $pdd = $result->distrito . ' - ' . $result->provincia . ' - ' . $result->departamento;
            return [
                'result' => [
                    "RazonSocial" => $result->nombre,
                    "EstadoContribuyente" => $result->estado,
                    "CondicionContribuyente" => $result->condicion,
                    "Direccion" => $result->direccion . trim($result->distrito != '' ? $pdd : ''),
                    "TipoPersona" => substr($result->numeroDocumento,0,2) == 20 ? 1 : 2,
                ],
                'success' => true
            ];
        }

        return [
            'success' => false,
            'message' => $result->error
        ];
    }
    
    public function dni($number)
    {
        $result = $this->get($number, 'dni');
        if (isset($result->nombre)) {

            return[
                'result' => [
                    "RazonSocial" => $result->nombre,
                    "EstadoContribuyente" => $result->estado,
                    "CondicionContribuyente" => $result->condicion,
                    "Direccion" => $result->direccion,
                    "TipoPersona" => 2,
                ],
                'success' => true
            ];
        }

        return [
            'success' => false,
            'message' => isset($result->error) ? $result->error : "DNI invalido" 
        ];
    }

    public function get($number, $type)
    {
        $token = 'apis-token-1.aTSI1U7KEuT-6bbbCguH-4Y8TI6KS73N';

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://api.apis.net.pe/v1/'.$type.'?numero=' . $number,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
            'Authorization: Bearer ' . $token
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        return json_decode($response);
    }
}

