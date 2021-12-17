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
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $fixedRate->rate_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $fixedRate->rate_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Fixed Rate'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="fixedRate form content">
            <?= $this->Form->create($fixedRate) ?>
            <fieldset>
                <legend><?= __('Edit Fixed Rate') ?></legend>
                <?php
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('currency');
                    echo $this->Form->control('obs_value');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
