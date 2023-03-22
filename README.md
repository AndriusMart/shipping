laravel/php

### Installing

* Download this project

### Executing program

* How to run the program
* Step-by-step bullets

*!!! Rename .env.example file to .env
```
npm install
```
```
composer install
```
```
npm run dev
```
### Running unit test
```
php artisan test
```
### Main code paths
* app\Services\ShipmentDiscountCalculator.php   (logic)
* app\Http\Controllers\ShipmentController.php   (Calling functions)
* config\shipment.php   (shipments info (size, provider, price))
* storage\app\input.txt     (input data)
* storage\app\results.txt   (results)
* tests\Unit\ShippingTest.php   (unit test)
* resources\views\home.blade.php    (view with results)
### More info
Results can be seen on web or in results.txt file.
## Authors

[Andrius Martinkėnas]((https://github.com/AndriusMart))