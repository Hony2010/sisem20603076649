<?php
require_once(TOMCAT_PATH."webapps/JavaBridge/java/Java.inc");
defined('BASEPATH') OR exit('No direct script access allowed');

class JavaBridge {

  public $_session = null;

  public function IniciarJava($argumento, $argumento2 = null, $argumento3 = null)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_java=$this->_session->get("sisem_java"))) {
         $this->_session->put("sisem_java", $sisem_java=new Java("com.sisem.GenerarDocumentosServiceImpl"));
       }

       if(java_is_null($sisem_report_java=$this->_session->get("sisem_report_java"))) {
         $this->_session->put("sisem_report_java",$sisem_report_java=new Java("com.sisem.ReporteDocumentosServiceImpl"));
       }

       //PRUEBAS
       if(java_is_null($sisem_validarCertificado=$this->_session->get("com.sisemperu.sunat.Certificado"))) {
         $this->_session->put("com.sisemperu.sunat.Certificado",$sisem_validarCertificado=new Java("com.sisemperu.sunat.Certificado",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarCertificado($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_Certificado=$this->_session->get("com.sisemperu.sunat.Certificado"))) {
         $this->_session->put("com.sisemperu.sunat.Certificado",$sisem_Certificado=new Java("com.sisemperu.sunat.Certificado", $argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  /*public function IniciarArchivoPlano($argumento, $argumento2 = null, $argumento3 = null)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_archivoPlano=$this->_session->get("com.sisemperu.sunat.archivoplano.ArchivoPlano"))) {
         $this->_session->put("com.sisemperu.sunat.archivoplano.ArchivoPlano",$sisem_archivoPlano=new Java("com.sisemperu.sunat.archivoplano.ArchivoPlano", $argumento, $argumento2, $argumento3));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }*/

  public function IniciarArchivoJSON($argumento)
  {
    try{
       if($this->checkJavaExtension() )
       {
         $this->_session = java_session();

         if(java_is_null($sisem_archivoJSON=$this->_session->get("com.sisemperu.sunat.ArchivoJSON"))) {
           $this->_session->put("com.sisemperu.sunat.ArchivoJSON",$sisem_archivoJSON=new Java("com.sisemperu.sunat.ArchivoJSON", $argumento));
         }

         return $this->_session;
      } else {
        // ERROR loading Java Extension
        echo "ERROR loading Java Extension";
      }
    }
    catch (JavaException $ex) {
      $trace = new Java('java.io.ByteArrayOutputStream');
      $ex->printStackTrace(new Java('java.io.PrintStream', $trace));
      print nl2br("java stack trace: $trace\n");
      //exit;
    }
  }

  public function Destruir()
  {
    $this->_session->destroy();
  }

  public function IniciarTemplateManager($argumento, $argumento2)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_templateManager=$this->_session->get("com.sisemperu.sunat.TemplateManager"))) {
         $this->_session->put("com.sisemperu.sunat.TemplateManager",$sisem_templateManager=new Java("com.sisemperu.sunat.TemplateManager",$argumento, $argumento2));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarXMLSchema($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_XMLSchema=$this->_session->get("com.sisemperu.sunat.XMLSchema"))) {
         $this->_session->put("com.sisemperu.sunat.XMLSchema",$sisem_XMLSchema=new Java("com.sisemperu.sunat.XMLSchema",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarXMLTransformer($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_XMLTransformer=$this->_session->get("com.sisemperu.sunat.XMLTransformer"))) {
         $this->_session->put("com.sisemperu.sunat.XMLTransformer",$sisem_XMLTransformer=new Java("com.sisemperu.sunat.XMLTransformer",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarXMLSignaturer($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_XMLSignaturer=$this->_session->get("com.sisemperu.sunat.XMLSignaturer"))) {
         $this->_session->put("com.sisemperu.sunat.XMLSignaturer",$sisem_XMLSignaturer=new Java("com.sisemperu.sunat.XMLSignaturer",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarZip($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_Zip=$this->_session->get("com.sisemperu.sunat.Zip"))) {
         $this->_session->put("com.sisemperu.sunat.Zip",$sisem_Zip=new Java("com.sisemperu.sunat.Zip",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }


  public function IniciarDOMXMLSignature($argumento, $argumento2)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_DOMXMLSignature=$this->_session->get("com.sisemperu.sunat.DOMXMLSignature"))) {
         $this->_session->put("com.sisemperu.sunat.DOMXMLSignature",$sisem_DOMXMLSignature=new Java("com.sisemperu.sunat.DOMXMLSignature",$argumento, $argumento2));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarJRLoader()
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_JRLoader=$this->_session->get("net.sf.jasperreports.engine.util.JRLoader"))) {
         $this->_session->put("net.sf.jasperreports.engine.util.JRLoader",$sisem_JRLoader=new Java("net.sf.jasperreports.engine.util.JRLoader"));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarJRXmlUtils()
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_JRXmlUtils=$this->_session->get("net.sf.jasperreports.engine.util.JRXmlUtils"))) {
         $this->_session->put("net.sf.jasperreports.engine.util.JRXmlUtils",$sisem_JRXmlUtils=new Java("net.sf.jasperreports.engine.util.JRXmlUtils"));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarQRCoder($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_QRCoder=$this->_session->get("com.sisemperu.sunat.QRCoder"))) {
         $this->_session->put("com.sisemperu.sunat.QRCoder",$sisem_QRCoder=new Java("com.sisemperu.sunat.QRCoder",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }

  public function IniciarReportManager($argumento)
  {

     if($this->checkJavaExtension() )
     {
       $this->_session = java_session();

       if(java_is_null($sisem_ReportManager=$this->_session->get("com.sisemperu.sunat.ReportManager"))) {
         $this->_session->put("com.sisemperu.sunat.ReportManager",$sisem_ReportManager=new Java("com.sisemperu.sunat.ReportManager",$argumento));
       }

       return $this->_session;
    } else {
      // ERROR loading Java Extension
      echo "ERROR loading Java Extension";
    }
  }


  function checkJavaExtension() {
         $java_bridge_lib = RUTA_LIBRERIA_REPORTES_TOMCAT_JAVA_INC;

         if(!extension_loaded('java')) {
             $sapi_type = php_sapi_name();
             //$port = (isset($_SERVER['SERVER_PORT']) && (($_SERVER['SERVER_PORT'])>1024)) ? $_SERVER['SERVER_PORT'] : '8080';
             if ($sapi_type == "cgi" || $sapi_type == "cgi-fcgi" || $sapi_type == "cli")    {
                 if(!(PHP_SHLIB_SUFFIX=="so" &&
                     @dl('java.so')) &&
                     !(PHP_SHLIB_SUFFIX=="dll" &&
                     @dl('php_java.dll')) &&
                     !(@include_once($java_bridge_lib)) &&
                     !(require_once($java_bridge_lib)))
                 {
                     echo "java extension not installed.";
                     return false;
                 }
             } else {
                 if(!(@include_once($java_bridge_lib))) {
                     require_once($java_bridge_lib);
                 }
             }
         }

         if(!function_exists("java_get_server_name"))
         {
             echo "The loaded java extension is not the PHP/Java Bridge";
             return false;
         }
         return true;
     }
}
