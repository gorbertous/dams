<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Dictionary $dictionary
 */

//dd($dictionaryValues);
if(!$this->request->is('ajax')) {
 $this->Breadcrumbs->add([
        [
            'title' => 'Home', 
            'url' => ['controller' => 'Home', 'action' => 'home'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => 'Dictionaries', 
            'url' => ['controller' => 'Dictionary', 'action' => 'index'],
            'options' => ['class'=> 'breadcrumb-item']
        ],
        [
            'title' => $dictionary->name, 
            'url' => ['controller' => 'Dictionary', 'action' => 'view', $dictionary->dictionary_id],
            'options' => [
                'class' => 'breadcrumb-item active',
                'innerAttrs' => [
                    'class' => 'test-list-class',
                    'id' => 'the-dict-crumb'
                ]
            ]
        ]
    ]);
?>
<h3><?= 'Dictionary '. $dictionary->name . ' (id '. $dictionary->dictionary_id.')'  ?></h3>
<?= $this->Form->create(null, ['type' => 'get', 'class' => 'form-inline formAjax', 'id' => 'filters']) ?>
<?= $this->Form->control('Dictionary.id', ['type' => 'number', 'label' => false, 'placeholder' => 'ID', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('Dictionary.id'), 'id' => 'DictionaryId']) ?>
<?= $this->Form->control('Dictionary.code', ['label' => false, 'placeholder' => 'Code', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('Dictionary.code'), 'id' => 'DictionaryCode']) ?>
<?= $this->Form->control('Dictionary.translation', ['label' => false, 'placeholder' => 'Translation', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('Dictionary.translation'), 'id' => 'DictionaryTranslation']) ?>
<?= $this->Form->control('Dictionary.label', ['label' => false, 'placeholder' => 'Label', 'class' => 'form-control mr-2 my-2 filtersLive', 'value' => $this->request->getQuery('Dictionary.label'), 'id' => 'DictionaryLabel']) ?>

<?= $this->Form->reset('Reset', ['class' => 'btn btn-secondary py-2 my-2', 'id' => 'reset_filter']) ?>
<?= $perm->hasRead('export')?$this->Html->link(__('Export'), ['action' => 'export',
                                     $dictionary->dictionary_id,
                                     '?' => ['Dictionary.id' => $this->request->getQuery('Dictionary.id'),
                                             'Dictionary.code' => $this->request->getQuery('Dictionary.code'),
                                             'Dictionary.translation' => $this->request->getQuery('Dictionary.translation'),
                                             'Dictionary.label' => $this->request->getQuery('Dictionary.label')]],
                                     ['class' => 'btn btn-primary ml-5 my-2 py-2', 'id' => 'export']):'' 
?>
<?= $this->Form->end() ?>
<div id="filters_data">
<?php } ?>
<div class="table-responsive">
    <table class="table table-striped">
        <thead>
            <tr>

                <th><?= $this->Paginator->sort('dicoval_id','ID') ?></th>
                <th><?= $this->Paginator->sort('code') ?></th>
                <th><?= $this->Paginator->sort('translation') ?></th>
                <th><?= $this->Paginator->sort('label') ?></th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>

            </tr>
        </thead>
        <tbody>
            <?php foreach ($dictionaryValues as $dictionaryValue): ?>
            <tr>
                <td><?= h($dictionaryValue->dicoval_id) ?></td>
                <td><?= h($dictionaryValue->code) ?></td>
                <td><?= h($dictionaryValue->translation) ?></td>
                <td><?= h($dictionaryValue->label) ?></td>
                <td><?= !empty($dictionaryValue->created) ? h($dictionaryValue->created->format('Y-m-d H:i:s')): '' ?></td>
                <td><?= !empty($dictionaryValue->modified) ? h($dictionaryValue->modified->format('Y-m-d H:i:s')): '' ?></td>

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

<div style="display: none;">
<?php
	echo $this->Form->create(null, ['url' => '/damsv2/dictionary/export', 'id'=>'export_dico_values', 'class'=>'form-inline']);
	echo $this->Form->input('DictionaryValue.dictionary_id', [
		'label'		=> false, 
		'type'		=> 'hidden',
		'value'		=> $dictionary->dictionary_id,
	]);
	 echo $this->Form->input('DictionaryValue.code', [
		'label'		=> false, 
		'type'		=> 'text',
                'id'            => 'code',
	]);
	echo $this->Form->input('DictionaryValue.translation', [
		'label'		=> false, 
		'type'		=> 'text',
                'id'            => 'translation',
	]);
	echo $this->Form->input('DictionaryValue.label', [
		'label'		=> false, 
		'type'		=> 'text',
                'id'            => 'label',
	]);
	echo $this->Form->end();
    
?>
</div>
<?php if (!$this->request->is('ajax')) { ?>
</div>
<script>
function export_dico_values()
{
    //ajax to generate the list
    $("#export_dico_values #code").val( $("#filters #DictionaryCode").val() );
    $("#export_dico_values #translation").val( $("#filters #DictionaryTranslation").val() );
    $("#export_dico_values #label").val( $("#filters #DictionaryLabel").val() );
    var data = $("#export_dico_values").serialize();
    $.ajax({
            type: 'POST',
            data: data,
            url: '/damsv2/ajax/export_dico',
           // headers: {'X-CSRF-TOKEN': '<?= $this->request->getAttribute('csrfToken') ?>'},
            success: function(data)
            {
                //success message + download file
                window.open("/damsv2/ajax/download-file/"+data+"/export");
            }
    });
    return false;
}

$(document).ready(function () {
     $("#reset_filter").on("click", function (e) {
        e.preventDefault();
        $(this).closest('form').find("input[type=text], textarea").val("");
        window.location.replace('/damsv2/dictionary/view/<?= $dictionary->dictionary_id ?>');
    });
    
    $("#export").on("click", function (e) {
        e.preventDefault();
        //$('#export_dico_values').submit();
        export_dico_values();
    });
 
});
</script>
<?php } ?>