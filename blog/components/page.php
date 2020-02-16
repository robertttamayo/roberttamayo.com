<?php 

class Page {
    private $name = "page";

    private $components = [];
    private $scripts = [];
    private $styles = [];
    private $template = "";
    private $model = [];
    
    public function __construct() {}

    public function render(){
        $models = [];
        foreach($this->components as $component){
            $models[$component->getName()] = $component->render();
            $this->scripts = array_merge($this->scripts, $component->getScripts());
            $this->styles = array_merge($this->scripts, $component->getStyles());
        }
        $this->model = array_merge($this->model, $models);
        
    }

    public function getScripts(){
        return $this->scripts;
    }
    public function addScripts($scripts){
        $this->scripts = array_merge($this->scripts, $scripts);
    } 
    public function getStyles() {
        return $this->styles;
    }
    public function addStyles($styles) {
        $this->styles = array_merge($this->styles, $styles);
    }
    public function setTemplate($template) {
        $this->template = $template;
    }
    public function setModel($model) {
        $this->model = $model;
    }
    public function appendModel($model) {
        $this->model = array_merge($this->model, $model);
    }
    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        $this->name = $name;
    }
}