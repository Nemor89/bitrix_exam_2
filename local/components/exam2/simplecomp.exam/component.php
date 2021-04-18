<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if (!isset($arParams["CACHE_TIME"]))
{
    $arParams["CACHE_TIME"] = 36000000;
}
if (!isset($arParams["PRODUCTS_IBLOCK_ID"]))
{
    $arParams["PRODUCTS_IBLOCK_ID"] = 0;
}
if (!isset($arParams["NEWS_BLOCK_ID"]))
{
    $arParams["NEWS_BLOCK_ID"] = 0;
}

if ($this->startResultCache())
{
    $arNews = [];
    $arNewsID = [];
    $obNews = CIBlockElement::GetList(
        [],
        [
            "IBLOCK_ID" => $arParams["NEWS_BLOCK_ID"],
            "ACTIVE" => "Y",

        ],
        false,
        false,
        [
            "NAME",
            "ACTIVE_FROM",
            "ID"
        ]
    );
    while ($newsElements = $obNews->Fetch())
    {
        $arNewsID[] = $newsElements["ID"];
        $arNews[$newsElements["ID"]] = $newsElements;
    }

    $arSections = [];
    $arSectionsID = [];
    $obSection = CIBlockSection::GetList(
        [],
        [
            "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
            "ACTIVE",
            $arParams["PRODUCTS_IBLOCK_ID_PROPERTY"] => $arNewsID
        ],
        false,
        [
            "NAME",
            "IBLOCK_ID",
            "ID",
            $arParams["PRODUCTS_IBLOCK_PROPERTY"]
        ],
        false,
    );
    while ($arSectionCatalog = $obSection->Fetch())
    {
        $arSectionsID[] = $arSectionCatalog["ID"];
        $arSections[$arSectionCatalog["ID"]] = $arSectionCatalog;
    }


    $obProducts = CIBlockElement::GetList(
        [
            "NAME" => "asc",
            "SORT" => "asc"
        ],
        [
            "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
            "ACTIVE" => "Y",
            "SECTION_ID" => $arSectionsID

        ],
        false,
        false,
        [
            "NAME",
            "IBLOCK_SECTION_ID",
            "ID",
            "CODE",
            "IBLOCK_ID",
            "PROPERTY_ARTNUMBER",
            "PROPERTY_MATERIAL",
            "PROPERTY_PRICE",
        ]
    );
    $arResult["PRODUCT_CNT"] =0;
    while ($arProducts  = $obProducts->Fetch())
    {
        $arProducts["DETAIL_TEMPLATE_LINK"] = str_replace(
            [
                "#SECTION_ID#",
                "#ELEMENT_CODE#",
            ],
            [
                $arProducts["IBLOCK_SECTION_ID"],
                $arProducts["CODE"],
            ],
            $arParams["DETAIL_TEMPLATE_LINK"]
        );

        $arResult["PRODUCT_CNT"] ++;
        foreach ($arSections[$arProducts["IBLOCK_SECTION_ID"]][$arParams["PRODUCTS_IBLOCK_PROPERTY"]] as $newsID)
        {
            $arNews[$newsID]["PRODUCTS"][] = $arProducts;
        }
    }

    foreach ($arSections as $arSection)
    {
        foreach ($arSection[$arParams["PRODUCTS_IBLOCK_PROPERTY"]] as $newID)
        {
            $arNews[$newID]["SECTIONS"][] = $arSection["NAME"];
        }
    }
    $arResult["NEWS"] = $arNews;

    $this->SetResultCacheKeys(["PRODUCT_CNT"]);
    $this->includeComponentTemplate();
}
else
{
    $this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("SIMPLECOMP_EXAM2_TITLE").$arResult["PRODUCT_CNT"]);
?>