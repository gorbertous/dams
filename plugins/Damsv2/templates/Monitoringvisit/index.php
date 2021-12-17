<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\Portfolio $portfolio
 */
$port_title = !empty($portfolio->deal_name) ? $portfolio->deal_name : 'Statistics';
$this->Breadcrumbs->add([
    [
        'title'   => 'Home',
        'url'     => ['controller' => 'Home', 'action' => 'home'],
        'options' => ['class' => 'breadcrumb-item']
    ],
    [
        'title'   => 'Monitoring Visit follow up',
        'url'     => ['controller' => 'monitoringvisit', 'action' => 'index'],
        'options' => ['class' => 'breadcrumb-item']
    ],
]);
?>
<h3>Monitoring Visit follow up</h3>
<hr>

<?= $this->Form->create(null, ['class' => 'form-inline', 'id' => 'mvfilters']) ?>

<?= $this->Form->select('product_id', $products,
        [
            'empty' => '-- Product --',
            'class' => 'form-control mr-2 my-2',
            'value' => !empty($prid) ? $prid : $this->request->getData('product_id'),
            'id'    => 'productid'
        ]
);
?>

<?= $this->Form->select('portfolio_id', $portfolios,
        [
            'empty'    => '-- Portfolio --',
            'class'    => 'w-25 form-control mr-2 my-2',
            'value'    => !empty($pid) ? $pid : $this->request->getData('portfolio_id'),
            'id'       => 'portfolioid',
            'required' => true,
        ]
);
?>

<?= $this->Form->end() ?>


<div id="save" class="float-right">
<?php
$disabled = !$perm->hasUpdate(array('action' => 'index'));
?>
    <button id="save_m_file" class="btn btn-primary mr-2 my-2 <?php if($disabled) echo "disabled"; ?>" type="button" <?php if($disabled) echo "disabled"; ?> > Save </button>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-striped table-hover table-condensed">
        <thead>
            <tr>
                <th style="width: 150px;">Portfolio</th>
                <th style="width: 10px;">Follow up</th>
                <th style="width: 350px;">Document</th>
            </tr>
        </thead>
        <tbody id="mFileRows">
            <?php foreach ($mfiles as $row): ?>
                <tr data-portfolio-id="<?= $row->portfolio_id; ?>">
                    <td><?= $row->portfolio_name ?></td>
                    <td class="text-center"><?php
                        $sel = ($row->modifications_expected == 'Y');
						$options_checkbox = ['checked ' => $sel, 'id ' => 'modifications_expected_' . $row->portfolio_id];
						if ($disabled)
						{
							$options_checkbox[] = 'disabled';
						}
                        print $this->Form->checkbox('modifications_expected_' . $row->portfolio_id, $options_checkbox);
                        ?></td>
                    <td class="text-center" id="row_<?= $row->portfolio_id; ?>">
                        <?php
						$option_text =  [
                            'id'   => 'm_files_link_' . $row->portfolio_id,
                            'div'     => false,
                            'class'   => 'form-control mr-2 my-2',
                            //'style'   => 'width: 780px;float: left;',
                            'default' => $row->m_files_link,
                        ];
						if ($disabled)
						{
							$option_text[] = 'disabled';
						}
                        echo $this->Form->input(
                            'm_files_link_' . $row->portfolio_id, $option_text);
                        ?>
                        <div class="alert alert-success">
                            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                            <span id="error_msg_<?= $row->portfolio_id; ?>"></span>
                        </div>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>
<div class="paginator">
    <ul class="pagination">
        <?= $this->Paginator->first('<< ' . __('first')) ?>
        <?= $this->Paginator->prev('< ' . __('previous')) ?>
        <?= $this->Paginator->numbers() ?>
        <?= $this->Paginator->next(__('next') . ' >') ?>
        <?= $this->Paginator->last(__('last') . ' >>') ?>
    </ul>
    <p><?= $this->Paginator->counter(__('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')) ?></p>
</div>

<div style="display:none;">
    <?php
    echo $this->Form->create(null, ['url' => '/damsv2/monitoringvisit/save-m-file', 'id' => 'updateMfile']);
    echo $this->Form->input('Portfolio.portfolio_id', [
        'type'  => 'text',
        'id'    => 'portfolioid',
    ]);
    echo $this->Form->input('Portfolio.modifications_expected', [
        'type'  => 'text',
        'id'    => 'modexpected',
    ]);
    echo $this->Form->input('Portfolio.m_files_link', [
        'type'  => 'text',
        'id' => 'mfileslink',
    ]);
    echo $this->Form->end();
    ?>
</div>

<script>
function check_mandatory_m_file()
{
    $('.alert').hide();
    $("#mFileRows tr").each(function (count, element)
    {
        element = $(element);
        var portfolio_id = element.attr("data-portfolio-id");
        var checked = element.find('#modifications_expected_' + portfolio_id + ':checked').length;
        var m_file_element = element.find('#m_files_link_' + portfolio_id);
        var m_file_row = element.find('#row_' + portfolio_id);
        var mfile = m_file_element.val();
        if (checked)
        {
            if (mfile == '')
            {
                m_file_row.find('.alert').removeClass('alert-success');
                m_file_row.find('.alert').addClass('alert-danger');
                $('#error_msg_' + portfolio_id).html('When the box is ticked, the Document field is mandatory');
                m_file_row.find('.alert').show();
            } else
            {
                $('#updateMfile #portfolioid').val(portfolio_id);
                $('#updateMfile #modexpected').val('Y');
                $('#updateMfile #mfileslink').val(mfile);
                var data = $('#updateMfile').serialize();
                $.ajax({
                    url: '/damsv2/monitoringvisit/save-m-file',
                    type: 'post',
                    dataType: 'json',
                    data: data
                }).done(function (data) {
                    result_saving(data, portfolio_id);
                });
            }
        } else
        {
            if (mfile != '')
            {
                m_file_row.find('.alert').removeClass('alert-success');
                m_file_row.find('.alert').addClass('alert-danger');
                $('#error_msg_' + portfolio_id).html('When the box is not ticked, the Document field should remain empty');
                m_file_row.find('.alert').show();
            } else
            {
                $('#updateMfile #portfolioid').val(portfolio_id);
                $('#mfileslink' + portfolio_id).val(''); //empty the 'document' field in view
                $('#updateMfile #modexpected').val('N');
                $('#updateMfile #mfileslink').val('');
                var data = $('#updateMfile').serialize();
                $.ajax({
                    url: '/damsv2/monitoringvisit/save-m-file',
                    type: 'post',
                    dataType: 'json',
                    data: data                    
                }).done(function (data) {
                    result_saving(data, portfolio_id);
                });
            }
        }
    });
}

function style_empty(e)
{
    var val = $(e.target).val();
    if (val != '')
    {
        $(e.target).css('border', '');
    }
}

function result_saving(data, portfolio_id)
{
    var m_file_row = $("#mFileRows").find('#row_' + portfolio_id);
    if (!$.trim(data))
    {
        //show error
        m_file_row.find('.alert').removeClass('alert-success');
        m_file_row.find('.alert').addClass('alert-danger');
        $('#error_msg_' + portfolio_id).html('Update failed');
        m_file_row.find('.alert').show();
    } else
    {        
        if (data.Portfolio.change)
        {
            if (data.Portfolio.error)
            {
                m_file_row.find('.alert').removeClass('alert-success');
                m_file_row.find('.alert').addClass('alert-danger');
                $('#error_msg_' + portfolio_id).html('Update failed');
            } else
            {
                m_file_row.find('.alert').removeClass('alert-danger');
                m_file_row.find('.alert').addClass('alert-success');
                $('#error_msg_' + portfolio_id).html('Update successful');
            }
            m_file_row.find('.alert').show();
        }
    }
}

$(document).ready(function () {
    $('#mvfilters #portfolioid, #mvfilters #productid').change(function (e) {
        $('#mvfilters').submit();
    });
    
    $('#save_m_file').mousedown(function (e)
    {
        check_mandatory_m_file();
        return false;
    });
    
    $('.alert').hide();
 
    $('input').keydown(style_empty);
});
</script>
