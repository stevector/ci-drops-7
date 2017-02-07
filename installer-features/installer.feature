Feature: Installer
  In order to know that we can install the site via the installer
  As a website user
  I need to be able to install a Drupal site

  Scenario: Install via Drush

    Given I have wiped the site
    # For this to work, an automation script needs to have made settings.php
    # Without it (it is not in drops-7 by default) the command fails.
    When I have run the drush command "si -y"
    And I visit "/"
    Then I should see "Welcome to Site-Install"
