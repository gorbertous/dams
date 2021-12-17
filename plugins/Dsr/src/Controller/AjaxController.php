<?php

declare(strict_types=1);

namespace Dsr\Controller;

use Cake\Event\EventInterface;
use App\Lib\DownloadLib;


class AjaxController extends AppController
{

    public $name = 'Ajax';

    public function initialize(): void
    {
        parent::initialize();
        //$this->loadComponent('Security');
        //$this->loadComponent('Spreadsheet');
    }

    public function beforeFilter(EventInterface $event)
    {
        parent::beforeFilter($event);

        //$this->Security->setConfig('blackHoleCallback', 'blackhole');
    }

    public function downloadFile()
    {
        $download_file = $this->request->getAttribute('params');
        $download_file = DownloadLib::filter_parameters($download_file['pass']);
        if (empty($download_file[1])) {
            $this->Flash->error('Wrong download path!');
            $this->redirect('/');
            return;
        }
        $path = array(
            'archive'            => "/upload/",
            'error'              => "/data/DSR/errors/",
            'upload'              => "/data/DSR/upload/",
            'docs'               => "/data/docs/",
            
        );
        if (!(empty($download_file[2]))) {
            $download_file_path = $path[$download_file[1]] . '/' . $download_file[2] . '/' . $download_file[0];
        } else {
            $download_file_path = $path[$download_file[1]] . $download_file[0];
        }
        
        DownloadLib::download($download_file_path);
        exit();
    }

}
