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

    public function __construct($name, $value = null, $id = null, $args = array(), $section = null)
    {
        $this->id = $id;

        $this->name = $name;

        $this->args = $args;

        $this->value = $value;

        if (isset($this->args['label'])) {
            $this->label = $this->args['label'];
        }

        \wp_enqueue_media();
        \wp_enqueue_script('custom-header');

        \add_action('admin_menu', array($this, 'createMenuBO'));
    }

    public function createMenuBO()
    {
        /* create menu page */
           \add_media_page($this->label, $this->label, 'manage_options', 'update_file_'.$this->id, array($this, 'uploadFile'));
    }

    /**
     * Retourne le bouton HTML
     *
     * @return string
     */
    public function getDisplay()
    {
        $modal_update_href = esc_url(add_query_arg(array(
            'page' => 'update_file_'.$this->id,
            '_wpnonce' => wp_create_nonce('update_file_'.$this->id),
            'post' => $_GET['post'],
        ), admin_url('upload.php')));

        $content_type = $_GET['post_type'];

        if (!isset($content_type)) {
            $content_type = 'post';
        }

        $media_id = $this->value;

        if ($media_id) {
            $image_attributes = wp_get_attachment_image_src($media_id);

            $img_src = '<img src="'.$image_attributes[0].'" width="'.$image_attributes[1].'" height="'.$image_attributes[2].'">';
        } else {
            $img_src = '';
        }

        $description = '';
        if (isset($this->args['desc'])) {
            $description = $this->args['desc'];
        }

        $sHtml = '<p>'.$img_src.'<br />
        <a id="choose-from-library-link" href="#"
            data-update-link="'.esc_attr($modal_update_href).'"
            data-choose="Choose a Default Image"
            data-update="Set '.$this->label.'">Set '.$this->label.'
        </a> | '.$description.'
        </p>';

        return $sHtml;
    }

    /**
     * Valide si la valeur du champs est bien un entier.
     *
     * @return boolean
     */
    public function validate()
    {
        if ((int) $this->value == (string) $this->value) {
            return true;
        }

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
        return true;
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
     * Upload une image via l'uploader wordpress
     * Enregistre la meta data
     * Redirige vers le formulaire
     */
    public function uploadFile()
    {
        // Add to the top of our data-update-link page
        if (isset($_GET['file'])) {
            $id_file = $_GET['file'];

            $id_input = str_replace('update_file_', '', $_GET['page']);

            $post_id = $_GET['post'];

            $update = update_post_meta($post_id, $id_input, $id_file);

            $url_to_redirect = esc_url(add_query_arg(array(
                                    'post' => $post_id,
                                    'action' => 'edit',
                                ), admin_url('post.php')));

            //var_dump($url_to_redirect); die();
            ?>
        <script>
            jQuery(window).ready(function(){
                window.location = '<?php echo str_replace('&#038;', '&', $url_to_redirect);
            ?>';
            });
        </script>
            <?php
            exit;
        }
    }
}
