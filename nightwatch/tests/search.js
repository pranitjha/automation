const loginHelper = require("./helper/loginHelper");
module.exports = {
  'Search in Application': function(browser) {
    const loginHelper = require('./helper/loginHelper');
    loginHelper.login(browser);

    browser
      .assert.urlContains('/user')
      .click('button.header__menu-btn.js-toggle-nav')
      .setValue('input#nav__search-input', 'Coronavirus')
      .click('button#nav__search-btn')
      .waitForElementVisible('.search-results', 1000)
      .pause(10000)
      .click('.search-results__wrap .search-results__item:first-child a')
      .waitForElementVisible('.content', 1000)
      .pause(10000)
      // When ready to log out, we click the logout link or button.
      //Use JavaScript to Force Click
      //Use JavaScript execution to bypass the DOM and simulate a click directly which ignores the overlaying elements.
      .execute(function() {
        document.getElementById('toolbar-item-user').click();
      })

      .waitForElementPresent('a[href*="logout"]', 10000) // Ensure element is present in the DOM
      .execute(function() {
        document.querySelector('a[href*="logout"]').click();
      })

      .url(browser.globals.application_url + '/user')
      // Optionally wait for the login page or confirmation message that indicates successful logout.
      // Again, replace '.login-page' or 'message' with the actual selector that confirms the logout.
      .waitForElementVisible('form#user-login-form', 5000, function(result) {
        this.assert.equal(result.status, 0, 'User is redirected to login page after logout.')
      })

      // Close the browser when done.

      .end();
  }
};
