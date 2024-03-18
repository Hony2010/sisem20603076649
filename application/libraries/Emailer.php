<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require "PHPMailer/PHPMailerAutoload.php";

class Emailer {

        public $CI;
        private $mail;
        private $HostSMTP;
        private $UsuarioSMTP;
        private $ClaveSMTP;
        private $PuertoSMTP;
        private $EmailEmpresa;
        private $RazonSocial;

        function __construct()
        {
            //parent::__construct();
            /*Additional code which you want to run automatically in every function call */
            if (!isset($this->CI))
            {
                $this->CI =& get_instance();
            }

            $this->CI->load->service('Configuracion/General/sEmpresa');

        }

        function initialize($config)
        {
      	    $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();// Set mailer to use SMTP
            $this->mail->Host = $config["smtp_host"];  // Specify main and backup SMTP servers
            $this->mail->SMTPAuth = true;                               // Enable SMTP authentication
            $this->mail->Username = $config["smtp_user"]; // SMTP username
            $this->mail->Password = $config["smtp_pass"]; // SMTP password
            $this->mail->SMTPSecure = $config['smtp_crypto'];// Enable TLS encryption, `ssl` also accepted
            $this->mail->Port = $config['smtp_port']; // TCP port to connect to
            $this->mail->isHTML( $config['smtp_mailtype'] == 'html' ? true : false);// Set email format to HTML
            $this->mail->SMTPOptions = $config['smtp_options'];

            if($config['email_from_mail'] == "" || $config['email_from_mail'] == null)
            {}
            else {
              $this->mail->setFrom($config['email_from_mail'],$config['email_from_name']);
            }
        }

        function send_mail($title, $body, $recipient_email, $recipient_name,$adjunto = null)
        {
          try {
            $this->IniciarParams();

            if($this->EmailEmpresa == "" || $this->EmailEmpresa == null)
            {
              throw new Exception("Por favor agregue un email a su empresa para poder enviar el correo.", 1);
            }

            $this->mail->Subject = $title;
            $this->mail->Body    = $body;

            $this->mail->addAddress($recipient_email, $recipient_name); // Add a recipient

            if ($adjunto != null)
            {
              foreach ($adjunto as $key => $value) {
                $this->mail->AddAttachment($value['archivo']);
                // $this->mail->AddEmbeddedImage($adjunto['image'],$adjunto['filename']);
              }
            }

            return $this->mail->send();
          } catch (phpmailerException  $e) {
            return $e->getMessage();
          }

        }

        public function IniciarParams()
        {
          try {
            $this->mail = new PHPMailer(true);

            $data_documento["IdEmpresa"] = ID_EMPRESA;
            $DatosEmpresa = $this->CI->sEmpresa->ListarEmpresas($data_documento)[0];

            $this->HostSMTP = $DatosEmpresa["HostSMTP"];
            $this->UsuarioSMTP = $DatosEmpresa["UsuarioSMTP"];
            $this->ClaveSMTP = $DatosEmpresa["ClaveSMTP"];
            $this->PuertoSMTP = $DatosEmpresa["PuertoSMTP"];
            $this->EmailEmpresa = $DatosEmpresa["EmailEmpresa"];
            $this->RazonSocial = $DatosEmpresa["RazonSocial"];

            $config['smtp_host'] = $this->HostSMTP;//'mail.grupoartamis.com';//
            $config['smtp_user'] = $this->UsuarioSMTP;
            $config['smtp_pass'] = $this->ClaveSMTP;
            $config['smtp_port'] = $this->PuertoSMTP;
            $config['smtp_timeout'] = 10;
            $config['smtp_keepalive'] = false;
            $config['smtp_crypto'] = 'ssl';
            $config['smtp_mailtype'] = 'html';
            $config['smtp_options'] =  ['ssl' => [
                                            'verify_peer' => false,
                                            'verify_peer_name' => false,
                                            'allow_self_signed' => true
                                            ]
                                        ];
            $config['email_from_mail'] = $this->EmailEmpresa;
            $config['email_from_name'] = $this->RazonSocial;

            $this->initialize($config);
          } catch (Exception $e) {
            return $e->getMessage();
          }

        }
}
