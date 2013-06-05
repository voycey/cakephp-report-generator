cakephp_report_generator
========================

Outputs CSV files from Custom Queries

I created this because one of my clients has asked for a reporting mechanism but they don't know what reports they want yet (This happens pretty often), the plan is to basically allow queries to be created in raw SQL depending on the clients needs and have this provide a basic framework for delivering these reports in a CSV format. The problem was just working out a way to take multiple multidimensional arrays and generate a CSV file - Im sure there is some magic formula but this will do for now.

I havent tested this using CakePHP Find methods yet, I dont expect it to work out of the bag but it does work using raw custom queries (Your mileage may vary as I have only tested it on a couple of queries).

Howto
=====

1) Create a function in Model/Report.php that contains your query and a call to $this->run_query($query):

```php
public function users_addresses() {
  $query = "Select
                addresses.billing_line_1,
                addresses.billing_line_2,
                addresses.billing_state,
                addresses.billing_postcode,
                countries.name as billing_country,
                users.email,
                user_details.job_title,
                user_details.company
              From
                users Inner Join
                user_details On user_details.user_id = users.id Inner Join
                addresses On addresses.user_id = users.id Inner Join
                countries On addresses.billing_country_id = countries.id";
  return $this->run_query($query);

}
```

2) Create a function in Controllers/ReportsController.php that handles the request using this query and the filename to save:

```php
public function users_addresses_report() {
  $this->autoRender = false;
  $result = $this->Report->users_addresses();
  $final_csv = $this->generateCSV($result);
  $this->export($final_csv, "address_report");
}
```
3) Call your method from the URL and hopefully you will get a nice shiny CSV (well actually a HSV as I have defaulted it to delimit with #'s)

Disclaimer:
===========

I have just hacked this together - I dont expect it to work on everyones systems but if it does and it saves you a couple of hours then that will do. The functions in the controller can probably be accomplished with inbuilt php functions - I just couldnt easily get them to do what I wanted.

The final function could probably be reused but for my stuff ill need to do some extra processing on it



