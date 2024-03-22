module.exports = {
  'Add Content to Basic Page Content Type': function (browser) {

    const loginHelper = require('./helper/loginHelper');
    const titleSelector = 'input[name="title[0][value]"]'; // CSS selector for the Title field
    const title = 'My New Post';
    const descSelector = 'textarea#edit-field-page-description-0-value'; // CSS selector for the Body field
    const desc = 'This is the content of the new post.';
    const submitButtonSelector = 'input#edit-submit'; // CSS selector for the Submit button
    const successMessageSelector = '.messages.messages--status'; // CSS selector for the success message
    const expectedMessage = `Status message
Basic page ${title} has been created.`;

    // First, log in using the login helper
    loginHelper.login(browser);

    browser

      .url(browser.globals.application_url + '/node/add/page')
      .waitForElementVisible('body', 5000)

      .setValue(titleSelector, title)
      .setValue(descSelector, desc)

      .click(submitButtonSelector)

      .waitForElementVisible(successMessageSelector, 10000)
      .expect.element(successMessageSelector).text.to.contain(expectedMessage);

      browser.end();
  }
};
