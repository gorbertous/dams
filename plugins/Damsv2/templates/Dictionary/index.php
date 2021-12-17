<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dictionary[]|\Cake\Collection\CollectionInterface $dictionary
 */
if (!$this->request->is('ajax')) {
    $this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Dictionaries',
        'url'     => ['controller' => 'Dictionary', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-dict-crumb'
            ]
        ]
    ]
]);
?>

<h3><?= __('Dictionaries') ?></h3>
<?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline formAjax', 'id' => 'filters']) ?>
<?= $this->Form->control('Dictionary.id', ['type' => 'number', 'label' => false, 'placeholder' => 'ID', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('Dictionary.id'), 'id' => 'DictionaryId']) ?>

<?= $this->Form->control('Dictionary.name', ['label' => false, 'placeholder' => 'Name', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('Dictionary.name'), 'id' => 'DictionaryName']) ?>

<?= $this->Form->select('Dictionary.mandate', $mandates,
        [
            'empty' => '--Mandate--',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $this->request->getQuery('Dictionary.mandate'),
            'id'    => 'DictionaryMandate',
            'style' => 'width:200px',
        ]
);
?>    
<?= $this->Form->select('Dictionary.template', $templates,
        [
            'empty' => '-- Choose a template --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $this->request->getQuery('Dictionary.template'),
            'id'    => 'DictionaryTemplate',
            'disabled' => count($templates) == 0
        ]
);
?>
<?= $this->Form->select('Dictionary.field', $fields,
        [
            'empty' => '-- Choose a field --',
            'class' => 'form-control mr-2 my-2 filters',
            'value' => $this->request->getQuery('Dictionary.field'),
            'id'    => 'DictionaryTemplate',
            'disabled' => count($fields) == 0
        ]
);
?>
<?= $this->Form->reset('Reset', ['class' => 'btn btn-secondary py-2 my-2', 'id' => 'reset_filter']) ?>
<?= $this->Form->end() ?>
<div id="filters_data">
<?php } ?>
<div class="table-responsive" id="dico_list">
    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('dictionary_id',__('ID')) ?></th>
                <th><?= $this->Paginator->sort('name') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <?php if ($perm->hasRead('view')) { ?>
                <th class="actions"><?= __('Actions') ?></th>
                <?php } ?>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($dictionary as $dictionary): ?>
            <tr>
                <td><?= $this->Number->format($dictionary->dictionary_id) ?></td>
                <td><?= h($dictionary->name) ?></td>
                <td><?= !empty($dictionary->created) ? h($dictionary->created->format('Y-m-d H:i:s')) : '' ?></td>
                <td><?= !empty($dictionary->modified) ? h($dictionary->modified->format('Y-m-d H:i:s')) : '' ?></td>

                <?php if ($perm->hasRead('view')) { ?>
                <td class="actions">
                    <?= $this->Html->link(
                            '<i class="fas fa-eye"></i>' . __('View'),
                            ['action' => 'view', $dictionary->dictionary_id],
                            ['escape' => false, 'title' => __('View'), 'class' => 'btn btn-info btn-xs']
                    )
                    ?>
                </td>
                <?php } ?>
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
<div style="display:none;">
<?php
echo $this->Form->create(null, ['url' => '/damsv2/dictionary/get-names', 'id' => 'getnamesautocomplete']);
echo $this->Form->input('Dictionary.name', [
	'type'  => 'text',
	'label' => false,
	'div'   => false,
	'id'	=> 'getNames',
]);
echo $this->Form->end();
?>
</div>
<?php if (!$this->request->is('ajax')) { ?>
</div>
<script>
//reset the form function
function resetForm($form) {
    $form.find('input:text, select, textarea').val('');
    $form.find('input:radio, input:checkbox')
            .removeAttr('checked').removeAttr('selected');
}

$(document).ready(function () {
     $("#reset_filter").on("click", function (e) {
        e.preventDefault();
        resetForm($('#filters'));
        window.location.replace("/damsv2/dictionary");
    });
	
	/*$('#DictionaryName').keyup(function(e)
	{
		$('#getNames').val( $('#DictionaryName').val() );
		var data = $('#getnamesautocomplete').serialize();
		$.ajax({
			async: true,
			data: data,
			type: "POST",
			url: "/damsv2/dictionary/get-names",
			headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
			success: function (data) {
				$('#deal_id').replaceWith(data);
			}
		});
	});*/
});
</script>
<?php } ?>