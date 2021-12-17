<?php

declare(strict_types=1);

namespace Damsv2\Controller;
use App\Lib\Helpers;

/**
 * Validation Controller
 *
 * @method \App\Model\Entity\Monitoringvisit[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class MonitoringvisitController extends AppController
{

    public function index()
    {
        $session = $this->request->getSession();
        if (!$session->read('Form.data.monitoringvisit')) {
            $session->write('Form.data.monitoringvisit', [
                'Product'   => ['product_id' => ''],
                'Portfolio' => ['portfolio_id' => '']
            ]);
        }
        $this->loadModel('Damsv2.Portfolio');
        //post data
        if ($this->request->is('post')) {
            $session->write('Form.data.monitoringvisit', $this->request->getData());
        }
        //page filters with form data        
        $this->loadModel('Damsv2.Product');
        $products = $this->Product->getProducts();

        $conditions_portfolio = [];
        $conditions_mfile = [];

        if ($session->read('Form.data.monitoringvisit.product_id')) {
            $conditions_portfolio = Helpers::arrayPushAssoc($conditions_portfolio, 'Product.product_id', $session->read('Form.data.monitoringvisit.product_id'));
            $conditions_mfile = Helpers::arrayPushAssoc($conditions_mfile, 'Product.product_id', $session->read('Form.data.monitoringvisit.product_id'));
            $this->set('prid', intval($session->read('Form.data.monitoringvisit.product_id')));
        }

        if ($session->read('Form.data.monitoringvisit.portfolio_id')) {
            $conditions_mfile = Helpers::arrayPushAssoc($conditions_mfile, 'Portfolio.portfolio_id', $session->read('Form.data.monitoringvisit.portfolio_id'));
            $this->set('pid', intval($session->read('Form.data.monitoringvisit.portfolio_id')));
        }

        $portfolios = $this->Portfolio->find('list', [
                    'contain'    => ['Product'],
                    'fields'     => ['Product.name', 'Portfolio.portfolio_name', 'Portfolio.portfolio_id'],
                    'keyField'   => 'portfolio_id',
                    'valueField' => 'portfolio_name',
                    'groupField' => 'product.name',
                    'order'      => ['Product.name', 'Portfolio.portfolio_name'],
                    'conditions' => [$conditions_portfolio]
                ])->toArray();

        //query data
        $query = $this->Portfolio->find('all', [
            'contain'    => ['Product'],
            'conditions' => [$conditions_mfile]
        ]);
        //paginate
        $mfiles = $this->paginate($query);
        $this->set(compact('mfiles', 'portfolios', 'products'));
    }

    public function saveMFile()
    {
        $this->viewBuilder()->setLayout('ajax');
        $portfolio_id = intval($this->request->getData('Portfolio.portfolio_id'));        
        
        $this->loadModel('Damsv2.Portfolio');
        $old_data = $this->Portfolio->get($portfolio_id);
     
        if ($this->request->getData('Portfolio.modifications_expected') == 'Y') {
            $modifications_expected = 'Y';
        } else {
            $modifications_expected = 'N';
        }
        $m_files_link = $this->request->getData('Portfolio.m_files_link');
      
        $isthesame = ($old_data->m_files_link === $m_files_link);
     
        //$uportfolio = $this->Portfolio->patchEntity($old_data, $this->request->getData());

        if (!empty($old_data)) {
            if ($old_data->modifications_expected == null) {
                $old_data->modifications_expected = 'N';
            }
            if ($old_data->modifications_expected == '') {
                $old_data->modifications_expected = 'N';
            }
            $isdsame = ($old_data->modifications_expected === $modifications_expected) && $isthesame;
           
            error_log("old m_files_link : " . json_encode($old_data->modifications_expected));
            error_log(" m_files_link : " . json_encode($modifications_expected));
            if ($isdsame) {
                //no change
                $saved = ['Portfolio' => [
                        'portfolio_id'           => $portfolio_id,
                        'modifications_expected' => $modifications_expected,
                        'm_files_link'           => $m_files_link,
                        'change'                 => false,
                        'error'                  => false,
                ]];
            } else {
                $old_data->modifications_expected = $modifications_expected;
                $old_data->m_files_link = $m_files_link;
               
                $this->Portfolio->save($old_data);

                $saved = ['Portfolio' => [
                        'portfolio_id'           => $portfolio_id,
                        'modifications_expected' => $modifications_expected,
                        'm_files_link'           => $m_files_link,
                        'change'                 => true,
                        'error'                  => false,
                ]];
            }
        } else {
            $saved = ['Portfolio' => [
                    'portfolio_id'           => $portfolio_id,
                    'modifications_expected' => $modifications_expected,
                    'm_files_link'           => $m_files_link,
                    'change'                 => false,
                    'error'                  => true,
            ]];
        }
        $this->logDams('Monitoring Visit follow up update: '.json_encode($saved), 'dams', 'MV follow up');
        $this->set('saved', $saved);
    }

}
