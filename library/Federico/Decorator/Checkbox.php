<?php
class Federico_Decorator_Checkbox extends Zend_Form_Decorator_Abstract
{
    public function render($content)
    {
        $element = $this->getElement();
        if (!$element instanceof Zend_Form_Element_Multi) {
            return $content;
        }
        if (null === ($view = $element->getView())) {
            return $content;
        }
        var_dump($this);die;
        $translator = $element->getTranslator();
        
        $html     = '';
        $values   = (array) $element->getValue();
        $baseName = $element->getName();
        foreach ($element->getMultiOptions() as $category => $values) {
            $boxes = '<ul class="checkboxes">';
            foreach ($values as $value => $label) {
                if ($translator instanceof Zend_Translate) {
                    $label = $translator->translate($label);
                }
                $boxName = $baseName . '[' . $value . ']';
                $boxId   = $basename . '-' . $value;
                $attribs = array(
                    'id'      => $boxId,
                    'checked' => in_array($value, $values),
                );
                $boxes .= '<li>'
                       .  $view->formCheckbox($boxName, $value, $attribs)
                       .  $view->formLabel($boxName, $label)
                       .  '</li>';
            }
            $boxes .= '</ul>';

            $legend = ($translator instanceof Zend_Translate)
                    ? $translator->translate($category)
                    : ucfirst($category);
            $attribs = array('legend' => $legend);
            $html .= $view->fieldset($category, $boxes, $attribs);
        }
        
         $placement = $this->getPlacement();
        $separator = $this->getSeparator();
        switch ($placement) {
            case 'APPEND':
                return $content . $separator . $html;
            case 'PREPEND':
                return $html . $separator . $content;
            case null:
            default:
                return $html;

        }
    }
    
}
