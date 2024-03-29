# Nightwatch Test Integration in a Drupal Project

This README provides guidance on how to integrate [Nightwatch.js](http://nightwatchjs.org/) tests into a Drupal project. Nightwatch.js is an automated testing framework for web applications and websites, running on Node.js and using the W3C WebDriver API (formerly known as Selenium WebDriver).

## Prerequisites

Before you proceed with the integration, ensure you have the following prerequisites installed and set up:

- Node.js (Check [Node.js website](https://nodejs.org/) for installation instructions)
- An existing Drupal project
- Drupal's Nightwatch test configuration set up as per the Drupal documentation

## Step 1: Install Nightwatch

Start by installing Nightwatch.js in your Drupal project. Run the following command in the root of your project:

```
- npm init nightwatch

Press y when you see the prompt to install create-nightwatch.
This installs Nightwatch, asks your preferences and sets up the nightwatch.conf.js file based on your preferences.
For more details check this: https://nightwatchjs.org/guide/quickstarts/create-and-run-a-nightwatch-test.html
```

## Step 2: Executing Nightwatch Test cases

Once your setup is done, you can run tests with this command:

```
npx nightwatch </path/of/the/test/case>

Path can be individual test case or a directory which contains multiple test cases.

In case you are running custom commands, please find below command to execute the test case:
yarn --cwd /var/www/html/docroot/core test:nightwatch PATH_TO_YOUR_TEST_CASE

Even the test cases can be categorised into different tags, and a particular tag can be executed:
--tag tagName
```

## Step 3: Adding Nightwatch JS in pipeline

If you want to run the Nightwatch JS related tests in the code studio pipeline,
Add the below code in you .gitlab-ci.yml file.

Add variables:
```
variables:
  # ACQUIA
  #We want Code Studio to build the Drupal code base (e.g run composer to bring in our application's dependencies).
  ACQUIA_JOBS_BUILD_DRUPAL: 'true'
  #We will execute the Code Studio tests stage which will test Drupal.
  ACQUIA_JOBS_TEST_DRUPAL: 'true'
  # We will not create a CDE to deploy our codebase (feel free to change this to true if you have a CDE entitlement and want Code Studio to deploy the build artifact to it.
  ACQUIA_JOBS_CREATE_CDE: 'false'
  # We will install Drupal during the pipeline execution.
  ACQUIA_TASKS_SETUP_DRUPAL: 'true'
  # We will install a fresh copy of Drupal.
  ACQUIA_TASKS_SETUP_DRUPAL_STRATEGY: 'install'
  # We will use the "standard" install profile 
  ACQUIA_TASKS_SETUP_DRUPAL_PROFILE: 'standard'
  # We will not install the site from configuration or import configuration during the setup process.
  ACQUIA_TASKS_SETUP_DRUPAL_CONFIG_IMPORT: 'false'

  # DATABASE
  # Username for the MySQL database.
  MYSQL_USER: drupal
  # Password for the MySQL database.
  MYSQL_PASSWORD: drupal
  # Name of the database to use for Drupal.
  MYSQL_DATABASE: drupal
  # Hostname of the database container.
  DB_HOST: mysql

  # OTHER
  # Where we want to store our test reports.
  REPORT: '$CI_PROJECT_DIR/tests/reports'

  # Nightwatch
  # The URL where Drupal will be accessible during our tests.
  DRUPAL_TEST_BASE_URL: 'http://127.0.0.1:8080'
  # The Drupal database URL Drupal will use during our tests.
  DRUPAL_TEST_DB_URL: mysql://$MYSQL_USER:$MYSQL_PASSWORD@$DB_HOST:3306/$MYSQL_DATABASE
  # Hostname of the WebDriver server (Chromedriver in our case) that will be used for testing.
  DRUPAL_TEST_WEBDRIVER_HOSTNAME: '127.0.0.1'
  # Port number of the WebDriver.
  DRUPAL_TEST_WEBDRIVER_PORT: '9515'
  # Whether ChromeDriver should start automatically during tests.
  DRUPAL_TEST_CHROMEDRIVER_AUTOSTART: "false"
  # Arguments for the Chrome browser when used by WebDriver. 
  DRUPAL_TEST_WEBDRIVER_CHROME_ARGS: --disable-gpu --headless --no-sandbox --disable-dev-shm-usage --disable-extensions
  # Where to store the Nightwatch reports.
  DRUPAL_NIGHTWATCH_OUTPUT: $REPORT/nightwatch
  # Directories to ignore when running tests.
  DRUPAL_NIGHTWATCH_IGNORE_DIRECTORIES: node_modules,vendor,.*,sites/*/files,sites/*/private,sites/simpletest
  # Path to the directory where Nightwatch should search for tests. In our case the route of our repository since that's where we defined our "tests" directory.
  DRUPAL_NIGHTWATCH_SEARCH_DIRECTORY: ../
```

And the Nightwatch section can be added like this:
```
"Nightwatch":
  stage: "Test Drupal"
  services:
    - mysql:5.7
    - drupalci/webdriver-chromedriver:production
  extends: .cache_strategy_pull
  needs: ["Build Code" , "Manage Secrets"]
  rules:
  - when: on_success
    allow_failure: false
  artifacts:
    when: always
    expose_as: 'Nightwatch'
    paths:
        - $REPORT/nightwatch/
    expire_in: 1 week
  before_script:
    - !reference [.clone_standard_template, script]
    - . "$STANDARD_TEMPLATE_PATH"/ci-files/scripts/stages/build/job_build_code.sh
    - . "$STANDARD_TEMPLATE_PATH"/ci-files/scripts/utility/install_drupal.sh
    - start_section install_yarn "Installing yarn and updating chromedriver"
    # Install yarn
    - curl -o- -L https://yarnpkg.com/install.sh | bash
    # Make available in the current terminal
    - export PATH="$HOME/.yarn/bin:$HOME/.config/yarn/global/node_modules/.bin:$PATH"
    # Update chromedriver as Drupal package.json can be late on chrome version.
    - |
      yarn --cwd $CI_PROJECT_DIR/docroot/core upgrade \
      chromedriver@$(curl -s http://$DRUPAL_TEST_WEBDRIVER_HOSTNAME:$DRUPAL_TEST_WEBDRIVER_PORT/status | jq '.value.build.version' | tr -d '"' | cut -d. -f1)
    - exit_section install_yarn
  script:
    - start_section configure_nightwatch "Configure Nightwatch"
    # Configure Nightwatch for Drupal.
    # see: https://git.drupalcode.org/project/gitlab_templates/-/blob/main/includes/include.drupalci.main.yml#L639
    - touch $CI_PROJECT_DIR/docroot/core/.env
    - |
      cat <<EOF > $CI_PROJECT_DIR/docroot/core/.env
      DRUPAL_TEST_BASE_URL='${DRUPAL_TEST_BASE_URL}'
      DRUPAL_TEST_CHROMEDRIVER_AUTOSTART=${DRUPAL_TEST_CHROMEDRIVER_AUTOSTART}
      DRUPAL_TEST_DB_URL='${DRUPAL_TEST_DB_URL}'
      DRUPAL_TEST_WEBDRIVER_HOSTNAME='${DRUPAL_TEST_WEBDRIVER_HOSTNAME}'
      DRUPAL_TEST_WEBDRIVER_CHROME_ARGS='${DRUPAL_TEST_WEBDRIVER_CHROME_ARGS}'
      DRUPAL_TEST_WEBDRIVER_PORT='${DRUPAL_TEST_WEBDRIVER_PORT}'
      EOF
    - exit_section configure_nightwatch
    - start_section verify_test_dependencies "Verify test dependencies"
    # Verify service is running before doing anything
    - curl http://$DRUPAL_TEST_WEBDRIVER_HOSTNAME:$DRUPAL_TEST_WEBDRIVER_PORT/status | jq '.'
    # Confirm Drupal is setup
    - $CI_PROJECT_DIR/vendor/bin/drush status
    # Confirm Nightwatch environment variables.
    - cat $CI_PROJECT_DIR/docroot/core/.env
    - exit_section verify_test_dependencies
    # Start Drupal's PHP server and surpress its logging output to keep the test output clean.
    - cd $CI_PROJECT_DIR/docroot && php -S 127.0.0.1:8080 .ht.router.php >/dev/null 2>&1 &
    # Run nightwatch tests
    - yarn --cwd $CI_PROJECT_DIR/docroot/core install
    - yarn --cwd $CI_PROJECT_DIR/docroot/core test:nightwatch --verbose --tag general
```
or the last line can be changed to path of a specific test case.

NOTE: 

1. Create a global.js file as given and update the Drupal credentials given in it so that the credentials can be used in other test cases.
2. In nightwatch.conf.js file, add global.js location in variable globals_path.
3. If you are creating custom commands, in your Nightwatch configuration file (nightwatch.conf.js), ensure the custom_commands_path attribute is set to point to the directory where you will be storing your custom command files.
4. In case your test case fails, please make sure that the selectors are added correctly in the test case as per your project.

### Resources
- [NightwatchJS](https://www.npmjs.com/package/nightwatch)
- [Install NightwatchJS](https://nightwatchjs.org/guide/quickstarts/create-and-run-a-nightwatch-test.html)
- [Execute Drupal core tests](https://git.drupalcode.org/project/drupal/-/blob/10.1.x/core/tests/README.md)
- [Nightwatch in Drupal Core](https://www.lullabot.com/articles/nightwatch-in-drupal-core)
