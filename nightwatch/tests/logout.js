const loginHelper = require('./helper/loginHelper');

module.exports = {
  '@tags': ['drupalLogout'],

  'Logout Test Case': function (browser) {

    // First, log in using the login helper
    loginHelper.login(browser);

    // Click the logout link - you need to know the specific selector for your Drupal instance
    // The selector below is a common pattern but might differ depending on your theme or customizations
    browser
      .waitForElementVisible('a[href="/user"]')
      .click('a[href="/user"]')
      .waitForElementVisible('#toolbar-item-user-tray .toolbar-menu .logout a[href="/user/logout"]')
      .click('#toolbar-item-user-tray .toolbar-menu .logout a[href="/user/logout"]')

      // After clicking logout, verify that you are logged out
      // This can be done by checking for the presence of the login link or a confirmation message
      .waitForElementVisible('body')
      .url(browser.globals.application_url + '/user/login')
      .waitForElementVisible('body')
      .pause(browser.globals.waitForConditionTimeout)
      // Optionally you can check for a status message if your site displays one after logging out
      .assert.textContains('body', 'Log in') // This depends on the message Drupal shows after logout

      .end();
  }
};
