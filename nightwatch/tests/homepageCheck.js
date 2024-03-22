module.exports = {
  'Homepage Loading Test': function(browser) {

    browser
      .url(browser.globals.application_url)
      .waitForElementVisible('body', 10000) // Wait for the body of the homepage to be visible
      .assert.visible('header') // Check if the header element is visible, for example
      .assert.visible('.footer.region.region-footer') // Check if the footer element is visible
      .end();
  }
};
