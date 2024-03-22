module.exports = {
  '@tags': ['drupalForgetPassword'],

  'Forgot Password Test Case': function (browser) {
    // Define variables for the test
    const userEmail = browser.globals.userEmail;
    const urlWithoutHttps = browser.globals.url_without_https;
    const loginUrl = browser.globals.application_url + '/user/login';
    const shieldUsername = browser.globals.shieldUsername;
    const shieldPassword = browser.globals.shieldPassword;

    // Navigate to the user login page
    browser
      // Apply basic authentication if your site uses a shield
      .url('https://' + shieldUsername + ':' + shieldPassword + '@' + urlWithoutHttps)
      // Goto Login Page
      .url(loginUrl)
      .waitForElementVisible('body', browser.globals.waitForConditionTimeout) // Wait for the login form to appear

      // Click the "Forgot your password?" link
      .waitForElementVisible('a[href="/user/password"]', browser.globals.waitForConditionTimeout)
      .click('a[href="/user/password"]')

      // Wait for the password reset form to appear
      .waitForElementVisible('.form-item-name #edit-name', browser.globals.waitForConditionTimeout)

      // Enter the user's email address into the form
      .setValue('.form-item-name #edit-name', userEmail)

      // Click the submit button to send the password reset request
      .click('#edit-actions input[id=edit-submit]')

      // Verify that the confirmation message is displayed
      .waitForElementVisible('body .messages--status', browser.globals.asyncHookTimeout)
      .assert.textContains('body .messages--status', 'instructions have been sent to your email address.')

      .end();
  }
};
