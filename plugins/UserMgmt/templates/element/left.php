<div id="layoutSidenav_nav" class="mb-5">
    <nav class="sb-sidenav accordion sb-sidenav-light elevation-4">
        <div class="sb-sidenav-menu">
            <div class="nav">
                <a class="nav-link" href="/user-mgmt/user/profile" id="profile">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-dark"></i>
                    </div> Profile
                </a>
				<?php if($perm->hasWrite(array('controller' => 'User', 'action' => 'index'))){ ?>
                <a class="nav-link" href="/user-mgmt/user/index" id="users">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-dark"></i>
                    </div> Users
                </a>
                <a class="nav-link" href="/user-mgmt/user/groups" id="groups">
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-dark"></i>
                    </div> Profiles
                </a>
                <a class="nav-link" id="permissions">
				<!-- href="/user-mgmt/user/permissions"  -->
                    <div class="sb-nav-link-icon">
                        <i class="fas fa-home text-dark"></i>
                    </div> Permissions
                </a>
				<?php } ?>
            </div>
        </div>
    </nav>
</div>

