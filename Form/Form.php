<?php
namespace Form;

use Form\Input\InputInterface;
use Form\Errors\Validation;
/**
 * Create a form instance
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Form
{
    private $inputs = array();

    private $post = array();
    
    private $msg_error;

    public function __construct($post = array())
    {
        if ($post) {
            $this->post = $post;
        }
    }

    /**
     * Add an input to the form
     *
     * @param \Form\Input\InputInterface $input
     */
    public function addInput(InputInterface $input)
    {
        $this->inputs[] = $input;
    }

    /**
     * Get the form HTML
     *
     * @return string
     */
    public function getDisplay($html_before = '', $html_after = '')
    {
        $html = '';
        $html .= $html_before;
        foreach ($this->inputs as $input) {
            $html .= $input->getDisplay();
        }
        $html .= $html_after;

        return $html;
    }

    /**
     * Remove an input from the form
     *
     * @param \Form\Input\InputInterface $input
     */
    public function removeInput(InputInterface $input)
    {
        //$key = array_search($input, $this->inputs, true);

        $item = null;
        foreach ($this->inputs as $key => $input_form) {
            if ($input === $input_form) {
                $item = $key;
                break;
            }
        }

        if ($item !== null) {
            unset($this->inputs[$item]);

            return true;
        }

        return false;
    }

    /**
     * Validate a form validation
     *
     * @return boolean
     */
    public function validate()
    {
        $validate = true;

        foreach ($this->inputs as $input) {
            if (!$input->validate()) {
                $validate = false;
                $this->msg_error = $input->getErrorMessage();
                //\add_action( 'admin_notices', array($this, 'errorForm') );
                break;
            }
        }

        return $validate;
    }
    
    /**
     * Return global form error
     * 
     * @return string|false
     */
    public function errorForm()
    {
        
        if(!empty($this->msg_error)){
            $class = "error";
            $message = "Il y a des erreurs dans le formulaires : " . $this->msg_error;

            return "<div class=\"$class\"> <p>$message</p></div>";
        } else {
            return false;
        }
    }

    public function setValues(array $values)
    {
        foreach ($this->inputs as $input) {
            if($input->getName()){
                if ($values[$input->getName()] == null) {
                    $values[$input->getName()] = '';
                }
                $input->setValue($values[$input->getName()]);
            }
        }
    }

    public function save($post_id)
    {
        if ($this->validate()) {
            foreach ($this->inputs as $k => $input) {
                 $save[$k] = $input->save($post_id);
            }
        } else {
            //new Validation($this);
            return false;
        }
    }
}
