<?php

use Drupal\Tests\BrowserTestBase;

/**
 * Tests the functionality of saving a value to a configuration object.
 *
 * @group phptest
 */
class PhpTestConfigurationTest extends BrowserTestBase {

  /**
   * Modules to enable.
   */
  protected static $modules = ['phptest', 'config'];

  /**
   * The default theme.
   *
   * @var string
   */
  protected $defaultTheme = 'stark';

  /**
   * Test saving a value to the configuration object.
   */
  public function testValueSavedToConfigurationObject() {
    // Simulate submitting the form with a specific value.
    $value_to_save = 'Test Value PhpUnit';

    // Submit the form.
    $this->drupalGet('test-settings');
    $this->submitForm(['dummy_text' => $value_to_save], 'Save configuration');

    // Load the configuration and check if the value matches the saved configuration.
    $config = \Drupal::config('phptest.settings');
    $saved_value = $config->get('dummy_text');

    // Assert that the value entered in the form matches the value in the configuration object.
    $this->assertEquals($value_to_save, $saved_value, 'The entered value is saved correctly in the configuration object.');
  }

}
