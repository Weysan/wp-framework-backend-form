<?php
namespace Form\Input\File;

use Form\Input\InputInterface;

/**
 * Upload an image
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class File implements InputInterface
{
    private $name;

    private $id;

    private $label;

    private $args;

    private $value;

    private $error_msg;

    public function __construct($name, $value = null, $id = null, $args = array(), $section = null)
    {
        $this->id = $id;

        $this->name = $name;

        $this->args = $args;

        $this->value = $value;

        if (isset($this->args['label'])) {
            $this->label = $this->args['label'];
        }

        if (!post_type_supports($this->args['content_type'], 'post-thumbnails')) {
            \wp_enqueue_media();
        }

        \wp_enqueue_script(
            $handle = 'hs-img-uploader',
            $src = plugin_dir_url(__FILE__) . 'js/uploader.js',
            $deps = array('jquery'),
            $ver = false,
            $in_footer = true
        );

        \add_action('admin_menu', array($this, 'createMenuBO'));

        /* AJAX data */
        \add_action('wp_ajax_file_' . $this->id, array($this, 'ajaxFileLink'));
    }


    public function createMenuBO()
    {
        /* create menu page */
        //\add_media_page($this->label, $this->label, 'manage_options', 'update_file_'.$this->id, array($this, 'uploadFile'));
    }

    /**
     * Retourne le bouton HTML
     *
     * @return string
     */
    public function getDisplay()
    {
        $content_type = $_GET['post_type'];

        if (!isset($content_type)) {
            $content_type = 'post';
        }

        $media_id = $this->value;

        if ($media_id) {
            $image_attributes = wp_get_attachment_image_src($media_id, 'medium');

            if ($image_attributes) {
                $img_src = '<img src="' . $image_attributes[0] . '" width="' . $image_attributes[1] . '" height="' . $image_attributes[2] . '">';
            } else {
                $link = wp_get_attachment_url($media_id);
                $img_src = '<p><a href="'.$link.'" target="_blank">'.$link.'</a></p>';
            }
        } else {
            $img_src = '';
        }

        $description = '';
        if (isset($this->args['desc'])) {
            $description = $this->args['desc'];
        }

        $sHtml = '<div id="apercu-' . $this->id . '">' . $img_src . '</div>'
            . '<input type="text" id="value_' . $this->id . '" name="' . $this->id . '" value="' . $this->value . '" />'
            . '<button class="button-secondary upload_image_button" data-pic-to="apercu-' . $this->id . '">' . $this->label . '</button>';

        return $sHtml;
    }

    /**
     * Valide si la valeur du champs est bien un entier.
     *
     * @return boolean
     */
    public function validate()
    {
        if ((int)$this->value == (string)$this->value) {
            return true;
        }

        $this->error_msg = $this->label . " n'a pas une valeur correcte.";
        return false;
    }

    /**
     * Modifie la valeur du champs
     *
     * @param integer $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    public function save($post_id)
    {
        $post = get_post($post_id);

        if (!$post) {
            return false;
        }

        return update_post_meta($post_id, $this->id, $this->value);
    }

    /**
     * Retourne le nom ud champs
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     *
     */
    public function ajaxFileLink()
    {
        $id_attachment = $_POST['attachment_id'];
        //var_dump($id_attachment);
        $data = wp_get_attachment_image_src($id_attachment, 'medium');

        $return = array();

        if ($data) {
            $return['url'] = $data[0];
        }

        echo json_encode($return);
        die();
    }
}
