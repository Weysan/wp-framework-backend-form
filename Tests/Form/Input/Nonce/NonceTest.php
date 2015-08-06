<?php
namespace Tests\Form\Input\Nonce;

use Form\Input\Nonce\Nonce;
/**
 * Description of NonceTest
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class NonceTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        \WP_Mock::setUp();
    }

    public function tearDown()
    {
        \WP_Mock::tearDown();
    }

    public function test_nonce_field()
    {
        \WP_Mock::wpFunction('wp_create_nonce', array(
            'times' => 1,
         ));

        $nonce = new Nonce();

        $html = $nonce->getDisplay();

        $this->assertRegExp('/type=\"hidden\"/', $html);
        $this->assertRegExp('/name=\"custom_meta_box_nonce\"/', $html);

        $nonce->setValue('test');

        $html_new = $nonce->getDisplay();

        $this->assertEquals($html, $html_new);
    }
}
