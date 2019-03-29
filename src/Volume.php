<?php
/**
 * @link https://mattgrayisok.com/
 * @copyright Copyright (c) Bit Breakfast Ltd.
 * @license MIT
 */

namespace mattgrayisok\backblazeb2;

//use Aws\CloudFront\CloudFrontClient;
//use Aws\CloudFront\Exception\CloudFrontException;
//use Aws\Credentials\Credentials;
//use Aws\Handler\GuzzleV6\GuzzleHandler;
//use Aws\Rekognition\RekognitionClient;
//use Aws\S3\Exception\S3Exception;
//use Aws\S3\S3Client;
//use Aws\Sts\StsClient;
use Craft;
use craft\base\FlysystemVolume;
use craft\behaviors\EnvAttributeParserBehavior;
use craft\helpers\ArrayHelper;
use craft\helpers\Assets;
use craft\helpers\DateTimeHelper;
use craft\helpers\StringHelper;
use DateTime;
//use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\AdapterInterface;

use Mhetreramesh\Flysystem\BackblazeAdapter;
use BackblazeB2\Client;

/**
 * Class Volume
 *
 * @property mixed $settingsHtml
 * @property string $rootUrl
 * @author Matt Gray <matt@mattgrayisok.com>
 * @since 1.0
 */
class Volume extends FlysystemVolume
{
    // Constants
    // =========================================================================

    /**
     * Cache key to use for caching purposes
     */
    const CACHE_KEY_PREFIX = 'backblaze.';

    /**
     * Cache duration for access token
     */
    const CACHE_DURATION_SECONDS = 3600;

    // Static
    // =========================================================================

    /**
     * @inheritdoc
     */
    public static function displayName(): string
    {
        return 'Backblaze B2';
    }

    // Properties
    // =========================================================================

    /**
     * @var bool Whether this is a local source or not. Defaults to false.
     */
    protected $isVolumeLocal = false;

    /**
     * @var string Subfolder to use
     */
    public $subfolder = '';

    /**
     * @var string Backblaze Account ID
     */
    public $accountId = '';

    /**
     * @var string Backblaze application key
     */
    public $applicationKey = '';

    /**
     * @var string Bucket to use
     */
    public $bucket = '';

    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function __construct(array $config = [])
    {
        parent::__construct($config);
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['parser'] = [
            'class' => EnvAttributeParserBehavior::class,
            'attributes' => [
                'accountId',
                'applicationKey',
                'bucket',
                'subfolder'
            ],
        ];
        return $behaviors;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules[] = [['bucket', 'accountId', 'applicationKey'], 'required'];

        return $rules;
    }

    /**
     * @inheritdoc
     */
    public function getSettingsHtml()
    {
        return Craft::$app->getView()->renderTemplate('backblaze-b2/volumeSettings', [
            'volume' => $this,
            'periods' => array_merge(['' => ''], Assets::periodList()),
        ]);
    }

    /**
     * @inheritdoc
     */
    public function getRootUrl()
    {
        if (($rootUrl = parent::getRootUrl()) !== false) {
            $rootUrl .= $this->_subfolder();
        }
        return $rootUrl;
    }

    // Protected Methods
    // =========================================================================

    /**
     * @inheritdoc
     * @return BackblazeAdapter
     */
    protected function createAdapter()
    {
        $accountId = Craft::parseEnv($this->accountId);
        $applicationKey = Craft::parseEnv($this->applicationKey);

        $client = static::client($accountId, $applicationKey);

        return new BackblazeAdapter($client, Craft::parseEnv($this->bucket));
    }

    /**
     * Get the Backblaze B2 client.
     *
     * @param $config
     * @return Client
     */
    protected static function client($accountId, $applicationKey): Client
    {
        return new Client($accountId, $applicationKey);
    }

    /**
     * @inheritdoc
     */
    protected function addFileMetadataToConfig(array $config): array
    {
        return parent::addFileMetadataToConfig($config);
    }

    /**
     * @inheritdoc
     */
    protected function invalidateCdnPath(string $path): bool
    {
        //TODO: Clear cloudflare cdn if set

        return true;
    }

    // Private Methods
    // =========================================================================

    /**
     * Returns the parsed subfolder path
     *
     * @return string|null
     */
    private function _subfolder(): string
    {
        if ($this->subfolder && ($subfolder = rtrim(Craft::parseEnv($this->subfolder), '/')) !== '') {
            return $subfolder . '/';
        }
        return '';
    }

    /**
     * Returns the visibility setting for the Volume.
     *
     * @return string
     */
    protected function visibility(): string {
        return AdapterInterface::VISIBILITY_PUBLIC;
    }
}
