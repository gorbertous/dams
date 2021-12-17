<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\TemplateType $templateType
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Template Type'), ['action' => 'edit', $templateType->type_id], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Template Type'), ['action' => 'delete', $templateType->type_id], ['confirm' => __('Are you sure you want to delete # {0}?', $templateType->type_id), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Template Type'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Template Type'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="templateType view content">
            <h3><?= h($templateType->name) ?></h3>
            <table class="table table-striped">
                <tr>
                    <th><?= __('Name') ?></th>
                    <td><?= h($templateType->name) ?></td>
                </tr>
                <tr>
                    <th><?= __('Type Id') ?></th>
                    <td><?= $this->Number->format($templateType->type_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($templateType->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($templateType->modified) ?></td>
                </tr>
            </table>
            <div class="related">
                <h4><?= __('Related Rules') ?></h4>
                <?php if (!empty($templateType->rules)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Rule Id') ?></th>
                            <th><?= __('Rule Number') ?></th>
                            <th><?= __('Rule Category') ?></th>
                            <th><?= __('Rule Level') ?></th>
                            <th><?= __('Product Id') ?></th>
                            <th><?= __('Mandate Id') ?></th>
                            <th><?= __('Portfolio Id') ?></th>
                            <th><?= __('Is Warning') ?></th>
                            <th><?= __('Inclusion And Edit') ?></th>
                            <th><?= __('Rule Name') ?></th>
                            <th><?= __('Top Level') ?></th>
                            <th><?= __('Rule Type') ?></th>
                            <th><?= __('Checked Entity') ?></th>
                            <th><?= __('Checked Field') ?></th>
                            <th><?= __('Operator') ?></th>
                            <th><?= __('Param 1 Value') ?></th>
                            <th><?= __('Param 2 Value') ?></th>
                            <th><?= __('Truepart Id') ?></th>
                            <th><?= __('Falsepart Id') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Version Number') ?></th>
                            <th><?= __('Template Type Id') ?></th>
                            <th><?= __('User Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($templateType->rules as $rules) : ?>
                        <tr>
                            <td><?= h($rules->rule_id) ?></td>
                            <td><?= h($rules->rule_number) ?></td>
                            <td><?= h($rules->rule_category) ?></td>
                            <td><?= h($rules->rule_level) ?></td>
                            <td><?= h($rules->product_id) ?></td>
                            <td><?= h($rules->mandate_id) ?></td>
                            <td><?= h($rules->portfolio_id) ?></td>
                            <td><?= h($rules->is_warning) ?></td>
                            <td><?= h($rules->inclusion_and_edit) ?></td>
                            <td><?= h($rules->rule_name) ?></td>
                            <td><?= h($rules->top_level) ?></td>
                            <td><?= h($rules->rule_type) ?></td>
                            <td><?= h($rules->checked_entity) ?></td>
                            <td><?= h($rules->checked_field) ?></td>
                            <td><?= h($rules->operator) ?></td>
                            <td><?= h($rules->param_1_value) ?></td>
                            <td><?= h($rules->param_2_value) ?></td>
                            <td><?= h($rules->truepart_id) ?></td>
                            <td><?= h($rules->falsepart_id) ?></td>
                            <td><?= h($rules->description) ?></td>
                            <td><?= h($rules->version_number) ?></td>
                            <td><?= h($rules->template_type_id) ?></td>
                            <td><?= h($rules->user_id) ?></td>
                            <td><?= h($rules->created) ?></td>
                            <td><?= h($rules->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Rules', 'action' => 'view', $rules->rule_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Rules', 'action' => 'edit', $rules->rule_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Rules', 'action' => 'delete', $rules->rule_id], ['confirm' => __('Are you sure you want to delete # {0}?', $rules->rule_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Rules Log History') ?></h4>
                <?php if (!empty($templateType->rules_log_history)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Log Id') ?></th>
                            <th><?= __('Rule Id') ?></th>
                            <th><?= __('Rule Number') ?></th>
                            <th><?= __('Is Warning') ?></th>
                            <th><?= __('Inclusion And Edit') ?></th>
                            <th><?= __('Rule Name') ?></th>
                            <th><?= __('Top Level') ?></th>
                            <th><?= __('Rule Type') ?></th>
                            <th><?= __('Checked Entity') ?></th>
                            <th><?= __('Checked Field') ?></th>
                            <th><?= __('Operator') ?></th>
                            <th><?= __('Param 1 Value') ?></th>
                            <th><?= __('Param 2 Value') ?></th>
                            <th><?= __('Truepart Id') ?></th>
                            <th><?= __('Falsepart Id') ?></th>
                            <th><?= __('Description') ?></th>
                            <th><?= __('Version Number') ?></th>
                            <th><?= __('Portfolio Id') ?></th>
                            <th><?= __('Template Type Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th><?= __('User') ?></th>
                            <th><?= __('Datetime') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($templateType->rules_log_history as $rulesLogHistory) : ?>
                        <tr>
                            <td><?= h($rulesLogHistory->log_id) ?></td>
                            <td><?= h($rulesLogHistory->rule_id) ?></td>
                            <td><?= h($rulesLogHistory->rule_number) ?></td>
                            <td><?= h($rulesLogHistory->is_warning) ?></td>
                            <td><?= h($rulesLogHistory->inclusion_and_edit) ?></td>
                            <td><?= h($rulesLogHistory->rule_name) ?></td>
                            <td><?= h($rulesLogHistory->top_level) ?></td>
                            <td><?= h($rulesLogHistory->rule_type) ?></td>
                            <td><?= h($rulesLogHistory->checked_entity) ?></td>
                            <td><?= h($rulesLogHistory->checked_field) ?></td>
                            <td><?= h($rulesLogHistory->operator) ?></td>
                            <td><?= h($rulesLogHistory->param_1_value) ?></td>
                            <td><?= h($rulesLogHistory->param_2_value) ?></td>
                            <td><?= h($rulesLogHistory->truepart_id) ?></td>
                            <td><?= h($rulesLogHistory->falsepart_id) ?></td>
                            <td><?= h($rulesLogHistory->description) ?></td>
                            <td><?= h($rulesLogHistory->version_number) ?></td>
                            <td><?= h($rulesLogHistory->portfolio_id) ?></td>
                            <td><?= h($rulesLogHistory->template_type_id) ?></td>
                            <td><?= h($rulesLogHistory->created) ?></td>
                            <td><?= h($rulesLogHistory->modified) ?></td>
                            <td><?= h($rulesLogHistory->user) ?></td>
                            <td><?= h($rulesLogHistory->datetime) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'RulesLogHistory', 'action' => 'view', $rulesLogHistory->log_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'RulesLogHistory', 'action' => 'edit', $rulesLogHistory->log_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'RulesLogHistory', 'action' => 'delete', $rulesLogHistory->log_id], ['confirm' => __('Are you sure you want to delete # {0}?', $rulesLogHistory->log_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Template') ?></h4>
                <?php if (!empty($templateType->template)) : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <tr>
                            <th><?= __('Template Id') ?></th>
                            <th><?= __('Name') ?></th>
                            <th><?= __('Template Type Id') ?></th>
                            <th><?= __('Callback Id') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($templateType->template as $template) : ?>
                        <tr>
                            <td><?= h($template->template_id) ?></td>
                            <td><?= h($template->name) ?></td>
                            <td><?= h($template->template_type_id) ?></td>
                            <td><?= h($template->callback_id) ?></td>
                            <td><?= h($template->created) ?></td>
                            <td><?= h($template->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Template', 'action' => 'view', $template->template_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Template', 'action' => 'edit', $template->template_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Template', 'action' => 'delete', $template->template_id], ['confirm' => __('Are you sure you want to delete # {0}?', $template->template_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
