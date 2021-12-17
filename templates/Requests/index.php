<?php
/**
 * @var \App\View\AppView $this
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Requests',
        'url'     => ['controller' => 'Requests', 'action' => 'index'],
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
<div class="accordion" id="Requests">
<?php
$id = 0;
end($requests);
while ($value = current($requests)) {
  $key = key($requests);
  ?>
  <div class="card">
    <div class="card-header" id="heading<?= "-" . $id ?>">
        <h4 class="mb-0">
            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= "-" . $id ?>" aria-expanded="true" aria-controls="collapse<?= "-" . $id ?>">
                <?php echo $key . " - " . ($value['requestToken']==null?"(empty)":substr($value['requestToken'],0,10));
                ?>
            </button>
        </h4>
    </div>
    <div id="collapse<?= "-" . $id ?>" class="collapse" aria-labelledby="heading<?= "-" . $id ?>" data-parent="#Requests">
      <div class="card-body">
      <?php echo $this->element('profilerItem', ['profilerTree' => $value['data'], 'path' => $id]); 
      //var_export($value['data']);?>
      </div>
    </div>
  </div>
<?php 
    $id++;
    prev($requests);
} ?>
</div>
<h4 class="mb-0">
    <a href="requests/reset">Reset</a>
</h4>
