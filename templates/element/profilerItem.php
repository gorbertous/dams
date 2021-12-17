<div class="accordion" id="ProfilerItems<?= $path ?>">
<?php 
foreach ($profilerTree as $key => $value) {
    $id = $path . "-" . $key; 
?>
  <div class="card">
    <div class="card-header" id="heading<?= "-" . $id ?>">
        <h4 class="mb-0">
            <button class="btn btn-link btn-block text-left" type="button" data-toggle="collapse" data-target="#collapse<?= "-" . $id ?>" aria-expanded="true" aria-controls="collapse<?= "-" . $id ?>">
                <?php echo $value->class . "->" . $value->method . " Elapsed Time:" . $value->elapsedTime . " (" . ($value->success?"OK":"Error") . ")";
                ?>
            </button>
        </h4>
    </div>
    <div id="collapse<?= "-" . $id ?>" class="collapse" aria-labelledby="heading<?= "-" . $id ?>" data-parent="#ProfilerItems<?= $path ?>">
      <div class="card-body">
          <?php echo $this->element('profilerItem', ['profilerTree' => $value->profilerItems, 'path' => $id]);
                //var_export($value->profilerItems);?>
      </div>
    </div>
  </div>
<?php
}
?>
</div>
