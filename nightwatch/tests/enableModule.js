module.exports = {
  'Enable a Module': function(browser) {
    const moduleName = 'dblog'; // The machine-readable name (ID) of the module.
    const loginHelper = require('./helper/loginHelper');
    loginHelper.login(browser);

    browser
      .assert.urlContains('/user')

      .url(browser.globals.application_url + '/admin/modules')
      .waitForElementVisible('body', 5000)

      .waitForElementVisible(`input[id="edit-modules-${moduleName}-enable"]`, 5000)
      .click(`input[id="edit-modules-${moduleName}-enable"]`)

      // Scroll to and click the 'Install' button at the bottom of the page
      .execute('window.scrollTo(0,document.body.scrollHeight);')
      .click('input#edit-submit')

      // Finish the session
      .end();
  }
};
