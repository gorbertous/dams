<?php
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'user', 'action' => 'profile'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Users list',
        'url'     => ['controller' => 'User', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Users</h3>

<div class="table-responsive">

<?= $this->Form->create(null, ['id' => 'searchuser']); ?>
<?php echo $this->Form->input('User.search',  [
							'class'	=> 'mr-2 my-2 py-2',
							'id'	=> 'id_searchuser'
                        ]); ?>
<?= $this->Form->end() ?>

    <table class="table table-striped">
        <thead>
            <tr>
                <th><?= $this->Paginator->sort('id', '#') ?></th>
                <th><?= $this->Paginator->sort('first_name') ?></th>
                <th><?= $this->Paginator->sort('last_name') ?></th>
                <th><?= $this->Paginator->sort('username') ?></th>
                <th>Profiles</th>
                <th><?= $this->Paginator->sort('created') ?></th>
                <th><?= $this->Paginator->sort('modified') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user->id ?></td>
                    <td><?= $user->first_name ?></td>
                    <td><?= $user->last_name ?></td>
                    <td><?= $user->username ?></td>
                    <td><?=
					implode(', ',array_column($user->profiles, 'name'));
					?></td>
                    <td><?= $user->created ?></td>
                    <td><?= $user->modified ?></td>
                    <td><?= $this->Html->link('Edit', ['controller' => 'User', 'action' => 'viewUser', $user->id], ["class" => "btn btn-primary"]) ?></td>
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
<script>
	$(document).ready(function() {
		$("#id_searchuser").change(function(event) {
			$("#searchuser").submit();
		});
	});
</script>