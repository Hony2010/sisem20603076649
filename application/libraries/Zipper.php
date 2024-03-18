<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Zipper {

  public function LeerZIPXML($data)
  {
    $path = $data["Destino"];
    $zip = $data["UbicacionZIP"];
    $file = $data["File"];

    //Creamos un objeto de la clase ZipArchive()
    $enzipado = new ZipArchive();

    //Abrimos el archivo a descomprimir
    $enzipado->open($zip);
    $data2 = $enzipado->getFromName($file);

    //Extraemos el contenido del archivo dentro de la carpeta especificada
    $extraido = $enzipado->extractTo($path);
    //Si el archivo se extrajo correctamente listamos los nombres de los
     //archivos que contenia de lo contrario mostramos un mensaje de error

    $simple = file_get_contents($path.$file);
    $p = xml_parser_create();
    xml_parse_into_struct($p, $simple, $vals, $index);
    xml_parser_free($p);

    if(array_key_exists("cbc:ResponseCode",$index)) {
      $indice_codigo = $index["cbc:ResponseCode"][0];
      $value_codigo= $vals[$indice_codigo]["value"];
      $indice_descripcion = $index["cbc:Description"][0];
      $value_descripcion = $vals[$indice_descripcion]["value"];  
    }
    else {
      $indice_codigo = $index["CBC:RESPONSECODE"][0];
      $value_codigo= $vals[$indice_codigo]["value"];
      $indice_descripcion = $index["CBC:DESCRIPTION"][0];
      $value_descripcion = $vals[$indice_descripcion]["value"];  
    }

    $response["CodigoRespuesta"] = $value_codigo;
    $response["MensajeRespuesta"] = $value_descripcion;
    unlink($path.$file);

    return $response;
  }

  public function ExtraerXML($data)
  {
    $path = $data["Destino"];
    $zip = $data["UbicacionZIP"];
    $file = $data["File"];

    //Creamos un objeto de la clase ZipArchive()
    $enzipado = new ZipArchive();

    //Abrimos el archivo a descomprimir
    $enzipado->open($zip);
    // $data2 = $enzipado->getFromName($file);

    //Extraemos el contenido del archivo dentro de la carpeta especificada
    $extraido = $enzipado->extractTo($path);
    //Si el archivo se extrajo correctamente listamos los nombres de los
     //archivos que contenia de lo contrario mostramos un mensaje de error
     // echo $path;
    return $data;
  }

  public function ZipearArchivos($name, $files)
  {
    $zip = new ZipArchive();
    $filename = $name;

    if($zip->open($filename, ZIPARCHIVE::CREATE)===true)
    {
      if(is_array($files))
      {
        if(count($files)>0)
        {
          foreach ($files as $key => $value) {
            $zip->addFile($value);
          }
        }
      }
        $zip->renameName('currentname.txt','newname.txt');
        $zip->close();
        return 'Se ha creado correactamente el archivo';
    }
    else {
      // code...
      return 'Error creando el archivo ZIP';
    }
  }

}
