<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Guarantee $guarantee
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Guarantees'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="guarantees form content">
            <?= $this->Form->create($guarantee) ?>
            <fieldset>
                <legend><?= __('Add Guarantee') ?></legend>
                <?php
                    echo $this->Form->control('transaction_id', ['options' => $transactions, 'empty' => true]);
                    echo $this->Form->control('portfolio_id');
                    echo $this->Form->control('sme_id');
                    echo $this->Form->control('transaction_reference');
                    echo $this->Form->control('fiscal_number');
                    echo $this->Form->control('report_id');
                    echo $this->Form->control('fi_guarantee_amount');
                    echo $this->Form->control('fi_guarantee_amount_eur');
                    echo $this->Form->control('fi_guarantee_amount_curr');
                    echo $this->Form->control('fi_guarantee_rate');
                    echo $this->Form->control('fi_guarantee_signature_date', ['empty' => true]);
                    echo $this->Form->control('fi_guarantee_maturity_date', ['empty' => true]);
                    echo $this->Form->control('subintermediary');
                    echo $this->Form->control('guarantee_comments');
                    echo $this->Form->control('error_message');
                    echo $this->Form->control('subintermediary_address');
                    echo $this->Form->control('subintermediary_postcode');
                    echo $this->Form->control('subintermediary_place');
                    echo $this->Form->control('subintermediary_type');
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
