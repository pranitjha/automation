const loginHelper = require("./helper/loginHelper");
module.exports = {
  'Check Drupal Version': function(browser) {
    const loginHelper = require('./helper/loginHelper');
    loginHelper.login(browser);

    browser
      .assert.urlContains('/user')

      // Navigating to the Drupal Status Reports page.
      .url(browser.globals.application_url + '/admin/reports/status')
      .waitForElementVisible('body', 5000)
      .expect.element('.system-status-general-info__item-details').text.to.match(/Drupal Version[\s\S]*10\.\d+\.\d+/);

    browser.end();
  }
};
