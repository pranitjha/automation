<?php

namespace Drupal\Tests\phptest\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Test case for enabling a module.
 *
 * @group phptest
 */
class EnableModuleTest extends BrowserTestBase {

  /**
   * The profile to install.
   *
   * @var string
   */
  protected $profile = 'standard';

  /**
   * A user with permission to administer modules.
   *
   * @var \Drupal\user\UserInterface
   */
  protected $adminUser;

  /**
   * Modules to enable.
   *
   * @var array
   */
  protected static $modules = [];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();

    // Create an administrator user with the permission to administer modules.
    $this->adminUser = $this->drupalCreateUser(['administer modules']);
  }

  /**
   * Tests enabling a module.
   */
  public function testEnableModule() {
    // Log in as the administrator user.
    $this->drupalLogin($this->adminUser);

    // Go to the Extend page.
    $this->drupalGet('/admin/modules');

    // Verify that the Extend page is accessible.
    $this->assertSession()->statusCodeEquals(200);

    // Enable the module. Replace 'mymodule' with the machine name of the module.
    $edit = [
      'modules[syslog][enable]' => TRUE,
    ];
    $this->submitForm($edit, 'Install');

    // Verify that the module has been enabled.
    $this->assertSession()->pageTextContains('Module Syslog has been enabled.');
  }

}
