<?php
putenv("PHP_ENV=test");

include_once __DIR__.'/../../vendor/autoload.php';
include_once __DIR__.'/../../html/config.php';
include_once __DIR__.'/../../html/http.php';

use Behat\Behat\Context\ClosuredContextInterface,
    Behat\Behat\Context\TranslatedContextInterface,
    Behat\Behat\Context\BehatContext,
    Behat\Behat\Exception\PendingException;

use Behat\Gherkin\Node\PyStringNode,
    Behat\Gherkin\Node\TableNode;

use Behat\MinkExtension\Context\MinkContext;

use cncflora\Utils;

class FeatureContext extends MinkContext {

    /** @BeforeFeature */
    public static function prepareForTheFeature(){
      // TODO: delete all first
      $file =file_get_contents(__DIR__."/load.json");
      $json =json_decode($file);
      http_post(COUCHDB."/cncflora_test/_bulk_docs",array('docs'=>$json));
      sleep(1);
    }

    /**
     * @When /^I click on "([^"]*)"$/
     */
    public function iClickOn($selector) {
        $this->getMainContext()->getSession()->getPage()->find('css',$selector)->click();
    }


    /**
     * @Then /^I wait (\d+)$/
     */
    public function iWait($t) {
        $this->getMainContext()->getSession()->wait((int)$t);
    }

    /**
     * @Then /^I fill field "([^"]+)" with "([^"]+)"$/
     */
    public function iFillField($sel,$text) {
        $this->getMainContext()->getSession()->executeScript('$("'.$sel.'").val("'.$text.'")');
    }
}

