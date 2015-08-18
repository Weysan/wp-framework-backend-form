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
    
    /**
     * Get error messages and init filters
     * 
     * @param \Form\Form $form
     */
    public function __construct(\Form\Form $form)
    {
        
        $this->error_msg = $form->errorForm();
        
        if($this->error_msg){
            \add_filter('save_post', array($this, 'getContentType'), 99);
        }
        
        \add_filter('post_updated_messages', array($this, 'addErrorMessage'));
    }
    
    /**
     * Get the current content type
     * 
     * @param integer $post
     */
    public function getContentType($post)
    {
        $data = get_post($post);
        
        $_SESSION['flash']['error_msg']['type'] = $data->post_type;

        \add_filter('redirect_post_location', array($this, 'redirectionPostFilter'), 99);
    }
    
    /**
     * Hook the Redirect URL to add the error message.
     * 
     * @param string $location
     * @return string
     */
    public function redirectionPostFilter($location)
    {
        
        $_SESSION['flash']['error_msg']['msg'] = $this->error_msg;
        
        
        $location = add_query_arg('message', 99, $location);
        
        return $location;
    }
    
    /**
     * Add the custom error message
     * 
     * @param array $messages
     * @return array
     */
    public function addErrorMessage($messages)
    {
        if(isset($_SESSION['flash']['error_msg']['msg'])){
            $messages[$_SESSION['flash']['error_msg']['type']][99] = $_SESSION['flash']['error_msg']['msg'];
        }
        unset($_SESSION['flash']['error_msg']);
        
        return $messages;
    }
    
}
