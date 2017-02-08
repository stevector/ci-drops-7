<?php

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Behat\Hook\Scope\BeforeScenarioScope;
use Drupal\DrupalExtension\Context\MinkContext;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use Behat\Behat\Hook\Scope\AfterStepScope;

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Define application features from the specific context.
 */
class FeatureContext extends RawDrupalContext implements Context, SnippetAcceptingContext {
  /**
   * Initializes context.
   * Every scenario gets its own context object.
   *
   * @param array $parameters
   *   Context parameters (set them in behat.yml)
   */
  public function __construct(array $parameters = []) {
    // Initialize your context here
  }

  /** @var \Drupal\DrupalExtension\Context\MinkContext */
  private $minkContext;
  /** @BeforeScenario */
  public function gatherContexts(BeforeScenarioScope $scope)
  {
    $environment = $scope->getEnvironment();
    $this->minkContext = $environment->getContext('Drupal\DrupalExtension\Context\MinkContext');
  }

  /**
   * Fills in form field with specified id|name|label|value
   * Example: And I enter the value of the env var "TEST_PASSWORD" for "edit-account-pass-pass1"
   *
   * @Given I enter the value of the env var :arg1 for :arg2
   */
  public function fillFieldWithEnv($value, $field)
  {
    $this->minkContext->fillField($field, getenv($value));
  }


  /**
   * @Given I have wiped the site
   */
  public function iHaveWipedTheSite()
  {
    $site = getenv('TERMINUS_SITE');
    $env = getenv('TERMINUS_ENV');

    passthru("terminus env:wipe $site.$env --yes");
  }

  /**
   * @Given I have reinstalled
   */
  public function iHaveReinstalled()
  {
    $site = getenv('TERMINUS_SITE');
    $env = getenv('TERMINUS_ENV');
    $site_name = getenv('TEST_SITE_NAME');
    $site_mail = getenv('ADMIN_EMAIL');
    $admin_password = getenv('ADMIN_PASSWORD');

    passthru("terminus --yes drush $site.$env -- --yes site-install standard --site-name=\"$site_name\" --site-mail=\"$site_mail\" --account-name=admin --account-pass=\"$admin_password\"'");
  }

  /**
   * @Given I have run the drush command :arg1
   */
  public function iHaveRunTheDrushCommand($arg1)
  {
    $site = getenv('TERMINUS_SITE');
    $env = getenv('TERMINUS_ENV');

    $return = '';
    $output = array();
    exec("terminus drush $site.$env -- " . $arg1, $output, $return);
    // echo $return;
    // print_r($output);

  }

  /**
   * @AfterStep
   */
  public function afterStep(AfterStepScope $scope)
  {
    // Do nothing on steps that pass
    $result = $scope->getTestResult();
    if ($result->isPassed()) {
      return;
    }

    // Otherwise, dump the page contents.
    $session = $this->getSession();
    $page = $session->getPage();
    $html = $page->getContent();
    $html = static::trimHead($html);

    print "::::::::::::::::::::::::::::::::::::::::::::::::\n";
    print $html . "\n";
    print "::::::::::::::::::::::::::::::::::::::::::::::::\n";
  }

  /**
   * Remove everything in the '<head>' element except the
   * title, because it is long and uninteresting.
   */
  protected static function trimHead($html)
  {
    $html = preg_replace('#\<head\>.*\<title\>#sU', '<head><title>', $html);
    $html = preg_replace('#\</title\>.*\</head\>#sU', '</title></head>', $html);
    return $html;
  }
}