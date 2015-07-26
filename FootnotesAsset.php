<?php
/**
 * FootnotesAsset Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   Footnotes
 */

namespace beastbytes\footnotes;

/**
 * FootnotesAsset Class.
 * Asset bundle for Footnotes widget CSS.
 */
class FootnotesAsset extends \yii\web\AssetBundle
{
    public $sourcePath = '@beastbytes/footnotes/assets';
    public $css = ['footnotes.css'];
}
