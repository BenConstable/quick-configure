Feature: dump
    In order to apply the configuration to an existing application
    I need to be able to dump it to a compatible format
    From the command line

Scenario: Dump config to a file
    Given I have some generated config at "features/data"
    And I specify the path "features/data/tmp"
    And I specify the filename "dump_test"
    When I run the dump command
    Then I should get a file containing that config

Scenario: Dump config to a certain format
    Given I have some generated config at "features/data"
    And I specify the path "features/data/tmp"
    And I specify the filename "dump_test"
    And I specify the format "json"
    When I run the dump command
    Then I should get a file containing that config
    And it should be in JSON format

Scenario: Dump config to STDOUT
    Given I have some generated config at "features/data"
    And I specify the path "features/data/tmp"
    And I specify the filename "dump_test"
    And I specify STDOUT
    When I run the dump command
    Then I should see that config on my CLI
