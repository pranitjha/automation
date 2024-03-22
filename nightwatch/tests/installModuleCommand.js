const customSettings = require('../../utilities/customSettingsUtil');
module.exports = {
  'Install a Drupal Module': function(browser) {
    // Replace 'dblog' with the machine name of the module you wish to enable
    const moduleName = 'dblog';

    // Utilize the custom settings utility function.
    customSettings(browser);

    browser.drupalInstallModule(moduleName, function() {
      // This callback function is called after the module is successfully installed
      console.log(moduleName + ' module has been installed.');
    });

    // Continue with any other commands or finish the test
    browser.end();
  }
};
