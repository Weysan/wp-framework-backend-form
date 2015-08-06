<?php
namespace Tests\Form;

use Form\Form;
/**
 * test de la création d'un formulaire
 *
 * @author Raphael GONCALVES <contact@raphael-goncalves.fr>
 */
class FormTest extends \PHPUnit_Framework_TestCase
{
    private function mockInputText($name, $validate = true)
    {
        $stub = $this->getMockBuilder('Form\Input\Text\Text')
                     ->disableOriginalConstructor()
                     ->getMock();

        $stub->input = '<input type="text" name="'.$name.'" value="test value" />';

        $stub->method('getDisplay')
             ->willReturn($stub->input);

        $stub->method('validate')
             ->willReturn($validate);

        $stub->method('getName')
             ->willReturn($name);

        $stub->method('setValue');

        return $stub;
    }

    public function test_formulaire_creation()
    {
        $input3 = $this->mockInputText('name3');
        $input2 = $this->mockInputText('name2');
        $input1 = $this->mockInputText('name1');

        $form = new Form();

        $form->addInput($input3);

        $form_html = $form->getDisplay();

        $this->assertRegExp('/<input type=\"text\"/', $form_html);

        $form->addInput($input1);
        $form->addInput($input2);

        $form_html = $form->getDisplay();

        $this->assertRegExp('/name3/', $form_html);
        $this->assertRegExp('/name1/', $form_html);
        $this->assertRegExp('/name2/', $form_html);

        $this->assertEquals(3, substr_count($form_html, 'input'));

        $remove = $form->removeInput($input3);

        $this->assertTrue($remove);

        $form_html = $form->getDisplay();

        $this->assertNotRegExp('/name3/', $form_html);
        $this->assertRegExp('/name1/', $form_html);
        $this->assertRegExp('/name2/', $form_html);

        $this->assertEquals(2, substr_count($form_html, 'input'));
    }

    public function test_validate_form()
    {
        $inputTrue = $this->mockInputText('name3');

        $inputFalse = $this->mockInputText('name4', false);

        $form = new Form();

        $form->addInput($inputTrue);

        $this->assertTrue($form->validate());

        $form->addInput($inputFalse);

        $this->assertFalse($form->validate());
    }

    public function test_set_values_form()
    {
        $input3 = $this->mockInputText('name3');
        $input2 = $this->mockInputText('name2');
        $input1 = $this->mockInputText('name1');

        $input3->expects($this->once())
            ->method('setValue');

        $form = new Form();

        $form->addInput($input3);

        $aValues = array(
            'name3' => 'value 3 du formulaire',
        );

        $form->setValues($aValues);

        $form_html = $form->getDisplay();
    }
}
