<?php
    $prefix = str_replace(['.'],['-'], $path);
    $show_label = $path=='' || $path=='0.';
    echo '<span heigth="100%" style="text-align: bottom;">' . ($show_label?'</br>':'') . 'IF &nbsp;</span>';                
    //echo $this->Form->control($path . 'rule_number', ['label' => $path!=''?'':null, 'class' => 'form-control mr-2 my-2', 'style' => 'font-size: 0.8rem;']);
    echo $this->Form->hidden($path . 'rule_id', ['value' => $rule->rule_id]);
    echo $this->Form->hidden($path . 'rule_number', ['value' => $rule->rule_number]);
    echo $this->Form->hidden($path . 'datatype', ['value' => 'text', 'id' => $path . 'datatype']);
    echo '<div class="input text">';
    if ($show_label) {
        echo $this->Form->label($path . 'checked_entity', __('Entity'));
    }
    $checked_field_id = $path . 'checked_field';
    echo $this->Form->select($path . 'checked_entity', $checked_entities,
    [
        'class' => 'form-control mr-2 my-2',
        'value' => $rule->checked_entity,
        'style' => 'max-width: 180px',
        'id'    => $path . 'checked_entity',
        'onchange' => 'updateHiddenOptions(\''.$checked_field_id.'\', this.value);'
    ]
    );
    echo '</div>';
    echo '<div class="input text">';
    if ($show_label) {
        echo $this->Form->label($path . 'checked_field', __('Field'));
    }
    echo $this->Form->select($path . 'checked_field', $checked_fields,
    [
        'class' => 'form-control mr-2 my-2',
        'value' => $rule->checked_field,
        'style' => 'max-width: 180px',
        'id'    => $path . 'checked_field',
        'onchange' => 'updateFieldTypes(\''.$prefix.'\', this.options[this.selectedIndex].getAttribute(\'type\')); filterOperators(\''. $path . 'operator\', this.options[this.selectedIndex].getAttribute(\'type\'));'
    ]
    );
    echo '</div>';
    echo '<div class="input text">';
    if ($show_label) {
        echo $this->Form->label($path . 'operator', __('Operator'));
    }
    echo $this->Form->select($path . 'operator', $operators,
    [
        'class' => 'form-control mr-2 my-2',
        'value' => $rule->operator,
        'style' => 'max-width: 120px',
        'id'    => $path . 'operator',
        'onchange' => 'updateFieldVisibility(\''.$prefix.'\', this.options[this.selectedIndex].getAttribute(\'params\'));'
    ]
    );
    echo '</div>';
    $this->Form->setTemplates(['inputContainer'=>'<div class="input {{type}}{{required}}">{{content}}<div style="display:none;"></div></div>']);
    echo $this->Form->control($path . 'param_1_value', ['value' => $rule->param_1_value,'label' => $show_label?__('Parameter 1'):'', 'class' => 'form-control mr-2 my-2']);
    echo $this->Form->control($path . 'param_2_value', ['value' => $rule->param_2_value,'label' => $show_label?__('Parameter 2'):'', 'class' => 'form-control mr-2 my-2']);
    echo '<span heigth="100%" style="text-align: bottom;">' . ($show_label?'</br>':'') . 'THEN &nbsp;</span>';                
    echo '<div class="input text">';
    $id_falsepart = $prefix . 'falsepart';
    $id_truepart = $prefix . 'truepart';
    if ($show_label) {
        echo $this->Form->label($path . 'truepart_id', __('True Action'));
    }
    echo $this->Form->select($path . 'truepart_id', [$rule->rule_number . 'T' => 'Sub-rule'],
    [
        'empty' =>  __('No Breach'),
        'class' => 'form-control mr-2 my-2',
        'value' => $rule->truepart_id,
        'id'    => 'truepart_id',
        'onChange' => $rule->truepart!=null?'toggleVisible(\''.$id_truepart.'-1\',this.value); toggleVisible(\''.$id_truepart.'-2\',this.value);':'setValueAndSubmit(\'expandField\', \''.$id_truepart.'\');'
    ]
    );
    echo '</div>';    
    echo '<span heigth="100%" style="text-align: bottom;">' . ($show_label?'</br>':'') . 'ELSE &nbsp;</span>';                
    echo '<div class="input text">';
    if ($show_label) {
        echo $this->Form->label($path . 'falsepart_id', __('False Action'));
    }
    echo $this->Form->select($path . 'falsepart_id', [$rule->rule_number . 'F' => 'Sub-rule'],
    [
        'empty' =>  __('Error Message'),
        'class' => 'form-control mr-2 my-2',
        'value' => $rule->falsepart_id,
        'id'    => 'falsepart_id',
        'onChange' => $rule->falsepart!=null?'toggleVisible(\''.$id_falsepart.'-1\',this.value); toggleVisible(\''.$id_falsepart.'-2\',this.value);':'setValueAndSubmit(\'expandField\', \''.$id_falsepart.'\');'
    ]
    );
    echo '</div>';    
    echo $this->Form->control($path . 'description', ['value' => $rule->description,'label' => $show_label?__('Message'):'', 'class' => 'form-control mr-2 my-2', 'style' => 'min-width: 250px; height: 2.2rem;']);
    echo '<table class="form-inline" style="width: 100%; float: left; display: table; padding-right: 0px;">';
    if ($rule->truepart!=null) {
        echo '<tr id="'.$id_truepart.'-1"><td style="width: .5%; background-color: green;">&nbsp;</td><td class="form-inline" style="width: 100%; float: left;">'; 
        echo '<div width="100%" style="color: green;">True part</div></td></tr>';
        echo '<tr id="'.$id_truepart.'-2"><td style="width: .5%; background-color: green;">&nbsp;</td><td class="form-inline" style="width: 100%; float: left; padding-right:0px;">'; 
        echo $this->element('subRuleElement', ['rule' => $rule->truepart, 'path' => $path . 'truepart.']);
        echo '</td></tr>';
    } 
    if ($rule->falsepart!=null) {
        echo '<tr id="'.$id_falsepart.'-1"><td style="width: .5%; background-color: red;">&nbsp;</td><td class="form-inline" style="width: 100%; float: left;">'; 
        echo '<div width="100%" style="color: red;">False part</div></td></tr>';
        echo '<tr id="'.$id_falsepart.'-2"><td style="width: .5%; background-color: red;">&nbsp;</td><td class="form-inline" style="width: 100%; float: left; padding-right:0px;">'; 
        echo $this->element('subRuleElement', ['rule' => $rule->falsepart, 'path' => $path . 'falsepart.']); 
        echo '</td></tr>';
    }
    echo '';
    echo '</table>';
?>