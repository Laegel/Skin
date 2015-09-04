<?php

/* namespace SkinTemplate;
  use \SkinTemplate\SkeletonParser as SkeletonParser,
  \SkinTemplate\SkeletonToElement as SkeletonElement,
  \SkinTemplate\SkinHTML5 as SkinHTML5; */

interface SkinLayerInterface
{
    static function getElements();

    static function getElementsAlias();

    static function getGlobalAttributes();

    static function getSnippets();

    static function getTagStructure();
}
