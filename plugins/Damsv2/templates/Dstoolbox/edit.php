<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dstoolbox $dstoolbox
 */
$this->Breadcrumbs->add([
        [
            'title' => 'Home', 
            'url' => ['controller' => 'Home', 'action' => 'home'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'Toolbox', 
            'url' => ['controller' => 'dstoolbox', 'action' => 'index'],
            'options' => [
                'class' => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id' => 'the-inv-crumb'
                ]
            ]
        ],
        [
            'title' => 'Edit form', 
            'url' => ['controller' => 'dstoolbox', 'action' => 'edit', $dstoolbox->dstoolbox_id],
            'options' => [
                'class' => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id' => 'the-port-crumb'
                ]
            ]
        ]
    ]);
?>
<div class="row ml-3">
    <div class="column-responsive column-80">
      
            <?= $this->Form->create($dstoolbox, ['enctype' => 'multipart/form-data']) ?>
            <fieldset>
                <legend>Import project</legend>
                <?php
                    $existing_file = !empty($dstoolbox->filename) ? 'File : '.$dstoolbox->filename : 'File';
                    echo $this->Form->control('name', ['type'=>'text', 'class'=>'form-control mb-3','required'=>true]);
                    echo $this->Form->control('description',['type' => 'textarea','class'=>'form-control mb-3']);
                    echo $this->Form->control('filename_temp',['type' => 'file','label' =>$existing_file,'class' =>'form-control-file mb-3']);
                    echo $this->Form->control('BO_url', ['type' => 'url','label' =>'URL to BO','class'=>'form-control mb-3']);
                   
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
       
    </div>
</div>
