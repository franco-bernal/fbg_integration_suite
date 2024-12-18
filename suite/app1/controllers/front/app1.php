<?php

class App1Controller
{
    private $proxy;

    public function setProxy($proxy)
    {
        $this->proxy = $proxy;
    }

    public function initContent($params = [])
    {
        $this->proxy->setTemplate('module:fbg_integration_suite/suite/app1/views/front/default.tpl');
    }
}