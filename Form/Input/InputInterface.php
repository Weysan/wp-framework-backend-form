<?php
namespace Form\Input;

/**
 * Interface pour créer de nouveaux input de formulaire
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
interface InputInterface
{
    public function getDisplay();

    public function validate();

    public function setValue($value);

    public function save($post_id);

    public function getName();
    
    public function getErrorMessage();
}
