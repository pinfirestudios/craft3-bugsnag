<?php
/**
 * craft3-bugsnag plugin for Craft CMS 3.x
 *
 * Enables errors to be sent to Bugsnag (www.bugsnag.com)
 *
 * @link      https://www.pinfirestudios.com
 * @copyright Copyright (c) 2017 Pinfire Studios <www.pinfirestudios.com>
 */

namespace pinfirestudios\craft3bugsnag;

use pinfirestudios\craft3bugsnag\models\Settings;

use Craft;
use craft\base\Plugin;

use yii\base\BootstrapInterface;

class Bootstrap implements BootstrapInterface
{
    /**
     * Installs our components during the bootstrap process to get us loaded 
     * sooner in case something crashes.
     *
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        $app->getPlugins()->getPlugin('craft3-bugsnag');
    }
}
