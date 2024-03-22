module.exports = {
  '@tags': ['drupalRedirect'],

  'Check Redirection': function (browser) {
    // Define variables for the test
    const urlWithoutHttps = browser.globals.url_without_https;
    const shieldUsername = browser.globals.shieldUsername;
    const shieldPassword = browser.globals.shieldPassword;

    const initialUrl = browser.globals.application_url + '/main/homecare'; // Replace with the path that should redirect
    const expectedRedirectionUrl = browser.globals.application_url + '/partners/homecare'; // Replace with the expected destination path

    browser
      // Apply basic authentication if your site uses a shield
      .url('https://' + shieldUsername + ':' + shieldPassword + '@' + urlWithoutHttps)

      // Navigate to the URL that should redirect
      .url(initialUrl)

      // Use a pause to allow time for the redirection to happen
      // The amount of time needed for the pause may vary based on network conditions and server response time
      .pause(browser.globals.asyncHookTimeout)

      // Now check the current URL to see if it has been redirected to the expected URL
      // It's important to note that 301/302 HTTP status codes are automatically followed by the browser
      .assert.urlEquals(expectedRedirectionUrl, 'The URL has been successfully redirected to the expected destination.')

      // End the session
      .end();
  }
};
