<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\UmbrellaPortfolio $umbrellaPortfolio
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $umbrellaPortfolio->umbrella_portfolio_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $umbrellaPortfolio->umbrella_portfolio_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Umbrella Portfolio'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="umbrellaPortfolio form content">
            <?= $this->Form->create($umbrellaPortfolio) ?>
            <fieldset>
                <legend><?= __('Edit Umbrella Portfolio') ?></legend>
                <?php
                    echo $this->Form->control('umbrella_portfolio_name');
                    echo $this->Form->control('iqid');
                    echo $this->Form->control('product_id', ['options' => $product]);
                    echo $this->Form->control('splitting_field');
                    echo $this->Form->control('splitting_table');
                    echo $this->Form->control('deleted._ids', ['options' => $deleted]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
