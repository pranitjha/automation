const customSettings = require('../../utilities/customSettingsUtil');
module.exports = {
  'User Login Test': function(browser) {
    const username = 'xyz';
    const password = 'xyz';

    // Utilize the custom settings utility function.
    customSettings(browser);

    browser.drupalLogin({
      name: username,
      password: password
    });

    browser.end();
  }
};
