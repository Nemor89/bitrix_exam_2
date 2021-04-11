<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader,
	Bitrix\Iblock;

if(!Loader::includeModule("iblock"))
{
	ShowError(GetMessage("SIMPLECOMP_EXAM2_IBLOCK_MODULE_NONE"));
	return;
}

if (empty($arParams["CACHE_TIME"]))
{
    $arParams["CACHE_TIME"] = 36000000;
}
if (empty($arParams["PRODUCTS_IBLOCK_ID"]))
{
    $arParams["PRODUCTS_IBLOCK_ID"] = 0;
}
if (empty($arParams["CLASSIF_IBLOCK_ID"]))
{
    $arParams["CLASSIF_IBLOCK_ID"] = 0;
}

$arParams["PROPERTY_CODE"] = trim($arParams["PROPERTY_CODE"]);

global $USER;
if ($this->startResultCache(false, [$USER->GetGroups()]))
{
    $arClassif = [];
    $arClassifID = [];
    $arResult["COUNT"] = 0;

    $arSelectElems = array (
        "ID",
        "IBLOCK_ID",
        "NAME"
    );
    $arFilterElems = array (
        "IBLOCK_ID" => $arParams["CLASSIF_IBLOCK_ID"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
        "ACTIVE" => "Y"
    );
    $arSortElems = array (
        "NAME" => "ASC"
    );

    $rsElements = CIBlockElement::GetList($arSortElems, $arFilterElems, false, false, $arSelectElems);
    while($arElement = $rsElements->GetNext())
    {
        $arClassif[$arElement["ID"]] = $arElement;
        $arClassifID[] = $arElement["ID"];
    }
    $arResult["COUNT"] = count($arClassifID);

    $arSelectElemsCatalog = array (
        "ID",
        "IBLOCK_ID",
        "IBLOCK_SECTION_ID",
        "NAME",
        "DETAIL_PAGE_URL"
    );
    $arFilterElemsCatalog = array (
        "IBLOCK_ID" => $arParams["PRODUCTS_IBLOCK_ID"],
        "CHECK_PERMISSIONS" => $arParams["CACHE_GROUPS"],
        "PROPERTY_".$arParams["PROPERTY_CODE"] => $arClassifID,
        "ACTIVE" => "Y"
    );
    $arSortElemsCatalog = array (
        "NAME" => "ASC"
    );

    $rsElements = CIBlockElement::GetList($arSortElemsCatalog, $arFilterElemsCatalog, false, false, $arSelectElemsCatalog);
    while($rsElem = $rsElements->GetNextElement())
    {
        $arField = $rsElem->GetFields();
        $arField["PROPERTY"] = $rsElem->GetProperties();
        foreach ($arField["PROPERTY"]["FIRM"]["VALUE"] as $firm)
        {
            $arClassif[$firm]["ELEMENTS"][$arField["ID"]] = $arField;
        }
    }
    $arResult["CLASSIF"] = $arClassif;

    $this->SetResultCacheKeys(["COUNT"]);
    $this->includeComponentTemplate();
}
else
{
    $this->abortResultCache();
}
$APPLICATION->SetTitle(GetMessage("COUNT_71").$arResult["COUNT"]);
?>