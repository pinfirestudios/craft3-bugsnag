<?php
/**
 * craft3-bugsnag plugin for Craft CMS 3.x
 *
 * Enables errors to be sent to Bugsnag (www.bugsnag.com)
 *
 * @link      https://www.pinfirestudios.com
 * @copyright Copyright (c) 2017 Pinfire Studios <www.pinfirestudios.com>
 */

namespace pinfirestudios\craft3bugsnag\models;

use pinfirestudios\craft3bugsnag\Craft3bugsnag;

use Craft;
use craft\base\Model;

/**
 * @author    Pinfire Studios <www.pinfirestudios.com>
 * @package   Craft3bugsnag
 * @since     1.0.0
 */
class Settings extends Model
{
    // Public Properties
    // =========================================================================

	/**
	 * Integration API Key for Bugsnag
     * @var string
     */
	public $apiKey;

	/**
	 * Release stage to use for Bugsnag.  If empty, CRAFT_ENVIRONMENT
	 * will be used.
	 *
	 * @var string
	 */
	public $releaseStage;

	/**
	 * Newline-separated list of release stages to send data to Bugsnag
	 * @var string
	 */
	public $notifyReleaseStages = 'production';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function rules()
    {
		return [
			[['apiKey', 'releaseStage', 'notifyReleaseStages'], 'string'],
			[['apiKey', 'notifyReleaseStages'], 'required'],
        ];
    }
}
