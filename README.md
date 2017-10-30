# Bugsnag plugin for Craft CMS 3.x

Enables errors to be sent to [Bugsnag](https://www.bugsnag.com)

## Requirements

This plugin requires Craft CMS 3.0.0-beta.23 or later.

## Installation

To install the plugin, follow these instructions.

1. Open your terminal and go to your Craft project:

        cd /path/to/project

2. Then tell Composer to load the plugin:

        composer require pinfirestudios/craft3-bugsnag

3. In the Control Panel, go to Settings → Plugins and click the “Install” button for craft3-bugsnag.

4. If you want to be able to capture early initialization errors, you need to add this plugin to your project's [bootstrap  configuration](http://www.yiiframework.com/doc-2.0/yii-base-application.html#$bootstrap-detail).  To do this, in config/app.php, add:
        
    'bootstrap' => [
        '\pinfirestudios\craft3bugsnag\Bootstrap'
    ]

This will load the Bugsnag component and logging portion early in the project initialization, giving you the greatest visibility into errors.  If you don't enable this, Bugsnag will be initialized when plugins are set up, later in the loading process.

## Configuring craft3-bugsnag

You will need to provide your API key and optionally the release stage via the settings page or via config/env files.  The recommended way however is to copy src/config.php to /APP_ROOT/config/craft3-bugsnag.php, then either edit it directly, or add BUGSNAG_API_KEY to your .env file.  For the release stage, if not set diretly, it will default to the CRAFT_ENVIRONMENT variable.

## Using craft3-bugsnag

If things crash and this is enabled, visit your Bugsnag dashboard to see why.
