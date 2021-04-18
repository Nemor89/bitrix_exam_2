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
if (empty($arParams["NEWS_IBLOCK_ID"]))
{
    $arParams["NEWS_IBLOCK_ID"] = 0;
}
$arParams["PROPERTY_AUTHOR"] = trim($arParams["PROPERTY_AUTHOR"]);
$arParams["PROPERTY_AUTHOR_TYPE"] = trim($arParams["PROPERTY_AUTHOR_TYPE"]);

global $USER;
if($USER->isAuthorized())
{
    $arResult["COUNT"] = 0;
    $currentUserID = $USER->GetID();
    $currentUserType = CUser::GetList(
        ($by = "id"),
        ($order = "asc"),
        array("ID" => $currentUserID),
        array("SELECT" => array($arParams["PROPERTY_AUTHOR_TYPE"]))

    )->Fetch()[$arParams["PROPERTY_AUTHOR_TYPE"]];
}

if ($this->StartResultCache(false, $currentUserType, $currentUserID))
{
    $userList = [];
    $userListID = [];
    $rsUsers = CUser::GetList(
        ($by = "id"),
        ($order = 'asc'),
        [
            $arParams["PROPERTY_AUTHOR_TYPE"] => $currentUserType,
        ],
        [
            "SELECT" => ["LOGIN", "ID"]
        ]
    );
    while($arUser = $rsUsers->Fetch())
    {
       $userList[$arUser["ID"]] = ["LOGIN" => $arUser["LOGIN"]];
       $userListID[] = $arUser["ID"];
    }

    $arNewsAuthor = [];
    $arNewsList = [];
    $arNewsID = [];
    $rsElements = CIBlockElement::GetList(
        [],
        [
            "IBLOCK_ID" => $arParams["NEWS_IBLOCK_ID"],
            "PROPERTY_" . $arParams["PROPERTY_AUTHOR"] => $userListID,
        ],
        false,
        false,
        [
            "NAME",
            "ACTIVE_FROM",
            "ID",
            "IBLOCK_ID",
            "PROPERTY_" . $arParams["PROPERTY_AUTHOR"]
        ]
    );
    while($arElement = $rsElements->GetNext())
    {
        $arNewsAuthor[$arElement["ID"]][] = $arElement["PROPERTY_" . $arParams["PROPERTY_AUTHOR"] . "_VALUE"];

        if (empty($arNewsList[$arElement["ID"]]))
        {
            $arNewsList[$arElement["ID"]] = $arElement;
        }

        if ($arElement["PROPERTY_" . $arParams["PROPERTY_AUTHOR"] . "_VALUE"] != $currentUserID)
        {
            $arNewsList[$arElement["ID"]]["AUTHORS"][] = $arElement["PROPERTY_" . $arParams["PROPERTY_AUTHOR"] . "_VALUE"];
        }
    }
    foreach ($arNewsList as $key => $value)
    {
        if (in_array($currentUserID, $arNewsAuthor[$value["ID"]]))
        {
            continue;
        }

        foreach ($value["AUTHORS"] as $authorID)
        {
            $userList[$authorID]["NEWS"][] = $value;
            $arNewsID[$value["ID"]] = $value["ID"];
        }
    }
    unset($userList[$currentUserID]);

    $arResult["AUTHORS"] = $userList;
    $arResult["COUNT"] = count($arNewsID);
    $this->SetResultCacheKeys(["COUNT"]);
    $this->includeComponentTemplate();
}
else
{
    $this->AbortResultCache();
}
$APPLICATION->SetTitle(GetMessage("COUNT_97") . $arResult["COUNT"]);
?>