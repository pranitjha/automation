const loginHelper = require('./helper/loginHelper');

module.exports = {
  '@tags': ['drupalLogin'],

  'Drupal Login': function (browser) {
    loginHelper.login(browser);
    browser.end();
  }
};
