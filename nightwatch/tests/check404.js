module.exports = {
  '@tags': ['drupal404'],

  'Check 404 Error Page': function (browser) {
    // Define variables for the test
    const urlWithoutHttps = browser.globals.url_without_https;
    const shieldUsername = browser.globals.shieldUsername;
    const shieldPassword = browser.globals.shieldPassword;

    const nonExistentUrl = browser.globals.application_url + '/this-page-is-not-found'; // Replace with the path that should redirect

    // Define a part of the text or a unique element on your 404 page
    const errorText = 'We are sorry'; // Replace this with actual text or an element on your 404 page

    browser
      // Apply basic authentication if your site uses a shield
      .url('https://' + shieldUsername + ':' + shieldPassword + '@' + urlWithoutHttps)

      // Navigate to the non-existent URL
      .url(nonExistentUrl)

      // Wait for the 404 page to load, you can use unique text or an element that appears only on your 404 page
      // Note: If there is a specific element with an id or class, use waitForElementVisible for greater accuracy
      .waitForElementVisible('body', 1000)

      // Check if the specific text appears on the 404 page
      .assert.textContains('body', errorText, 'The 404 error text is present on the page.')

      // Optionally, you could also check for elements unique to the 404 page
      // For example, if your 404 page has a unique title or heading with "Page not found"
      .assert.textContains('h1', errorText, 'The page title contains Not Found message.')

      // If your 404 page has a unique CSS class or id, you can use that for a more accurate test
      .assert.visible('.coh-style-sitewide-search-form .form-item-keywords #edit-keywords', 'The error 404 search element is visible on the page.')

      // End the browser session
      .end();
  }
};
