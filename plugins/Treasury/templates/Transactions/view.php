<?php
/**
 * @var \App\View\AppView $this
 * @var \Treasury\Model\Entity\Transaction $transaction
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Html->link(__('Edit Transaction'), ['action' => 'edit', $transaction->tr_number], ['class' => 'side-nav-item']) ?>
            <?= $this->Form->postLink(__('Delete Transaction'), ['action' => 'delete', $transaction->tr_number], ['confirm' => __('Are you sure you want to delete # {0}?', $transaction->tr_number), 'class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('List Transactions'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
            <?= $this->Html->link(__('New Transaction'), ['action' => 'add'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="transactions view content">
            <h3><?= h($transaction->tr_number) ?></h3>
            <table>
                <tr>
                    <th><?= __('Tr Type') ?></th>
                    <td><?= h($transaction->tr_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tr State') ?></th>
                    <td><?= h($transaction->tr_state) ?></td>
                </tr>
                <tr>
                    <th><?= __('Parent Transaction') ?></th>
                    <td><?= $transaction->has('parent_transaction') ? $this->Html->link($transaction->parent_transaction->tr_number, ['controller' => 'Transactions', 'action' => 'view', $transaction->parent_transaction->tr_number]) : '' ?></td>
                </tr>
                <tr>
                    <th><?= __('External Ref') ?></th>
                    <td><?= h($transaction->external_ref) ?></td>
                </tr>
                <tr>
                    <th><?= __('Depo Term') ?></th>
                    <td><?= h($transaction->depo_term) ?></td>
                </tr>
                <tr>
                    <th><?= __('Depo Type') ?></th>
                    <td><?= h($transaction->depo_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Depo Renew') ?></th>
                    <td><?= h($transaction->depo_renew) ?></td>
                </tr>
                <tr>
                    <th><?= __('Rate Type') ?></th>
                    <td><?= h($transaction->rate_type) ?></td>
                </tr>
                <tr>
                    <th><?= __('Date Basis') ?></th>
                    <td><?= h($transaction->date_basis) ?></td>
                </tr>
                <tr>
                    <th><?= __('Scheme') ?></th>
                    <td><?= h($transaction->scheme) ?></td>
                </tr>
                <tr>
                    <th><?= __('AccountA IBAN') ?></th>
                    <td><?= h($transaction->accountA_IBAN) ?></td>
                </tr>
                <tr>
                    <th><?= __('AccountB IBAN') ?></th>
                    <td><?= h($transaction->accountB_IBAN) ?></td>
                </tr>
                <tr>
                    <th><?= __('Ps Account') ?></th>
                    <td><?= h($transaction->ps_account) ?></td>
                </tr>
                <tr>
                    <th><?= __('Booking Status') ?></th>
                    <td><?= h($transaction->booking_status) ?></td>
                </tr>
                <tr>
                    <th><?= __('Eom Booking') ?></th>
                    <td><?= h($transaction->eom_booking) ?></td>
                </tr>
                <tr>
                    <th><?= __('Source Fund') ?></th>
                    <td><?= h($transaction->source_fund) ?></td>
                </tr>
                <tr>
                    <th><?= __('Benchmark') ?></th>
                    <td><?= h($transaction->benchmark) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tr Number') ?></th>
                    <td><?= $this->Number->format($transaction->tr_number) ?></td>
                </tr>
                <tr>
                    <th><?= __('Source Group') ?></th>
                    <td><?= $this->Number->format($transaction->source_group) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reinv Group') ?></th>
                    <td><?= $this->Number->format($transaction->reinv_group) ?></td>
                </tr>
                <tr>
                    <th><?= __('Original Id') ?></th>
                    <td><?= $this->Number->format($transaction->original_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Linked Trn') ?></th>
                    <td><?= $this->Number->format($transaction->linked_trn) ?></td>
                </tr>
                <tr>
                    <th><?= __('Amount') ?></th>
                    <td><?= $this->Number->format($transaction->amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Interest Rate') ?></th>
                    <td><?= $this->Number->format($transaction->interest_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Total Interest') ?></th>
                    <td><?= $this->Number->format($transaction->total_interest) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tax Amount') ?></th>
                    <td><?= $this->Number->format($transaction->tax_amount) ?></td>
                </tr>
                <tr>
                    <th><?= __('Mandate ID') ?></th>
                    <td><?= $this->Number->format($transaction->mandate_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cmp ID') ?></th>
                    <td><?= $this->Number->format($transaction->cmp_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Instr Num') ?></th>
                    <td><?= $this->Number->format($transaction->instr_num) ?></td>
                </tr>
                <tr>
                    <th><?= __('Cpty Id') ?></th>
                    <td><?= $this->Number->format($transaction->cpty_id) ?></td>
                </tr>
                <tr>
                    <th><?= __('Accrued Interst') ?></th>
                    <td><?= $this->Number->format($transaction->accrued_interst) ?></td>
                </tr>
                <tr>
                    <th><?= __('Accrued Tax') ?></th>
                    <td><?= $this->Number->format($transaction->accrued_tax) ?></td>
                </tr>
                <tr>
                    <th><?= __('Eom Interest') ?></th>
                    <td><?= $this->Number->format($transaction->eom_interest) ?></td>
                </tr>
                <tr>
                    <th><?= __('Eom Tax') ?></th>
                    <td><?= $this->Number->format($transaction->eom_tax) ?></td>
                </tr>
                <tr>
                    <th><?= __('Tax ID') ?></th>
                    <td><?= $this->Number->format($transaction->tax_ID) ?></td>
                </tr>
                <tr>
                    <th><?= __('Reference Rate') ?></th>
                    <td><?= $this->Number->format($transaction->reference_rate) ?></td>
                </tr>
                <tr>
                    <th><?= __('Spread Bp') ?></th>
                    <td><?= $this->Number->format($transaction->spread_bp) ?></td>
                </tr>
                <tr>
                    <th><?= __('Commencement Date') ?></th>
                    <td><?= h($transaction->commencement_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Maturity Date') ?></th>
                    <td><?= h($transaction->maturity_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Indicative Maturity Date') ?></th>
                    <td><?= h($transaction->indicative_maturity_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Fixing Date') ?></th>
                    <td><?= h($transaction->fixing_date) ?></td>
                </tr>
                <tr>
                    <th><?= __('Created') ?></th>
                    <td><?= h($transaction->created) ?></td>
                </tr>
                <tr>
                    <th><?= __('Modified') ?></th>
                    <td><?= h($transaction->modified) ?></td>
                </tr>
            </table>
            <div class="text">
                <strong><?= __('Comment') ?></strong>
                <blockquote>
                    <?= $this->Text->autoParagraph(h($transaction->comment)); ?>
                </blockquote>
            </div>
            <div class="related">
                <h4><?= __('Related Bonds') ?></h4>
                <?php if (!empty($transaction->bonds)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Bond Id') ?></th>
                            <th><?= __('ISIN') ?></th>
                            <th><?= __('State') ?></th>
                            <th><?= __('Currency') ?></th>
                            <th><?= __('Issuer') ?></th>
                            <th><?= __('Issue Date') ?></th>
                            <th><?= __('First Coupon Accrual Date') ?></th>
                            <th><?= __('First Coupon Payment Date') ?></th>
                            <th><?= __('Maturity Date') ?></th>
                            <th><?= __('Coupon Rate') ?></th>
                            <th><?= __('Coupon Frequency') ?></th>
                            <th><?= __('Date Basis') ?></th>
                            <th><?= __('Date Convention') ?></th>
                            <th><?= __('Tax Rate') ?></th>
                            <th><?= __('Country') ?></th>
                            <th><?= __('Issue Size') ?></th>
                            <th><?= __('Covered') ?></th>
                            <th><?= __('Secured') ?></th>
                            <th><?= __('Seniority') ?></th>
                            <th><?= __('Guarantor') ?></th>
                            <th><?= __('Structured') ?></th>
                            <th><?= __('Issuer Type') ?></th>
                            <th><?= __('Issue Rating STP') ?></th>
                            <th><?= __('Issue Rating MDY') ?></th>
                            <th><?= __('Issue Rating FIT') ?></th>
                            <th><?= __('Retained Rating') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($transaction->bonds as $bonds) : ?>
                        <tr>
                            <td><?= h($bonds->bond_id) ?></td>
                            <td><?= h($bonds->ISIN) ?></td>
                            <td><?= h($bonds->state) ?></td>
                            <td><?= h($bonds->currency) ?></td>
                            <td><?= h($bonds->issuer) ?></td>
                            <td><?= h($bonds->issue_date) ?></td>
                            <td><?= h($bonds->first_coupon_accrual_date) ?></td>
                            <td><?= h($bonds->first_coupon_payment_date) ?></td>
                            <td><?= h($bonds->maturity_date) ?></td>
                            <td><?= h($bonds->coupon_rate) ?></td>
                            <td><?= h($bonds->coupon_frequency) ?></td>
                            <td><?= h($bonds->date_basis) ?></td>
                            <td><?= h($bonds->date_convention) ?></td>
                            <td><?= h($bonds->tax_rate) ?></td>
                            <td><?= h($bonds->country) ?></td>
                            <td><?= h($bonds->issue_size) ?></td>
                            <td><?= h($bonds->covered) ?></td>
                            <td><?= h($bonds->secured) ?></td>
                            <td><?= h($bonds->seniority) ?></td>
                            <td><?= h($bonds->guarantor) ?></td>
                            <td><?= h($bonds->structured) ?></td>
                            <td><?= h($bonds->issuer_type) ?></td>
                            <td><?= h($bonds->issue_rating_STP) ?></td>
                            <td><?= h($bonds->issue_rating_MDY) ?></td>
                            <td><?= h($bonds->issue_rating_FIT) ?></td>
                            <td><?= h($bonds->retained_rating) ?></td>
                            <td><?= h($bonds->created) ?></td>
                            <td><?= h($bonds->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Bonds', 'action' => 'view', $bonds->bond_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Bonds', 'action' => 'edit', $bonds->bond_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Bonds', 'action' => 'delete', $bonds->bond_id], ['confirm' => __('Are you sure you want to delete # {0}?', $bonds->bond_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Histo Ps') ?></h4>
                <?php if (!empty($transaction->histo_ps)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Histo Id') ?></th>
                            <th><?= __('Bu Ps') ?></th>
                            <th><?= __('Bu Gl') ?></th>
                            <th><?= __('Ledger Group') ?></th>
                            <th><?= __('Transaction Id') ?></th>
                            <th><?= __('Transaction Line') ?></th>
                            <th><?= __('Accounting Dt') ?></th>
                            <th><?= __('Account') ?></th>
                            <th><?= __('Deptid') ?></th>
                            <th><?= __('Product') ?></th>
                            <th><?= __('Pic Tiers') ?></th>
                            <th><?= __('Portefeuille') ?></th>
                            <th><?= __('Type De Taux') ?></th>
                            <th><?= __('Idpgl') ?></th>
                            <th><?= __('Code Region') ?></th>
                            <th><?= __('Grp Produit') ?></th>
                            <th><?= __('Origine Fond') ?></th>
                            <th><?= __('Code Mandat') ?></th>
                            <th><?= __('Project Code') ?></th>
                            <th><?= __('Jrnl Ln Ref') ?></th>
                            <th><?= __('Foreign Currency') ?></th>
                            <th><?= __('Foreign Amount') ?></th>
                            <th><?= __('Line Descr') ?></th>
                            <th><?= __('Abd Date') ?></th>
                            <th><?= __('Trans Ref Num') ?></th>
                            <th><?= __('Tr Number') ?></th>
                            <th><?= __('Header Description') ?></th>
                            <th><?= __('Revision') ?></th>
                            <th><?= __('Book Type') ?></th>
                            <th><?= __('Eom Booking') ?></th>
                            <th><?= __('Created') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($transaction->histo_ps as $histoPs) : ?>
                        <tr>
                            <td><?= h($histoPs->histo_id) ?></td>
                            <td><?= h($histoPs->bu_ps) ?></td>
                            <td><?= h($histoPs->bu_gl) ?></td>
                            <td><?= h($histoPs->ledger_group) ?></td>
                            <td><?= h($histoPs->transaction_id) ?></td>
                            <td><?= h($histoPs->transaction_line) ?></td>
                            <td><?= h($histoPs->accounting_dt) ?></td>
                            <td><?= h($histoPs->account) ?></td>
                            <td><?= h($histoPs->deptid) ?></td>
                            <td><?= h($histoPs->product) ?></td>
                            <td><?= h($histoPs->pic_tiers) ?></td>
                            <td><?= h($histoPs->portefeuille) ?></td>
                            <td><?= h($histoPs->type_de_taux) ?></td>
                            <td><?= h($histoPs->idpgl) ?></td>
                            <td><?= h($histoPs->code_region) ?></td>
                            <td><?= h($histoPs->grp_produit) ?></td>
                            <td><?= h($histoPs->origine_fond) ?></td>
                            <td><?= h($histoPs->code_mandat) ?></td>
                            <td><?= h($histoPs->project_code) ?></td>
                            <td><?= h($histoPs->jrnl_ln_ref) ?></td>
                            <td><?= h($histoPs->foreign_currency) ?></td>
                            <td><?= h($histoPs->foreign_amount) ?></td>
                            <td><?= h($histoPs->line_descr) ?></td>
                            <td><?= h($histoPs->abd_date) ?></td>
                            <td><?= h($histoPs->trans_ref_num) ?></td>
                            <td><?= h($histoPs->tr_number) ?></td>
                            <td><?= h($histoPs->header_description) ?></td>
                            <td><?= h($histoPs->revision) ?></td>
                            <td><?= h($histoPs->book_type) ?></td>
                            <td><?= h($histoPs->eom_booking) ?></td>
                            <td><?= h($histoPs->created) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'HistoPs', 'action' => 'view', $histoPs->histo_id]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'HistoPs', 'action' => 'edit', $histoPs->histo_id]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'HistoPs', 'action' => 'delete', $histoPs->histo_id], ['confirm' => __('Are you sure you want to delete # {0}?', $histoPs->histo_id)]) ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
                <?php endif; ?>
            </div>
            <div class="related">
                <h4><?= __('Related Transactions') ?></h4>
                <?php if (!empty($transaction->child_transactions)) : ?>
                <div class="table-responsive">
                    <table>
                        <tr>
                            <th><?= __('Tr Number') ?></th>
                            <th><?= __('Tr Type') ?></th>
                            <th><?= __('Tr State') ?></th>
                            <th><?= __('Source Group') ?></th>
                            <th><?= __('Reinv Group') ?></th>
                            <th><?= __('Original Id') ?></th>
                            <th><?= __('Parent Id') ?></th>
                            <th><?= __('Linked Trn') ?></th>
                            <th><?= __('External Ref') ?></th>
                            <th><?= __('Amount') ?></th>
                            <th><?= __('Commencement Date') ?></th>
                            <th><?= __('Maturity Date') ?></th>
                            <th><?= __('Indicative Maturity Date') ?></th>
                            <th><?= __('Depo Term') ?></th>
                            <th><?= __('Interest Rate') ?></th>
                            <th><?= __('Total Interest') ?></th>
                            <th><?= __('Tax Amount') ?></th>
                            <th><?= __('Depo Type') ?></th>
                            <th><?= __('Depo Renew') ?></th>
                            <th><?= __('Rate Type') ?></th>
                            <th><?= __('Date Basis') ?></th>
                            <th><?= __('Mandate ID') ?></th>
                            <th><?= __('Cmp ID') ?></th>
                            <th><?= __('Scheme') ?></th>
                            <th><?= __('AccountA IBAN') ?></th>
                            <th><?= __('AccountB IBAN') ?></th>
                            <th><?= __('Instr Num') ?></th>
                            <th><?= __('Cpty Id') ?></th>
                            <th><?= __('Ps Account') ?></th>
                            <th><?= __('Booking Status') ?></th>
                            <th><?= __('Eom Booking') ?></th>
                            <th><?= __('Accrued Interst') ?></th>
                            <th><?= __('Accrued Tax') ?></th>
                            <th><?= __('Fixing Date') ?></th>
                            <th><?= __('Eom Interest') ?></th>
                            <th><?= __('Eom Tax') ?></th>
                            <th><?= __('Tax ID') ?></th>
                            <th><?= __('Source Fund') ?></th>
                            <th><?= __('Comment') ?></th>
                            <th><?= __('Reference Rate') ?></th>
                            <th><?= __('Spread Bp') ?></th>
                            <th><?= __('Benchmark') ?></th>
                            <th><?= __('Created') ?></th>
                            <th><?= __('Modified') ?></th>
                            <th class="actions"><?= __('Actions') ?></th>
                        </tr>
                        <?php foreach ($transaction->child_transactions as $childTransactions) : ?>
                        <tr>
                            <td><?= h($childTransactions->tr_number) ?></td>
                            <td><?= h($childTransactions->tr_type) ?></td>
                            <td><?= h($childTransactions->tr_state) ?></td>
                            <td><?= h($childTransactions->source_group) ?></td>
                            <td><?= h($childTransactions->reinv_group) ?></td>
                            <td><?= h($childTransactions->original_id) ?></td>
                            <td><?= h($childTransactions->parent_id) ?></td>
                            <td><?= h($childTransactions->linked_trn) ?></td>
                            <td><?= h($childTransactions->external_ref) ?></td>
                            <td><?= h($childTransactions->amount) ?></td>
                            <td><?= h($childTransactions->commencement_date) ?></td>
                            <td><?= h($childTransactions->maturity_date) ?></td>
                            <td><?= h($childTransactions->indicative_maturity_date) ?></td>
                            <td><?= h($childTransactions->depo_term) ?></td>
                            <td><?= h($childTransactions->interest_rate) ?></td>
                            <td><?= h($childTransactions->total_interest) ?></td>
                            <td><?= h($childTransactions->tax_amount) ?></td>
                            <td><?= h($childTransactions->depo_type) ?></td>
                            <td><?= h($childTransactions->depo_renew) ?></td>
                            <td><?= h($childTransactions->rate_type) ?></td>
                            <td><?= h($childTransactions->date_basis) ?></td>
                            <td><?= h($childTransactions->mandate_ID) ?></td>
                            <td><?= h($childTransactions->cmp_ID) ?></td>
                            <td><?= h($childTransactions->scheme) ?></td>
                            <td><?= h($childTransactions->accountA_IBAN) ?></td>
                            <td><?= h($childTransactions->accountB_IBAN) ?></td>
                            <td><?= h($childTransactions->instr_num) ?></td>
                            <td><?= h($childTransactions->cpty_id) ?></td>
                            <td><?= h($childTransactions->ps_account) ?></td>
                            <td><?= h($childTransactions->booking_status) ?></td>
                            <td><?= h($childTransactions->eom_booking) ?></td>
                            <td><?= h($childTransactions->accrued_interst) ?></td>
                            <td><?= h($childTransactions->accrued_tax) ?></td>
                            <td><?= h($childTransactions->fixing_date) ?></td>
                            <td><?= h($childTransactions->eom_interest) ?></td>
                            <td><?= h($childTransactions->eom_tax) ?></td>
                            <td><?= h($childTransactions->tax_ID) ?></td>
                            <td><?= h($childTransactions->source_fund) ?></td>
                            <td><?= h($childTransactions->comment) ?></td>
                            <td><?= h($childTransactions->reference_rate) ?></td>
                            <td><?= h($childTransactions->spread_bp) ?></td>
                            <td><?= h($childTransactions->benchmark) ?></td>
                            <td><?= h($childTransactions->created) ?></td>
                            <td><?= h($childTransactions->modified) ?></td>
                            <td class="actions">
                                <?= $this->Html->link(__('View'), ['controller' => 'Transactions', 'action' => 'view', $childTransactions->tr_number]) ?>
                                <?= $this->Html->link(__('Edit'), ['controller' => 'Transactions', 'action' => 'edit', $childTransactions->tr_number]) ?>
                                <?= $this->Form->postLink(__('Delete'), ['controller' => 'Transactions', 'action' => 'delete', $childTransactions->tr_number], ['confirm' => __('Are you sure you want to delete # {0}?', $childTransactions->tr_number)]) ?>
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
