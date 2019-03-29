<?php

namespace mattgrayisok\backblazeb2;

use craft\base\Element;
use craft\elements\Asset;
use craft\events\ModelEvent;
use craft\events\RegisterComponentTypesEvent;
use craft\services\Volumes;
use yii\base\Event;


/**
 * Plugin represents the Backblaze B2 volume plugin.
 *
 * @author Matt Gray <matt@mattgrayisok.com>
 * @since 1.0
 */
class Plugin extends \craft\base\Plugin
{
    // Properties
    // =========================================================================

    /**
     * @inheritdoc
     */
    public $schemaVersion = '1.0';


    // Public Methods
    // =========================================================================

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        Event::on(Volumes::class, Volumes::EVENT_REGISTER_VOLUME_TYPES, function(RegisterComponentTypesEvent $event) {
            $event->types[] = Volume::class;
        });
    }
}
