# Laravel Auto Hard Deleter

![PHP Composer](https://github.com/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/workflows/PHP%20Composer/badge.svg)
[![Build Status](https://travis-ci.org/SiavashBamshadnia/Laravel-Auto-Hard-Deleter.svg?branch=master)](https://travis-ci.org/SiavashBamshadnia/Laravel-Auto-Hard-Deleter)
[![Build Status](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/badges/build.png?b=master)](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/build-status/master)
[![StyleCI](https://github.styleci.io/repos/241140247/shield?branch=master)](https://github.styleci.io/repos/241140247)
[![Latest Stable Version](https://poser.pugx.org/sbamtr/Laravel-Auto-Hard-Deleter/version)](https://packagist.org/packages/sbamtr/laravel-auto-hard-deleter)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/SiavashBamshadnia/Laravel-Auto-Hard-Deleter/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)
[![License](https://poser.pugx.org/sbamtr/Laravel-Auto-Hard-Deleter/license)](https://github.com/SiavashBamshadnia/Laravel-Auto-Hard-Deleter)

![](cover.jpg)

This package deletes soft deleted rows automatically after a time interval that you define. 

*For Laravel and Lumen 5.5+, 6*

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
in your models that used `SoftDeletes` trait, you can enable Auto Hard Delete with this code:
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
The final code is:
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

Writen with <span style="color: #e25555;">&hearts;</span> by Siavash Bamshadnia.

Please support me by staring this repository.