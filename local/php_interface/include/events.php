<?php
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EX2", "EX2_50"));
AddEventHandler("main", "OnEpilog", Array("EX2", "EX2_93"));
AddEventHandler("main", "OnBeforeEventAdd", array("EX2", "EX2_51"));
AddEventHandler("main", "OnBuildGlobalMenu", Array("EX2", "EX2_95"));
AddEventHandler("main", "OnBeforeProlog", Array("EX2", "EX2_94"));

IncludeModuleLangFile(__FILE__);

class EX2
{
    function EX2_50(&$arFields)
    {
        if ($arFields["IBLOCK_ID"] == IBLOC_CATALOG)
        {
            if ($arFields["ACTIVE"] == "N")
            {
                $arSelect = Array(
                    "ID",
                    "IBLOCK_ID",
                    "NAME",
                    "SHOW_COUNTER"
                );
                $arFilter = Array(
                    "IBLOCK_ID" => IBLOC_CATALOG,
                    "ID" => $arFields["ID"]
                );
                $res = CIBlockElement::GetList(Array(), $arFilter, false, false, $arSelect);
                $arItems = $res->fetch();

                if ($arItems["SHOW_COUNTER"] > MAX_COUNT)
                {
                    global $APPLICATION;
                    $APPLICATION->throwException(GetMessage("ERROR_MSG", ["#COUNT#" => $arItems["SHOW_COUNTER"]]));
                    return false;
                }
            }
        }
    }
    function EX2_93()
    {
        if (defined("ERROR_404") && ERROR_404 == "Y")
        {
            global $APPLICATION;
            $APPLICATION->RestartBuffer();
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/header.php";
            include $_SERVER["DOCUMENT_ROOT"] . "/404.php";
            include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/footer.php";
            CEventLog::Add(
                [
                    "SEVERITY" => "INFO",
                    "AUDIT_TYPE_ID" => "ERROR_404",
                    "MODULE_ID" => "main",
                    "DESCRIPTION" => $APPLICATION->GetCurPage()
                ]
            );
        }
    }
    function EX2_51(&$event, &$lid, &$arFields)
    {
        if ($event == "FEEDBACK_FORM")
        {
            global $USER;
            if ($USER->isAuthorized())
            {
                $arFields["AUTHOR"] = GetMessage("AUTH",
                    [
                        "#ID#" => $USER->GetID(),
                        "#LOGIN#" => $USER->GetLogin(),
                        "#NAME#" => $USER->GetFullName(),
                        "#FORM_NAME#" => $arFields["AUTHOR"]
                    ]
                );
            }
            else
            {
                $arFields["AUTHOR"] = GetMessage("NOT_AUTH",
                    [
                        "#FORM_NAME#" => $arFields["AUTHOR"]
                    ]
                );
            }
            CEventLog::Add(
                [
                    "SEVERITY" => "SECURITY",
                    "AUDIT_TYPE_ID" => "REPLACEMENT",
                    "MODULE_ID" => "main",
                    "ITEM_ID" => $event,
                    "DESCRIPTION" => GetMessage("REPLACEMENT", ["AUTHOR" => $arFields["AUTHOR"]]),
                ]
            );
        }
    }
    function EX2_95(&$aGlobalMenu, &$aModuleMenu)
    {
        global $USER;
        $userGroups = CUser::GetUserGroupList($USER->GetID());
        $contentManagerGroupID = CGroup::GetList(
          $by = "c_sort",
          $order = "asc",
            [
                "STRING_ID" => "content_editor"
            ]
        )->Fetch()["ID"];

        while ($group = $userGroups->Fetch())
        {
            if ($group["GROUP_ID"] == 1)
            {
                $isAdmin = true;
            }
            if ($group["GROUP_ID"] == $contentManagerGroupID)
            {
                $isContentManager = true;
            }
        }
        if (!$isAdmin && $isContentManager)
        {
            foreach ($aModuleMenu as $key => $item)
            {
                if ($item["items_id"] == "menu_iblock_/news")
                {
                    $aModuleMenu = [$item];
                    foreach ($item["items"] as $childItem)
                    {
                        if ($childItem["items_id"] == "menu_iblock_/news/1")
                        {
                            $aModuleMenu[0]["items"] = [$childItem];
                            break;
                        }
                    }
                    break;
                }
            }
        }
    }
    function EX2_94()
    {
        global $APPLICATION;
        $curPage = $APPLICATION->GetCurDir();

        if (Bitrix\Main\Loader::includeModule("iblock"))
        {
            $arFilter = [
                "IBLOCK_ID" => IBLOCK_META,
                "NAME" => $curPage
            ];
            $arSelect = [
                "IBLOCK_ID",
                "ID",
                "PROPERTY_TITLE",
                "PROPERTY_DESCRIPTION"
            ];

            $ob = CIBlockElement::GetList(
                [],
                $arFilter,
                false,
                false,
                $arSelect
            );

            if ($arRes = $ob->Fetch())
            {
                $APPLICATION->SetPageProperty("title", $arRes["PROPERTY_TITLE_VALUE"]);
                $APPLICATION->SetPageProperty("description", $arRes["PROPERTY_DESCRIPTION_VALUE"]);
            }
        }
    }
}