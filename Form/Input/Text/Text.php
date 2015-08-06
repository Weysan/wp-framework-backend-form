<?php
namespace Form\Input\Text;

use Form\Input\InputInterface;
/**
 * Type de champs Input text
 *
 * @author Raphael GONCALVES <raphael@couleur-citron.com>
 */
class Text implements InputInterface
{
    protected $name;

    protected $value;

    protected $id;

    protected $args;

    protected $section;

    protected $label;

    public function __construct($name, $value = null, $id = null, $args = array(), $section = null)
    {
        $this->name = $name;

        $this->value = $value;

        $this->id = $id;

        $args['class'] = 'large-text';

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

        $html .= '<td><input type="text" name="'.$this->name.'" value="'.$this->value.'"';

        if ($this->id) {
            $html .= ' id="'.$this->id.'"';
        }

        if (isset($this->args['class'])) {
            $html .= ' class="'.$this->args['class'].'"';
        }

        $html .= ' /></td>';

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
            return false;
        }

        if (!is_string($this->value) && !is_null($this->value)) {
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
        /* Can users be here? */
//        if(strtolower($_POST['post_type']) === 'page') {
//            if(!current_user_can('edit_page', $post_id)) {
//                return $post_id;
//            }
//        }


        $post = get_post($post_id);

        if (!$post) {
            return false;
        }

        return update_post_meta($post_id, $this->id, $this->value);
    }
}
