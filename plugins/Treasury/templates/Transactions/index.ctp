<h2>Transactions</h2>

<table class='table table-bordered table-stripped'>
    <!-- table heading -->
    <tr style='background-color:#fff;'>
        <th>TRN</th>
        <th>Type</th>
        <th>State</th>
        <th>Amount</th>
        <th>Com Date</th>
        <th>Maturity Date</th>
        <th>Depo Term</th>
        <th>Interest Rate</th>
        <th>Total interest</th>
        <th>Depo type</th>
        <th>Depo renew</th>
        <th>Rate type</th>
        <th>Mandate</th>
        <th>Counterparty</th>
        <th>Account A</th>
        <th>Account B</th>
        <th>Booking</th>
        <th>Actions</th>
    </tr>

<?php foreach( $users as $user ): ?>

    <tr>
        <td><?php echo $transactions['Transaction']['tr_number'] ?></td>
        <td><?php echo $transactions['Transaction']['tr_number'] ?></td>
        <td class='actions'>
                <?php echo $this->Html->link( 'Edit', array('action' => 'edit', $user['User']['id']) );

                //in cakephp 2.0, we won't use get request for deleting records
                //we use post request (for security purposes)
                echo $this->Form->postLink( 'Delete', array(
                        'action' => 'delete',
                        $user['User']['id']), array(
                            'confirm'=>'Are you sure you want to delete that user?' ) );
            echo "</td>";
        echo "</tr>";
        ?>
<?php endforeach; ?>

</table>