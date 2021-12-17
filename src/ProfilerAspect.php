<?php
// src/ProfilerAspect.php

namespace App;

use Go\Aop\Aspect;
use Go\Aop\Intercept\FieldAccess;
use Go\Aop\Intercept\MethodInvocation;
use Go\Lang\Annotation\After;
use Go\Lang\Annotation\Before;
use Go\Lang\Annotation\Around;
use Go\Lang\Annotation\Pointcut;

/**
 * Profiler aspect
 */
class ProfilerAspect implements Aspect
{

    protected $level = -1;
    protected $profilerInfo = [];
    protected $id;
    protected $session;
    protected $requestToken;
    protected $requestTime;

    protected function levelUp($invocation) {
        $this->level++;
        if($this->level == 0) {
            $this->id = $invocation->getThis()->getRequest()->getUri();
            $this->session = $invocation->getThis()->getRequest()->getSession();
            $this->requestToken = $invocation->getThis()->getRequest()->getAttributes()['csrfToken'] ?? null;
            $this->requestTime = new \DateTime('NOW');    
        }
    }

    protected function &getProfiler() {
        //echo "Level: " . $this->level;
        $ret = &$this->profilerInfo;
        for ($pos = 0; $pos < $this->level; $pos++) {
            $ret = &$ret[count($ret)-1]->profilerItems;
        }
        return $ret;
    }

    protected function writeProfilerInfo(&$item, $invocation, $time, $result) {
        $item->class = preg_replace('/__AopProxied/','',$invocation->getMethod()->class);
        $item->method = $invocation->getMethod()->name;
        $item->elapsedTime = $time;
        $item->success = $result;
        $item->message = "";
    }

    protected function levelDown() {
        $this->level--;
        if ($this->level < 0) {
            $profilerData = $_SESSION['PROFILER_DATA'] ?? [];
            $profilerData[$this->id . " " . $this->requestTime->format(\DateTime::ISO8601)] = ['requestToken' => $this->requestToken, 'data' => $this->profilerInfo];
            $_SESSION['PROFILER_DATA'] = $profilerData;
            //var_export($this->profilerInfo);
        }
    }
    /**
     * Common methods
     *
     * @param MethodInvocation $invocation Invocation
     * @Around("!execution(public Damsv2\Controller\AppController->*(*)) && !execution(public Damsv2\Controller\*->initialize|beforeFilter|logDams|validate_param|__construct|components|loadComponent|redirect|render|referer|paginate|dispatchEvent|log|loadModel|createView|set|startupProcess|shutdownProcess(*)) && execution(public Damsv2\Controller\*->*(*)) || @execution(App\Lib\TimeProfiling)") // Full-qualified pointcut name home*|index*|add*|copy*|edit*|delete*|view*|export*|get
     */
    public function aroundControllers(MethodInvocation $invocation)
    {
        $skip = false;
        if (preg_replace('/__AopProxied/','',$invocation->getMethod()->class) == "Damsv2\\Controller\\AppController") $skip = true;
        if (preg_replace('/__AopProxied/','',$invocation->getMethod()->class) == "Cake\\Controller\\Controller") $skip = true;
        if (!$skip) {
            $this->levelUp($invocation);
            $time  = microtime(true);
            $success = false;
            $profilerItems = &$this->getProfiler();
            $item = new ProfilerItem();
            $profilerItems[] = &$item;
        }
        try {
            $return = $invocation->proceed();
            $success = true;
        } catch (Exception $err) {
            throw $err;
        } finally {
            if (!$skip) {
                $execution_time = microtime(true)-$time;
                $this->writeProfilerInfo($item, $invocation, $execution_time, $success);
                $this->levelDown();
            }
        }
        return $return;
    }
}
class ProfilerItem {
    public $class;
    public $method;
    public $elapsedTime;
    public $success;
    public $message;
    public $profilerItems = []; 
}