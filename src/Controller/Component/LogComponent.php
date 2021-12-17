<?php

class LogComponent extends Component
{

    var $components = ['Session'];

    public function log_project($msg)
    {
        $file_log_path = "/var/www/html/data/damsv2/DSToolbox/history.log";
        $log_line = "\n" . date("Y-m-d h:i") . " " . $this->getUserid() . "	" . $this->getUserName() . "	" . $msg;
        file_put_contents($file_log_path, $log_line, FILE_APPEND);
    }

    public function getUserName()
    {
        return $this->Session->read('UserAuth.User.username');
    }

    public function getUserid()
    {
        return $this->Session->read('UserAuth.User.id');
    }

}
