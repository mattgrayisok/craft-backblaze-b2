<?php
/**
 * @link https://mattgrayisok.com/
 * @copyright Copyright (c) Bit Breakfast Ltd.
 * @license MIT
 */

namespace mattgrayisok\backblazeb2;

use craft\web\AssetBundle;
use craft\web\assets\cp\CpAsset;

/**
 * Asset bundle for the Dashboard
 */
class BackblazeB2Bundle extends AssetBundle
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        $this->sourcePath = '@mattgrayisok/backblazeb2/resources';

        $this->depends = [
            CpAsset::class,
        ];

        $this->js = [
            'js/editVolume.js',
        ];

        parent::init();
    }
}
