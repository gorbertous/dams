<?php
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'List',
        'url'     => ['controller' => 'external', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
]);
?>
<h3>External Data Upload</h3>
<hr>
<?php if (empty($sasResult)): ?>
    <div class="row ml-3">
        <div class="column-responsive column-80">

            <?= $this->Form->create(null, ['enctype' => 'multipart/form-data']) ?>

            <?= $this->Form->control('filename_temp',['type' => 'file','label' =>'File','class' =>'form-control-file mb-3']);?>

            <?= $this->Form->button(__('Upload Data'),['class'=>'btn btn-primary my-3']); ?>

            <?= $this->Form->end() ?>

        </div>
    </div>
<?php else : ?>
    <?php
        echo "<h3>Result</h3>";
        echo $sasResult;
        echo "<br />";
        echo $this->Html->link('Ok', '/damsv2/external/index', array('class' => 'btn btn-primary'));
    ?>
<?php endif; ?>

