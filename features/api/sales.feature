Feature: Competition Registration Api
  In order to register for a competition
  As a customer
  I need to be able to submit registration information

#  Scenario: Checking the application's kernel environment
#    Then the application's kernel should use "test" environment

  Scenario: Request the initial /api/sales page
    When I request "/api/sales" using HTTP "GET"
    Then the response code is "200"


  Scenario: Request /api/sales/home page
    When I request "/api/sales/home" using HTTP "GET"
    Then the response code is "404 Not Found"