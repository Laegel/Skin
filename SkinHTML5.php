<?php

//namespace SkinTemplate;

class SkinHTML5 extends SkinLayerAbstract
{
    static $specialContainers = [
        'style', 'script'
    ];

    static function getTagStructure()
    {
        return [
            'selfClosing' => [
                'start' => '<TAG ATTR>'
            ],
            'doubleClosing' => [
                'start' => '<TAG ATTR>',
                'end' => '</TAG>'
            ]
        ];
    }

    static function getGlobalAttributes()
    {
        return [
            'accesskey', 'class',
            'dir', 'draggable', 'dropzone',
            'hidden', 'id', 'lang',
            'spellcheck', 'style', 'tabindex',
            'title', 'translate'
        ];
    }
    
    static function invalidAttribute($attribute)
    {
        return 0 === strpos($attribute, 'data-') ? $attribute : 'data-' . $attribute;
    }

    static function getElements()
    {
        return [
            'doctype' => [
                'selfClosing' => true,
                'required' => true,
                'output' => '<!DOCTYPE html>'
            ],
            'a' => [
                'selfClosing' => false,
                'attributes' => [
                    'download', 'href', 'hreflang', 'media', 'rel', 'target', 'type'
                ]
            ],
            'abbr' => [
                'selfClosing' => false
            ],
            'address' => [
                'selfClosing' => false
            ],
            'area' => [
                'selfClosing' => false,
                'attributes' => [
                    'alt', 'coords', 'download',
                    'href', 'hreflang', 'media',
                    'rel', 'shape', 'target',
                    'type'
                ]
            ],
            'article' => [
                'selfClosing' => false
            ],
            'aside' => [
                'selfClosing' => false
            ],
            'audio' => [
                'selfClosing' => false,
                'attributes' => [
                    'autoplay', 'controls', 'loop',
                    'muted', 'preload', 'src'
                ]
            ],
            'b' => [
                'selfClosing' => false
            ],
            'base' => [
                'selfClosing' => false
            ],
            'bdi' => [
                'selfClosing' => false
            ],
            'bdo' => [
                'selfClosing' => false
            ],
            'blockquote' => [
                'selfClosing' => false
            ],
            'body' => [
                'selfClosing' => false,
                'required' => true,
            ],
            'br' => [
                'selfClosing' => true,
                'output' => '<br>'
            ],
            'button' => [
                'selfClosing' => false,
                'attributes' => [
                    'autofocus', 'disabled', 'form',
                    'formaction', 'formenctype', 'formmethod',
                    'formnovalidate', 'formtarget', 'name',
                    'type', 'value'
                ]
            ],
            'canvas' => [
                'selfClosing' => false,
                'attributes' => [
                    'height', 'width'
                ]
            ],
            'caption' => [
                'selfClosing' => false
            ],
            'cite' => [
                'selfClosing' => false
            ],
            'code' => [
                'selfClosing' => false
            ],
            'col' => [
                'selfClosing' => false,
                'attributes' => [
                    'span'
                ]
            ],
            'colgroup' => [
                'selfClosing' => false,
                'attributes' => [
                ]
            ],
            'datalist' => [
                'selfClosing' => false
            ],
            'dd' => [
                'selfClosing' => false
            ],
            'del' => [
                'selfClosing' => false,
                'attributes' => [
                    'cite', 'datetime'
                ]
            ],
            'details' => [
                'selfClosing' => false,
                'attributes' => [
                    'open'
                ]
            ],
            'dfn' => [
                'selfClosing' => false
            ],
            'dialog' => [
                'selfClosing' => false,
                'attributes' => [
                    'open'
                ]
            ],
            'div' => [
                'selfClosing' => false,
                'attributes' => [
                    'contenteditable', 'contextmenu'
                ]
            ],
            'dl' => [
                'selfClosing' => false
            ],
            'dt' => [
                'selfClosing' => false
            ],
            'em' => [
                'selfClosing' => false
            ],
            'embed' => [
                'selfClosing' => false,
                'attributes' => [
                    'height', 'src', 'type', 'width'
                ]
            ],
            'fieldset' => [
                'selfClosing' => false,
                'attributes' => [
                    'disabled', 'form', 'name'
                ]
            ],
            'figcaption' => [
                'selfClosing' => false
            ],
            'figure' => [
                'selfClosing' => false
            ],
            'footer' => [
                'selfClosing' => false
            ],
            'form' => [
                'selfClosing' => false,
                'attributes' => [
                    'accept-charset', 'action', 'autocomplete',
                    'enctype', 'method', 'name',
                    'novalidate', 'target'
                ]
            ],
            'h1' => [
                'selfClosing' => false
            ],
            'h2' => [
                'selfClosing' => false
            ],
            'h3' => [
                'selfClosing' => false
            ],
            'h4' => [
                'selfClosing' => false
            ],
            'h5' => [
                'selfClosing' => false
            ],
            'h6' => [
                'selfClosing' => false
            ],
            'head' => [
                'selfClosing' => false,
                'required' => true,
            ],
            'header' => [
                'selfClosing' => false
            ],
            'hgroup' => [
                'selfClosing' => false
            ],
            'hr' => [
                'selfClosing' => true
            ],
            'html' => [
                'selfClosing' => false,
                'required' => true,
                'attributes' => [
                    'manifest', 'xmlns'
                ]
            ],
            'i' => [
                'selfClosing' => false
            ],
            'iframe' => [
                'selfClosing' => false,
                'attributes' => [
                    'height', 'name', 'sandbox',
                    'seamless', 'src', 'srcdoc',
                    'width'
                ]
            ],
            'img' => [
                'selfClosing' => true,
                'attributes' => [
                    'alt', 'crossorigin', 'height',
                    'ismap', 'src', 'usemap',
                    'width'
                ]
            ],
            'input' => [
                'selfClosing' => true,
                'attributes' => [
                    'accept', 'alt', 'autocomplete',
                    'autofocus', 'checked', 'disabled',
                    'form', 'formaction', 'formenctype',
                    'formmethod', 'formnovalidate', 'formtarget',
                    'height', 'list', 'max',
                    'maxlenght', 'min', 'multiple',
                    'name', 'pattern', 'placeholder',
                    'text', 'readonly', 'required',
                    'size', 'src', 'step',
                    'type', 'value', 'width'
                ]
            ],
            'ins' => [
                'selfClosing' => false
            ],
            'kbd' => [
                'selfClosing' => false
            ],
            'keygen' => [
                'selfClosing' => true,
                'attributes' => [
                    'autofocus', 'challenge', 'disabled',
                    'form', 'keytype', 'name'
                ]
            ],
            'label' => [
                'selfClosing' => false,
                'attributes' => [
                    'for', 'form'
                ]
            ],
            'legend' => [
                'selfClosing' => false
            ],
            'li' => [
                'selfClosing' => false,
                'attributes' => [
                    'value'
                ]
            ],
            'link' => [
                'selfClosing' => true,
                'attributes' => [
                    'crossorigin', 'href', 'hreflang',
                    'media', 'rel', 'sizes',
                    'type'
                ]
            ],
            'main' => [
                'selfClosing' => false
            ],
            'map' => [
                'selfClosing' => false
            ],
            'mark' => [
                'selfClosing' => false
            ],
            'menu' => [
                'selfClosing' => false
            ],
            'menuitem' => [
                'selfClosing' => false,
                'attributes' => [
                    'checked', 'command', 'default',
                    'disabled', 'icon', 'label',
                    'radiogroup', 'type'
                ]
            ],
            'meta' => [
                'selfClosing' => true,
                'attributes' => [
                    'charset', 'content', 'http-equiv',
                    'name'
                ]
            ],
            'meter' => [
                'selfClosing' => false,
                'attributes' => [
                    'form', 'high', 'low',
                    'max', 'min', 'optimum',
                    'value'
                ]
            ],
            'nav' => [
                'selfClosing' => false
            ],
            'noscript' => [
                'selfClosing' => false
            ],
            'object' => [
                'selfClosing' => false,
                'attributes' => [
                    'data', 'form', 'height',
                    'name', 'type', 'usemap',
                    'width'
                ]
            ],
            'ol' => [
                'selfClosing' => false,
                'attributes' => [
                    'reversed', 'start', 'type'
                ]
            ],
            'optgroup' => [
                'selfClosing' => false,
                'attributes' => [
                    'disabled', 'label'
                ]
            ],
            'option' => [
                'selfClosing' => false,
                'attributes' => [
                    'disabled', 'label', 'selected',
                    'value'
                ]
            ],
            'output' => [
                'selfClosing' => false,
                'attributes' => [
                    'for', 'form', 'name'
                ]
            ],
            'p' => [
                'selfClosing' => false,
                'attributes' => [
                    'contenteditable', 'contextmenu'
                ]
            ],
            'param' => [
                'selfClosing' => true,
                'attributes' => [
                    'name', 'value'
                ]
            ],
            'pre' => [
                'selfClosing' => false
            ],
            'progress' => [
                'selfClosing' => false,
                'attributes' => [
                    'max', 'value'
                ]
            ],
            'q' => [
                'selfClosing' => false,
                'attributes' => [
                    'cite'
                ]
            ],
            'rp' => [
                'selfClosing' => false
            ],
            'rt' => [
                'selfClosing' => false
            ],
            'ruby' => [
                'selfClosing' => false
            ],
            's' => [
                'selfClosing' => false
            ],
            'samp' => [
                'selfClosing' => false
            ],
            'script' => [
                'selfClosing' => false,
                'attributes' => [
                    'async', 'charset', 'defer',
                    'src', 'type'
                ]
            ],
            'section' => [
                'selfClosing' => false
            ],
            'select' => [
                'selfClosing' => false,
                'attributes' => [
                    'autofocus', 'disabled', 'form',
                    'multiple', 'name', 'required',
                    'size'
                ]
            ],
            'small' => [
                'selfClosing' => false
            ],
            'source' => [
                'selfClosing' => true,
                'attributes' => [
                    'media', 'src', 'type'
                ]
            ],
            'span' => [
                'selfClosing' => false,
                'attributes' => [
                    'contenteditable', 'contextmenu'
                ]
            ],
            'strong' => [
                'selfClosing' => false
            ],
            'style' => [
                'selfClosing' => false,
                'attributes' => [
                    'media', 'scoped', 'type'
                ]
            ],
            'sub' => [
                'selfClosing' => false
            ],
            'summary' => [
                'selfClosing' => false
            ],
            'sup' => [
                'selfClosing' => false
            ],
            'table' => [
                'selfClosing' => false,
                'attributes' => [
                    'sortable'
                ]
            ],
            'tbody' => [
                'selfClosing' => false
            ],
            'td' => [
                'selfClosing' => false,
                'attributes' => [
                    'colspan', 'headers', 'rowspan'
                ]
            ],
            'textarea' => [
                'selfClosing' => false,
                'attributes' => [
                    'autofocus', 'cols', 'disabled',
                    'form', 'maxlenght', 'name',
                    'placeholder', 'readonly', 'required',
                    'rows', 'wrap'
                ]
            ],
            'tfoot' => [
                'selfClosing' => false
            ],
            'th' => [
                'selfClosing' => false,
                'attributes' => [
                    'abbr', 'colspan', 'headers',
                    'rowspan', 'scope', 'sorted'
                ]
            ],
            'thead' => [
                'selfClosing' => false
            ],
            'time' => [
                'selfClosing' => false,
                'attributes' => [
                    'datetime'
                ]
            ],
            'title' => [
                'selfClosing' => false,
                'required' => true,
            ],
            'tr' => [
                'selfClosing' => false
            ],
            'track' => [
                'selfClosing' => true,
                'attributes' => [
                    'default', 'kind', 'label',
                    'src', 'srclang'
                ]
            ],
            'u' => [
                'selfClosing' => false
            ],
            'ul' => [
                'selfClosing' => false
            ],
            'var' => [
                'selfClosing' => false
            ],
            'video' => [
                'selfClosing' => false,
                'attributes' => [
                    'autoplay', 'controls', 'height',
                    'loop', 'muted', 'poster',
                    'preload', 'src', 'width'
                ]
            ],
            'wbr' => [
                'selfClosing' => false
            ]
        ];
    }

    static function getElementsAlias()
    {
        return [];
    }

    static function getSnippets()
    {
        return [];
    }

}
