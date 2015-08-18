<?php
namespace Form\Errors;

/**
 * Display errors after saving the entity
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class Validation
{
    private $error_msg;
    
    private $content_type;
    
    public function __construct(\Form\Form $form)
    {
        
        $this->error_msg = $form->errorForm();
        
        if($this->error_msg){
            \add_filter('save_post', array($this, 'getContentType'), 99);
        }
        
        \add_filter('post_updated_messages', array($this, 'addErrorMessage'));
    }
    
    public function getContentType($post)
    {
        $data = get_post($post);
        
        $_SESSION['flash']['error_msg']['type'] = $data->post_type;

        \add_filter('redirect_post_location', array($this, 'redirectionPostFilter'), 99);
    }
    
    public function redirectionPostFilter($location)
    {
        
        $_SESSION['flash']['error_msg']['msg'] = $this->error_msg;
        
        
        $location = add_query_arg('error', 99, $location);
        
        return $location;
    }
    
    public function addErrorMessage($messages)
    {
        $messages[$_SESSION['flash']['error_msg']['type']][99] = $_SESSION['flash']['error_msg'];

        //unset($_SESSION['flash']['error_msg']);
        var_dump($messages);
        die();
        return $messages;
    }
    
}
