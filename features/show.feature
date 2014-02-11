Feature: show
    In order to check that I have configuration
    From the command line
    I need a command to show my configuration

Scenario: Show previously generated config
    Given I have some generated config at "features/data"
    When I run the Show command
    Then I should see that config

Scenario: Show previously generated config for an environment
    Given I have some generated config at "features/data"
    And I set the environment as "development"
    When I run the Show command
    Then I should see that config
