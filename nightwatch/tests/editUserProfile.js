module.exports = {
  'Edit User Profile Test': function(browser) {
    const newEmailAddress = 'testemail@example.com';
    const loginHelper = require('./helper/loginHelper');
    loginHelper.login(browser);

    browser
      .assert.urlContains('/user')

      .url(browser.globals.application_url + '/user/696/edit')
      .waitForElementVisible('input[id="edit-mail"]')

      .clearValue('input[id="edit-mail"]')
      .setValue('input[id="edit-mail"]', newEmailAddress)
      .click('#edit-submit')

      .waitForElementVisible('.messages--status')
      .assert.containsText('.messages--status', 'The changes have been saved.')
      .assert.value('input[id="edit-mail"]', newEmailAddress)

      // End the test
      .end();
  }
};
