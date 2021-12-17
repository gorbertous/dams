<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Invoice[]|\Cake\Collection\CollectionInterface $invoices
 */
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Invoices',
        'url'     => ['controller' => 'Invoice', 'action' => 'index'],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ],
    [
        'title'   => $invoice->portfolio->deal_name,
        'url'     => ['controller' => 'Invoice', 'action' => 'accounting', $invoice->invoice_id],
        'options' => [
            'class'      => 'breadcrumb-item active',
            'innerAttrs' => [
                'class' => 'test-list-class',
                'id'    => 'the-inv-crumb'
            ]
        ]
    ]
]);
?>

<h3>Accounting data input</h3>
<hr>

<?= $this->Form->create(null, ['id' => 'accounting']) ?>
<div class="row">
    <div class="col-8 form-inline">

        <label class="col-sm-4 col-form-label h6">Portfolio</label>
        <div class="mr-2 my-2 py-2"><?= $invoice->portfolio->deal_name ?></div>
        

    </div>
</div>
<div class="row">
    <div class="col-8 form-inline">

        <label class="col-sm-4 col-form-label h6">Expected payment date</label>
        <div class="mr-2 my-2 py-2"><?= !empty($invoice->expected_payment_date) ? $invoice->expected_payment_date->format('Y-m-d') : '' ?></div>

    </div>
</div>
<div class="row">
    <div class="col-8 col-8 form-inline">

        <label class="col-sm-4 col-form-label h6 required">Accounting payment date</label>
        <div class="mr-2 my-2 py-2">
            <?= $this->Form->input('payment_date', [
                'label'    => false,
                'class'    => 'form-control datepicker mr-2 my-2 py-2',
                'required' => true,
            ]);
            ?>
        </div>

    </div>
</div>
<div class="row">
    <div class="col-8 form-inline">

        <label class="col-sm-4 col-form-label h6">Contract currency</label>
        <div class="mr-2 my-2 py-2"><?= $invoice->contract_currency ?></div>

    </div>
</div>
<div class="row">
    <div class="col-8 form-inline">

        <label class="col-sm-4 col-form-label h6">Expected amount in contract ccy</label>
        <div class="mr-2 my-2 py-2"><?= $invoice->amount_curr ?></div>

    </div>
</div>
<?php foreach ($ccys as $ccy): ?>

    <div class="row">
        <div class="col-8 form-inline">

            <label class="col-sm-4 col-form-label h6 required">Paid amount for <?= $ccy ?> in contract ccy</label>
            <div class="mr-2 my-2 py-2"><?= $this->Form->control("paid.paid_ammount_$ccy", [
                    'required' => true,
                    'label' => false,
                    'class'    => 'paid_amount form-control mr-2 my-2'
                ])
                ?>
            </div>
            <?php
            if (isset($amount_error) && $amount_error && ($ccy == $contract_currency)) {
                echo '<span style="color: red;">Entered amount is not equal to the invoiced amount</span>';
            }
            ?>

        </div>
    </div>

<?php endforeach; ?>

<div class="row">
    <div class="col-8 form-inline">

        <label class="col-sm-4 col-form-label h6">Total paid in contract ccy</label>
        <div class="mr-2 my-2 py-2" ><span id="total_paid"></span></div>

    </div>
</div>
<div class="row">
    <div class="col-8 form-inline">
        <?= $this->Form->submit('Save', ['class' => 'btn btn-primary ml-2 my-2', 'id'	=> 'submit_id', 'disabled' => !$perm->hasWrite(array('controller' => 'Invoice', 'action' => 'accounting'))])?>
        <?= $this->Html->link('Cancel', ['controller' => 'invoice', 'action' => 'index'], ['class' => 'btn btn-danger ml-2 my-2']) ?>
    </div>
</div>
<?= $this->Form->end(); ?>


<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#accountingPaymentDate").datepicker({
            dateFormat: "yy-mm-dd"
        });
        $('.paid_amount').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -999999999999999999999, vMax: 99999999999999999999999999999});
        $('#total_paid').autoNumeric('init', {aSep: ',', aDec: '.', mDec: 2, vMin: -999999999999999999999, vMax: 99999999999999999999999999999});
        $(".paid_amount").keyup(function (event) {
            updateTotalPaid();
        });
        function updateTotalPaid() {
            var sum = 0;
            $(".paid_amount").each(function () {
                var tmp = $(this).val().replace(/,/g, '');
                if ($(this).val())
                    sum += parseFloat(tmp);
            });
            $("#total_paid").autoNumeric('set', sum);
        }
        ;
        updateTotalPaid();
        
        $("form").submit(function (e){
            document.getElementById("submit_id").disabled = true;
        });
    });
</script>
