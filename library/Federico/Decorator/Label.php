<?php

class Federico_Decorator_Label extends Zend_Form_Decorator_Abstract {

    public function render($content) {
        return $content[0] . '=' . $content[1];
        //return '<label>'. $content . '<label/>';
    }

}