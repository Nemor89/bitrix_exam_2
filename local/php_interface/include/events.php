<?php
AddEventHandler("iblock", "OnBeforeIBlockElementUpdate", Array("EX2", "EX2_50"));
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
}