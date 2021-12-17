<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Limit $limit
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $limit->limit_ID],
                ['confirm' => __('Are you sure you want to delete # {0}?', $limit->limit_ID), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Limits'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="limits form content">
            <?= $this->Form->create($limit) ?>
            <fieldset>
                <legend><?= __('Edit Limit') ?></legend>
                <?php
                    echo $this->Form->control('limit_name');
                    echo $this->Form->control('limit_date_from', ['empty' => true]);
                    echo $this->Form->control('limit_date_to', ['empty' => true]);
                    echo $this->Form->control('mandategroup_ID');
                    echo $this->Form->control('counterpartygroup_ID');
                    echo $this->Form->control('cpty_ID');
                    echo $this->Form->control('automatic');
                    echo $this->Form->control('rating_lt');
                    echo $this->Form->control('rating_st');
                    echo $this->Form->control('cpty_rating');
                    echo $this->Form->control('max_maturity');
                    echo $this->Form->control('limit_eur');
                    echo $this->Form->control('max_concentration');
                    echo $this->Form->control('concentration_limit_unit');
                    echo $this->Form->control('is_current');
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
