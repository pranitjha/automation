function customSettings(browser) {
  browser.deleteCookies();
  browser.globals.drupalSitePath = 'sites/default';
}

module.exports = customSettings;
