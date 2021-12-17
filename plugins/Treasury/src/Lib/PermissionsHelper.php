<?php

declare(strict_types=1);

namespace Treasury\Lib;

//use Cake\Datasource\ConnectionManager;

class PermissionsHelper
{
    private $controller = "";
    private $action = "";
    private $perms = [];

    function __construct($controller, $action, $perms) {
        $this->controller = $controller;
        $this->action = $action;
        $this->perms = $perms;
    }

    public function getAction() {
        return $this->action;
    }

    public function getController() {
        return $this->controller;
    }

    public function hasRead($data = null) {
        return $this->getPermission($data) > 0;
    }
    public function hasInsert($data = null) {
        return ($this->getPermission($data) & 2) > 0;
    }
    public function hasUpdate($data = null) {
        return ($this->getPermission($data) & 4) > 0;
    }
    public function hasDelete($data = null) {
        return ($this->getPermission($data) & 8) > 0;
    }
    public function hasWrite($data = null) {
        return $this->getPermission($data) > 1;
    }
    private function getPermission($data = null) {
        $controller = $this->controller;
        $action = $this->action;
        $filter = null;
        if ($data != null) {
            if (is_array($data)) {
                if (isset($data['controller'])) {
                    $controller = $data['controller'];
                }
                if (isset($data['action'])) {
                    $action = $data['action'];
                }
                if (isset($data['filter'])) {
                    $filter = $data['filter'];
                }
            } else {
                $action = $data;
            }
        }
        if (isset($this->perms[$controller][$action])) {
            $perm = $this->perms[$controller][$action];
            if ($filter != null) {
                return isset($perm[$filter])?$perm[$filter]:0;
            } else {
                return isset($perm['value'])?$perm['value']:0;
            }               
        } else {
            return 0;
        }
    }
}