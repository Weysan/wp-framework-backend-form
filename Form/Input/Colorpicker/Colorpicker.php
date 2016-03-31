<?php
namespace Form\Input\Colorpicker;

use Form\Input\InputInterface;
use Form\Input\Text\Text;
/**
 * Upload an image
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Colorpicker extends Text implements InputInterface
{

    public function __construct($name, $value = null, $id = null, $args = array(), $section = null)
    {
        parent::__construct($name, $value, $id, $args, $section);
	     
        // Add the color picker css file       
        \wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        \wp_enqueue_script( 'zest-color-picker-admin', \plugin_dir_url(__FILE__) . 'js/color-script.js', array( 'jquery', 'wp-color-picker' ), false, true ); 
    }


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

        $html .= '<td><input type="text" name="'.$this->name.'" value="'.$this->value.'"';

        if ($this->id) {
            $html .= ' id="'.$this->id.'"';
        }

        if (isset($this->args['class'])) {
            $html .= ' class="'.$this->args['class'].' color-field"';
        }

        $html .= ' /></td>';

        $html .= '</tr>';

        return $html;
    }
}