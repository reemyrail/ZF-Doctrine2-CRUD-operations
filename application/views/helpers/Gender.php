<?php
class Zend_View_Helper_Gender extends Zend_View_Helper_Abstract
{
    public function Gender ($gen)
    {
        if ($gen == 'm') {
            return 'he';
        } else {
            return 'she';
        }
    }
}