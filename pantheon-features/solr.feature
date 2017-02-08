Feature: Solr
  In order to build a better Search interface
  As a website user
  I need to connect my site to Solr

  @api
  Scenario: Download and enable modules
    Given I have run the drush command "pm-download search_api_solr -y"
    Given I have run the drush command "pm-download search_api -y"
    And I have run the drush command "pm-enable search_api_solr -y"
    And I have run the drush command "pm-enable pantheon_apachesolr  -y"
    And  I am logged in as a user with the "administrator" role
    Then I should not see "A site slogan set through Drush"
    When I am on "admin/config/search/pantheon"
    Then I should see "You have not posted the schema.xml"

  @api
  Scenario: Post Schema with Drush
    Given I have run the drush command "pantheon-apachesolr-schema-post sites/all/modules/search_api_solr/solr-conf/3.x/schema.xml"
    And  I am logged in as a user with the "administrator" role
    When I am on "admin/config/search/pantheon"
    Then I should see "environment successfully responded to a/an search_api_solr Solr ping request"
