describe('accessibility testing', function() {
  it('accessibility rule subset', function(browser) {
    browser
      .navigateTo(browser.globals.application_url)
      .axeInject()
      .axeRun('body', {
        runOnly: ['color-contrast', 'image-alt'],
      });
  });
});
