<?php
namespace Form\Input\Int;

use Form\Input\Text\Text;

/**
 *
 * @author Raphael
 *        
 */
class Integer extends Text
{
    /**
     * Retourne le HTML du champs en BO
     *
     * @return string
     */
    public function getDisplay()
    {
        $html = '<tr>';
    
        if ($this->label) {
            $html .= '<th><label for="'.$this->id.'">'.$this->label.'</label></th>';
        }
    
        $html .= '<td><input type="int" name="'.$this->name.'" value="'.$this->value.'"';
    
        if ($this->id) {
            $html .= ' id="'.$this->id.'"';
        }
    
        if (isset($this->args['class'])) {
            $html .= ' class="'.$this->args['class'].'"';
        }
    
        $html .= ' /><br />'.$this->args['desc'].'</td>';
    
        $html .= '</tr>';
    
        return $html;
    }    
}