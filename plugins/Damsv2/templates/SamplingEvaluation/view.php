<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\SamplingEvaluation $samplingEvaluation
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Sampling Evaluation'), ['action' => 'edit', $samplingEvaluation->evaluation_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Sampling Evaluation'), ['action' => 'delete', $samplingEvaluation->evaluation_id], ['confirm' => __('Are you sure you want to delete # {0}?', $samplingEvaluation->evaluation_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Sampling Evaluation'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Sampling Evaluation'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="samplingEvaluation view content">
            <h3><?= h($samplingEvaluation->evaluation_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('User') ?></th>
                    <td><?= h($samplingEvaluation->user) ?></td>
                </tr>
                <tr>
                    <th><?= __('Evaluation Id') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->evaluation_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Evaluation Year') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->evaluation_year) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value Pds') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->value_pds) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value Pds Sampled') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->value_pds_sampled) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nb Pds Sampled') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->nb_pds_sampled) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nb Hv Sampled') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->nb_hv_sampled) ?></td>
                </tr>
                <tr>
                    <th><?= __('Nb Lv Sampled') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->nb_lv_sampled) ?></td>
                </tr>
                <tr>
                    <th><?= __('Value Hv') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->value_hv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Overstatements Hv') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->overstatements_hv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Materiality Threshold') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->materiality_threshold) ?></td>
                </tr>
                <tr>
                    <th><?= __('Materiality Threshold Eur') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->materiality_threshold_eur) ?></td>
                </tr>
                <tr>
                    <th><?= __('Res Materiality Threshold') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->res_materiality_threshold) ?></td>
                </tr>
                <tr>
                    <th><?= __('Average Taint Lv') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->average_taint_lv) ?></td>
                </tr>
                <tr>
                    <th><?= __('Confidence No Overstate') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->confidence_no_overstate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Probability Overstate') ?></th>
                    <td><?= $this->Number->format($samplingEvaluation->probability_overstate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($samplingEvaluation->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($samplingEvaluation->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
