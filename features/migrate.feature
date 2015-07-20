Feature: Migrate data between databases

  Scenario: Listings
        Given I am on "/dbs.php"
        Then I should see "cncflora_test0"
        Then I should see "cncflora_test1"
        Given I am on "/families.php?db=cncflora_test0"
        Then I should see "FOACEAE"
        Given I am on "/species.php?db=cncflora_test0&family=FOACEAE"
        Then I should see "Foo bar"
        Then I should not see "Foo baz"

  Scenario: Selection order  
        Given I am on "/"
        And I wait 1000
        Then I should see "Origem"
        Then I should not see "Família"
        And I should not see "Migrar"
        When I select "CNCFLORA TEST0" from "src"
        And I wait 500
        Then I should see "Família"
        And I should not see "Espécie"
        And I should not see "Migrar"
        When I select "FOACEAE" from "family"
        And I wait 500
        Then I should see "Espécie"
        And I should not see "Migrar"
        And I should see "Foo bar" in the "#spp" element
        And I should not see "Foo baz" in the "#spp" element
        When I select "Foo bar" from "spp"
        Then I should see "Destino"
        And I should not see "Migrar"
        And I should not see "CNCFLORA TEST0" in the "#dst" element
        And I should see "CNCFLORA TEST1" in the "#dst" element
        When I select "CNCFLORA TEST1" from "dst"
        Then I should see "Migrar"

