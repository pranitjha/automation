const loginHelper = require('./helper/loginHelper');

module.exports = {
  '@tags': ['drupalCreateContent'],

  'Create Page Content Test Case': function (browser) {
    // Reusing the login helper method
    loginHelper.login(browser);

    // Now, let's create a page content type
    const title = 'Test Page';
    const mainLocation = '71'; // Lifebridge Health.
    const pageLabel = '6'; // General

    browser
      .url(browser.globals.application_url + '/node/add/page')
      .waitForElementVisible('input#edit-title-0-value', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-title-0-value', title)
      .waitForElementVisible('select#edit-field-main-location', browser.globals.waitForConditionPollInterval)
      .click('select#edit-field-main-location option[value="' + mainLocation + '"]')
      .waitForElementVisible('select#edit-field-page-label', browser.globals.waitForConditionPollInterval)
      .click('select#edit-field-page-label option[value="' + pageLabel + '"]')
      .waitForElementVisible('select#edit-moderation-state-0-state', browser.globals.waitForConditionPollInterval)
      .click('select#edit-moderation-state-0-state option[value="published"]')
      // .pause(browser.globals.waitForConditionTimeout)
      // .waitForElementVisible('[data-testid="ssa-add-to-canvas"]', browser.globals.waitForConditionTimeout)
      // .click('[data-testid="ssa-add-to-canvas"]')

      .waitForElementVisible('input#edit-submit', browser.globals.waitForConditionPollInterval)
      .click('input#edit-submit') // Adjust the selector to the correct one for your Drupal theme

      // Confirm the page was created successfully
      .waitForElementVisible('body', browser.globals.waitForConditionTimeout)
      .assert.textContains('body', 'has been created')

      .end();
  }
};
