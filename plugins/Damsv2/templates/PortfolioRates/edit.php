<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\PortfolioRates $portfolioRates
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $portfolioRate->portfolio_rates_id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $portfolioRate->portfolio_rates_id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Portfolio Rates'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="portfolioRates form content">
            <?= $this->Form->create($portfolioRate) ?>
            <fieldset>
                <legend><?= __('Edit Portfolio Rate') ?></legend>
                <?php
                    echo $this->Form->control('portfolio_id', ['options' => $portfolio]);
                    echo $this->Form->control('theme');
                    echo $this->Form->control('effective_date', ['empty' => true]);
                    echo $this->Form->control('availability_start', ['empty' => true]);
                    echo $this->Form->control('availability_end', ['empty' => true]);
                    echo $this->Form->control('rate_application_date', ['empty' => true]);
                    echo $this->Form->control('guarantee_rate');
                    echo $this->Form->control('cap_rate');
                    echo $this->Form->control('commitment');
                    echo $this->Form->control('cap_amount');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
