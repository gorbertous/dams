<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice[]|\Cake\Collection\CollectionInterface $invoices
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Invoices',
        'url'     => ['controller' => 'Invoice', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => 'Processing Form',
        'url'     => ['controller' => 'Invoice', 'action' => 'processing-form', $invoice_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);
?>
<h3>PDLR processing form</h3>
<hr>
<div class="row">
    <div class="col-12">
        <?php echo $result ?>
    </div>
</div>
<?php if (!empty($result)): ?>
<div class="row">
    <div class="col-6">
         <?= $this->Html->link(__('Export to PDF'), ['action' => 'pdf-processing', $invoice_id ],['class' => 'btn btn-secondary']) ?>
      
    </div>
</div>
<?php endif ?>
