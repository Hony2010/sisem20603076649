<nav class="navbar navbar-static-top header-navbar">
  <div class="header-navbar-mobile">
    <div class="header-navbar-mobile__menu">
      <button id="btn-mobile" class="btn btn-default" type="button"><i class="fa fa-bars"></i></button>
    </div>
    <div class="header-navbar-mobile__title"><span id="opciones-mobile">Opciones</span><i class="caret"></i></div>
    <div class="header-navbar-mobile__settings dropdown">
      <a id="Logout" class="btn btn-default dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
          <i class="fa fa-power-off"></i>
      </a>
    </div>
  </div>
  <div class="navbar-header">
    <a class="navbar-brand" id="LogoSistema" href="<?php echo base_url()?>Index.php/cDashBoard/">
      <div class="logo text-nowrap">
        <div class="logo__img">
          <i class="fa fa-chevron-right"></i>
        </div>
        <div class="logo__text">
          <div class="logo__text_nombre">
            <?php echo $this->session->userdata("Empresa_".LICENCIA_EMPRESA_RUC)["RazonSocial"];?>
          </div>
          <div class="logo__text_ruc">
            <?php echo 'RUC: '.$this->session->userdata("Empresa_".LICENCIA_EMPRESA_RUC)["CodigoEmpresa"];?>
          </div>
        </div>
      </div>
    </a>
  </div>
  <div class="topnavbar topnavbar_size">
    <div class="datalist__result">
      <ul id="menu" class="nav navhead nav-tabs" role="tablist">
        <!-- <li class="active" role="presentation">
          <a href="#catalogos" rel="catalogos" class="option-menu-tabs navtabs headnav" role="tab" data-toggle="tab" name="catalogos"> Catálogos </a>
        </li> -->
        <li class="dropdown btn-off"><a id="Logout" class="navtabs headnav dropdown-toggle" href="" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><i class="fa fa-power-off"></i></a>
        </li>
      </ul>
      <div class="tab-content tabnavcont">
        <div class="tab-pane active" id="catalogos" role="tabpanel">
          <nav id="chapternav" class="chapternav" >
            <div class="chapternav-wrapper dropdownmenu">
              <ul id="opcion-menu-tabs" class="chapternav-items">
                <li class="chapternav-item ">
                  <a  class="opcion-item-tabs chapternav-link" href="#">
                    <figure class="chapternav-icon">
                      <div class="icono-default"></div>
                    </figure>
                    <div class="text-default"></div>
                  </a>
                </li>
                <li class="chapternav-item ">
                  <a  class="opcion-item-tabs chapternav-link" href="#">
                    <figure class="chapternav-icon">
                      <div class="icono-default"></div>
                    </figure>
                    <div class="text-default"></div>
                  </a>
                </li>
                <li class="chapternav-item ">
                  <a  class="opcion-item-tabs chapternav-link" href="#">
                    <figure class="chapternav-icon">
                      <div class="icono-default"></div>
                    </figure>
                    <div class="text-default"></div>
                  </a>
                </li>
                <li class="chapternav-item ">
                  <a  class="opcion-item-tabs chapternav-link" href="#">
                    <figure class="chapternav-icon">
                      <div class="icono-default"></div>
                    </figure>
                    <div class="text-default"></div>
                  </a>
                </li>
                <li class="chapternav-item ">
                  <a  class="opcion-item-tabs chapternav-link" href="#">
                    <figure class="chapternav-icon">
                      <div class="icono-default"></div>
                    </figure>
                    <div class="text-default"></div>
                  </a>
                </li>
              </ul>
		        </div>
	        </nav>
        </div>
      </div>
    </div>
  </div>
</nav>
