<?php
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'user', 'action' => 'profile'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Profiles',
        'url'     => ['controller' => 'User', 'action' => 'groups'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Edit',
        'url'     => ['controller' => 'User', 'action' => 'edit-group',  $profile->get('id')],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Profile <?php echo $profile->get('name'); ?></h3>


<div class="table-responsive">
	<?= $this->Form->create(null, ['id' => 'editgroup']); ?>
    <table class="table table-striped">
        <tbody>
			<tr>
				<td>profile ID</td><td><?php echo $profile->get('id'); ?></td>
			</tr><tr>
				<td>profile name</td><td><?php echo $this->Form->input('Profile.name',  [
                            'value'   => $profile->get('name'),
							'class'	=> 'mr-2 my-2 py-2',
                        ]); ?></td>
			</tr><tr>
				<td>profile alias</td><td><?php echo $this->Form->input('Profile.alias_name',  [
                            'value'   => $profile->get('alias_name'),
							'class'	=> 'mr-2 my-2 py-2',
                        ]); ?></td>
			</tr>
        </tbody>
    </table>
	<div class="col-6 form-inline">
	<?= $this->Form->submit('Submit', ['class' => 'btn btn-primary  mr-2 my-2 py-2']) ?>
	<?= $this->Html->link('Cancel', ['action' => 'groups'], ['class' => 'btn btn-danger']) ?>
	</div>
	<?= $this->Form->end() ?>
</div>

