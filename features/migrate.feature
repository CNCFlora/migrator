Feature: Migrate data between dbs

  Scenario: Copy taxon from one db to another
        Given I am on "/"
        Then I wait 1000
        When I select "cncflora_test" from "src"
        And I select "cncflora2_test" from "dst"
        And I fill in "spp" with "Foo bar"
        And I press "Migrar"
  Scenario: Move taxon from one db to another
        Given I am on "/"
  Scenario: Copy taxon, occurrences, profiles and assessments from one db to another
        Given I am on "/"
  Scenario: Move taxon, occurrences, profiles and assessments from one db to another
        Given I am on "/"

