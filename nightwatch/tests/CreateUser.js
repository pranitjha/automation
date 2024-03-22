describe("CreateUser", function () {
  it("tests CreateUser", function (browser) {
    const loginHelper = require('./helper/loginHelper');
    // First, log in using the login helper
    loginHelper.login(browser);

    browser
      .navigateTo(browser.globals.application_url + '/admin/people')
      .click("#block-claro-local-actions a")
      .click("#edit-mail")
      .setValue("#edit-mail", "test@test.com")
      .click("div.page-wrapper")
      .perform(function() {
        const actions = this.actions({async: true});
        return actions
          .keyDown(this.Keys.META);
      })
      .perform(function() {
        const actions = this.actions({async: true});
        return actions
          .keyUp(this.Keys.META);
      })
      .click("#edit-name")
      .setValue("#edit-name", "test")
      .click("#edit-pass-pass1")
      .setValue("#edit-pass-pass1", "test#")
      .click("#edit-pass-pass2")
      .setValue("#edit-pass-pass2", "test#")
      .click("#edit-submit")
      .end();
  });
});
