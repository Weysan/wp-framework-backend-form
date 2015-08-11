<?php
namespace Tests\Form\Input\Checkbox;

use Form\Input\Checkbox\Checkbox;
/**
 * Test input checkboxes
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class CheckboxTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }
    
    public function test_display_checkboxes()
    {
        $name = 'test-input';

        $value = null;

        $id = null;

        $args = array();

        $section = null;

        $checkbox = new Checkbox($name, $value, $id, $args, $section);

        $this->assertInstanceOf('Form\Input\InputInterface', $checkbox);

        $html_input = $checkbox->getDisplay();

        $this->assertRegExp('/<input type=\"checkbox\"/', $html_input);

        $this->assertRegExp('/name=\"'.$name.'\"/', $html_input);

        $this->assertRegExp('/value=\"1\"/', $html_input);

        $this->assertNotRegExp('/id=/', $html_input);
        
        $this->assertNotRegExp('/checked=/', $html_input);
    }
    
    public function test_display_checkboxes_checked()
    {
        $name = 'test-input';

        $value = 1;

        $id = null;

        $args = array();

        $section = null;

        $checkbox = new Checkbox($name, $value, $id, $args, $section);

        $html_input = $checkbox->getDisplay();
        
        $this->assertRegExp('/checked=\"checked\"/', $html_input);
    }
    
    public function test_save_input_value()
    {
        $name = 'nom';

        $value = 'test';

        $id = 'id_form_test';

        $post = new \stdClass();
        $post->ID = 42;

        $checkbox = new Checkbox($name, $value, $id);

        \WP_Mock::wpFunction('get_post', array(
            'times' => 1,
            'arg' => array($post->ID),
            'return' => $post,
         ));

        \WP_Mock::wpFunction('update_post_meta', array(
            'times' => 1,
            'arg' => array($post->ID, $id, $value),
            'return' => 4,
         ));

        $this->assertNotFalse($checkbox->save($post->ID));
    }
    
    public function test_save_input_value_null()
    {
        $name = 'nom';

        $value = '';

        $id = 'id_form_test';

        $post = new \stdClass();
        $post->ID = 42;

        $checkbox = new Checkbox($name, $value, $id);

        \WP_Mock::wpFunction('get_post', array(
            'times' => 1,
            'arg' => array($post->ID),
            'return' => $post,
         ));

        \WP_Mock::wpFunction('update_post_meta', array(
            'times' => 1,
            'arg' => array($post->ID, $id, $value),
            'return' => 4,
         ));

        $this->assertNotFalse($checkbox->save($post->ID));
    }
}
