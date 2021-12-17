<?php
$cakeDescription = 'DAMSV2';
?>
<!DOCTYPE html>
<html>
    <head>
        <?= $this->Html->charset() ?>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>
            <?= $cakeDescription ?>:
            <?= $this->fetch('title') ?>
        </title>
        <?= $this->Html->meta('icon') ?>
        <?= $this->Html->meta('csrfToken', $this->request->getAttribute('csrfToken')) ?>
        <!-- fontawesome bundle loaded locally from the webfonts directory -->
        <?= $this->Html->css(['fontawesome.min', 'fonts_google_api', 'bootstrap.min', 'jquery-ui', 'site']) ?>

        <?= $this->fetch('meta') ?>
        <?= $this->fetch('css') ?>


        <?= $this->Html->script(['jquery.min', 'bootstrap.bundle.min', 'jquery-ui', 'autonumeric']) ?>
        <?= $this->fetch('script') ?>

    </head>
    <body class="sb-nav-fixed">


        <!-- this is header -->

        <div id="layoutSidenav">

            <!-- Navbar -->
            <?= $this->element('nav'); ?>


            <div id="layoutSidenav_content">
                <!-- Breadcrumbs -->
                <?php
                $this->Breadcrumbs->setTemplates([
                    'wrapper' => '<nav aria-label="breadcrumb"><ol class="breadcrumb" {{attrs}}>{{content}}</ol></nav>',
                    'item'    => '<li {{attrs}}><a href="{{url}}"{{innerAttrs}}>{{title}}</a></li>{{separator}}',
                ]);
                echo $this->Breadcrumbs->render();
                ?>

                <main style="height:100%;">

                    <div class="container-fluid float-left py-1">

                        <!-- Left menu navigation -->
                        <?= $this->element('left'); ?>

                        <div class="d-flex">

                            <!-- Main -->

                            <div class="p-2 flex-fill">

                                <?= $this->Flash->render() ?>
                                <?= $this->fetch('content') ?>

                            </div>

                        </div>
                </main>

                <!-- Footer -->
                <?= $this->element('footer'); ?>

            </div>


        </div>

        <?= $this->Html->script(['client']) ?>
        <?= $this->fetch('script') ?>
        <?= $this->fetch('scriptBottom') ?>
    </body>
</html>
