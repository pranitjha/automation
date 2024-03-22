module.exports = {
  'Create Test Content Type': function(browser) {
    const loginHelper = require('./helper/loginHelper');
    loginHelper.login(browser);

    browser
      .waitForElementVisible('body', 1000)

      // Navigate to the 'Content Types Add' section.
      .url(browser.globals.application_url + '/admin/structure/types/add') // Replace with the actual selector to the Content Types link.
      .waitForElementVisible('input[id="edit-name"]', 1000)

      // Fill in the form for creating a content type.
      .setValue('input[id="edit-name"]', 'Test Content Type') // Replace with the actual input field.
      // ... continue setting values for all required fields

      // Assume there is a submit button that needs to be clicked to save the content type.
      .pause(5000)
      .click('input[id="edit-save-continue"]') // Replace with the actual selector
      .waitForElementVisible('h2#message-status-title', 10000) // Wait for the success message

      // Close the browser when done.
      .end();
  }
};
