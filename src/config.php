<?php
/**
 * craft3-bugsnag plugin for Craft CMS 3.x
 *
 * Enables errors to be sent to Bugsnag (www.bugsnag.com)
 *
 * @link      https://www.pinfirestudios.com
 * @copyright Copyright (c) 2017 Pinfire Studios <www.pinfirestudios.com>
 */

/**
 * craft3-bugsnag config.php
 *
 * This file exists only as a template for the craft3-bugsnag settings.
 * It does nothing on its own.
 *
 * Don't edit this file, instead copy it to 'craft/config' as 'craft3-bugsnag.php'
 * and make your changes there to override default settings.
 *
 * Once copied to 'craft/config', this file will be multi-environment aware as
 * well, so you can have different settings groups for each environment, just as
 * you do for 'general.php'
 */

return [
	'apiKey' => getenv('BUGSNAG_API_KEY'),

	// Defaults to CRAFT_ENVIRONMENT if null
	'releaseStage' => getenv('CRAFT_ENVIRONMENT'),
	'notifyReleaseStages' => "staging\nproduction",
];
