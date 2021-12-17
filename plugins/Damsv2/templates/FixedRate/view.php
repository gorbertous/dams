<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\FixedRate $fixedRate
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Fixed Rate'), ['action' => 'edit', $fixedRate->rate_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Fixed Rate'), ['action' => 'delete', $fixedRate->rate_id], ['confirm' => __('Are you sure you want to delete # {0}?', $fixedRate->rate_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Fixed Rate'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Fixed Rate'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="fixedRate view content">
            <h3><?= h($fixedRate->rate_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Currency') ?></th>
                    <td><?= h($fixedRate->currency) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rate Id') ?></th>
                    <td><?= $this->Number->format($fixedRate->rate_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Id') ?></th>
                    <td><?= $this->Number->format($fixedRate->portfolio_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Obs Value') ?></th>
                    <td><?= $this->Number->format($fixedRate->obs_value) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($fixedRate->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($fixedRate->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
