# Settings Module Documentation

## Overview

The Settings module provides a flexible way to manage application settings through the admin panel. It includes a database table for storing key-value pairs organized into groups.

## Features

1. **Database Storage**: Settings are stored in the `settings` table
2. **Admin Management**: Manage settings through the Filament admin panel
3. **Grouping**: Settings can be organized into groups (General, Payment, Email, Social)
4. **Easy Access**: Helper methods to retrieve settings in the application
5. **Extensible**: Easy to add new settings and groups

## Installation

The settings module is automatically installed when you run migrations:

```bash
php artisan migrate
```

This will create the `settings` table and seed it with initial payment gateway settings.

## Usage

### Accessing Settings in Code

You can access settings in your application using the `Setting` model:

```php
use App\Models\Setting;

// Get a setting value
$value = Setting::get('payment_gateway_key_id');

// Get a setting with a default value
$value = Setting::get('payment_gateway_key_id', 'default_value');

// Set a setting value
Setting::set('payment_gateway_key_id', 'new_value');
```

### Payment Gateway Helper

For payment gateway settings, there's a dedicated helper class:

```php
use App\Helpers\PaymentGateway;

// Get payment gateway key ID
$keyId = PaymentGateway::getKeyId();

// Get payment gateway key secret
$keySecret = PaymentGateway::getKeySecret();

// Get all payment gateway settings
$settings = PaymentGateway::getSettings();
```

### Environment Variables

For security, you can also set payment gateway settings using environment variables:

```env
PAYMENT_GATEWAY_KEY_ID=your_key_id
PAYMENT_GATEWAY_KEY_SECRET=your_key_secret
```

These will be used as fallback values if the database settings are not set.

## Admin Panel

In the admin panel, you can:

1. **View Settings**: See all settings organized by group
2. **Create Settings**: Add new settings with key, value, and group
3. **Edit Settings**: Modify existing settings
4. **Delete Settings**: Remove settings (use with caution)

Settings are accessible through the "Administration" section in the navigation menu.

## Adding New Setting Groups

To add new setting groups, modify the `config/settings.php` file:

```php
'groups' => [
    'general' => 'General',
    'payment' => 'Payment',
    'email' => 'Email',
    'social' => 'Social',
    'new_group' => 'New Group', // Add new groups here
],
```

## Seeding Initial Settings

To add initial settings for your application, create a new seeder:

```bash
php artisan make:seeder YourSettingsSeeder
```

And add it to the `DatabaseSeeder`:

```php
public function run(): void
{
    $this->call([
        PaymentGatewaySettingsSeeder::class,
        YourSettingsSeeder::class, // Add your seeder here
    ]);
}
```