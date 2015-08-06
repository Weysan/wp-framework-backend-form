<?php
namespace Tests\Form\Input\Text;

use Form\Input\Text\Text;
/**
 * test du type de champs texte de wordpress
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class TextTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    public function test_display_input_text()
    {
        $name = 'test-input';

        $value = null;

        $id = null;

        $args = array();

        $section = null;

        $text = new Text($name, $value, $id, $args, $section);

        $this->assertInstanceOf('Form\Input\InputInterface', $text);

        $html_input = $text->getDisplay();

        $this->assertRegExp('/<input type=\"text\"/', $html_input);

        $this->assertRegExp('/name=\"'.$name.'\"/', $html_input);

        $this->assertRegExp('/value=\"\"/', $html_input);

        $this->assertNotRegExp('/id=/', $html_input);
    }

    public function test_display_input_text_with_value()
    {
        $name = 'test-input-autre';

        $value = "ma valeur";

        $id = null;

        $args = array(
            'class' => 'test_class',
        );

        $section = null;

        $text = new Text($name, $value, $id, $args, $section);
        $html_input = $text->getDisplay();

        $this->assertRegExp('/<input type=\"text\"/', $html_input);

        $this->assertRegExp('/name=\"'.$name.'\"/', $html_input);

        $this->assertRegExp('/value=\"'.$value.'\"/', $html_input);

        $this->assertNotRegExp('/id=/', $html_input);
    }

    public function test_display_input_text_with_value_and_id()
    {
        $name = 'test-input-autre';

        $value = "ma valeur 2";

        $id = "id-input";

        $args = array(
            'class' => 'test_class',
        );

        $section = null;

        $text = new Text($name, $value, $id, $args, $section);
        $html_input = $text->getDisplay();

        $this->assertRegExp('/<input type=\"text\"/', $html_input);

        $this->assertRegExp('/name=\"'.$name.'\"/', $html_input);

        $input_name = $text->getName();
        $this->assertTrue($name === $input_name);

        $this->assertRegExp('/value=\"'.$value.'\"/', $html_input);

        $this->assertRegExp('/id=\"'.$id.'\"/', $html_input);

        $text->setValue('autre valeur');
        $html_input = $text->getDisplay();

        $this->assertRegExp('/value=\"autre valeur\"/', $html_input);
    }

    public function test_validation_input()
    {
        $name = 'nom';

        $value = 'test';

        $text = new Text($name, $value);

        $retour = $text->validate();

        $this->assertTrue($retour);

        $args = array(
            'required' => true,
        );

        $text = new Text($name, $value, null, $args);

        $retour = $text->validate();

        $this->assertTrue($retour);

        $text = new Text($name, null, null, $args);

        $retour = $text->validate();

        $this->assertFalse($retour);

        $text = new Text($name);

        $retour = $text->validate();

        $this->assertTrue($retour);

        $text = new Text($name, array('test' => 'test'));

        $retour = $text->validate();

        $this->assertFalse($retour);
    }

    public function test_save_input_value()
    {
        $name = 'nom';

        $value = 'test';

        $id = 'id_form_test';

        $post = new \stdClass();
        $post->ID = 42;

        $text = new Text($name, $value, $id);

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

        $this->assertNotFalse($text->save($post->ID));
    }
}
