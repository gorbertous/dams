<?php

declare(strict_types=1);

namespace Damsv2\Controller;

use Cake\Datasource\ConnectionManager;
use Cake\Event\EventInterface;

/**
 * Template Controller
 *
 * @property \App\Model\Table\TemplateTable $Template
 * @method \App\Model\Entity\Template[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class TemplateController extends AppController
{

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        $this->loadComponent('Spreadsheet');
        $this->loadComponent('File');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);
        //$this->Security->setConfig('unlockedActions', ['inclusion']);
        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null|void Renders view
     */
    public function index()
    {
        $this->paginate = [
            'contain' => ['TemplateType'],
        ];
        $template = $this->paginate($this->Template);

        $this->set(compact('template'));
    }

    /**
     * View method
     *
     * @param string|null $id Template id.
     * @return \Cake\Http\Response|null|void Renders view
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($id = null)
    {
        $template = $this->Template->get($id, [
            'contain' => ['TemplateType', 'Portfolio'],
        ]);

        $this->set(compact('template'));
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null|void Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $template = $this->Template->newEmptyEntity();
        if ($this->request->is('post')) {
            $template = $this->Template->patchEntity($template, $this->request->getData());
            if ($this->Template->save($template)) {
                $this->Flash->success(__('The template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The template could not be saved. Please, try again.'));
        }
        $templateTypes = $this->Template->TemplateType->find('list', ['limit' => 200]);
        $portfolio = $this->Template->Portfolio->find('list', ['limit' => 200]);
        $this->set(compact('template', 'templateTypes', 'callbacks', 'portfolio'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Template id.
     * @return \Cake\Http\Response|null|void Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $template = $this->Template->get($id, [
            'contain' => ['Portfolio'],
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $template = $this->Template->patchEntity($template, $this->request->getData());
            if ($this->Template->save($template)) {
                $this->Flash->success(__('The template has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The template could not be saved. Please, try again.'));
        }
        $templateTypes = $this->Template->TemplateType->find('list', ['limit' => 200]);
        $portfolio = $this->Template->Portfolio->find('list', ['limit' => 200]);
        $this->set(compact('template', 'templateTypes', 'callbacks', 'portfolio'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Template id.
     * @return \Cake\Http\Response|null|void Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $template = $this->Template->get($id);
        if ($this->Template->delete($template)) {
            $this->Flash->success(__('The template has been deleted.'));
        } else {
            $this->Flash->error(__('The template could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    public function dashboard()
    {
        $this->loadModel('Damsv2.Product');
        $this->loadModel('Damsv2.Portfolio');
        $products = $this->Product->getProducts();

        $portfolios = $templates = [];
        $post = false;
        $this->set(compact('products', 'portfolios', 'templates', 'post'));

        // FORM PROCESSING PART
        if ($this->request->is('post')) {
            $post = true;
            $product_id = $this->request->getData('Product.product_id');
            $portfolio_id = $this->request->getData('Portfolio.portfolio_id');
            if (strpos($portfolio_id, '_') !== false) {// if umbrella
                $id = explode('_', $portfolio_id);
                $portfolio_id = $id[1];
            }

            if (strpos($portfolio_id, '_') !== false) {// if umbrella
                $id = explode('_', $portfolio_id);
                $portfolio_id = $id[1];
            }

            $portfolios = $this->Portfolio->getPortfoliosByProductIdwithUmbrellaAndSubportfolio($product_id);

            $portfolio = $this->Portfolio->find('all', [
                        'contain'    => ['Template', 'Template.TemplateType'],
                        'conditions' => ['Portfolio.portfolio_id' => $portfolio_id]
                    ])->first();

            $template_types = [1, 2, 3, 5];
            if (count($portfolio->template) > 0) {
                foreach ($portfolio->template as $template) {
                    if (in_array($template->template_type_id, $template_types)) {
                        $url = '/damsv2/ajax/download-file/';
                        $short_name = $template->name;
                        if (strpos($template->name, 'Inclusion') !== false) {
                            $short_name = "Inclusion";
                        }
                        if (strpos($template->name, 'PD') !== false) {
                            $short_name = "PD";
                        }
                        if (strpos($template->name, 'LR') !== false) {
                            $short_name = "LR";
                        }
                        if (strpos($template->name, 'Edit') !== false) {
                            $short_name = "EDIT DATA";
                        }

                        //specific to Edit Inclusion INNOVFIN Direct&Counter
                        if ($template->name == "Edit Inclusion INNOVFIN Direct&Counter") {
                            $c_or_d = $this->getDirectOrCounter($portfolio);
                            if ($c_or_d == 'counter') {
                                $url .= '/EDIT_DATA_INNOVFIN_CG_template.xls/templates_test/' . $short_name;
                            } else {
                                $url .= '/EDIT_DATA_INNOVFIN_DG_template.xls/templates_test/' . $short_name;
                            }
                        } elseif ($template->name == "Edit Inclusion INNOVFIN Direct&Counter V2") {
                            $c_or_d = $this->getDirectOrCounter($portfolio);
                            if ($c_or_d == 'counter') {
                                $url .= '/EDIT_DATA_INNOVFIN_CG_v2_template.xls/templates_test/' . $short_name;
                            } else {
                                $url .= '/EDIT_DATA_INNOVFIN_DG_v2_template.xls/templates_test/' . $short_name;
                            }
                        } else {
                            $templateFile = $this->getTemplateFile($template->name);
                            if ($templateFile == "") {
                                $url = "";
                            } else {
                                $url .= $this->getTemplateFile($template->name) . '/templates_test/' . $short_name;
                            }
                        }
                        $templates[$template->name] = $url;
                    }
                }
                $templates['Business keys edit'] = '/damsv2/ajax/download-file/EDIT_BK_template.xls/templates_test/EDIT BK';
                $templates['Expired to performing'] = '/damsv2/ajax/download-file/From_EXPIRED_to_PERFORMING_template.xls/templates_test/EDIT DATA';
                $templates['To be Excluded'] = '/damsv2/ajax/download-file/EDIT_DATA_TBE_template.xls/templates_test/EDIT DATA';
            } else {
                $this->Flash->error(__('No template for this portfolio'));
                $this->redirect($this->referer());
            }
        }

        $this->set(compact('templates', 'post', 'portfolios'));
    }

    private function getDirectOrCounter($portfolio)
    {
        //template_type_id inclusion : 1
        $connection = ConnectionManager::get('default');
        $row = $connection->execute("SELECT * FROM template as t, template_portfolio as tp WHERE t.template_id = tp.template_id AND tp.portfolio_id = " . intval($portfolio->portfolio_id) . " AND t.template_type_id = 1")->fetchAll('assoc');
        $return = '';
        if (strpos(strtolower($row[0]['name']), 'direct') === false) {
            $return = 'counter';
        } else {
            $return = 'direct';
        }
        return $return;
    }

    private function getTemplateFile($name)
    {
        //@$this->validate_param('string', $name);// some file names have a '&'. it is not a user input though
        $name = trim($name, ' ');
        $files = [
            "Inclusion FLPG BG&RO"                                                 => "Inclusion_FLPG_BG%26RO_template.xls",
            "Inclusion FLPG Standard"                                              => "Inclusion_FLPG_template.xls",
            "PD FLPG BG&RO"                                                        => "PD_FLPG_BG%26RO_template.xls",
            "LR FLPG BG&RO"                                                        => "LR_FLPG_BG%26RO_template.xls",
            "Edit FLPG BG&RO"                                                      => "EDIT_DATA_FLPG_BG%26RO_template.xls",
            "Edit FLPG Standard"                                                   => "EDIT_DATA_FLPG_template.xls",
            "Inclusion FLPG SK"                                                    => "Inclusion_FLPG_SK_template.xls",
            "Edit FLPG SK"                                                         => "EDIT_DATA_FLPG_SK_template.xls",
            "Inclusion A WB"                                                       => "Inclusion_WB_template.xls",
            "PD WB"                                                                => "PD_WB_template.xls",
            "LR WB"                                                                => "LR_WB_template.xls",
            "Edit A WB"                                                            => "EDIT_DATA_WB_template.xls",
            "Inclusion Direct Guarantee RSI"                                       => "Inclusion_RSI_DG_template.xls",
            "PD Direct Guarantee RSI"                                              => "PD_RSI_DG_template.xls",
            "LR Direct Guarantee RSI"                                              => "LR_RSI_DG_template.xls",
            "Edit Direct Guarantee RSI"                                            => "EDIT_DATA_RSI_DG_template.xls",
            "Inclusion Direct Guarantee Halkbank RSI"                              => "Inclusion_RSI_DGH_template.xls",
            "PD Direct Guarantee Halkbank RSI"                                     => "PD_RSI_DGH_template.xls",
            "LR Direct Guarantee Halkbank RSI"                                     => "LR_RSI_DGH_template.xls",
            "Edit Direct Guarantee Halkbank RSI"                                   => "EDIT_DATA_RSI_DGH_template.xls",
            "Inclusion Direct Guarantee KfW RSI"                                   => "Inclusion_RSI_DGKFW_template.xls",
            "PD Direct Guarantee KfW RSI"                                          => "PD_RSI_DGKFW_template.xls",
            "LR Direct Guarantee KfW RSI"                                          => "LR_RSI_DGKFW_template.xls",
            "Edit Direct Guarantee KfW RSI"                                        => "EDIT_DATA_RSI_DGKFW_template.xls",
            "Inclusion Direct Guarantee Leasing RSI"                               => "Inclusion_RSI_DGL_template.xls",
            "PD Direct Guarantee Leasing RSI"                                      => "PD_RSI_DGL_template.xls",
            "LR Direct Guarantee Leasing RSI"                                      => "LR_RSI_DGL.xls",
            "Edit Direct Guarantee Leasing RSI"                                    => "EDIT_DATA_RSI_DGL_template.xls",
            "Inclusion Direct Guarantee LLR RSI"                                   => "Inclusion_RSI_DGLLR_template.xls",
            "PD Direct Guarantee LLR RSI"                                          => "PD_RSI_DGLLR_template.xls",
            "LR Direct Guarantee LLR RSI"                                          => "LR_RSI_DGLLR_template.xls",
            "Edit Direct Guarantee LLR RSI"                                        => "EDIT_DATA_RSI_DGLLR_template.xls",
            "Inclusion Counter Guarantee RSI"                                      => "Inclusion_RSI_CG_template.xls",
            "PD Counter Guarantee RSI"                                             => "PD_RSI_CG_template.xls",
            "LR Counter Guarantee RSI"                                             => "LR_RSI_CG_template.xls",
            "Edit Counter Guarantee RSI"                                           => "EDIT_DATA_RSI_CG_template.xls",
            "Inclusion PRSL"                                                       => "Inclusion_PRSL_template.xls",
            "Inclusion PRSL EX"                                                    => "Inclusion_PRSL_EX_template.xls",
            "Inclusion PRSL BG"                                                    => "Inclusion_PRSL_BG_template.xls",
            "Edit Inclusion PRSL"                                                  => "EDIT_DATA_PRSL_template.xls",
            "Edit Inclusion PRSL EX"                                               => "EDIT_DATA_PRSL_EX_template.xls",
            "Edit Inclusion PRSL BG"                                               => "EDIT_DATA_PRSL_BG_template.xls",
            "Inclusion INNOVFIN Direct"                                            => "Inclusion_INNOVFIN_DG_template.xls",
            "PD Inclusion INNOVFIN Direct"                                         => "PD_INNOVFIN_DG_template.xls",
            "LR Inclusion INNOVFIN Direct"                                         => "LR_INNOVFIN_DG_template.xls",
            "Inclusion INNOVFIN Counter"                                           => "Inclusion_INNOVFIN_CG_template.xls",
            "PD Inclusion INNOVFIN Counter"                                        => "PD_INNOVFIN_CG_template.xls",
            "LR Inclusion INNOVFIN Counter"                                        => "LR_INNOVFIN_CG_template.xls",
            "Edit Inclusion INNOVFIN Direct&Counter"                               => "EDIT_DATA_INNOVFIN_DG_template.xls",
            "Inclusion INNOVFIN Direct V2"                                         => "Inclusion_INNOVFIN_DG_v2_template.xls",
            "PD Inclusion INNOVFIN Direct V2"                                      => "PD_INNOVFIN_DG_v2_template.xls",
            "LR Inclusion INNOVFIN Direct V2"                                      => "LR_INNOVFIN_DG_v2_template.xls",
            "Inclusion INNOVFIN Counter V2"                                        => "Inclusion_INNOVFIN_CG_v2_template.xls",
            "PD Inclusion INNOVFIN Counter V2"                                     => "PD_INNOVFIN_CG_v2_template.xls",
            "LR Inclusion INNOVFIN Counter V2"                                     => "LR_INNOVFIN_CG_v2_template.xls",
            "Edit Inclusion INNOVFIN Direct&Counter V2"                            => "EDIT_DATA_INNOVFIN_DG_v2_template.xls",
            "Inclusion INNOVFIN On_Lending"                                        => "Inclusion_INNOVFIN_OL_template.xls",
            "PD Inclusion INNOVFIN On_Lending"                                     => "PD_INNOVFIN_OL_template.xls",
            "LR Inclusion INNOVFIN On_Lending"                                     => "LR_INNOVFIN_OL_template.xls",
            "Edit Inclusion INNOVFIN On_Lending"                                   => "EDIT_DATA_INNOVFIN_OL_template.xls",
            "Inclusion INNOVFIN Direct Anthilia"                                   => "Inclusion_INNOVFIN_DA_template.xls",
            "Edit Inclusion INNOVFIN Direct Anthilia"                              => "EDIT_DATA_INNOVFIN_DA_template.xls",
            "Inclusion COSME Direct"                                               => "Inclusion_COSME_DG_template.xls",
            "PD Inclusion COSME Direct"                                            => "PD_COSME_DG_template.xls",
            "LR Inclusion COSME Direct"                                            => "LR_COSME_DG_template.xls",
            "Edit Inclusion COSME Direct"                                          => "EDIT_DATA_COSME_DG_template.xls",
            "Inclusion COSME Counter"                                              => "Inclusion_COSME_CG_template.xls",
            "PD Inclusion COSME Counter"                                           => "PD_COSME_CG_template.xls",
            "LR Inclusion COSME Counter"                                           => "LR_COSME_CG_template.xls",
            "Edit Inclusion COSME Counter"                                         => "EDIT_DATA_COSME_CG_template.xls",
            "Inclusion COSME On-lending"                                           => "Inclusion_COSME_OL_template.xls",
            "PD Inclusion COSME On-lending Payment Demand"                         => "PD_COSME_OL_template.xls",
            "PD Inclusion COSME On-lending"                                        => "PD_COSME_OL_template.xls",
            "LR Inclusion COSME On-lending"                                        => "LR_COSME_OL_template.xls",
            "Edit Inclusion COSME On-lending"                                      => "EDIT_DATA_COSME_OL_template.xls",
            "Inclusion FCP SSL"                                                    => "Inclusion_FCP_SSL_template.xls",
            "Edit Inclusion FCP SSL"                                               => "EDIT_DATA_FCP_SSL_template.xls",
            "Inclusion FCP RS"                                                     => "Inclusion_FCP_RS_template.xls",
            "Edit Inclusion FCP RS"                                                => "EDIT_DATA_FCP_RS_template.xls",
            "Inclusion FMA Direct"                                                 => "Inclusion_FMA_DG_template.xls",
            "Edit Inclusion FMA Direct"                                            => "EDIT_DATA_FMA_DG_template.xls",
            "Inclusion FMA Counter"                                                => "Inclusion_FMA_CG_template.xls",
            "Edit Inclusion FMA Counter"                                           => "EDIT_DATA_FMA_CG_template.xls",
            "PD Inclusion FMA Direct"                                              => "PD_FMA_DG_template.xls",
            "PD Inclusion FMA Counter"                                             => "PD_FMA_CG_template.xls",
            "Inclusion FMA Direct Millenium"                                       => "Inclusion_FMA_DM_template.xls",
            "Edit Inclusion FMA Direct Millenium"                                  => "EDIT_DATA_FMA_DM_template.xls",
            "Inclusion CYPEF"                                                      => "Inclusion_CYPEF_template.xls",
            "Edit Inclusion CYPEF"                                                 => "EDIT_DATA_CYPEF_template.xls",
            "Inclusion ERASMUS"                                                    => "Inclusion_ERASMUS_DG_template.xls",
            "Edit Inclusion ERASMUS"                                               => "EDIT_DATA_ERASMUS_DG_template.xls",
            "PD Inclusion ERASMUS"                                                 => "PD_ERASMUS_DG_template.xls",
            "LR Inclusion ERASMUS"                                                 => "LR_ERASMUS_DG_template.xls",
            "Inclusion Loan Guarantee Facility Direct CIP"                         => "Inclusion_CIP_LGF_DG_template.xls",
            "Edit Inclusion Loan Guarantee Facility Direct CIP"                    => "EDIT_DATA_CIP_LGF_DG_template.xls",
            "PD Inclusion Loan Guarantee Facility Direct CIP"                      => "PD_CIP_LGF_DG_template.xls",
            "Inclusion Loan Guarantee Facility Counter CIP"                        => "Inclusion_CIP_LGF_CG_template.xls",
            "Edit Inclusion Loan Guarantee Facility Counter CIP"                   => "EDIT_DATA_CIP_LGF_CG_template.xls",
            "PD Inclusion Loan Guarantee Facility Counter CIP"                     => "PD_CIP_LGF_CG_template.xls",
            "Inclusion Micro Credit Direct CIP"                                    => "Inclusion_CIP_MC_DG_template.xls",
            "Edit Inclusion Micro Credit Direct CIP"                               => "EDIT_DATA_CIP_MC_DG_template.xls",
            "PD Inclusion Micro Credit Direct CIP"                                 => "PD_CIP_MC_DG_template.xls",
            "Inclusion Micro Credit Counter CIP"                                   => "Inclusion_CIP_MC_CG_template.xls",
            "Edit Inclusion Micro Credit Counter CIP"                              => "EDIT_DATA_CIP_MC_CG_template.xls",
            "PD Inclusion Micro Credit Counter CIP"                                => "PD_CIP_MC_CG_template.xls",
            "Inclusion Equity Guarantee Direct CIP"                                => "Inclusion_CIP_EQ_DG_template.xls",
            "Edit Inclusion Equity Guarantee Direct CIP"                           => "EDIT_DATA_CIP_EQ_DG_template.xls",
            "PD Inclusion Equity Guarantee Direct CIP"                             => "PD_CIP_EQ_CG_template.xls",
            "Inclusion Direct SMEi"                                                => "Inclusion_SMEi_DG_template.xls",
            "PD Inclusion Direct SMEi"                                             => "PD_SMEi_DG_template.xls",
            "LR Inclusion Direct SMEi"                                             => "LR_SMEi_DG_template.xls",
            "Edit Inclusion Direct SMEi"                                           => "EDIT_DATA_SMEi_DG_template.xls",
            "Edit Inclusion Direct SE EaSI"                                        => "EDIT_DATA_EASI_SE_DG_template.xls",
            "PD Inclusion Direct SE EaSI"                                          => "PD_EASI_SE_DG_template.xls",
            "Inclusion Direct SE EaSI"                                             => "Inclusion_EASI_SE_DG_template.xls",
            "Inclusion Direct MF EaSI"                                             => "Inclusion_EASI_MF_DG_template.xls",
            "PD Inclusion Direct MF EaSI"                                          => "PD_EASI_MF_DG_template.xls",
            "Edit Inclusion Direct MF EaSI"                                        => "EDIT_DATA_EASI_MF_DG_template.xls",
            "Inclusion Counter MF EaSI"                                            => "Inclusion_EASI_MF_CG_template.xls",
            "PD Inclusion Counter MF EaSI"                                         => "PD_EASI_MF_CG_template.xls",
            "Edit Inclusion Counter MF EaSI"                                       => "EDIT_DATA_EASI_MF_CG_template.xls",
            "Inclusion Counter SE EaSI"                                            => "Inclusion_EASI_SE_CG_template.xls",
            "Edit Inclusion Counter SE EaSI"                                       => "EDIT_DATA_EASI_SE_CG_template.xls",
            "PD Inclusion Counter SE EaSI"                                         => "PD_EASI_SE_CG_template.xls",
            "Inclusion INNOVFIN Counter V2 Fees"                                   => "Inclusion_INNOVFIN_CG_v2F_template.xls",
            "PD Inclusion INNOVFIN Counter V2 Fees"                                => "PD_INNOVFIN_CG_v2_template.xls",
            "LR Inclusion INNOVFIN Counter V2 Fees"                                => "LR_INNOVFIN_CG_v2_template.xls",
            "Edit Inclusion INNOVFIN Counter V2 Fees"                              => "EDIT_DATA_INNOVFIN_CG_v2F_template.xls",
            //"Inclusion INNOVFIN Counter V2 F temp" => "Inclusion_INNOVFIN_CG_v2_Fee.xls",
            "Inclusion GAGF Counter"                                               => "Inclusion_GAGF_CG_template.xls",
            "Inclusion GAGF Direct"                                                => "Inclusion_GAGF_DG_template.xls", // /!\
            "PD Inclusion GAGF Direct"                                             => "PD_GAGF_DG_template.xls",
            "PD Inclusion GAGF Counter"                                            => "PD_GAGF_CG_template.xls", // /!\
            "Edit Inclusion GAGF Direct"                                           => "EDIT_DATA_GAGF_DG_template.xls",
            "Edit Inclusion GAGF Counter"                                          => "EDIT_DATA_GAGF_CG_template.xls", // /!\
            //umbrella
            "Inclusion Procredit umbrella"                                         => "Inclusion_INNOVFIN_DG_v2_template.xls",
            "Edit Inclusion Procredit umbrella"                                    => "EDIT_DATA_INNOVFIN_DG_v2_template.xls",
            "Inclusion Banco Cooperativo umbrella"                                 => "Inclusion_SMEi_umbrella_template.xls",
            "Edit Inclusion Banco Cooperativo umbrella"                            => "EDIT_DATA_SMEi_umbrella_template.xls",
            "Inclusion Banco Cooperativo Sub_umbrella"                             => "Inclusion_SMEi_umbrella_template.xls",
            "Edit Inclusion Banco Cooperativo Sub_umbrella"                        => "EDIT_DATA_SMEi_DG_template.xls",
            "Inclusion Procredit Sub_umbrella"                                     => "Inclusion_INNOVFIN_DG_v2_template.xls",
            "Edit Inclusion Procredit Sub_umbrella"                                => "EDIT_DATA_INNOVFIN_DG_v2_template.xls",
//			"LR_FLPG_template"
            //CCS
            "Inclusion CCS Direct"                                                 => "Inclusion_CCS_DG_template.xls",
            "PD Inclusion CCS Direct"                                              => "PD_CCS_DG_template.xls",
            "LR Inclusion CCS Direct"                                              => "LR_CCS_template.xls",
            "Edit Inclusion CCS Direct"                                            => "EDIT_DATA_CCS_DG_template.xls",
            "Inclusion CCS Counter"                                                => "Inclusion_CCS_CG_template.xls",
            "PD Inclusion CCS Counter"                                             => "PD_CCS_CG_template.xls",
            "LR Inclusion CCS Counter"                                             => "LR_CCS_template.xls",
            "Edit Inclusion CCS Counter"                                           => "EDIT_DATA_CCS_CG_template.xls",
            "Inclusion CCS On-lending"                                             => "Inclusion_CCS_OL_template.xls",
            "PD Inclusion CCS On-lending"                                          => "PD_CCS_OL_template.xls",
            "LR Inclusion CCS On-lending"                                          => "LR_CCS_template.xls",
            "Edit Inclusion CCS On-lending"                                        => "EDIT_DATA_CCS_OL_template.xls",
            "Edit Converted Revolving CCS"                                         => "", //should stay empty
            //WB2
            'Inclusion Direct WB EDIF2'                                            => 'Inclusion_WB_EDIF2_DG_template.xls',
            'PD Inclusion Direct WB EDIF2'                                         => 'PD_WB_EDIF2_DG_template.xls',
            'LR Inclusion Direct WB EDIF2'                                         => 'LR_WB_EDIF2_DG_template.xls',
            'Edit Inclusion Direct WB EDIF2'                                       => 'EDIT_DATA_WB_EDIF2_DG_template.xls',
            // Direct SMEi Bulgaria
            'PD Inclusion Direct SMEi Bulgaria'                                    => 'PD_SMEi_Bulgaria_template.xls',
            'LR Inclusion Direct SMEi Bulgaria'                                    => 'LR_SMEi_Bulgaria_template.xls',
            'Edit Inclusion Direct SMEi Bulgaria'                                  => 'EDIT_DATA_SMEi_Bulgaria_template.xls',
            'Inclusion Direct SMEi Bulgaria'                                       => 'Inclusion_SMEi_Bulgaria_template.xls',
            'Edit Converted Revolving SMEi Bulgaria'                               => 'EDIT_DATA_SMEi_Bulgaria_template.xls',
            //CBSI
            'Inclusion Direct EREM CBSI'                                           => 'Inclusion_CBSI_template.xls',
            'Edit Inclusion Direct EREM CBSI'                                      => 'EDIT_DATA_CBSI_template.xls',
            // FOSTER PRSL
            "Recoveries FOSTER PRSL"                                               => 'R_PRSL_FOSTER_template.xls',
            "Inclusion FOSTER PRSL"                                                => 'Inclusion_PRSL_FOSTER_template.xls',
            "Edit FOSTER PRSL"                                                     => 'EDIT_DATA_PRSL_FOSTER_template.xls',
            //FOSTER FLPG
            "Inclusion FOSTER FLPG"                                                => "Inclusion_FOSTER_FLPG_template.xls",
            "PD Inclusion FOSTER FLPG"                                             => "PD_FOSTER_FLPG_template.xls",
            "LR Inclusion FOSTER FLPG"                                             => "LR_FOSTER_FLPG_template.xls",
            "Edit Inclusion FOSTER FLPG"                                           => "EDIT_DATA_FOSTER_FLPG_template.xls",
            //FOSTER AGRI
            "Inclusion FOSTER AGRI"                                                => "Inclusion_FOSTER_AGRI_template.xls",
            "PD Inclusion FOSTER AGRI"                                             => "PD_FOSTER_AGRI_template.xls",
            "LR Inclusion FOSTER AGRI"                                             => "LR_FOSTER_AGRI_template.xls",
            "Edit Inclusion FOSTER AGRI"                                           => "EDIT_DATA_FOSTER_AGRI_template.xls",
            //JEREMIE FRSP
            "Inclusion JEREMIE FRSP Greece"                                        => "Inclusion_JEREMIE_FRSP_Greece_template.xls",
            "Inclusion JEREMIE FRSP"                                               => "Inclusion_JEREMIE_FRSP_standard_template.xls",
            "Edit JEREMIE FRSP Greece"                                             => "EDIT_DATA_JEREMIE_FRSP_Greece_template.xls",
            "Edit JEREMIE FRSP"                                                    => "EDIT_DATA_JEREMIE_FRSP_standard_template.xlsx",
            //EASI V2
            "Edit Inclusion Counter SE EaSI v2"                                    => "EDIT_DATA_EASI_SE_CG_v2_template.xls",
            "PD Inclusion Counter SE EaSI v2"                                      => "PD_EASI_SE_CG_v2_template.xls",
            "Inclusion Counter SE EaSI v2"                                         => "Inclusion_EASI_SE_CG_v2_template.xls",
            "Edit Inclusion Direct SE EaSI v2"                                     => "EDIT_DATA_EASI_SE_DG_v2_template.xls",
            "PD Inclusion Direct SE EaSI v2"                                       => "PD_EASI_SE_DG_v2_template.xls",
            "Inclusion Direct SE EaSI v2"                                          => "Inclusion_EASI_SE_DG_v2_template.xls",
            //2.5 InnovFin DGv2 sub-debt
            "Edit Converted Revolving INNOVFIN Direct V2 sub-debt"                 => "",
            "Edit Inclusion INNOVFIN Direct V2 sub-debt"                           => "EDIT_DATA_INNOVFIN_DG_v2_subdebt_template.xls",
            "LR Inclusion INNOVFIN Direct V2 sub-debt"                             => "LR_INNOVFIN_DG_v2_template.xls",
            "PD Inclusion INNOVFIN Direct V2 sub-debt"                             => "PD_INNOVFIN_DG_v2_template.xls",
            "Inclusion INNOVFIN Direct V2 sub-debt"                                => "Inclusion_INNOVFIN_DG_v2_subdebt_template.xls",
            //DCFTA
            "LR Inclusion DCFTA"                                                   => "LR_DCFTA_template.xls",
            "PD Inclusion DCFTA"                                                   => "PD_DCFTA_template.xls",
            "Inclusion DCFTA"                                                      => "Inclusion_DCFTA_template.xls",
            "Edit DCFTA"                                                           => "EDIT_DATA_DCFTA_template.xls",
            // SME initiative Italy
            "Inclusion SMEi - Italy"                                               => "Inclusion_SMEi_Italy_template.xls",
            "Edit SMEi - Italy"                                                    => "EDIT_DATA_SMEi_Italy_template.xls",
            //esif prsl
            "Edit ESIF PRSL"                                                       => 'EDIT_DATA_ESIF_PRSL_template.xls',
            "Inclusion ESIF PRSL"                                                  => 'Inclusion_ESIF_PRSL_template.xls',
            "Recoveries ESIF PRSL"                                                 => 'LR_ESIF_PRSL_template.xls',
            // WB EDIF 2 Serbia, DAMS 642
            "Edit Inclusion Direct Serbia WB EDIF2"                                => "EDIT_DATA_Serbia_WB_EDIF2_DG_template.xls",
            "Inclusion Direct Serbia WB EDIF2"                                     => "Inclusion_Serbia_WB_EDIF2_DG_template.xls",
            "LR Inclusion Direct Serbia WB EDIF2"                                  => "LR_Serbia_WB_EDIF2_DG_template.xls",
            "PD Inclusion Direct Serbia WB EDIF2"                                  => "PD_Serbia_WB_EDIF2_DG_template.xls",
            // ESIF AGRI PRSL Romania
            'Inclusion ESIF EAFRD Romania'                                         => 'Inclusion_ESIF_AGRI_PRSL_template.xls',
            'Edit ESIF EAFRD Romania'                                              => 'EDIT_DATA_ESIF_AGRI_PRSL_template.xls',
            //sme initiative counter
            'Inclusion Counter SMEi'                                               => 'Inclusion_SMEi_CG_template.xls',
            'PD Inclusion Counter SMEi'                                            => 'PD_SMEi_CG_template.xls',
            'LR Inclusion Counter SMEi'                                            => 'LR_SMEi_CG_template.xls',
            'Edit Inclusion Counter SMEi'                                          => 'EDIT_DATA_SMEi_CG_template.xls',
            'Edit ESIF AGRI FLPG Italy'                                            => 'EDIT_DATA_ESIF_AGRI_FLPG_Italy_template.xls',
            'Inclusion ESIF AGRI FLPG Italy'                                       => 'Inclusion_ESIF_AGRI_FLPG_Italy_template.xls',
            'LR Inclusion ESIF AGRI FLPG Italy'                                    => 'LR_ESIF_AGRI_FLPG_Italy_template.xls',
            'PD Inclusion ESIF AGRI FLPG Italy'                                    => 'PD_ESIF_AGRI_FLPG_Italy_template.xls',
            'PD Counter EaSI MF BDS'                                               => 'PD_EASI_MF_BDS_CG_template.xls',
            'Inclusion Counter EaSI MF BDS'                                        => 'Inclusion_EASI_MF_BDS_CG_template.xls',
            'Edit Inclusion Counter EaSI MF BDS'                                   => 'EDIT_DATA_EASI_MF_BDS_CG_template.xls',
            'PD Direct EaSI MF BDS'                                                => 'PD_EASI_MF_BDS_DG_template.xls',
            'Inclusion Direct EaSI MF BDS'                                         => 'Inclusion_EASI_MF_BDS_DG_template.xls',
            'Edit Inclusion Direct EaSI MF BDS'                                    => 'EDIT_DATA_EASI_MF_BDS_DG_template.xls',
            'Inclusion INNOVFIN Counter V2 sub-debt'                               => 'Inclusion_INNOVFIN_CG_v2_subdebt_template.xls',
            'PD Inclusion INNOVFIN Counter V2 sub-debt'                            => 'PD_INNOVFIN_CG_v2_subdebt_template.xls',
            'LR Inclusion INNOVFIN Counter V2 sub-debt'                            => 'LR_INNOVFIN_CG_v2_subdebt_template.xls',
            'Edit Inclusion INNOVFIN Counter V2 sub-debt'                          => 'EDIT_DATA_INNOVFIN_CG_v2_subdebt_template.xls',
            //'Edit Converted Revolving Counter Counter V2 sub-debt' => '',
            //'Edit Inclusion Direct EaSI MF BDS' => 'EDIT_DATA_EASI_MF_DG_BDS_template.xls',
            //'Inclusion Direct EaSI MF BDS' => 'Inclusion_EASI_MF_DG_BDS_template.xls',
            'PD Direct EaSI MF BDS'                                                => 'PD_EASI_MF_DG_BDS_template.xls',
            //ddf
            'Inclusion EFSI DDF'                                                   => 'Inclusion_DDF_template.xls',
            'Edit EFSI DDF'                                                        => 'EDIT_DDF_template.xls',
            //Easi on lending
            'Inclusion Direct EaSI SE On-Leading'                                  => 'Inclusion_EASI_SE_OnLending_template.xls',
            'PD Inclusion EaSI Direct SE On-Leading'                               => 'PD_EASI_SE_OnLending_template.xls',
            'Edit Inclusion EaSI Direct SE On-Leading'                             => 'EDIT_DATA_EASI_SE_OnLending_template.xls',
            //Future Growth
            'Inclusion Future Growth'                                              => 'Inclusion_Future_Growth_template.xls',
            'PD Inclusion Future Growth'                                           => 'PD_Future_Growth_template.xls',
            'LR Inclusion Future Growth'                                           => 'LR_Future_Growth_template.xls',
            'Edit Future Growth'                                                   => 'EDIT_DATA_Future_growth_template.xls',
            // FOSTER AGRI FLPG MP
            'Inclusion FOSTER AGRI FLPG MP'                                        => 'Inclusion_FOSTER_AGRI_FLPG_MP_template.xls',
            'PD Inclusion FOSTER AGRI FLPG MP'                                     => 'PD_FOSTER_AGRI_FLPG_MP_template.xls',
            'LR Inclusion FOSTER AGRI FLPG MP'                                     => 'LR_FOSTER_AGRI_FLPG_MP_template.xls',
            'Edit Inclusion FOSTER AGRI FLPG MP'                                   => 'EDIT_DATA_FOSTER_AGRI_FLPG_MP_template.xls',
            //AlterNa
            'Edit Inclusion ESIF EAFRD Nouvelle Aquitaine'                         => 'EDIT_DATA_ESIF_EAFRD_Nouvelle_Aquitaine.xls',
            'Inclusion ESIF EAFRD Nouvelle Aquitaine'                              => 'Inclusion_ESIF_EAFRD_Nouvelle_Aquitaine.xls',
            'LR Inclusion ESIF EAFRD Nouvelle Aquitaine'                           => 'LR_ESIF_EAFRD_Nouvelle_Aquitaine.xls',
            'PD Inclusion ESIF EAFRD Nouvelle Aquitaine'                           => 'PD_ESIF_EAFRD_Nouvelle_Aquitaine.xls',
            'Inclusion FOSTER AGRI FLPG LR'                                        => 'Inclusion_FOSTER_AGRI_FLPG_LR_template.xls',
            'PD Inclusion FOSTER AGRI FLPG LR'                                     => 'PD_FOSTER_AGRI_FLPG_LR_template.xls',
            'LR Inclusion FOSTER AGRI FLPG LR'                                     => 'LR_FOSTER_AGRI_FLPG_LR_template.xls',
            'Edit Inclusion FOSTER AGRI FLPG LR'                                   => 'EDIT_DATA_FOSTER_AGRI_FLPG_LR_template.xls',
            'Inclusion LR EaSi'                                                    => 'LR_EaSi_template.xls',
            // inaf
            'PD Inclusion ESIF EAFRD INAF'                                         => 'PD_ESIF_EAFRD_INAF.xls',
            'LR Inclusion ESIF EAFRD INAF'                                         => 'LR_ESIF_EAFRD_INAF.xls',
            'Inclusion ESIF EAFRD INAF'                                            => 'Inclusion_ESIF_EAFRD_INAF.xls',
            'Edit Inclusion ESIF EAFRD INAF'                                       => 'EDIT_DATA_ESIF_EAFRD_INAF.xls',
            //cosme new templates
            // COSME direct guarantee templates:
            'Inclusion COSME Direct Guarantee Digitalisation'                      => 'Inclusion_COSME_DG_digitalization_template.xls',
            'PD Inclusion COSME Direct Guarantee Digitalisation'                   => 'PD_COSME_DG_digitalization_template.xls',
            'LR Inclusion COSME Direct Guarantee Digitalisation'                   => 'LR_COSME_DG_digitalization_template.xls',
            'Edit Inclusion COSME Direct Guarantee Digitalisation'                 => 'EDIT_DATA_COSME_DG_digitalization_template.xls',
            //COSME counter guarantee templates:
            'Inclusion COSME Counter Guarantee Digitalisation'                     => 'Inclusion_COSME_CG_digitalization_template.xls',
            'PD Inclusion COSME Counter Guarantee Digitalisation'                  => 'PD_COSME_CG_digitalization_template.xls',
            'LR Inclusion COSME Counter Guarantee Digitalisation'                  => 'LR_COSME_CG_digitalization_template.xls',
            'Edit Inclusion COSME Counter Guarantee Digitalisation'                => 'EDIT_DATA_COSME_CG_digitalization_template.xls',
            // COSME Onleding templates
            'Inclusion COSME On-Lending Digitalisation'                            => 'Inclusion_COSME_OL_digitalization_template.xls',
            'PD Inclusion COSME On-Lending Digitalisation'                         => 'PD_COSME_OL_digitalization_template.xls',
            'LR Inclusion COSME On-Lending Digitalisation'                         => 'LR_COSME_OL_digitalization_template.xls',
            'Edit Inclusion COSME On-Lending Digitalisation'                       => 'EDIT_DATA_COSME_OL_digitalization_template.xls',
            'Inclusion InnovFin On-Lending digitalisation sub-debt'                => 'Inclusion_INNOVFIN_OL_digitalisation_subdebt_template.xls',
            'Inclusion InnovFin Direct Guarantee v2 digitalisation sub-debt'       => 'Inclusion_INNOVFIN_v2_DG_digitalisation_subdebt_template.xls',
            'Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'      => 'Inclusion_INNOVFIN_v2_CG_digitalisation_subdebt_template.xls',
            'LR Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'   => 'LR_INNOVFIN_CG_v2_digitalisation_subdebt_template.xls',
            'LR Inclusion InnovFin Direct Guarantee v2 digitalisation sub-debt'    => 'LR_INNOVFIN_DG_v2_digitalisation_subdebt_template.xls',
            'LR Inclusion InnovFin On-Lending digitalisation sub-debt'             => 'LR_INNOVFIN_OL_digitalisation_subdebt_template.xls',
            //'PD Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt' => 'PD_INNOVFIN_CG_v2_digitalisation_subdebt_template.xls',
            'PD Inclusion InnovFin Direct Guarantee v2 digitalisation sub-debt'    => 'PD_INNOVFIN_DG_v2_digitalisation_subdebt_template.xls',
            'PD Inclusion InnovFin On-Lending digitalisation sub-debt'             => 'PD_INNOVFIN_OL_digitalisation_subdebt_template.xls',
            'Edit Inclusion InnovFin Direct Guarantee v2 digitalisation sub-debt'  => 'EDIT_DATA_INNOVFIN_DG_v2_digitalization_subdebt_template.xls',
            'Edit Inclusion InnovFin On-Lending digitalisation sub-debt'           => 'EDIT_DATA_INNOVFIN_OL_digitalization_subdebt_template.xls',
            'Edit Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt' => 'EDIT_DATA_INNOVFIN_CG_v2_digitalization_subdebt_template.xls',
            'Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'      => 'Inclusion_INNOVFIN_v2_CG_digitalisation_subdebt_template.xls',
            'PD Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'   => 'PD_INNOVFIN_CG_v2_digitalisation_subdebt_template.xls',
            'LR Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'   => 'LR_INNOVFIN_CG_v2_digitalisation_subdebt_template.xls',
            'Edit Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt' => 'EDIT_DATA_INNOVFIN_CG_v2_digitalization_subdebt_template.xls',
            'Edit Inclusion ESIF AGRI FLPG Portugal'                               => 'EDIT_DATA_ESIF_AGRI_FLPG_Portugal.xls',
            'Inclusion ESIF AGRI FLPG Portugal'                                    => 'Inclusion_ESIF_AGRI_FLPG_Portugal.xls',
            'LR Inclusion ESIF AGRI FLPG Portugal'                                 => 'LR_ESIF_AGRI_FLPG_Portugal.xls',
            'PD Inclusion ESIF AGRI FLPG Portugal'                                 => 'PD_ESIF_AGRI_FLPG_Portugal.xls',
            'Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'      => 'Inclusion_INNOVFIN_v2_CG_digitalisation_subdebt_template.xls',
            'PD Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'   => 'PD_INNOVFIN_CG_v2_digitalisation_subdebt_template.xls',
            'LR Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt'   => 'LR_INNOVFIN_DG_v2_digitalisation_subdebt_template.xls',
            'Edit Inclusion InnovFin Counter Guarantee v2 digitalisation sub-debt' => 'EDIT_DATA_INNOVFIN_CG_v2_digitalization_subdebt_template.xls',
            // WB edif youth
            'Inclusion WB EDIF Youth'                                              => 'Inclusion_WB_ EDIF_YOUTH_template.xls',
            'Edit Inclusion WB EDIF Youth'                                         => 'EDIT_DATA_WB_ EDIF_YOUTH_template.xls',
            'PD Inclusion WB EDIF Youth'                                           => 'PD_WB_EDIF_YOUTH_template.xls',
            'LR Inclusion WB EDIF Youth'                                           => 'LR_WB_EDIF_YOUTH_template.xls',
            // JEREMIE Bulgaria
            'Inclusion JEREMIE Bulgaria Reflows Subtransactions'                   => 'Inclusion_Jeremia_Bulgaria_template.xls',
            'PD Inclusion JEREMIE Bulgaria Reflows Subtransactions'                => 'PD_Jeremia_Bulgaria_template.xls',
            'LR Inclusion JEREMIE Bulgaria Reflows Subtransactions'                => 'LR_Jeremia_Bulgaria_template.xls',
            'Edit Inclusion JEREMIE Bulgaria Reflows Subtransactions'              => 'EDIT_DATA_Jeremia_Bulgaria_template.xls',
            // EaSI_SE_Sub_Fund
            'Edit Inclusion Direct EaSI SE Sub-Fund'                               => 'Edit_Template_EaSI_SE_Sub_Fund.xls',
            'Inclusion Direct EaSI SE Sub-Fund'                                    => 'Inclusion_Template_EaSI_SE_Sub_Fund.xls',
            'PD Inclusion Direct EaSI SE Sub-Fund'                                 => 'Payment_Demand_EaSI_sub_fund.xls',
            // EASI_Direct_EaSI_MF_Sub_Fund
            'Edit Inclusion Direct EaSI MF Sub-Fund'                               => 'Edit_EASI_Direct_EaSI_MF_Sub_Fund.xls',
            'Inclusion Direct EaSI MF Sub-Fund'                                    => 'Inclusion_EASI_Direct_EaSI_MF_Sub_Fund.xls',
            'PD Inclusion Direct EaSI MF Sub-Fund'                                 => 'Payment_Demand_EaSI_Direct_EaSI_MF_Sub_Fund.xls',
            'Inclusion ESIF AGRI FLPG Greece'                                      => 'Inclusion_ESIF_AGRI_FLPG_Greece_template.xls',
            'Edit Inclusion ESIF AGRI FLPG Greece'                                 => 'EDIT_DATA_ESIF_AGRI_FLPG_Greece_template.xls',
            'PD Inclusion ESIF AGRI FLPG Greece'                                   => 'PD_ESIF_AGRI_FLPG_Greece_template.xls',
            'LR Inclusion ESIF AGRI FLPG Greece'                                   => 'LR_ESIF_AGRI_FLPG_Greece_template.xls',
            'Inclusion EFSI SE Direct'                                             => 'Inclusion_EFSI_SE_template.xls', //Inclusion EFSI SE Direct
            'LR Inclusion EFSI SE Direct'                                          => 'LR_EFSI_SE_template.xls',
            'PD Inclusion EFSI SE Direct'                                          => 'PD_EFSI_SE_template.xls',
            'Edit Inclusion EFSI SE Direct'                                        => 'EDIT_DATA_EFSI_SE_template.xls',
            'Inclusion JEREMIE Bulgaria Reflows Subtransactions'                   => 'Inclusion_JEREMIE_Bulgaria_Reflows_SubTrn.xls',
            'Edit Inclusion JEREMIE Bulgaria Reflows Subtransactions'              => 'EDIT_DATA_JEREMIE_Bulgaria_Reflows_SubTrn.xls',
            'PD Inclusion JEREMIE Bulgaria Reflows Subtransactions'                => 'PD_JEREMIE_Bulgaria_Reflows_SubTrn.xls',
            'LR Inclusion JEREMIE Bulgaria Reflows Subtransactions'                => 'LR_JEREMIE_Bulgaria_Reflows_SubTrn.xls',
            'Inclusion ESIF AGRI FLPG Greece'                                      => 'Inclusion_ESIF_AGRI_FLPG_Greece_template.xls',
            'Edit Inclusion ESIF AGRI FLPG Greece'                                 => 'EDIT_DATA_ESIF_AGRI_FLPG_Greece_template.xls',
            'PD Inclusion ESIF AGRI FLPG Greece'                                   => 'PD_ESIF_AGRI_FLPG_Greece_template.xls',
            'LR Inclusion ESIF AGRI FLPG Greece'                                   => 'LR_ESIF_AGRI_FLPG_Greece_template.xls',
            'LR Inclusion EGF Direct'                                              => 'LR_EGF_DG_template.xls',
            'PD Inclusion EGF Direct'                                              => 'PD_EGF_DG_template.xls',
            'Edit Inclusion EGF Direct'                                            => 'Edit_EGF_DIRECT_template.xls',
            'Inclusion EGF Direct'                                                 => 'Inclusion_EGF_DIRECT_template.xls',
            'Inclusion EGF Counter'                                                => 'Inclusion_EGF_Counter_template.xls',
            'LR Inclusion EGF Counter'                                             => 'LR_EGF_CG_template.xls.xls',
            'DG Inclusion EGF Counter'                                             => 'PD_EGF_CG_template.xls',
            'Edit Inclusion EGF Counter'                                           => 'EDIT_DATA_EGF_CG_template.xls',
        ];
        if (!empty($files[$name])) {
            return $files[$name];
        } else {
            error_log("missing mapping template for " . $name);
            return "";
        }
    }

    public function mappingView()
    {
        $this->loadModel('Damsv2.Product');
        $products = $this->Product->getProducts();

        //$this->loadModel('Damsv2.Portfolio');
        $portfolio = $this->getTableLocator()->get('Damsv2.Portfolio');
        $portfolios = $portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                ])->toArray();
        $templates = $this->Template->find('list', [
                    'keyField'   => 'template_id',
                    'valueField' => 'name',
                    'order'      => 'name asc',
                ])->toArray();

        $this->loadModel('Damsv2.TemplateType');
        $template_types = $this->TemplateType->find('list', [
                    'keyField'   => 'type_id',
                    'valueField' => 'name',
                    'order'      => 'name asc ',
                ])->toArray();

        $this->loadModel('Damsv2.MappingTable');
        $tables = $this->MappingTable->find('list', [
                    'keyField'   => 'table_name',
                    'valueField' => 'table_name',
                    'group'      => 'table_name',
                    'order'      => 'table_name asc',
                ])->toArray();

        $this->loadModel('Damsv2.MappingColumn');
        $columns = $this->MappingColumn->find('list', [
                    'keyField'   => 'table_field',
                    'valueField' => 'table_field',
                    'group'      => 'table_field',
                    'order'      => 'table_field asc',
                ])->toArray();

        $this->set(compact('products', 'portfolios', 'templates', 'template_types', 'tables', 'columns'));



//        if (!$this->Session->read('Form.filter_mapping')) {
//            $this->Session->write('Form.filter_mapping', [
//                'Product'   => [
//                    'product_id' => ''
//                ],
//                'Portfolio' => [
//                    'portfolio_id' => '',
//                ],
//                'Template'  => [
//                    'template_id'      => '',
//                    'template_type_id' => '',
//                ],
//            ]);
//        }

        if ($this->request->is('post')) {
//            $this->Session->write('Form.filter_mapping', $this->request->data);

            $conditions = [];
            if (!empty($this->request->getData('Product.product_id'))) {
                //$this->request->getData('Product.product_id') = intval($this->request->getData('Product.product_id'));
                $conditions['Product.product_id'] = $this->request->getData('Product.product_id');
            }
            if (!empty($this->request->getData('Portfolio.portfolio_id'))) {
                //$this->request->getData('Portfolio.portfolio_id') = intval($this->request->getData('Portfolio.portfolio_id'));
                $conditions['Portfolio.portfolio_id'] = $this->request->getData('Portfolio.portfolio_id');
            }
            if (!empty($this->request->getData('Template.template_id'))) {
                //$this->request->getData('Template.template_id') = intval($this->request->getData('Template.template_id'));
                $conditions['Template.template_id'] = $this->request->getData('Template.template_id');
            }
            if (!empty($this->request->getData('Template.template_type_id'))) {
                //$this->request->getData('Template.template_type_id') = intval($this->request->getData('Template.template_type_id'));
                $conditions['Template.template_type_id'] = $this->request->getData('Template.template_type_id');
            }
            if (!empty($this->request->getData('Mapping_table.table_name'))) {
                //$this->request->getData('Mapping_table.table_name') = $this->request->getData('Mapping_table.table_name');
                $conditions['Mapping_table.table_name'] = $this->request->getData('Mapping_table.table_name');
            }
            if (!empty($this->request->getData('MappingColumn.table_field'))) {
                //$this->request->getData('MappingColumn.table_field') = $this->request->getData('MappingColumn.table_field');
                $conditions['MappingColumn.table_field'] = $this->request->getData('MappingColumn.table_field');
            }
            if (!empty($conditions)) {
                $query = "SELECT product.name as productname, product.product_type as type, portfolio.portfolio_name,  template_type.name as temptypename, template.name as teplatename,  mapping_table.table_name, mapping_table.sheet_name, mapping_column.table_field, mapping_column.datatype, mapping_column.excel_column, mapping_column.is_null as mandatory, mapping_column.dictionary_id, dictionary.name as dictname FROM template JOIN template_portfolio ON template.template_id=template_portfolio.template_id ";
                $query .= "LEFT JOIN portfolio ON portfolio.portfolio_id=template_portfolio.portfolio_id LEFT JOIN product ON portfolio.product_id=product.product_id LEFT JOIN mapping_table ON mapping_table.template_id=template.template_id ";
                $query .= "LEFT JOIN template_type ON template_type.type_id=template.template_type_id ";
                $query .= "LEFT JOIN mapping_column ON mapping_column.table_id=mapping_table.table_id LEFT JOIN dictionary ON dictionary.dictionary_id=mapping_column.dictionary_id ";
                $query .= " WHERE mapping_column.excel_column > 0 AND mapping_column.table_field <>'action'";
                if (!empty($conditions['MappingColumn.table_field'])) {
                    $query .= " AND mapping_column.table_field='" . $conditions['MappingColumn.table_field'] . "'";
                }
                if (!empty($conditions['Mapping_table.table_name'])) {
                    $query .= " AND mapping_table.table_name='" . $conditions['Mapping_table.table_name'] . "'";
                }
                if (!empty($conditions['Template.template_type_id'])) {
                    $query .= " AND template.template_type_id=" . $conditions['Template.template_type_id'];
                }
                if (!empty($conditions['Template.template_id'])) {
                    $query .= " AND template_portfolio.template_id=" . $conditions['Template.template_id'];
                }
                if (!empty($conditions['Portfolio.portfolio_id'])) {
                    $query .= " AND portfolio.portfolio_id=" . $conditions['Portfolio.portfolio_id'];
                }
                if (!empty($conditions['Product.product_id'])) {
                    $query .= " AND product.product_id=" . $conditions['Product.product_id'];
                }
                $query .= " ORDER BY template.name, mapping_table.sheet_name, mapping_column.excel_column";

                $connection = ConnectionManager::get('default');
                $results = $connection->execute($query)->fetchAll('assoc');
                $filename = 'template_mapping_' . time() . '.xlsx';

                $filepath = '/var/www/html/data/damsv2/templates_test/' . $filename;
                //result is not ORM, in this case set the last parameter to false

                if (count($results) > 0) {
                    $file_generated = $this->Spreadsheet->generateExcelFromQuery($results, ['template'], $filepath, false);
                    if ($file_generated) {
                        //$this->File->keepLastNFiles('/var/www/html/data/damsv2/templates_test/', 'xlsx', 10);

                        $download_link = '/damsv2/ajax/download-file/' . basename($filepath) . '/templates_test';
                        $this->set('download_link', $download_link);
                        $this->set('results', $results);
                        $this->Flash->success(__(count($results) . ' results matching your search criteria exported to attached excel!!'));
                    } else {
                        $this->set('msg', 'empty report');
                    }
                } else {
                    $this->Flash->warning(__('No template results for this search criteria!!'));
                }
            }
        }

//        if ($this->Session->read('Form.filter_mapping.Product.product_id')) {
//            $conditions['Portfolio.product_id'] = $this->Session->read('Form.filter_mapping.Product.product_id');
//        }
//        if ($this->Session->read('Form.filter_mapping.Portfolio.portfolio_id')) {
//            $conditions['Portfolio.portfolio_id'] = $this->Session->read('Form.filter_mapping.Portfolio.portfolio_id');
//        }
    }

}
