<?php
namespace Form\Input\Nonce;

use Form\Input\InputInterface;

/**
 * Génère un champs "nonce" automatiquement
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Nonce implements InputInterface
{
    private $nonce;

    public function __construct()
    {
        $this->nonce = \wp_create_nonce(basename(__FILE__));
    }

    /**
     * Generate field html
     *
     * @return string
     */
    public function getDisplay()
    {
        return '<input type="hidden" name="custom_meta_box_nonce" value="'.$this->nonce.'" />';
    }

    /**
     * No validation - only wordpress can validate that field
     *
     * @return boolean
     */
    public function validate()
    {
        return true;
    }

    /**
     * No change value
     *
     * @param  string  $value
     * @return boolean
     */
    public function setValue($value)
    {
        return true;
    }

    /**
     * No saving data
     *
     * @param  integer $post_id
     * @return boolean
     */
    public function save($post_id)
    {
        return true;
    }

    public function getName()
    {
    }
}
