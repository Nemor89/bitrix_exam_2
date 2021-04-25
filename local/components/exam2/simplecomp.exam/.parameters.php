<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
		"PRODUCTS_IBLOCK_ID" => array(
			"NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_ID"),
			"PARENT" => "BASE",
			"TYPE" => "STRING",
		),
        "NEWS_BLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "PRODUCTS_IBLOCK_PROPERTY" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_CAT_IBLOCK_PROPERTY"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "ELEMENT_PER_PAGE" => array(
            "NAME" => GetMessage("ELEMENT_PER_PAGE"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
            "DEFAULT" => 2
        ),
        "DETAIL_TEMPLATE_LINK" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_DETAIL_TEMPLATE_LINK"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
            "DEFAULT" => "catalog_exam/#SECTION_ID#/#ELEMENT_CODE#"
        ),
        "CACHE_TIME"  =>  array("DEFAULT"=>36000000),

	),
);