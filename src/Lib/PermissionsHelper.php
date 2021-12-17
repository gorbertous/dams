<?php

declare(strict_types=1);

namespace App\Lib;

//use Cake\Datasource\ConnectionManager;

class PermissionsHelper
{
    private $plugin = "";
    private $controller = "";
    private $action = "";
    private $perms = [];

    function __construct($plugin, $controller, $action, $perms) {
        $this->plugin = $plugin;
        $this->controller = $controller;
        $this->action = $action;
        $this->perms = Helpers::copyLowerCaseKeysRecursively($perms);
    }

    public function getAction() {
        return $this->action;
    }

    public function getController() {
        return $this->controller;
    }

    public function getPlugin() {
        return $this->plugin;
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
        $plugin = Helpers::lowerWithoutSymbols($this->plugin);
        $controller = Helpers::lowerWithoutSymbols($this->controller);
        $action = Helpers::lowerWithoutSymbols($this->action);
        $filter = null;
        if ($data != null) {
            if (is_array($data)) {
                if (isset($data['plugin'])) {
                    $plugin = Helpers::lowerWithoutSymbols($data['plugin']);
                }
                if (isset($data['controller'])) {
                    $controller = Helpers::lowerWithoutSymbols($data['controller']);
                }
                if (isset($data['action'])) {
                    $action = Helpers::lowerWithoutSymbols($data['action']);
                }
                if (isset($data['filter'])) {
                    $filter = Helpers::lowerWithoutSymbols($data['filter']);
                }
            } else {
                $action = Helpers::lowerWithoutSymbols($data);
            }
        }
        if (isset($this->perms[$plugin][$controller][$action])) {
            $perm = $this->perms[$plugin][$controller][$action];
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