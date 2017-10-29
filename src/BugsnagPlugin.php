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

use yii\base\Event;

/**
 * Class BugsnagPlugin
 *
 * @author    Pinfire Studios <www.pinfirestudios.com>
 * @package   Craft3bugsnag
 * @since     1.0.0
 *
 */
class BugsnagPlugin extends Plugin
{
    /**
     * @var BugsnagPlugin 
     */
    public static $plugin;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        self::$plugin = $this;

        // Install our components
        Craft::$app->getLog()->targets[] = Craft::createObject([
            'class' => \pinfirestudios\yii2bugsnag\BugsnagLogTarget::class,
            'levels' => ['error', 'warning', 'info', 'trace'],
            'logVars' => [],
        ]);

        $settings = $this->getSettings();

        $releaseStage = $settings->releaseStage;
        if (empty($releaseStage)) {
            $releaseStage = CRAFT_ENVIRONMENT;
        }
        
        Craft::$app->set('bugsnag', [
            'class' => 'pinfirestudios\yii2bugsnag\BugsnagComponent',
            'bugsnag_api_key' => $settings->apiKey, 
            'notifyReleaseStages' => explode("\n", $settings->notifyReleaseStages),
            'releaseStage' => $releaseStage
        ]);

        $errorHandlerConfig = [
            'class' => 'pinfirestudios\craft3bugsnag\ErrorHandler',
        ];

        // Unregister the old error handler and copy its properties to our instance.
        $oldErrorHandler = Craft::$app->getErrorHandler();
        if ($oldErrorHandler) {
            $oldErrorHandler->unregister();

            $properties = [
                'callStackItemView',
                'discardExistingOutput',
                'displayVars',
                'errorAction',
                'errorView',
                'exceptionView',
                'maxSourceLines',
                'maxTraceSourceLines',
                'memoryReserveSize',
                'previousExceptionView',
            ];

            foreach ($properties as $property)
            {
                $errorHandlerConfig[$property] = $oldErrorHandler->{$property};
            }
        }
    
        Craft::$app->set('errorHandler', $errorHandlerConfig);
        $errorHandler = Craft::$app->getErrorHandler();
        $errorHandler->register();

        Craft::info(
            Craft::t(
                'craft3-bugsnag',
                '{name} plugin loaded',
                ['name' => $this->name]
            ),
            __METHOD__
        );
    }

    /**
     * @inheritdoc
     */
    protected function createSettingsModel()
    {
        return new Settings();
    }

    /**
     * @inheritdoc
     */
    protected function settingsHtml(): string
    {
        return Craft::$app->view->renderTemplate(
            'craft3-bugsnag/settings',
            [
                'settings' => $this->getSettings()
            ]
        );
    }
}
