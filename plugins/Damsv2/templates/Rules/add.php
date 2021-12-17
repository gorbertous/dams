<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Rule[] $rules
 */
?>
<div class="row">
    <div class="column-responsive">
        <div class="rules form content">
            <?= $this->Form->create($rules, ['url' => '/damsv2/rules/add', 'class' => 'form-inline', 'style' => 'align-items: left; width: 100%;', 'onEnter' => 'event.preventDefault();']);
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
                <h3><?= __('New Rule') ?></h3>
            </td><td>
            <?= $perm->hasInsert(array('controller' => 'Rules', 'action' => 'add'))?$this->Form->button(__('Save'),['class'=>'btn btn-primary float-right', 'onclick' => 'setValueAndSubmit(\'expandField\', \'\');']):"&nbsp;"; ?>            </td></tr>
            </table>
            </div>
            <div style="width: 100%">
                <?php
                    $readOnly = $this->request->getData('readonly')||$perm->getAction()=='copy';
                    echo $this->Form->hidden('expandField', ['value' => 'update', 'id' => 'expandField']);                    echo $this->Form->hidden('readonly', ['value' => $readOnly, 'id' => 'readonly']);
                    if ($readOnly) {
                        echo $this->Form->hidden('0.template_type', ['value' => $rules[0]->template_type_id, 'id' => 'template_type']);
                        echo $this->Form->hidden('0.rule_level', ['value' => $rules[0]->rule_level, 'id' => 'rule_level']);
                        echo $this->Form->hidden('0.rule_category', ['value' => $rules[0]->rule_category, 'id' => 'rule_category']);
                        echo $this->Form->hidden('0.product_id', ['value' => $rules[0]->product_id, 'id' => 'product_id']);
                        echo $this->Form->hidden('0.mandate_id', ['value' => $rules[0]->mandate_id, 'id' => 'mandate_id']);
                        echo $this->Form->hidden('0.portfolio_id', ['value' => $rules[0]->portfolio_id, 'id' => 'portfolio_id']);
                    }
                    $commonStyle = ' border: grey; border-width: thin; border-style: solid;';
               
                    echo '<table class="table table"><tr><td><h5>RULE SCOPE</h5></td></tr><tr><td class="form-inline">';
                    echo '<div class="input text">';
                    echo $this->Form->label('0.template_type', __('Template Type'));
                    echo $this->Form->select('0.template_type', $template_types,
                    [
                        'class' => 'form-control mr-2 my-2 filters',
                        'value' => $rules[0]->template_type_id,
                        'id'    => 'template_type',
                        'disabled' => $readOnly,
                        'style' => 'width: 130px',
                        'onChange' => 'filterTables(this.value);'
                    ]
                    );
                    echo '</div>';    
                    echo '<div class="input text">';
                    echo $this->Form->label('0.rule_level', __('Rule Level'));
                    echo $this->Form->select('0.rule_level', $rule_levels,
                    [
                        'class' => 'form-control mr-2 my-2 filters',
                        'value' => $rules[0]->rule_level,
                        'id'    => 'rule_level',
                        'onChange' => 'toggleVisible(\'product_id\',this.value==\'PRODUCT\'?this.value:\'\'); toggleVisible(\'mandate_id\',this.value==\'MANDATE\'?this.value:\'\'); toggleVisible(\'portfolio_id\',this.value==\'PORTFOLIO\'?this.value:\'\');',
                        'disabled' => $readOnly
                    ]
                    );
                    echo '</div>';    
                    echo '<div class="input text">';
                    echo $this->Form->label('0.rule_category', __('Rule Category'));
                    echo $this->Form->select('0.rule_category', $rule_categories,
                    [
                        'class' => 'form-control mr-2 my-2 filters',
                        'value' => $rules[0]->rule_category,
                        'id'    => 'rule_category',
                        'disabled' => $readOnly
                    ]
                    );
                    echo '</div>';
                    echo '<div id="product_id"'.($rules[0]->rule_level!='PRODUCT'&&$rules[0]->rule_level!=''?' style="display: none;"':'').'>';  
                    echo $this->Form->control('0.product_id', ['value' => $rules[0]->product_id, 'empty' =>  __('No Product'), 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:350px;', 'disabled' => $readOnly]);
                    echo '</div>';
                    echo '<div id="mandate_id"'.($rules[0]->rule_level!='MANDATE'?' style="display: none;"':'').'">';  
                    echo $this->Form->control('0.mandate_id', ['value' => $rules[0]->mandate_id, 'empty' =>  __('No Mandate'), 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:350px;','disabled' => $readOnly]);
                    echo '</div>';
                    echo '<div id="portfolio_id"'.($rules[0]->rule_level!='PORTFOLIO'?' style="display: none;"':'').'">';  
                    echo $this->Form->control('0.portfolio_id', ['value' => $rules[0]->portfolio_id, 'empty' =>  __('No Portfolio'), 'class' => 'form-control mr-2 my-2 filters', 'style' => 'width:350px;', 'disabled' => $readOnly]);
                    echo '</div>';
                    echo '</td></tr>';

                    foreach ($rules as $key => $rule) {
                        echo '<tr height="8px"></tr><tr style="'.$commonStyle.' margin-top: 10px; padding-top: 10px; margin-bottom: 0px; border-bottom-style: none;"><td class="form-inline" style="padding-bottom: 10px; padding-left: 10px;"><div class="form-inline" style="width: 100%; display: flex;">';
                        echo $this->Form->control($key.'.rule_name', ['value' => $rules[$key]->rule_name,'style'=>'width: 100%;', 'class' => 'form-control mr-2 my-2', 'templates' => ['inputContainer' => '<div class="input text" style="width: 600px;">{{content}}</div>']]);
                        
                        echo $this->Form->control($key.'.is_warning',['class' => 'ml-5 form-check-input', 'checked' => $rules[$key]->is_warning=='Y','type'=>'checkbox', 'hiddenField' => 'N', 'value' => 'Y', 'label' => 'Warning']);
                        
                        echo $this->Form->control($key.'.inclusion_and_edit', ['class' => 'ml-5 form-check-input', 'value' => $rules[$key]->inclusion_and_edit,'type'=>'radio', 'label' => false, 'options' => ['N' => __('At signature'), 'Y' => __('Continuous')]]);
                        echo '<div style="flex-grow: 1;">';                        
                        echo $this->Form->button(
                            '<i class="fas fa-trash-alt"></i>',
                            ['class' => 'btn btn-danger float-right', 'escapeTitle' => false, 'onclick' => 'setValueAndSubmit(\'expandField\', \'delete-rule-'.$key.'\');']
                        );
                        echo '</div></div>';
                        echo '</td></tr><tr style="'.$commonStyle.' margin-top: 0px; padding-top: 0px; border-top-style: none; padding-right: 0px;"><td class="form-inline">';
                        echo $this->element('subRuleElement', ['rule' => $rule,'path' =>$key.'.','checked_fields'=>$checked_fields, 'checked_entities'=>$checked_entities]);
                        echo '</td></tr>';
                    }
                    echo '<tr style="margin-top: 5px; padding-top: 5px; margin-bottom: 0px; border-bottom-style: none;"><td class="form-inline" style="padding-bottom: 8px; padding-left: 8px;">';
                    echo $this->Form->button(
                        __('Add rule'),
                        ['class' => 'btn btn-secondary float-left', 'onclick' => 'setValueAndSubmit(\'expandField\', \'add-rule\');']
                    );
                    echo '</td></tr>';
                    echo '</table>';
                ?>
            
            </div>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>
<?php $this->Html->scriptBlock('fireSelectsOnChange(); //window.onbeforeunload = confirmExit; function confirmExit() { return "You have attempted to leave this page. Are you sure?";}
$(\'.form-inline\').on(\'keyup keypress\', function(e) {
    var keyCode = e.keyCode || e.which;
    if (keyCode === 13) { 
      e.preventDefault();
      return false;
    }
  });',['block'=>'scriptBottom']) ?>
