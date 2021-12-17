<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Reinvestment $reinvestment
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Reinvestment'), ['action' => 'edit', $reinvestment->reinv_group], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Reinvestment'), ['action' => 'delete', $reinvestment->reinv_group], ['confirm' => __('Are you sure you want to delete # {0}?', $reinvestment->reinv_group), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Reinvestments'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Reinvestment'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="reinvestments view content">
            <h3><?= h($reinvestment->reinv_group) ?></h3>
            <table>
                <tr>
                    <th><?= __('Reinv Status') ?></th>
                    <td><?= h($reinvestment->reinv_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('AccountA IBAN') ?></th>
                    <td><?= h($reinvestment->accountA_IBAN) ?></td>
                </tr>
                <tr>
                    <th><?= __('AccountB IBAN') ?></th>
                    <td><?= h($reinvestment->accountB_IBAN) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reinv Type') ?></th>
                    <td><?= h($reinvestment->reinv_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reinv Group') ?></th>
                    <td><?= $this->Number->format($reinvestment->reinv_group) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate ID') ?></th>
                    <td><?= $this->Number->format($reinvestment->mandate_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cmp ID') ?></th>
                    <td><?= $this->Number->format($reinvestment->cmp_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpty ID') ?></th>
                    <td><?= $this->Number->format($reinvestment->cpty_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount LeftA') ?></th>
                    <td><?= $this->Number->format($reinvestment->amount_leftA) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount LeftB') ?></th>
                    <td><?= $this->Number->format($reinvestment->amount_leftB) ?></td>
                </tr>
                <tr>
                    <th><?= __('Availability Date') ?></th>
                    <td><?= h($reinvestment->availability_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($reinvestment->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($reinvestment->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
