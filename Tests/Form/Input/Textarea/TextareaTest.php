<?php
namespace Tests\Form\Input;

use Form\Input\Textarea\Textarea;
/**
 * Test pour l'affichage des textarea
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class TextareaTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    public function test_textarea_normal()
    {
        $textarea = new Textarea('mon_textarea', null, "mon_textarea_id");

        $html = $textarea->getDisplay();

        $this->assertRegExp('/<textarea(. *)+><\/textarea>/', $html);
    }

    public function test_textarea_wysiwyg()
    {
        $textarea = new Textarea('mon_textarea', null, "mon_textarea_id", array('wysiwyg' => true));

        \WP_Mock::expectActionAdded('admin_print_footer_scripts', array($textarea, 'addEditorTinymce'), 99);

        $html = $textarea->getDisplay();

        $this->assertRegExp('/<textarea(. *)+><\/textarea>/', $html);
    }
}
