<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rule[]|\Cake\Collection\CollectionInterface $rules
 */
if (!$this->request->is('ajax')) {
    $this->Breadcrumbs->add([
        [
            'title'   => 'Home',
            'url'     => ['controller' => 'Home', 'action' => 'home'],
            'options' => ['class' => 'breadcrumb-item']
        ],
        [
            'title'   => 'Rules Configuration',
            'url'     => ['controller' => 'Rules', 'action' => 'index'],
            'options' => [
                'class'      => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id'    => 'the-port-crumb'
                ]
            ]
        ]
    ]);
?>
<div class="rules index content">
   
    <?php if ($perm->hasWrite(array('controller' => 'Rules', 'action' => 'export'))) { ?><button type="button" class="btn btn-secondary float-right mr-5 my-2" id="export_data"><i class="fas fa-file-export"></i> &nbsp; Export to XLS</button><?php } ?>
    
    <?= ($perm->hasWrite(array('controller' => 'Rules', 'action' => 'copy'))&&$perm->hasWrite(array('controller' => 'Rules', 'action' => 'add')))?$this->Html->link(__('Copy Rule'), ['action' => 'copy'], ['class' => 'btn btn-primary float-right  mr-2 my-2', ]):"&nbsp;" ?>
    <?= $perm->hasWrite(array('controller' => 'Rules', 'action' => 'add'))?$this->Html->link(__('New Rule'), ['action' => 'add'], ['class' => 'btn btn-primary float-right mr-2 my-2', ]):"&nbsp;" ?>

    <h3><?= __('Rules Configuration') ?></h3>
   
    <?= $this->Form->create(null, ['class' => 'formAjax', 'id' => 'filters']) ?>
    <div class="row col-12 form-inline">
        
        <?= $this->Form->hidden('visible', ['value' => 1]) ?>
        <?= $this->Form->control('rule_name', ['label' => false, 'placeholder' => 'Rule Name', 'value' => $session->read('Form.data.brules.rule_name'), 'class' => 'form-control mr-2 my-2 filters']) ?>
        <?= $this->Form->select('template_type', $template_types,
            [
                'empty' => '-- Any Template --',
                'class' => 'form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.template_type'),
                'id'    => 'template_type'
            ]
        );
        ?>
        <?= $this->Form->select('rule_level', $rulelevels,
            [
                'empty' => '-- Any Level --',
                'class' => 'form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.rule_level'),
                'id'    => 'rule_level'
            ]
        );
        ?>
        <?= $this->Form->select('category_id', $categories,
            [
                'empty' => '-- Any Category --',
                'class' => 'form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.category_id'),
                'id'    => 'category_id'
            ]
        );
        ?>
        <?= $this->Form->select('is_warning', ['Y' => 'YES', 'N' => 'NO'],
            [
                'empty' => '-- Any Warning --',
                'class' => 'form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.is_warning'),
                'id'    => 'iswarning'
            ]
        );
        ?>
        
    </div>
    <div class="row col-12 form-inline">
        <?= $this->Form->select('product_id', $products,
            [
                'empty' => '-- Any product --',
                'class' => 'form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.product_id'),
                'id'    => 'product_id'
            ]
        );
        ?>       
        <?= $this->Form->select('mandate_id', $mandates,
            [
                'empty' => '-- Any mandate --',
                'class' => 'form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.mandate_id'),
                'id'    => 'mandate_id'
            ]
        );
        ?>
        <?= $this->Form->select('portfolio_id', $portfolios,
            [
                'empty' => '-- Any portfolio --',
                'class' => 'w-25 form-control mr-2 my-2 filters',
                'value' => $session->read('Form.data.brules.portfolio_id'),
                'id'    => 'portfolio_id'
            ]
        );
        ?>
        <?= $this->Html->link('Reset', ['controller' => 'invoice', 'action' => 'reset-filters'], ['class' => 'btn btn-secondary ml-2 my-2', 'id' => 'reset']) ?>
    </div>
    <?= $this->Form->end(); ?>
    <div id="filters_data">
    <?php } ?>
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?= $this->Paginator->sort('rule_id', '#') ?></th>
                        <th><?= $this->Paginator->sort('rule_number', __d('rules', 'ID')) ?></th>
                        <th><?= $this->Paginator->sort('rule_name', __d('rules', 'Name')) ?></th>
                        <th>
                            <?= $this->Paginator->sort('rule_level', __d('rules', 'Level')) ?><br>
                            <?= $this->Paginator->sort('rule_category', __d('rules', 'Category')) ?>
                        </th>
                        <th><?= $this->Paginator->sort('product_id', __d('rules', 'Product')) ?></th>
                        <th><?= $this->Paginator->sort('mandate_id', __d('rules', 'Mandate')) ?></th>
                        <th><?= $this->Paginator->sort('portfolio_id', __d('rules', 'Portfolio')) ?></th>
                        <th><?= $this->Paginator->sort('is_warning', __d('rules', 'Warning')) ?></th>
                        <th>
                            <?= $this->Paginator->sort('checked_entity', __d('rules', 'Entity')) ?><br>
                            <?= $this->Paginator->sort('checked_field', __d('rules', 'Field')) ?>
                        </th>
                        <th><?= $this->Paginator->sort('operator', __d('rules', 'Operator')) ?></th>
                        <th>
                            <?= $this->Paginator->sort('param_1_value', __d('rules', 'Param 1')) ?><br>
                            <?= $this->Paginator->sort('param_2_value', __d('rules', 'Param 2')) ?>
                        </th>
                        <th class="actions"><?= __('Actions') ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rules as $rule): ?>
                    <tr>
                        <td><?= $rule->rule_id ?></td>
                        <td><?= h($rule->rule_number) ?></td>
                        <td><?= h($rule->rule_name) ?></td>
                        <td>
                            <?= h($rule->rule_level) ?><br>
                            <?= h($rule->rule_category) ?>
                        </td>
                        <td><?= h($rule->product?$rule->product->name:'-') ?></td>
                        <td><?= h($rule->mandate?$rule->mandate->mandate_name:'-') ?></td>
                        <td><?= h($rule->portfolio?$rule->portfolio->portfolio_name:'-') ?></td>
                        <td><?= h($rule->is_warning=='Y'?'YES':'NO') ?></td>
                        <td>
                            <?= h($rule->checked_entity) ?><br>
                            <?= h($rule->checked_field) ?>
                        </td>
                        <td><?= h($rule->operator) ?></td>
                        <td>
                            <?= h(implode(PHP_EOL, str_split($rule->param_1_value, 25))) ?><br>
                            <?= h(implode(PHP_EOL, str_split($rule->param_2_value, 25))) ?>
                        </td>
                        <td class="actions" style="display: flex;">
                            <?= $perm->hasRead(array('controller' => 'Rules', 'action' => 'edit'))?$this->Html->link('<i class="fas fa-pen"></i>',
                                        ['action' => 'edit', $rule->rule_id],
                                        ['escape' => false, 'title' => __('Edit'), 'class' => 'btn btn-info btn-xs']):"&nbsp;" ?>
                            <?= $perm->hasDelete(array('controller' => 'Rules', 'action' => 'delete'))?$this->Form->postLink('<i class="fas fa-trash-alt"></i>', ['action' => 'delete', $rule->rule_id],  ['method' => 'delete','escape' => false, 'title' => __('Delete'), 'class' => 'btn btn-danger btn-xs','confirm' => __('Are you sure you want to delete {0}?', $rule->rule_number)]):"&nbsp;" ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="paginator">
            <ul class="pagination">
                <?= $this->Paginator->first('<< ' . __('first')) ?>
                <?= $this->Paginator->prev('< ' . __('previous')) ?>
                <?= $this->Paginator->numbers() ?>
                <?= $this->Paginator->next(__('next') . ' >') ?>
                <?= $this->Paginator->last(__('last') . ' >>') ?>
            </ul>
            <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
        </div>
    <?php if (!$this->request->is('ajax')) { ?>
    </div>
</div>
<?php } ?>

<script>
function exportData()
{
    //ajax to generate the list
    var data = $("#filters").serialize();
    $.ajax({
            type: 'post',
            data: data,
            url: '/damsv2/rules/export',
            headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function(returndata)
            {
                //success message + download file
                window.open("/damsv2/ajax/download-file/"+returndata+"/export");
            }
    });
    return false;
}
$(document).ready(function () {
    $("#export_data").on("click", function (e) {
        e.preventDefault();
        exportData();
    });
});

if ( window.history.replaceState ) {
  window.history.replaceState( null, null, window.location.href );
}
</script>
