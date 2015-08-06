<?php
namespace Form\Input\Url;

use Form\Input\InputInterface;
use Form\Input\Text\Text;
/**
 * Champs de type URL
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Url extends Text implements InputInterface
{
    public function validate()
    {
        $validation = parent::validate();

        if ($validation) {
            $url_absolute_preg = "/https?:\/\/(www\.)?([a-zA-Z0-9-_]+)\.([a-zA-Z0-9]{2,4})([\/\s])?/i";

            if (!preg_match($url_absolute_preg, $this->value)) {
                /* on vérifie alors que c'est une URL locale
                 * A optimiser !!!
                 *  */
                $path_match = "/^\/(([a-zA-Z0-9])+\/?)+(([a-zA-Z0-9])+\.?([a-zA-Z0-9]){0,3})?$/i"; //vérifie qu'une URL ressemble à /path/to/file.php

                if (!preg_match($path_match, $this->value)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }
        }

        return $validation;
    }
}
