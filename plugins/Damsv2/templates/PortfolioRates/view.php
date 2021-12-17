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
            <?= $this->Html->link(__('Edit Portfolio Rate'), ['action' => 'edit', $portfolioRate->portfolio_rates_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Portfolio Rate'), ['action' => 'delete', $portfolioRate->portfolio_rates_id], ['confirm' => __('Are you sure you want to delete # {0}?', $portfolioRate->portfolio_rates_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Portfolio Rates'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Portfolio Rate'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="portfolioRates view content">
            <h3><?= h($portfolioRate->portfolio_rates_id) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Portfolio') ?></th>
                    <td><?= $portfolioRate->has('portfolio') ? $this->Html->link($portfolioRate->portfolio->portfolio_id, ['controller' => 'Portfolio', 'action' => 'view', $portfolioRate->portfolio->portfolio_id]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('Theme') ?></th>
                    <td><?= h($portfolioRate->theme) ?></td>
                </tr>
                <tr>
                    <th><?= __('Portfolio Rates Id') ?></th>
                    <td><?= $this->Number->format($portfolioRate->portfolio_rates_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Guarantee Rate') ?></th>
                    <td><?= $this->Number->format($portfolioRate->guarantee_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cap Rate') ?></th>
                    <td><?= $this->Number->format($portfolioRate->cap_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Commitment') ?></th>
                    <td><?= $this->Number->format($portfolioRate->commitment) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cap Amount') ?></th>
                    <td><?= $this->Number->format($portfolioRate->cap_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Effective Date') ?></th>
                    <td><?= h($portfolioRate->effective_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Availability Start') ?></th>
                    <td><?= h($portfolioRate->availability_start) ?></td>
                </tr>
                <tr>
                    <th><?= __('Availability End') ?></th>
                    <td><?= h($portfolioRate->availability_end) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rate Application Date') ?></th>
                    <td><?= h($portfolioRate->rate_application_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($portfolioRate->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($portfolioRate->modified) ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
