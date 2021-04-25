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

$cFilter = false;

if (isset($_REQUEST["F"]))
{
    $cFilter = true;
}

global $USER;
if ($USER->isAuthorized())
{
    $arButtons = CIBlock::GetPanelButtons($arParams["PRODUCTS_IBLOCK_ID"]);
    $this->AddIncludeAreaIcons(
        [
            [
                "ID" => "link_ib",
                "TITLE" => GetMessage("IB_IN_ADMIN"),
                "URL" => $arButtons["submenu"]["element_list"]["ACTION_URL"],
                "IN_PARAMS_MENU" => true
            ]
        ]
    );
}

global $CACHE_MANAGER;

$arNavigation = CDBResult::GetNavParams($arNavParams);

if ($this->startResultCache(false, [$cFilter, $arNavigation], "/servicesIblock"))
{
    $CACHE_MANAGER->RegisterTag("iblock_id_3");

    $arNews = [];
    $arNewsID = [];
    $obNews = CIBlockElement::GetList(
        [],
        [
            "IBLOCK_ID" => $arParams["NEWS_BLOCK_ID"],
            "ACTIVE" => "Y",

        ],
        false,
        [
            "nPageSize" => $arParams["ELEMENT_PER_PAGE"],
            "bShowAll" => true
        ],
        [
            "NAME",
            "ACTIVE_FROM",
            "ID"
        ]
    );

    $arResult["NAV_STRING"] = $obNews->GetPageNavString(GetMessage("PAGE_TITLE"));

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

    $arFilterElements = [
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "ACTIVE" => "Y",
        "SECTION_ID" => $arSectionsID

    ];

    if ($cFilter)
    {
        $arFilterElements = [
            [
                "<=PROPERTY_PRICE" => 1700,
                "PROPERTY_MATERIAL" => "Дерево, ткань"
            ],
            [
                "<PROPERTY_PRICE" => 1500,
                "PROPERTY_MATERIAL" => "Металл, пластик"
            ],
            "LOGIC" => "OR"
        ];
        $this->AbortResultCache();
    }

    $obProducts = CIBlockElement::GetList(
        [
            "NAME" => "asc",
            "SORT" => "asc"
        ],
        $arFilterElements,
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
        $arButtons = CIBlock::GetPanelButtons(
            $arParams["PRODUCTS_IBLOCK_ID"],
            $arProducts["ID"],
            0,
            [
                "SECTION_BUTTONS" => false,
                "SESSID" => false,
            ]
        );

        $arProducts["EDIT_LINK"] = $arButtons["edit"]["edit_element"]["ACTION_URL"];
        $arProducts["DELETE_LINK"] = $arButtons["edit"]["delete_element"]["ACTION_URL"];

        $arResult["ADD_LINK"] = $arButtons["edit"]["add_element"]["ACTION_URL"];
        $arResult["IBLOCK_ID"] = $arParams["PRODUCTS_IBLOCK_ID"];

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
            if (isset($arNews[$newsID]))
            {
                $arNews[$newsID]["PRODUCTS"][] = $arProducts;
            }
        }
    }

    foreach ($arSections as $arSection)
    {
        foreach ($arSection[$arParams["PRODUCTS_IBLOCK_PROPERTY"]] as $newID)
        {
            if (isset($arNews[$newID]))
            {
                $arNews[$newID]["SECTIONS"][] = $arSection["NAME"];
            }
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