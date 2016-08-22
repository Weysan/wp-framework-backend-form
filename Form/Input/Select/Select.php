<?php
namespace Form\Input\Select;

use Form\Input\InputInterface;
/**
 * Création champs de type Select
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Select implements InputInterface
{
    protected $name;

    protected $value;

    protected $id;

    protected $args;

    protected $section;

    protected $label;

    protected $error_msg;

    public function __construct($name, $value = null, $id = null, $args = array(), $section = null)
    {
        $this->name = $name;

        $this->value = $value;

        $this->id = $id;

        $this->args = $args;

        if (isset($this->args['label'])) {
            $this->label = $this->args['label'];
        }

        $this->section = $section;
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

        $html .= '<td><select name="'.$this->name.'"';

        if ($this->id) {
            $html .= ' id="'.$this->id.'"';
        }

        if (isset($this->args['class'])) {
            $html .= ' class="'.$this->args['class'].'"';
        }

        $html .= '>';
        
        foreach($this->args['choices'] as $value => $label){
            $html .= '<option value="'.$value.'"';
            if($this->value && $this->value == $value){
                $html .= ' selected';
            }
            $html .= '>'.$label.'</option>';
        }
        

        $html .= '</select></td>';

        $html .= '</tr>';

        return $html;
    }

    /**
     * Valide une valeur d'un champs
     *
     * @return boolean
     */
    public function validate()
    {
        if (isset($this->args['required']) && $this->args['required'] && !$this->value) {
            $this->error_msg = $this->label . ' est requis.';
            return false;
        }

        return true;
    }

    /**
     * Modifie la value du champs
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Retourne le nom du champs
     *
     * @return type
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Save the meta data
     *
     * @param  integer $post_id
     * @return mixed   returns meta_id if the meta doesn't exist, otherwise returns true on success and false on failure
     */
    public function save($post_id)
    {

        $post = get_post($post_id);
        if (!$post) {
            return false;
        }

        return update_post_meta($post_id, $this->id, $this->value);
    }

    /**
     * Get error message
     *
     * @return string
     */
    public function getErrorMessage()
    {
        return $this->error_msg;
    }
}