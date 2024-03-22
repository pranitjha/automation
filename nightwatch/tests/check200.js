module.exports = {

  'Check Site Health': function (browser) {
    const urlWithoutHttps = browser.globals.url_without_https;
    const shieldUsername = browser.globals.shieldUsername;
    const shieldPassword = browser.globals.shieldPassword;

    // Navigates to the homepage
    browser
      .url('https://' + shieldUsername + ':' + shieldPassword + '@' + urlWithoutHttps)
      .waitForElementVisible('body', browser.globals.waitForConditionTimeout) // Waits until the body element is visible

      // Check if site logo is available.
      .assert.visible('.header-main-menu picture img', 'The site logo is visible on the homepage.')

      // Optionally, check the page title
      .getTitle(function(title) {
        this.assert.ok(title.includes('Homepage'), 'Page title is as expected.');
      })

      // Alternatively, you can check for a specific text on a page to confirm correct load
      // Replace 'Expected Text' with text you expect to see on the homepage.
      .assert.textContains('body', 'Health', 'The homepage body contains the expected text.')

      // End the browser session
      .end();
  }
};
