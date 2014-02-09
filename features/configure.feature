Feature: configure
    In order to generate configuration
    From the command line
    I need a command to read configuration options
    From a JSON file

Scenario: Find a configure file
    Given I have a config file at "features/data"

Scenario: Generate global configuration
    Given I have a config file at "features/data"
    When I run the Configure command
    Then I should see "...configured!"

Scenario: Generate configuration for an environment
    Given I have a config file at "features/data"
    And I set the environment as "development"
    When I run the Configure command
    Then I should see "...configured!"
