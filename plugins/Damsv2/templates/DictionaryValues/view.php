<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\DictionaryValue $dictionaryValue
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Dictionary Value'), ['action' => 'edit', $dictionaryValue->dicoval_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Dictionary Value'), ['action' => 'delete', $dictionaryValue->dicoval_id], ['confirm' => __('Are you sure you want to delete # {0}?', $dictionaryValue->dicoval_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Dictionary Values'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Dictionary Value'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="dictionaryValues view content">
            <h3><?= h($dictionaryValue->dicoval_id) ?></h3>
            <table>
                <tr>
                    <th><?= __('Dictionary') ?></th>
                    <td><?= $dictionaryValue->has('dictionary') ? $this->Html->link($dictionaryValue->dictionary->name, ['controller' => 'Dictionary', 'action' => 'view', $dictionaryValue->dictionary->dictionary_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Code') ?></th>
                    <td><?= h($dictionaryValue->code) ?></td>
                </tr>
                <tr>
                    <th><?= __('Translation') ?></th>
                    <td><?= h($dictionaryValue->translation) ?></td>
                </tr>
                <tr>
                    <th><?= __('Label') ?></th>
                    <td><?= h($dictionaryValue->label) ?></td>
                </tr>
                <tr>
                    <th><?= __('Dicoval Id') ?></th>
                    <td><?= $this->Number->format($dictionaryValue->dicoval_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($dictionaryValue->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($dictionaryValue->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
