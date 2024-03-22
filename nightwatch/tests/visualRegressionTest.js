const resemble = require('resemblejs');
const fs = require('fs');
const baselineImagePath = './screenshots/baseline/homepage.png'; // The path to your baseline image
const currentImagePath = './screenshots/current/homepage.png'; // Where you will save the current screenshot

module.exports = {
  'Visual Regression Test': function(browser) {
    browser
      .url(browser.globals.application_url)
      .waitForElementVisible('body', 1000)

      .saveScreenshot(currentImagePath, function() {
        // Ensure we only proceed once the file has been saved
        fs.readFile(currentImagePath, (err, currentImage) => {
          if (err) {
            throw err;
          }

          // Use resemble.js to compare the screenshots
          resemble(currentImage)
            .compareTo(baselineImagePath)
            .onComplete(function(data) {
              const mismatchPercentage = parseFloat(data.misMatchPercentage);

              // Fail the test if the mismatch is above a tolerance threshold
              if (mismatchPercentage > 0.01) { // 0.01% tolerance
                console.error(`Visual regression test failed: ${mismatchPercentage}% mismatch`);
                browser.assert.fail('Visual regression test failed.');
              }
            });
        });
      })
      .end();
  }
};
