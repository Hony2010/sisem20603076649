
<div class="scrollable scrollbar-macosx">
  <div class="sidebar__cont">
    <div class="sidebar__menu">
      <!-- <div class="contenido"> </div> -->
      <div class="users">
        <div class="div-photo">
          <center>
            <div class="user-photo">
              <!-- <img class="user-photo" src="<?php echo $this->session->userdata("Usuario")["RutaFoto"];?>" alt=""> -->
              <img class="user-photo" src="<?php echo base_url();?>/assets/img/usuarios.png" width="100" height="100" alt="">
            </div>
          </center>
        </div>
        <div class="user-data" align="center">
          <span><?php echo $this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)["NombreCompleto"];?></span>
        </div>
        <div class="user-data" align="center">
          <span><?php echo $this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)["ApellidoCompleto"];?></span>
        </div>
        <?php if ($this->session->userdata("Parametro_".LICENCIA_EMPRESA_RUC)["ParametroCaja"] == 1) :?>
        <div class="user-data" align="center">
          <span><?php echo 'Caja: '. NUMERO_CAJA ;?></span>
        </div>
        <div class="user-data" align="center">
          <span><?php echo $this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)["Turno"]["NombreTurno"].': '.$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)["Turno"]["HoraInicio"]. ' - ' .$this->session->userdata("Usuario_".LICENCIA_EMPRESA_RUC)["Turno"]["HoraFin"];?></span>
        </div>
        <?php endif;?>
      </div>
      <ul id="opcion-left" class="nav nav-menu">
        <div class="col-md-12">
          <div id="FacturasPendienteEnvio" class="text-center hide">
            <span>Usted tiene</span>
            <h2 class="link-url accesos_menu" data-url='<?php 
                if(isset($dataInicio))
                  echo base_url()."Index.php/FacturacionElectronica/cEnvioFactura/Index?Data=".json_encode($dataInicio["data"]["FacturacionElectronica"]["RangoFecha"]);
              ?>'>
              <span id="cantidadFacturaPendienteEnvio">0</span>
            </h2>
            <span id="MsgFacturas"></span>
            <span id="MsgDias"></span>
          </div>
        </div>
      </ul>
    </div>
  </div>
</div>

<style media="screen">
  .link-url{
    margin: auto;
    cursor: pointer;
  }
</style>
