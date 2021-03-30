<?php
if ($arParams['SPECIALDATE'] == "Y")
{
    $arResult["FIRST_NEWS_DATE"] = $arResult["ITEMS"][0]["ACTIVE_FROM"];
    $this->__component->SetResultCacheKeys("FIRST_NEWS_DATE");
}
