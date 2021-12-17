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
    [
        'title'   => 'Edit',
        'url'     => ['controller' => 'User', 'action' => 'view-user', $user->id],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>User <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h3>


<div class="table-responsive">
<?= $this->Form->create(null, ['id' => 'edituser']); ?>
    <table class="table table-striped">
        <tbody>
                <tr>
                    <td>id</td>
                    <td><?= $user->id ?></td>
				</tr><tr>
                    <td>first name</td>
                    <td><?= $this->Form->input('User.first_name',  [
                            'value'   =>$user->first_name,
							'class'	=> 'mr-2 my-2 py-2',
                        ]);
					?></td>
				</tr><tr>
                    <td>name</td>
                    <td><?= $this->Form->input('User.last_name',  [
                            'value'   =>$user->last_name,
							'class'	=> 'mr-2 my-2 py-2',
                        ]);
					?></td>
				</tr><tr>
                    <td>username</td>
                    <td><?= $this->Form->input('User.username',  [
                            'value'   =>$user->username,
							'class'	=> 'mr-2 my-2 py-2',
                        ]);
					?></td>
				</tr><tr>
                    <td>profiles</td>
                    <td><?php 
					echo $this->Form->select('User.profiles', $profiles_all, [
                            'val' => $profiles_selected,
							'class'	=> 'mr-2 my-2 py-2',
							'multiple' => true,
                        ]);
					
					/*foreach($profiles_array as $profile)
								{
									echo $profile['name']."<br />";
								}*/
					?></td>
				</tr><tr>
                    <td>created</td>
                    <td><?= $user->created ?></td>
				</tr><tr>
                    <td>modified</td>
                    <td><?= $user->modified ?></td>
				</tr>
        </tbody>
    </table>
	<div class="col-6 form-inline">
	<?= $this->Form->submit('Submit', ['class' => 'btn btn-primary  mr-2 my-2 py-2']) ?>
	<?= $this->Html->link('Cancel', ['action' => 'index'], ['class' => 'btn btn-danger']) ?>
	</div>
	<?= $this->Form->end() ?>
</div>

