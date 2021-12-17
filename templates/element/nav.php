<?php
use Cake\Core\Configure;

$c_name = $this->request->getParam('controller');
$a_name = $this->request->getParam('action');

$bgcolor = Configure::read('SSO.bgcolor');
?>

<nav class="sb-topnav navbar navbar-expand-lg navbar-light navbar-light <?= $bgcolor ?>">

    <div class="order-0 order-md-0">
        <button class="btn ml-1" id="sidebarToggle" href="#">
            <span class="navbar-toggler-icon p-2"></span>
        </button>
        <!--  Brand -->
        <a class="navbar-brand" href="/"><img src="/img/logo_eif.jpg" alt="EIF"> </a>

    </div>

    <div class="order-1 ml-5 d-none d-lg-block">
        <div class="collapse navbar-collapse" id="dropmenu">
            <ul class="navbar-nav ml-5">
                <li class="nav-item dropdown">
                    <a class="nav-link" href="#" id="navbarDropdownRestricted" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-lock"></i> Applications <i class="fas fa-angle-down"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownRestricted">
<?php $separator = false;
      if ($perm->hasRead(['plugin'=>'Damsv2', 'controller' => 'Home', 'action' => 'home'])) { 
        $separator = true; ?>
                        <a class="dropdown-item" href="/damsv2">Dams</a>
<?php } ?>
<?php if ($perm->hasRead(['plugin'=>'Treasury', 'controller' => 'Home', 'action' => 'home'])) { 
         if ($separator) { ?>
                                 <div class="dropdown-divider"></div>
         <?php }  
        $separator = true; ?>
                        <a class="dropdown-item" href="/treasury">Treasury</a>
<?php } ?>
<?php if ($perm->hasRead(['plugin'=>'DSR', 'controller' => 'Home', 'action' => 'home'])) {
         if ($separator) { ?>
                                 <div class="dropdown-divider"></div>
         <?php }  
        $separator = true; ?>
                        <a class="dropdown-item" href="/dsr">DSR</a>
<?php } ?>
<?php if ($perm->hasRead(['plugin'=>'Securitisation', 'controller' => 'Home', 'action' => 'home'])) {
         if ($separator) { ?>
                                 <div class="dropdown-divider"></div>
         <?php }  
        $separator = true; ?>
                        <a class="dropdown-item" href="/securitisation">Securitisation</a>
<?php } ?>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="order-2 ml-5 d-none d-lg-block">
        <ul class="navbar-nav">
            <li>
                <a class="nav-link" href="mailto:eifsas-support@eif.org"><i class="fas fa-envelope"></i> Contact</a>
            </li>
        </ul>
    </div>

    <div class="ml-auto order-3 d-none d-lg-block">
        <div class="collapse navbar-collapse" id="user">
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link mr-2" href="#" id="navbarDropdownProfile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <div class="sb-nav-link-icon">
                            <i class="fas fa-user"></i> <?= $this->Identity->get('first_name')?> <?= strtoupper($this->Identity->get('last_name'))?>&nbsp;&nbsp;<i class="fas fa-angle-down"></i>
                        </div>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="/user-mgmt/user/profile">Profile</a>

                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="/logout">Log out</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>

