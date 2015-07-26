# yii2-footnotes
Yii2 Extension that simplies adding and management of footnotes on a web page; it
generates accessible, automatically numbered footnotes.

Inspired by the [Accessible Footnotes with CSS](http://www.sitepoint.com/accessible-footnotes-css/) article.

For license information check the [LICENSE](LICENSE.md)-file.

## Installation

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist beastbytes/yii2-footnotes
```

or add

```json
"beastbytes/yii2-footnotes": "*"
```

to the require section of your composer.json.


## Usage

In a view:

```php
$footnotes = Footnotes::begin(); // Before any footnote references on the page
echo $footnotes->add('Local text', 'Footnote text'); // at every footnote reference
Footnotes::end(); // where the footnotes are to be rendered
```

### Example

```php
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