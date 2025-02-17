<?php 

class RenderView{
    public function LoadView($view, $args) {
        extract($args);
        require_once __DIR__."/../views/$view.php";
    }
}