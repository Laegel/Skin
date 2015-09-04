<?php
namespace SkinTemplate;

class SkinXHTML extends SkeletonToElement
{
	protected function getTagStructure() 
	{
		return [
			'selfClosing' => [
				'start' => '<TAG ATTR/>'
			],
			'doubleClosing' => [
				'start' => '<TAG ATTR>',
				'end' => '</TAG>'
			]
		];
	}

	protected function getFormat()
	{
		return 'NAME="VALUE"';
	}

	protected function getElements()
	{
		return [	
		    'doctype' => [
		    	'selfClosing' => true,
		    	'output' => '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Frameset//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-frameset.dtd"> '
		    ],
		    'a' => [
		    	'selfClosing' => false,
		    	'attributes' => [
		    		'href'
		    	]
		    ],
		    'abbr' => [
		    	'selfClosing' => false
		    ],
		    'address' => [
		    	'selfClosing' => false
		    ],
		    'area' => [
		    	'selfClosing' => false
		    ],
		    'b' => [
		    	'selfClosing' => false
		    ],
		    'base' => [
		    	'selfClosing' => false
		    ],
		    'bdo' => [
		    	'selfClosing' => false
		    ],
		    'big' => [
		    	'selfClosing' => false
		    ],
		    'blockquote' => [
		    	'selfClosing' => false
		    ],
		    'body' => [
		    	'selfClosing' => false
		    ],
		    'br' => [
		    	'selfClosing' => true,
		    	'output' => '<br>'
		    ],
		    'button' => [
		    	'selfClosing' => false
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
		    	'selfClosing' => false
		    ],
		    'colgroup' => [
		    	'selfClosing' => false
		    ],
		    'dd' => [
		    	'selfClosing' => false
		    ],
		    'del' => [
		    	'selfClosing' => false
		    ],
		    'dfn' => [
		    	'selfClosing' => false
		    ],
		    'div' => [
		    	'selfClosing' => false
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
		    'fieldset' => [
		    	'selfClosing' => false
		    ],
		    'form' => [
		    	'selfClosing' => false
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
		    	'selfClosing' => false
		    ],
		    'hr' => [
		    	'selfClosing' => true
		    ],
		    'html' => [
		    	'selfClosing' => false,
		    	'output' => '<html xmlns="http://www.w3.org/1999/xhtml">'
		    ],
		    'i' => [
		    	'selfClosing' => false
		    ],
		    'img' => [
		    	'selfClosing' => true
		    ],
		    'input' => [
		    	'selfClosing' => true
		    ],
		    'ins' => [
		    	'selfClosing' => false
		    ],
		    'kbd' => [
		    	'selfClosing' => false
		    ],
		    'label' => [
		    	'selfClosing' => false
		    ],
		    'legend' => [
		    	'selfClosing' => false
		    ],
		    'li' => [
		    	'selfClosing' => false
		    ],
		    'link' => [
		    	'selfClosing' => true
		    ],
		    'map' => [
		    	'selfClosing' => false
		    ],
		    'meta' => [
		    	'selfClosing' => true
		    ],
		    'noscript' => [
		    	'selfClosing' => false
		    ],
		    'object' => [
		    	'selfClosing' => false
		    ],
		    'ol' => [
		    	'selfClosing' => false
		    ],
		    'optgroup' => [
		    	'selfClosing' => false
		    ],
		    'option' => [
		    	'selfClosing' => false
		    ],
		    'p' => [
		    	'selfClosing' => false
		    ],
		    'param' => [
		    	'selfClosing' => true
		    ],
		    'pre' => [
		    	'selfClosing' => false
		    ],
		    'q' => [
		    	'selfClosing' => false
		    ],
		    'rb' => [
		    	'selfClosing' => false
		    ],
		    'rbc' => [
		    	'selfClosing' => false
		    ],
		    'rp' => [
		    	'selfClosing' => false
		    ],
		    'rt' => [
		    	'selfClosing' => false
		    ],
		    'rtc' => [
		    	'selfClosing' => false
		    ],
		    'ruby' => [
		    	'selfClosing' => false
		    ],
		    'samp' => [
		    	'selfClosing' => false
		    ],
		    'script' => [
		    	'selfClosing' => false
		    ],
		    'select' => [
		    	'selfClosing' => false
		    ],
		    'small' => [
		    	'selfClosing' => false
		    ],
		    'span' => [
		    	'selfClosing' => false
		    ],
		    'strong' => [
		    	'selfClosing' => false
		    ],
		    'style' => [
		    	'selfClosing' => false
		    ],
		    'sub' => [
		    	'selfClosing' => false
		    ],
		    'sup' => [
		    	'selfClosing' => false
		    ],
		    'table' => [
		    	'selfClosing' => false
		    ],
		    'tbody' => [
		    	'selfClosing' => false
		    ],
		    'td' => [
		    	'selfClosing' => false
		    ],
		    'textarea' => [
		    	'selfClosing' => false
		    ],
		    'tfoot' => [
		    	'selfClosing' => false
		    ],
		    'th' => [
		    	'selfClosing' => false
		    ],
		    'thead' => [
		    	'selfClosing' => false
		    ],
		    'title' => [
		    	'selfClosing' => false
		    ],
		    'tr' => [
		    	'selfClosing' => false
		    ],
		    'tt' => [
		    	'selfClosing' => false
		    ],
		    'ul' => [
		    	'selfClosing' => false
		    ],
		    'var' => [
		    	'selfClosing' => false
		    ]
		];
	}

	protected function getElementsAlias()
	{
		return [
			'article' => [
				'start' => '<div ATTR>',
		    	'end' => '</div>'
		    ],
		    'aside' => [
		    	'start' => '<div ATTR>',
		    	'end' => '</div>'
		    ],
		    'audio' => [#TODO
		    	'start' => '<div ATTR>',
		    	'end' => '</div>'
		    ],
		    'bdi' => [
		    	'start' => '<span ATTR>',
		    	'end' => '</span>'
		    ],
		    'canvas' => [
		    	'start' => '<div ATTR>',
		    	'end' => '</div>'
		    ],
		    'datalist' => [
		    	'start' => '<div class="datalist">',
		    	'end' => '</div>'
		    ],
		    'details' => [
		    	'selfClosing' => false
		    ],
		    'embed' => [
		    	'selfClosing' => false
		    ],
		    'figcaption' => [
		    	'selfClosing' => false
		    ],
		    'figure' => [
		    	'selfClosing' => false
		    ],
		    'footer' => [
		    	'start' => '<div ATTR>',
		    	'end' => '</div>'
		    ],
		    'header' => [
		    	'start' => '<div ATTR>',
		    	'end' => '</div>'
		    ],
		    'hgroup' => [
		    	'selfClosing' => false
		    ],
		    'iframe' => [
		    	'selfClosing' => false
		    ],
		    'keygen' => [
		    	'selfClosing' => true
		    ],
		    'mark' => [
		    	'selfClosing' => false
		    ],
		    'menu' => [
		    	'selfClosing' => false
		    ],
		    'meter' => [
		    	'selfClosing' => false
		    ],
		    'nav' => [
		    	'selfClosing' => false
		    ],
		    'output' => [
		    	'selfClosing' => false
		    ],
		    'progress' => [
		    	'selfClosing' => false
		    ],
		    's' => [
		    	'selfClosing' => false
		    ],
		    'section' => [
		    	'selfClosing' => false
		    ],
		    'source' => [
		    	'selfClosing' => true
		    ],
		    'summary' => [
		    	'selfClosing' => false
		    ],
		    'time' => [
		    	'selfClosing' => false
		    ],
		    'track' => [
		    	'selfClosing' => true
		    ],
		    'u' => [
		    	'selfClosing' => false
		    ],
		    'video' => [
		    	'selfClosing' => false
		    ],
		    'wbr' => [
		    	'selfClosing' => false
		    ]
		];
	}

	protected function getSnippets()
	{
		
	}
}