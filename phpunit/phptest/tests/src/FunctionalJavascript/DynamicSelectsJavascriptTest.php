<?php

namespace Drupal\Tests\phptest\FunctionalJavascript;

use Drupal\FunctionalJavascriptTests\WebDriverTestBase;

/**
 * Tests the dynamic selects form with JavaScript.
 *
 * @group phptest
 */
class DynamicSelectsJavascriptTest extends WebDriverTestBase {

  /**
   * The default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = ['phptest'];

  /**
   * Tests AJAX functionality of the form's dynamic selects.
   */
  public function testFormAjax() {
    // Create and log in a user with permission to access the form.
    $user = $this->drupalCreateUser(['access content']);
    $this->drupalLogin($user);

    // Navigate to the dynamic selects form.
    $this->drupalGet('/dynamic-selects-form');

    // Assert the presence of the first select field and trigger an AJAX call upon changing its value.
    $firstSelect = $this->getSession()->getPage()->find('css', 'select[name="first_select"]');
    $this->assertNotEmpty($firstSelect, 'The first select field is present.');
    $firstSelect->selectOption('option1');

    // Wait for the AJAX request to complete.
    $this->assertSession()->assertWaitOnAjaxRequest();

    // Assert that the second select field is populated correctly based on the AJAX call.
    $secondSelect = $this->getSession()->getPage()->find('css', 'select[name="second_select"]');
    $this->assertNotEmpty($secondSelect, 'The second select field is populated via AJAX.');

    // Assert that the second select field contains the correct number of options.
    $options = $secondSelect->findAll('css', 'option');
    $this->assertCount(2, $options, 'The second select has the correct number of options.');

    // Assert the correctness of the options in the second select field.
    foreach (['sub_option1', 'sub_option2'] as $expectedValue) {
      $option = $this->getSession()->getPage()->find('css', "select[name='second_select'] option[value='{$expectedValue}']");
      $this->assertNotNull($option, "Expected option '{$expectedValue}' is present in the second select.");
    }
  }

}
