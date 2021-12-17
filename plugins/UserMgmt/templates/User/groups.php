<?php
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'user', 'action' => 'profile'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Profiles',
        'url'     => ['controller' => 'Groups', 'action' => 'groups'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Profiles list</h3>


<div class="table-responsive">
    <table class="table table-striped">
		<thead>
            <tr>
                <th><?= $this->Paginator->sort('Profiles.id', '#') ?></th>
                <th><?= $this->Paginator->sort('Profiles.name') ?></th>
                <th><?= $this->Paginator->sort('Profiles.alias_name') ?></th>
                <th class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
		<?php foreach($profiles as $profile){
			?>
                <tr>
                    <td><?php echo $profile['id']; ?></td>
                    <td><?php echo $profile['name']; ?></td>
                    <td><?php echo $profile['alias_name']; ?></td>
                    <td><a href="/user-mgmt/user/edit-group/<?php echo $profile['id']; ?>" class="btn btn-primary">Edit</a></td>
				</tr>
		<?php } ?>
        </tbody>
    </table>
</div>

