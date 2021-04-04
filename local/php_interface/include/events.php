<?php
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EX2", "EX2_50"));
AddEventHandler("main", "OnEpilog", Array("EX2", "EX2_93"));
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
}