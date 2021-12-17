<?php
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'user', 'action' => 'profile'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Profile',
        'url'     => ['controller' => 'User', 'action' => 'profile'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>User <?php echo $user->first_name; ?> <?php echo $user->last_name; ?></h3>


<div class="table-responsive">
    <table class="table table-striped">
        <tbody>
                <tr>
                    <td>id</td>
                    <td><?= $user->id ?></td>
				</tr><tr>
                    <td>name</td>
                    <td><?= $user->last_name ?> <?= $user->first_name ?></td>
				</tr><tr>
                    <td>username</td>
                    <td><?= $user->username ?></td>
				</tr><tr>
                    <td>profiles</td>
                    <td><?php foreach($profiles as $profile)
								{
									echo $profile['name']."<br />";
								}
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
</div>

