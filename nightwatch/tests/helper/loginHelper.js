// Create a helper function for logging in
module.exports = {
  login(browser) {
    // Use variables from test settings
    const urlWithoutHttps = browser.globals.url_without_https;
    const loginUrl = browser.globals.application_url + '/user/login';
    const shieldUsername = browser.globals.shieldUsername;
    const shieldPassword = browser.globals.shieldPassword;
    const adminUsername = browser.globals.adminUsername;
    const adminPassword = browser.globals.adminPassword;

    browser
      // Apply basic authentication if your site uses a shield
      .url('https://' + shieldUsername + ':' + shieldPassword + '@' + urlWithoutHttps)

      // Navigate to the Drupal 10 login page
      .url(loginUrl)
      .waitForElementVisible('input[name=name]', browser.globals.waitForConditionTimeout)
      .setValue('input[name=name]', adminUsername)
      .setValue('input[name=pass]', adminPassword)
      .click('#edit-actions #edit-submit')

      // Check that the login was successful
      .waitForElementVisible('body', browser.globals.waitForConditionTimeout)
      .assert.urlContains('/user/'); // Depending on your Drupal setup confirm login success
  }
};
