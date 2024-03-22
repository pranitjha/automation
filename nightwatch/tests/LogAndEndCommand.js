module.exports = {
  'End Test and Log on Error': function(browser) {

    browser.drupalLogAndEnd({ onlyOnError: true }, function() {
      console.log('Ending the session and checking for errors to log.');
    });

    browser.end();
  }
};
