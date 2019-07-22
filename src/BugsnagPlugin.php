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

        Craft::$app->set('bugsnag', [
            'class' => 'pinfirestudios\yii2bugsnag\BugsnagComponent',
            'bugsnag_api_key' => $settings->apiKey, 
            'notifyReleaseStages' => explode("\n", str_replace("\r", "", $settings->notifyReleaseStages)),
			'releaseStage' => $settings->releaseStage ?? CRAFT_ENVIRONMENT
        ]);

		if (php_sapi_name() == 'cli') {
			// We'll only be linked in the console configuration if we add the bootstrap
			// setting to config/app.php
            $errorHandlerConfig = [
                'class' => '\pinfirestudios\yii2bugsnag\BugsnagConsoleErrorHandler'
			];

			$properties = [
				'discardExistingOutput',
				'memoryReserveSize'
			];
        } else {
            $errorHandlerConfig = [
                'class' => '\pinfirestudios\craft3bugsnag\ErrorHandler',
			];

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
		}

		// We are replacing Craft's (web) or Yii's (console) error handler, 
		// so transfer all the properties to our class so we keep settings
		// pure.
		//
		// To speed up initialization, we can set the errorHandler component
		// in config/app.db, but this will implement it otherwise.
		$oldErrorHandler = Craft::$app->getErrorHandler();
		if (
			$oldErrorHandler &&
			get_class($oldErrorHandler) != $errorHandlerConfig['class']
		) {
			$oldErrorHandler->unregister();

			foreach ($properties as $property)
			{
				$errorHandlerConfig[$property] = $oldErrorHandler->{$property};
			}
			
			$errorHandler = Craft::createObject($errorHandlerConfig);

			Craft::$app->set('errorHandler', $errorHandler);
			$errorHandler->register();
		}

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
