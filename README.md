# wp-framework-backend-form
Wordpress Framework to create custom fields in wordpress backoffice.

## Description
This is not a user interface to create custom fields in a content type. This wordpress plugin is destinated to wordpress developers who wants to create some custom fields with custom validation.

## Installation

### Manually

- Download the plugin
- Unzip in your wordpress plugin directory
- Activate the plugin in your wordpress backoffice

### Command Line

If you are using WP_CLI tools, you can use this command line to install the plugin :

`wp plugin install https://github.com/Weysan/wp-framework-backend-form/archive/master.zip --activate`

## Usage
In your template's functions.php :

`use Form\WpForm;

$fields_posts = array(

    array(
    
        'type' => 'Form\Input\Text\Text',
        
        'id' => 'id_form',
        
        'label' => 'Mon label',
        
        'desc' => 'description de mon champs'
        
    ),
    
    array(
    
        'type' => 'Form\Input\Text\Text',
        
        'id' => 'id_form_2',
        
        'label' => 'Mon aute label',
        
        'desc' => 'Autre description'
        
    ),
    
    array(
    
        'type' => 'Form\Input\File\File',
        
        'id' => 'id_form_file',
        
        'label' => 'Image',
        
        'desc' => 'Attention, le page sera rechargée.'
        
    ),
    
    array(
    
        'type' => 'Form\Input\Textarea\Textarea',
        
        'id' => 'test_wysiwyg',
        
        'label' => 'Un contenu HTML',
        
        'desc' => 'Un texte HTML',
        
        'wysiwyg' => true
        
    )
    
);

$form_posts = new WpForm('post', 'Custom field box\' title', $fields_posts);`

`WpForm` class generate the custom fields' box in a specific content type, the parameters are :
- The content type ('post', 'page', or a custom content type)
- The box title
- An array with the different fields data
  * Type : the input type class to use
  * id : the unique input's id
  * label : the input's label
  * desc : the field description
  * wysiwyg (for textarea's only) : transform the textarea to a tinymce area

## Using PHPUnit tests
You can use the PHPUnits tests. But before that, you need to install depedencies using composer :

`composer install`

PHPUnit tests are using WP_Mock : https://github.com/10up/wp_mock
