<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rule $rule
 */
?>
<div class="row">
    <div class="column-responsive">
        <div class="rules form content">
            <?php    $this->Breadcrumbs->add([
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
            <div class="float-left">
                    <h3><?= __('Edit Rule') ?></h3>
            </div>
            <div class='float-right'>
                <?= (!$view_only)?$this->Form->button(__('Save'),['type'=>'submit','class'=>'btn btn-primary float-right mr-2 my-2', 'form'=>'form-edit']):"&nbsp;"; ?>
                <?= (!$view_only)?$this->Form->postLink(
                    '<i class="fas fa-trash-alt"></i>',
                    ['action' => 'delete', $rule->rule_id],
                    ['method' => 'delete', 'confirm' => __('Are you sure you want to delete # {0}?', $rule->rule_id), 'class' => 'btn btn-danger float-left mr-2 my-2', 'escapeTitle' => false,]
                ):"&nbsp;" ?>
            </div>
            <div class='float-right'>
            </div>
            <?= $this->Form->create($rule, ['class' => 'form-inline', 'id' => 'form-edit','style' => 'align-items: left; width: 100%;']); ?>
            <div style="width: 100%;">
                <?php
                    echo $this->Form->hidden('expandField', ['value' => '', 'id' => 'expandField']);
                    echo $this->Form->hidden('template_type', ['value' => $rule->template_type_id, 'id' => 'template_type']);
                    echo $this->Form->hidden('rule_level', ['value' => $rule->rule_level, 'id' => 'rule_level']);
                    echo $this->Form->hidden('rule_category', ['value' => $rule->rule_category, 'id' => 'rule_category']);
                    echo $this->Form->hidden('product_id', ['value' => $rule->product_id, 'id' => 'product_id']);
                    echo $this->Form->hidden('mandate_id', ['value' => $rule->mandate_id, 'id' => 'mandate_id']);
                    echo $this->Form->hidden('portfolio_id', ['value' => $rule->portfolio_id, 'id' => 'portfolio_id']);
                    
                    $commonStyle = ' border: grey; border-width: thin; border-style: solid;';
                    echo '<table class="table table"><tr><td><h5>RULE SCOPE</h5></td></tr><tr><td class="form-inline">';
                    echo '<div class="input text">';
                    echo $this->Form->label('template_type', __('Process'));
                    echo $this->Form->select('template_type', $template_types,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'value' => $rule->template_type_id,
                        'id'    => 'template_type',
                        'style' => 'width: 130px',
                        'disabled' => true
                    ]
                    );
                    echo '</div>';    
                    echo '<div class="input text">';
                    echo $this->Form->label('rule_level', __('Rule Level'));
                    echo $this->Form->select('rule_level', $rule_levels,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'value' => $rule->rule_level,
                        'id'    => 'rule_level',
                        
                        'onChange' => 'toggleVisible(\'product_id\',this.value==\'PRODUCT\'?this.value:\'\'); toggleVisible(\'mandate_id\',this.value==\'MANDATE\'?this.value:\'\'); toggleVisible(\'portfolio_id\',this.value==\'PORTFOLIO\'?this.value:\'\');',
                        'disabled' => true
                    ]
                    );
                    echo '</div>';    
                    echo '<div class="input text">';
                    echo $this->Form->label('rule_category', __('Rule Category'));
                    echo $this->Form->select('rule_category', $rule_categories,
                    [
                        'class' => 'form-control mr-2 my-2',
                        'value' => $rule->rule_category,
                        'id'    => 'rule_category',
                        
                        'disabled' => true
                    ]
                    );
                    echo '</div>';
                    echo '<div id="product_id"'.($rule->rule_level!='PRODUCT'?' style="display: none;"':'').'>';  
                    echo $this->Form->control('product_id', ['value' => $rule->product_id, 'empty' =>  __('No Product'), 'class' => 'form-control mr-2 my-2', 'disabled' => true]);
                    echo '</div>';
                    echo '<div id="mandate_id"'.($rule->rule_level!='MANDATE'?' style="display: none;"':'').'">';  
                    echo $this->Form->control('mandate_id', ['value' => $rule->mandate_id, 'empty' =>  __('No Mandate'), 'class' => 'form-control mr-2 my-2', 'disabled' => true]);
                    echo '</div>';
                    echo '<div id="portfolio_id"'.($rule->rule_level!='PORTFOLIO'?' style="display: none;"':'').'">';  
                    echo $this->Form->control('portfolio_id', ['value' => $rule->portfolio_id, 'empty' =>  __('No Portfolio'), 'class' => 'form-control mr-2 my-2', 'disabled' => true]);
                    echo '</div>';
                    echo '</td></tr><tr height="8px"></tr><tr style="'.$commonStyle.' margin-top: 10px; padding-top: 10px; margin-bottom: 0px; border-bottom-style: none;"><td class="form-inline" style="padding-bottom: 10px; padding-left: 10px;">';
                    echo $this->Form->control('rule_name', ['value' => $rule->rule_name,'style'=>'width: 100%;', 'class' => 'form-control mr-2 my-2', 'templates' => ['inputContainer' => '<div class="input text" style="width: 600px;">{{content}}</div>']]);
                   
                    echo $this->Form->control('is_warning',['class' => 'ml-5 form-check-input','checked' => $rule->is_warning=='Y','type'=>'checkbox', 'hiddenField' => 'N', 'value' => 'Y', 'label' => 'Warning']);
                    
                    echo $this->Form->control('inclusion_and_edit', ['class' => 'ml-5 form-check-input','value' => $rule->inclusion_and_edit,'type'=>'radio', 'label' => false, 'options' => ['N' => __('At signature'), 'Y' => __('Continuous')]]);
                    echo '</td></tr><tr style="'.$commonStyle.' margin-top: 0px; padding-top: 0px; border-top-style: none;"><td class="form-inline">';
                    echo $this->element('subRuleElement', ['rule' => $rule,'path' =>'','checked_fields'=>$checked_fields, 'checked_entities'=>$checked_entities]);
                    echo '</td></tr></table>';
                ?>
            
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?php $this->Html->scriptBlock('fireSelectsOnChange();',['block'=>'scriptBottom']) ?>
<?php
if ($view_only)
{
	echo "
<script>
$(document).ready(function () {
	$('input, select, textarea').attr('disabled', true);
	
});
</script>
";
}
?>