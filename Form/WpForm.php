<?php
namespace Form;

use Form\Input\Nonce\Nonce;
/**
 * Va générer les actions wordpress
 * Les actions de callback
 * Le formulaire
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class WpForm
{
    private $fields;

    private $_post;

    private $form_title;

    private $content_type;

    private $formInstance;

    public function __construct($content_type, $form_title, array $formsFields, array $_post = array())
    {
        
//        $current_content_type = $_POST['post_type'];
//        if(!$current_content_type){
//            $current_content_type = 'post';
//        }
        
//        if($content_type != $current_content_type)
//            return;
            
        
        $this->fields = $formsFields;

        $this->_post = $_post;

        $this->content_type = $content_type;

        $this->form_title = $form_title;

        $this->generateForm();

        \add_action('add_meta_boxes', array( $this, 'addMetaBox' ));
        \add_action('save_post', array( $this, 'save' ));
    }

    /**
     * génère un formulaire prêt à l'emploi en fonction du tableau envoyé.
     *
     * Le tableau doit être de la forme :
     *
     *
     * array(
     *      array(
     *          'type' => 'Form\Input\Text\Text',
     *          'id' => 'test_id',
     *          'label' => 'label de mon champs',
     *          'desc' => 'description de mon champs'
     *      ),
     *      array(
     *          [...]
     *      )
     * )
     *
     */
    private function generateForm($post = null)
    {
        $form = new Form();
        $form->addInput(new Nonce());

        $this->formInstance = $form;

        foreach ($this->fields as $field) {
            // get value of this field if it exists for this post
            if (isset($post)) {
                $value = get_post_meta($post->ID, $field['id'], true);
            }

            $class_input = $field['type'];
            $field_id = $field['id'];
            $args = array(
               'label' => $field['label'],
               'desc' => $field['desc'],
               'wysiwyg' => $field['wysiwyg'],
               'content_type' => $this->content_type
            );

            $input = new $class_input($field_id, $value, $field_id, $args);

            $this->formInstance->addInput($input);
        }
    }

    /**
     * Display Meta box in the content type's edit page.
     * The callback function is generated automatically, but you can
     * override it by creating a function in your functions.php file :
     * show_custom_meta_box_{ content_type }
     */
    public function addMetaBox()
    {
        $title = $this->form_title;

        $callback = array( $this, 'show_custom_meta_box' );
        if (function_exists('show_custom_meta_box_'.$this->content_type)) {
            $callback = 'show_custom_meta_box_'.$this->content_type;
        }

        add_meta_box(
                    'custom_meta_box', // $id
                    $title, // $title
                    $callback, //'show_custom_meta_box_link', // $callback
                    $this->content_type, // $page
                    'normal', // $context
                    'high',
                    array(
                        $this->content_type,
                    )
                ); // $priority
    }

    /**
     * display fields in our custom meta box area
     *
     * @param  object  $post current post object
     * @return boolean true
     */
    public function show_custom_meta_box($post)
    {
        //vérification si on est dan sle bon content type
        if ($post->post_type != $this->content_type) {
            return;
        }

        $this->generateForm($post);

        $html_meta_box = $this->formInstance->getDisplay('<table class="form-table">', '</table>');

        echo $html_meta_box;

        return $html_meta_box;
    }

    /**
     * Save all custom fields adding by our methods.
     *
     * @param  integer $post_id the current post_id for saving meta data
     * @return boolean true
     */
    public function save($post_id)
    {
        $this->generateForm(get_post($post_id));
        
        $this->formInstance->setValues($_POST);
        
        return $this->formInstance->save($post_id);
    }
}
