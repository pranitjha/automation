# PHPUnit Test Integration in a Drupal Project
This module contains multiple generic scenario based PHPUnit test cases.
Please check all test cases listed under `tests/src` directory.

## Prerequisites
Make sure the following packages are installed in your Drupal application:
1. `composer require phpunit/phpunit --dev`
2. `composer require drupal/core-dev --dev`

## Executing PHPUnit Test cases
To run these test cases, you need to follow:
1. Inside `docroot/core` directory, you will find a `phpunit.xml.dist` file
2. Copy that file and create a `phpunit.xml` file from it.
3. We can keep this file inside `docroot/core` directory for local testing or inside our project root as well - in that case it will come as a git change and we can commit it to repo if needed.
4. Open the `phpunit.xml` file and update following details:
    - Add application's domain in `value` inside `<env name="SIMPLETEST_BASE_URL" value=""/>`
    - Add DB url inside `<env name="SIMPLETEST_DB" value=""/>`. The pattern is also mentioned in the XML file as `mysql://username:password@localhost/databasename#table_prefix`
    - If you want to view your testing status as a browser output, add the report output directory in `<env name="BROWSERTEST_OUTPUT_DIRECTORY" value=""/>`
5. Now simply run the test cases using the command `vendor/bin/phpunit -c [path_to_your_phpunit_xml_file] [path_to_test_case_files]`

    - You can run all test cases. Example: `vendor/bin/phpunit -c docroot/core docroot/modules/custom/phptest/tests`
    - You can run a single test case by specifying the exact file path. Example: `vendor/bin/phpunit -c docroot/core docroot/modules/custom/phptest/tests/src/Functional/AccessDeniedTest.php`
  
## Adding PHPUnit in pipeline
If you want to run the PHPUnit related tests in the code studio pipeline, Add the below code in you .gitlab-ci.yml file.

```
"PHPUnit":
  stage: "Test Drupal"
  services:
    - mysql:5.7
  extends: .cache_strategy_pull
  needs: ["Build Code" , "Manage Secrets"]
  rules:
  - if: ($CUSTOM_RUN_TESTS == "true")
    when: on_success
    allow_failure: false
  before_script:
    - !reference [.clone_standard_template, script]
    - . "$STANDARD_TEMPLATE_PATH"/ci-files/scripts/stages/build/job_build_code.sh
    - . "$STANDARD_TEMPLATE_PATH"/ci-files/scripts/utility/install_drupal.sh
  script:
    # Start Drupal's PHP server and surpress its logging output to keep the test output clean.
    - cd $CI_PROJECT_DIR/docroot && php -S 127.0.0.1:8888 .ht.router.php >/dev/null 2>&1 &
    # Confirm Drupal is setup
    - $CI_PROJECT_DIR/vendor/bin/drush status
    # Here you run your PHPUnit tests or any other command required
    - ./vendor/bin/phpunit -c /builds/pranit.jha/Pranit-Jha-Employee-Free/phpunit.xml ./path/of/the/tests
```
