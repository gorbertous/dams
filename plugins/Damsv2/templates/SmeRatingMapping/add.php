<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SmeRatingMapping $smeRatingMapping
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('List Sme Rating Mapping'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="smeRatingMapping form content">
            <?= $this->Form->create($smeRatingMapping) ?>
            <fieldset>
                <legend><?= __('Add Sme Rating Mapping') ?></legend>
                <?php
                    echo $this->Form->control('portfolio_id', ['options' => $portfolio]);
                    echo $this->Form->control('sme_fi_rating_scale');
                    echo $this->Form->control('sme_rating');
                    echo $this->Form->control('adjusted_sme_fi_scale');
                    echo $this->Form->control('adjusted_sme_rating');
                    echo $this->Form->control('equiv_ori_sme_rating');
                    echo $this->Form->control('user_id', ['options' => $users, 'empty' => true]);
                ?>
            </fieldset>
                        <?= $this->Form->button(__('Submit'),['class'=>'btn btn-outline-secondary my-3']); ?>

            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
