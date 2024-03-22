module.exports = {
  'Filter and Delete Content': function(browser) {
    const contentTitleToDelete = 'My New Post';
    const loginHelper = require('./helper/loginHelper');
    loginHelper.login(browser);

    browser
      .assert.urlContains('/user')

      .url(browser.globals.application_url + '/admin/content')
      .waitForElementVisible('body', 5000)

      .setValue('input[name="title"]', contentTitleToDelete)
      .click('input[id="edit-submit-content"]')
      .waitForElementVisible('body', 5000)

      .click(`th.select-all.views-field.views-field-node-bulk-form`)
      .click('select[id="edit-action"] option[value="node_delete_action"]')
      .click('input[id="edit-submit"]')

      .waitForElementVisible('input[id="edit-submit"]', 5000)
      .click('input[id="edit-submit"]')

      // End the session
      .end();
  }
};
