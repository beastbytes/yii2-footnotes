<?php
/**
 * Footnotes Widget Class file
 *
 * @author    Chris Yates
 * @copyright Copyright &copy; 2015 BeastBytes - All Rights Reserved
 * @license   BSD 3-Clause
 * @package   EmailObfuscator
 */

namespace beastbytes\footnotes;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Footnotes Widget Class.
 * Generates accessible, automatically numbered footnotes.
 * Inspired by {@link http://www.sitepoint.com/accessible-footnotes-css/ Accessible Footnotes with CSS}
 *
 * Usage:
 * $footnotes = Footnotes::begin(); // Before any footnote references on the page
 * echo $footnotes->add('Local text', 'Footnote text'); // at every footnote reference
 * Footnotes::end(); // where the footnotes are to be rendered
 *
 * Example:
 * ```php
 $footnotes = Footnotes::begin();
// Can be other view stuff here
echo Html::tag('p', strtr('This example shows how to use the {Footnotes widget}.', [
    '{Footnotes widget}' => $footnotes->add('Footnotes widget', 'The Footnotes widget makes it easy to add accessible footnotes.'),
]));
// More view stuff
echo Html::tag('p', strtr('The Footnotes widget uses the {Yii2 framework}.', [
    '{Yii2 framework}' => $footnotes->add('Yii 2 framework', 'The Yii2 framework is the best framework for developing web applications.'),
]));
// More view stuff, maybe with some more footnote references
Footnotes::end(); // renders the footnotes
```
 */
class Footnotes extends Widget
{
    /**
     * @var array options for the footnotes container element
     * In addition to HTML attributes the following options are recognised:
     * - encodeFootnote - boolean - whether to encode footnotes; defaults to TRUE.
     * Can be overridden for an individual footnote in $options parameter of the
     * add()  method
     * - encodeReference - boolean - whether to encode footnote references;
     * defaults to TRUE.
     * Can be overridden for an individual footnote in $options parameter of the
     * add() method
     * - tag - string - the tag for the footnotes section; defaults to section
     */
    public $options = ['class' => 'footnotes'];
    /**
     * @var string Prefix for foonotes
     */
    public $prefix = 'footnote';
    /**
     * @var array HTML option for footnote references. These can be overridden
     * for individual references by setting $options in the add() method
     */
    public $referenceOptions = [];
    /**
     * @var boolean whether to register widget assets.
     * Set FALSE if you are going to provide your own CSS.
     */
    public $registerAssets = true;
    /**
     * @var string ARIA label for the footnote return link.
     */
    public $returnAriaLabel = 'Return to content';
    /**
     * @var string text for the footnote return link.
     */
    public $returnText = '&larrhk;';
    /**
     * @var string Title for the foonotes section
     */
    public $title = 'Footnotes';

    /**
     * @var array the footnotes
     */
    private $_footnotes = [];
    /**
     * @var integer counter for auto-generating footnote reference ids
     */
    private $_counter = 0;

    /**
     * Initializes the widget
     */
    public function init()
    {
        if (isset($this->options['id'])) {
            $this->id = $this->options['id'];
        } else {
            $this->options['id'] = $this->id;
        };

        if ($this->registerAssets) {
            FootnotesAsset::register($this->getView());
        }

        parent::init();
    }

    /**
     * Adds a footnote reference and saves the footnote for rendering later
     *
     * @param string text for the footnote reference
     * @param array HTML aoptions for the footnote reference
     * @return string HTML for the footnote reference
     */
    public function add($reference, $footnote, $options = [])
    {
        if (isset($options['id'])) {
            $id = $options['id'];
        } else {
            $id = $this->id.'-'.$this->_counter++;
            $options['id'] = $id;
        }

        $this->_footnotes[$id] = (ArrayHelper::remove($options, 'encodeFootnote', true)
            ? Html::encode($footnote)
            : $footnote
        );

        $options = ArrayHelper::merge(
            $this->referenceOptions,
            $options,
            [
                'aria-describedby' => $this->prefix.'-label'
            ]
        );

        return Html::a(
            (ArrayHelper::remove($options, 'encodeReference', true)
                ? Html::encode($reference)
                : $reference
            ),
            '#'.$this->prefix.'-'.$id,
            $options
        );
    }

    /**
     * Renders the footnotes.
     */
    public function run()
    {
        $tag = ArrayHelper::remove($options, 'tag', 'section');

        echo Html::beginTag($tag, $this->options);
        echo Html::tag('h1', $this->title, ['id' => $this->prefix.'-label']);
        echo Html::ol($this->_footnotes, [
            'item' => function ($item, $index)
            {
                return Html::tag(
                    'li', $item.Html::a(
                        $this->returnText,
                        '#'.$index,
                        [
                            'aria-label' => $this->returnAriaLabel,
                            'class'      => 'back-to-content'
                        ]
                    ),
                    ['id' => $this->prefix.'-'.$index]
                );
            }
        ]);
        echo Html::endTag($tag);
    }
}
