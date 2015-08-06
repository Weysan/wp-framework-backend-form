<?php
namespace Tests\Form\Input\Url;

use Form\Input\Url\Url;
/**
 * Test pour un type de champs URL
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class UrlTest extends \PHPUnit_Framework_TestCase
{
    public function test_validation_url()
    {
        $url = new Url('test');

        $value_ot_test = 'http://google.fr';

        $url->setValue($value_ot_test);

        $this->assertTrue($url->validate());

        $value_ot_test = 'http://www.google.fr';

        $url->setValue($value_ot_test);

        $this->assertTrue($url->validate());

        $value_ot_test = 'https://www.google.fr';

        $url->setValue($value_ot_test);

        $this->assertTrue($url->validate());

        $value_ot_test = '/path/to/file/';

        $url->setValue($value_ot_test);

        $this->assertTrue($url->validate());

        $value_ot_test = '/path/to/file.php';

        $url->setValue($value_ot_test);

        $this->assertTrue($url->validate());

        $value_ot_test = '/path/to/file/.php';

        $url->setValue($value_ot_test);

        $this->assertFalse($url->validate());

        $value_ot_test = 'http://test/fr/machin/truc.fr';

        $url->setValue($value_ot_test);

        $this->assertFalse($url->validate());

        $value_ot_test = 'test.fr/test/';

        $url->setValue($value_ot_test);

        $this->assertFalse($url->validate());
    }
}
