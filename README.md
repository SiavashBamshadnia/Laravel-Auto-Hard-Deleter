# Laravel Auto Hard Deleter

[![Build Status](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/build-status/master)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![License: MIT](https://img.shields.io/badge/License-MIT-success.svg)](https://opensource.org/licenses/MIT)

This package, deletes soft deleted rows automatically after a time interval that you define. 

*For Laravel 5.5+, 6 and Lumen*

* [Installation](#installation)
* [Usage](#usage)
* [Auto Hard Delete Command](#auto-hard-delete-command)

## Installation
### Step 1
Require the package with composer using the following command:
```bash
composer require sbamtr/laravel-auto-hard-deleter
```
### Step 2
#### For Laravel
The service provider will automatically get registered. Or you may manually add the service provider in your `config/app.php` file:
```php
'providers' => [
    // ...
    \sbamtr\LaravelAutoHardDeleter\AutoHardDeleteServiceProvider::class,
];
```

#### For Lumen
Add this line of code under the `Register Service Providers` section of your `bootstrap/app.php`:
```php
$app->register(\sbamtr\LaravelAutoHardDeleter\AutoHardDeleteServiceProvider::class);
```

### Step 3
Now its the time for scheduling the command.
in you `app/Console/Kernel.php` file, paste this code in `schedule()` function:
```php
protected function schedule(Schedule $schedule)
{
    // ...
    $schedule->command(\sbamtr\LaravelAutoHardDeleter\HardDeleteExpiredCommand::class)->hourly();
    // ...
}
```
In the code above, the command scheduled to run hourly. you can change it. For more information, please read [this](https://laravel.com/docs/scheduling#scheduling-artisan-commands) page.

### Step 4 (Optional)
You can publish the config file with this following command:
```bash
php artisan vendor:publish --provider="sbamtr\LaravelAutoHardDeleter\AutoHardDeleteServiceProvider" --tag=config
```
**Note:** If you are using Lumen, you have to use [this package](https://github.com/laravelista/lumen-vendor-publish).

Also you can set the `AUTO_HARD_DELETE_AFTER` value in `.env` file. like the following code:

```.env
...
AUTO_HARD_DELETE_AFTER='1 day'
...
``` 

## Usage
in your models that used `SoftDeletes` trait, you can enable Auto Hard Delete with this line:
```php
class SampleModel extends Model
{
    use SoftDeletes;
    const AUTO_HARD_DELETE_ENABLED = true;
}
```
Just write `const AUTO_HARD_DELETE_ENABLED = true` in your models!
Also you can set expiration time for your deleted entities using the following line:
```php
const AUTO_HARD_DELETE_AFTER = '5 months';
```
In the code above, expiration time for your soft deleted entity model is 5 months.
More complete code is:
```php
class SampleModel extends Model
{
    use SoftDeletes;
    const AUTO_HARD_DELETE_ENABLED = true;
    const AUTO_HARD_DELETE_AFTER = '5 months';
}
```
You can set any other values for `AUTO_HARD_DELETE_AFTER` like `5`(means 5 days), `2 hours`, `45 days`, `2.5 months`, `1 year`, etc.

**Note:** If you don't set any value for `AUTO_HARD_DELETE_AFTER` in your model, the soft deleted models with `AUTO_HARD_DELETE_ENABLED = true` will be hard deleted after the time defined in config file named `auto-hard-deleter.php`.

## Auto Hard Delete Command
Also you can hard delete expired rows manually using this artisan command:
```bash
php artisan hard-delete-expired
```

