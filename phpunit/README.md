This module contains multiple generic scenario based PHPUnit test cases.
Please check all test cases listed under `tests/src` directory.

Make sure the following packages are installed in your Drupal application:
1. `composer require phpunit/phpunit --dev`
2. `composer require drupal/core-dev --dev`

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