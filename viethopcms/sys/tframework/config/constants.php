<?php

// Taxonomy term

abstract class Taxonomy{
    const MENU      = 'nav_menu';
    const TAG       = 'article_tag';
    const CATEGORY  = 'article_category';
    const SLIDER    = 'slider';
}

// Article Type

abstract class ArticleType{
    const ARTICLE   = 'article';
    const EVENT     = 'event';
    const SCHOLAR   = 'scholar';
    const PAGE      = 'page';
    const POST_FACE = 'fb_page';
}

// Article Status

abstract class ArticleStatus{
    const PUBLISH   = 'publish';
    const DRAFT     = 'draft';
    const PIN       = 1;
    const UNPIN     = 0;
}

abstract class ArticleShow{
    const ITEM_PER_PAGE   = 8;
}

// Language Status

abstract class LanguageStatus{
    const PUBLISH   = 'publish';
    const DRAFT     = 'draft';
    const ARRAY_TYPE            = ['publish' => 'publish', 'draft' => 'draft'];
}

// Article Type

abstract class OptionType{
    const META_GLOBAL           = 'meta_global';
    const META_SEO_DEFAULT      = 'meta_seo_default';
    const OP_SETTING            = 'op_setting';
    const ARRAY_TYPE            = [
                                    'meta_global' => 'meta_global',
                                    'meta_seo_default' => 'meta_seo_default',
                                    'op_setting' => 'op_setting'
                                ];
}

// ErrorCode

abstract class ErrorCode{
    const SUCCESS_ER            = 0;
    const DATABASE_ER           = 1001;
    const EX_ER                 = 1002;
    const DATA_EMPTY            = 1003;
    const PERMISSION_DENIED     = 1004;
}