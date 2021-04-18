<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
$arComponentParameters = array(
	"PARAMETERS" => array(
        "NEWS_IBLOCK_ID" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_NEWS_IBLOCK_ID_97"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "PROPERTY_AUTHOR" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_PROPERTY_AUTHOR_97"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "PROPERTY_AUTHOR_TYPE" => array(
            "NAME" => GetMessage("SIMPLECOMP_EXAM2_PROPERTY_AUTHOR_TYPE_97"),
            "PARENT" => "BASE",
            "TYPE" => "STRING",
        ),
        "CACHE_TIME"  =>  array(
            "DEFAULT"=>36000000,
            "PARENT" => "BASE",
        ),
        "CACHE_GROUPS" => array(
            "PARENT" => "CACHE_SETTINGS",
            "NAME" => GetMessage("CP_BNL_CACHE_GROUPS_97"),
            "TYPE" => "CHECKBOX",
            "DEFAULT" => "Y",
        ),
	),
);