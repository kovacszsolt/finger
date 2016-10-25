<?php
namespace finger\controller;

class  front extends \finger\controller\main
{
    protected $currentLang;
    public function __construct()
    {
        parent::__construct();
        $this->currentLang=$this->session->getValue('currentLang',$this->settings['defaultlangcode']);
    }

}
