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
      @http_delete(ELASTICSEARCH.'/cncflora_test0',[]);
      @http_delete(ELASTICSEARCH.'/cncflora_test1',[]);
      @http_delete(COUCHDB.'/cncflora_test0',[]);
      @http_delete(COUCHDB.'/cncflora_test1',[]);

      @http_put(ELASTICSEARCH.'/cncflora_test0',[]);
      @http_put(ELASTICSEARCH.'/cncflora_test1',[]);
      @http_put(COUCHDB.'/cncflora_test0',[]);
      @http_put(COUCHDB.'/cncflora_test1',[]);

      $file =file_get_contents(__DIR__."/load.json");
      $json =json_decode($file);
      $r = http_post(COUCHDB."/cncflora_test0/_bulk_docs",array('docs'=>$json));
      foreach($json as $doc) {
        $doc->id = $doc->_id;
        foreach($r as $revs) {
          if($revs->id == $doc->_id) {
            $doc->rev = $revs->rev;
            $doc->_rev = $revs->rev;
          }
        }
        http_put(ELASTICSEARCH.'/cncflora_test0/'.$doc->metadata->type.'/'.$doc->_id,$doc);
      }
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
    /**
     * @Then /^I save the page "([^"]*)"$/
     */
    public function iSaveThePage($name) {
        file_put_contents($name,$this->getMainContext()->getSession()->getPage()->getHtml());
    }

}

