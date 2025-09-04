# 💟 Filament Chatify Integration

Seamlessly integrate real-time messaging into your **Filament** dashboard using [Chatify](https://github.com/munafio/chatify). This plugin provides a ready-to-use chat widget for Filament, allowing you to enhance user communication effortlessly.


## ⚙️ Requirements
- **PHP**: 8.2+
- **Laravel**: 10+
- **Filament**: 4.x

## 💅 Installation

Run the following command to install the package via Composer:

```bash
composer require monzer/filament-chatify-integration
```
Install command:

```bash
php artisan filament-chatify-integration:install
```

Run database migration:

```bash
php artisan migrate
```

Publish assets:

```bash
php artisan filament:assets
```
## Configuration

Please visit [Chatify configuration section](https://chatify.munafio.com/configurations) to complete the configuration.


## 🚀 Usage

To enable the plugin in your Filament panel, register it as follows:

```php
use Monzer\FilamentChatifyIntegration\ChatifyPlugin;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(ChatifyPlugin::make());
}
```

Once installed, the plugin automatically provides a floating chat widget for real-time communication.

### 💎 Custom Chat Page Example

To customize the chat page:

#### Step 1: Create a custom page and extend the base chat page

```php
namespace App\Filament\Pages;

use Monzer\FilamentChatifyIntegration\Pages\Chatify as BaseChat;

class CustomChatifyPage extends BaseChat
{
    protected static ?string $slug = "chat";
    protected static ?string $navigationLabel = "Chat";
    protected static ?string $title = "Chat";
}
```

#### Step 2: Register the custom page in the plugin

```php
use Monzer\FilamentChatifyIntegration\ChatifyPlugin;
use App\Filament\Pages\CustomChatifyPage;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(ChatifyPlugin::make()->customPage(CustomChatifyPage::class)); // Also accepts a closure
}
```

### ❌ Disabling the Floating Chat Widget

To disable the floating chat widget, use the following configuration:

```php
use Monzer\FilamentChatifyIntegration\ChatifyPlugin;
use App\Filament\Pages\CustomChatifyPage;

public function panel(Panel $panel): Panel
{
    return $panel
        ->plugin(ChatifyPlugin::make()->disableFloatingChatWidget()); // Also accepts a closure
}
```

### 🛠️ Further Customization

For more advanced customization options, please visit the official [Chatify repository](https://github.com/munafio/chatify).

## 🛠 Support & Issues
If you encounter any issues or need support, feel free to open an issue on [GitHub](https://github.com/monzer/filament-chatify-integration/issues).

## 📝 License
This package is open-source and licensed under the **MIT License**.

### 👤 Attribution
This package integrates [Chatify](https://github.com/munafio/chatify), originally developed by [**Munafio**](https://github.com/munafio). Full credit goes to the original author for building Chatify, which serves as the foundation for this integration.

