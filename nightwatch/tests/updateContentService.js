const loginHelper = require('./helper/loginHelper');

module.exports = {
  '@tags': ['drupalUpdateContent'],

  'Create Page Content Test Case': function (browser) {
    // Reusing the login helper method
    loginHelper.login(browser);

    // Now, let's create a page content type
    const nodeId = 12956;
    const updatedTitle = 'Updated by Nightwatch - Test Service';
    const updatedAltTitle = 'Updated by Nightwatch - Alternate Title';
    const updatedTeaserTitle = 'Updated by Nightwatch - Teaser Title';
    const updatedCtaText = 'Go Back';
    const updatedMainLocation = '1'; // Lifebridge Health.

    browser
      .url(browser.globals.application_url + '/node/' + nodeId + '/edit')
      .waitForElementVisible('input#edit-title-0-value', browser.globals.waitForConditionTimeout)
      .clearValue('input[id=edit-title-0-value]')
      .setValue('input#edit-title-0-value', updatedTitle)
      .waitForElementVisible('input#edit-field-title-alternative-0-value', browser.globals.waitForConditionTimeout)
      .clearValue('input#edit-field-title-alternative-0-value')
      .setValue('input#edit-field-title-alternative-0-value', updatedAltTitle)
      .waitForElementVisible('input#edit-field-teaser-title-0-value', browser.globals.waitForConditionTimeout)
      .clearValue('input#edit-field-teaser-title-0-value')
      .setValue('input#edit-field-teaser-title-0-value', updatedTeaserTitle)
      .waitForElementVisible('input#edit-field-cta-link-0-uri', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-field-cta-link-0-title', updatedCtaText)
      .waitForElementVisible('select#edit-field-main-location', browser.globals.waitForConditionPollInterval)
      // Use sendKeys to perform multiple actions
      .sendKeys('select[id="edit-field-main-location"]', browser.Keys.CONTROL) // Press CONTROL key
      .click('select#edit-field-main-location option[value="' + updatedMainLocation + '"]')
      .sendKeys('select[id="edit-field-main-location"]', browser.Keys.NULL) // Release all keys

      .pause(browser.globals.waitForConditionPollInterval)

      .waitForElementVisible('input#edit-submit', browser.globals.waitForConditionPollInterval)
      .click('input#edit-submit') // Adjust the selector to the correct one for your Drupal theme

      // Confirm the page was created successfully
      .waitForElementVisible('body', browser.globals.waitForConditionTimeout)
      .assert.textContains('body', 'has been updated')
      .assert.textContains('h1', updatedTitle)

      .end();
  }
};
