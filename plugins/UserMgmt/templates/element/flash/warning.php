<?php
/**
 * @var \App\View\AppView $this
 * @var array $params
 * @var string $message
 */
if (!isset($params['escape']) || $params['escape'] !== false) {
    $message = h($message);
}
?>

<div class="alert alert-warning">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <?= $message ?>
</div>
