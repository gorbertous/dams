<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rule[] $rules
 */
?>
<div >
    <div class="column-responsive">
        <div class="rules form content">
            <?= $this->Form->create($rules, ['class' => 'form-inline', 'style' => 'align-items: left; width: 100%;', 'onSubmit' => 'checkBeforeCopy();']);
                $this->Breadcrumbs->add([
                    [
                        'title'   => 'Home',
                        'url'     => ['controller' => 'Home', 'action' => 'home'],
                        'options' => ['class' => 'breadcrumb-item']
                    ],
                    [
                        'title'   => 'Rules Configuration',
                        'url'     => ['controller' => 'Rules', 'action' => 'index'],
                        'options' => [
                            'class'      => 'breadcrumb-item active',
                            'innerAttrs' => [
                                'class' => 'test-list-class',
                                'id'    => 'the-port-crumb'
                            ]
                        ]
                    ]
                ]);
            ?>
            <div class="form-inline" style="width: 100%; float: right;">
            <table width="100%"><tr><td>
                <h3><?= __('Copy Rules') ?></h3>
            </td><td>
                <?= ($perm->hasInsert(array('controller' => 'Rules', 'action' => 'copy'))&&$perm->hasInsert(array('controller' => 'Rules', 'action' => 'add')))?$this->Form->submit(__('Copy'),['class'=>'btn btn-primary float-right']):"&nbsp;"; ?>
            </td></tr>
            </table>
            </div>
            <div style="width: 100%;">
                <?php
                    echo $this->Form->hidden('expandField', ['value' => '', 'id' => 'expandField']);
                    $commonStyle = ' border: grey; border-width: thin; border-style: solid;';
                    echo '<table class="table table"><tr><td><h5>RULE SCOPE</h5></td></tr><tr><td class="form-inline">';
                    echo '<div class="input text">';
                    echo $this->Form->label('template_type', __('Template Type'));
                    echo $this->Form->select('template_type', $template_types,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'id'    => 'template_type',
                        'style' => 'width: 130px',
                        'onChange' => 'setValueAndSubmit(\'expandField\', \'filter\')'
                    ]
                    );
                    echo '</div>';    
                    echo '<div class="input text">';
                    echo $this->Form->label('rule_level', __('Rule Level'));
                    echo $this->Form->select('rule_level', $rule_levels,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'id'    => 'rule_level',
                        'onChange' => 'toggleVisible(\'product_id\',this.value==\'PRODUCT\'?this.value:\'\'); toggleVisible(\'mandate_id\',this.value==\'MANDATE\'?this.value:\'\'); toggleVisible(\'portfolio_id\',this.value==\'PORTFOLIO\'?this.value:\'\'); setValueAndSubmit(\'expandField\', \'filter\');'
                    ]
                    );
                    echo '</div>';    
                    echo '<div class="input text">';
                    echo $this->Form->label('rule_category', __('Rule Category'));
                    echo $this->Form->select('rule_category', $rule_categories,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'id'    => 'rule_category',
                        'onChange' => 'setValueAndSubmit(\'expandField\', \'filter\')'
                    ]
                    );
                    echo '</div>';
                    echo '<div id="product_id"'.($rule_level!='PRODUCT'?' style="display: none;"':'').'>';  
                    echo '<div style="display: flex;">';
                    echo $this->Form->control('product_id', ['value' => $product_id, 'empty' =>  __('No Product'), 'label' => __("From Product"), 'class' => 'form-control mr-2 my-2', 'style' => 'width: 300px;', 'onchange' => 'setValueAndSubmit(\'expandField\', \'filter\')']);
                    echo $this->Form->control('product_id_to', ['value' => $product_id_to, 'options' => $products, 'empty' =>  __('No Product'), 'label' => __("To Product"), 'style' => 'width: 300px;', 'class' => 'form-control mr-2 my-2']);
                    echo '</div></div>';
                    echo '<div id="mandate_id"'.($rule_level!='MANDATE'?' style="display: none;"':'').'">';  
                    echo '<div style="display: flex;">';
                    echo $this->Form->control('mandate_id', ['value' => $mandate_id, 'empty' =>  __('No Mandate'), 'label' => __("From Mandate"), 'class' => 'form-control mr-2 my-2', 'style' => 'width: 300px;', 'onchange' => 'setValueAndSubmit(\'expandField\', \'filter\')']);
                    echo $this->Form->control('mandate_id_to', ['value' => $mandate_id_to, 'options' => $mandates, 'empty' =>  __('No Mandate'), 'label' => __("To Mandate"), 'style' => 'width: 300px;', 'class' => 'form-control mr-2 my-2']);
                    echo '</div></div>';
                    echo '<div id="portfolio_id"'.($rule_level!='PORTFOLIO'?' style="display: none;"':'').'">';  
                    echo '<div style="display: flex;">';
                    echo $this->Form->control('portfolio_id', ['value' => $portfolio_id, 'empty' =>  __('No Portfolio'), 'label' => __("From Portfolio"), 'class' => 'form-control mr-2 my-2','style' => 'width: 300px;', 'onchange' => 'setValueAndSubmit(\'expandField\', \'filter\')']);
                    echo $this->Form->control('portfolio_id_to', ['value' => $portfolio_id_to, 'options' => $portfolios, 'empty' =>  __('No Portfolio'), 'label' => __("To Portfolio"), 'class' => 'form-control mr-2 my-2', 'style' => 'width: 300px;']);
                    echo '</div></div>';
                    echo '</td></tr>';
                    if (count($rules)>0) {
                        echo '<tr height="8px"></tr><tr style="'.$commonStyle.' margin-top: 5px; padding-top: 5px; margin-bottom: 0px; border-style: solid;"><td class="form-inline" style="padding-bottom: 8px; padding-left: 8px;"><div class="form-inline" style="width: 100%; display: flex;">';
                ?>
                        <table class="table table-striped">
                        <thead>
                                <th></th>
                                <th><?= __d('rules', 'ID') ?></th>
                                <th><?= __d('rules', 'Name') ?></th>
                                <th><?= __d('rules', 'Warning') ?></th>
                                <th><?= __d('rules', 'Entity') ?></th>
                                <th><?= __d('rules', 'Field') ?></th>
                                <th><?= __d('rules', 'Operator') ?></th>
                                <th><?= __d('rules', 'Param 1') ?></th>
                                <th><?= __d('rules', 'Param 2') ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($rules as $key => $rule): ?>
                            <tr>
                                <td><?= $this->Form->control('rule['.$rule->rule_id.']',['type'=>'checkbox', 'hiddenField' => 'N', 'value' => 'Y', 'label' => false]);?></td> 
                                <td><?= h($rule->rule_number) ?></td>
                                <td><?= h($rule->rule_name) ?></td>
                                <td><?= h($rule->is_warning=='Y'?'YES':'NO') ?></td>
                                <td><?= h($rule->checked_entity) ?></td>
                                <td><?= h($rule->checked_field) ?></td>
                                <td><?= h($rule->operator) ?></td>
                                <td><?= strlen($rule->param_1_value) > 100 ? : implode(PHP_EOL, str_split($rule->param_1_value, 25)); h($rule->param_1_value) ?></td>
                                <td><?= strlen($rule->param_2_value) > 100 ? : implode(PHP_EOL, str_split($rule->param_2_value, 25)); h($rule->param_2_value) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        </table>
            <?php   }   ?>
                </div>
                        
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<script>
$(document).ready( function () {
    control = document.getElementById("template_type");
    if (control.options[control.selectedIndex].hidden) {
        control.selectedIndex++;
    }
});
function checkBeforeCopy() {
    if($(".expandField").value != "") {
        return true;
    }
    switch($(".rule_level").value) {
        case "PRODUCT":
            if($(".product_id").value != "" && $(".product_id_to").value != "" && $(".product_id").value != $(".product_id_to").value) {
                return true;
            }
            break;
        case "MANDATE":
            if($(".mandate_id").value != "" && $(".mandate_id_to").value != "" && $(".mandate_id").value != $(".mandate_id_to").value) {
                return true;
            }
            break;
        case "PORTFOLIO":
            if($(".portfolio_id").value != "" && $(".portfolio_id_to").value != "" && $(".portfolio_id").value != $(".portfolio_id_to").value) {
                return true;
            }
            break;
        default:
    }
    return false;
}
</script>
