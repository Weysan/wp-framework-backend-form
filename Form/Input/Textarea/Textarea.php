<?php
namespace Form\Input\Textarea;

use Form\Input\InputInterface;
use Form\Input\Text\Text;
/**
 * textarea
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Textarea extends Text implements InputInterface
{
    /**
     * Retourne le HTML du champs en BO
     *
     * @return string
     */
    public function getDisplay()
    {
        if (isset($this->args['wysiwyg']) && $this->args['wysiwyg']) {
            \add_action('admin_print_footer_scripts', array( $this, 'addEditorTinymce' ), 99);
            $this->args['class'] .= ' customEditor';
        }

        $html = '<tr>';

        if ($this->label) {
            $html .= '<th><label for="'.$this->id.'">'.$this->label.'</label></th>';
        }

        $html .= '<td>';

        $html .= '<textarea name="'.$this->name.'"';

        if ($this->id) {
            $html .= ' id="'.$this->id.'"';
        }

        if (isset($this->args['class'])) {
            $html .= ' class="'.$this->args['class'].'"';
        }

        $html .= '>'.$this->value.'</textarea>';

        $html .= '</td>';

        $html .= '</tr>';

        return $html;
    }

    public function addEditorTinymce()
    {
        ?>
        <script type="text/javascript">
            jQuery(function($)
            {
                $(document).ready(function(){
                    var id = '<?php echo $this->id;
        ?>';
                    tinyMCE.execCommand('mceAddEditor', false, id);
                });
            });
        </script>

            <?php


        return true;
    }
}
