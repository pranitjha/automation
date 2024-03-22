const customSettings = require('../../utilities/customSettingsUtil');
module.exports = {
  'Admin Login Test': function(browser) {

    // Utilize the custom settings utility function.
    customSettings(browser);

    browser.drupalLoginAsAdmin(function() {
      console.log('Admin login completed');
    });

    browser.end();
  }
};
