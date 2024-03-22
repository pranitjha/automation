const loginHelper = require('./helper/loginHelper');

module.exports = {
  '@tags': ['drupalCreateContent'],

  'Create Page Content Test Case': function (browser) {
    // Reusing the login helper method
    loginHelper.login(browser);

    // Now, let's create a page content type
    const title = 'Test Service';
    const altTitle = 'Alternate Title';
    const teaserTitle = 'Teaser Title';
    const ctaLink = '<front>';
    const ctaText = 'Goto Homepage';
    const mainLocation = '71'; // Lifebridge Health.

    browser
      .url(browser.globals.application_url + '/node/add/service')
      .waitForElementVisible('input#edit-title-0-value', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-title-0-value', title)
      .waitForElementVisible('input#edit-field-title-alternative-0-value', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-field-title-alternative-0-value', altTitle)
      .waitForElementVisible('input#edit-field-teaser-title-0-value', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-field-teaser-title-0-value', teaserTitle)
      .waitForElementVisible('input#edit-field-cta-link-0-uri', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-field-cta-link-0-uri', ctaLink)
      .waitForElementVisible('input#edit-field-cta-link-0-title', browser.globals.waitForConditionTimeout)
      .setValue('input#edit-field-cta-link-0-title', ctaText)
      .waitForElementVisible('select#edit-field-main-location', browser.globals.waitForConditionPollInterval)
      .click('select#edit-field-main-location option[value="' + mainLocation + '"]')
      .waitForElementVisible('select#edit-moderation-state-0-state', browser.globals.waitForConditionPollInterval)
      .click('select#edit-moderation-state-0-state option[value="published"]')
      .pause(browser.globals.waitForConditionTimeout)

      .waitForElementVisible('input#edit-submit', browser.globals.waitForConditionPollInterval)
      .click('input#edit-submit') // Adjust the selector to the correct one for your Drupal theme

      // Confirm the page was created successfully
      .waitForElementVisible('body', browser.globals.waitForConditionTimeout)
      .assert.textContains('body', 'has been created')
      .assert.textContains('h1', title)

      .end();
  }
};
