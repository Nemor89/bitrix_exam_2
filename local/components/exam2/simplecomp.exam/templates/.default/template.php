<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
    <br>
    ---
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE")?></b></p>
<?
$URL = $APPLICATION->GetCurPage()."?F=Y";
echo GetMessage("FILTER_TEMPLATE") . "<a href='" . $URL . "'>" . $URL . "</a>";
?>
<?if(count($arResult["NEWS"]) > 0):?>
<ul>
    <?foreach($arResult["NEWS"] as $arNews):?>
        <li>
            <b>
                <?=$arNews["NAME"]?>
            </b>
            - <?=$arNews["ACTIVE_FROM"]?>
            (<?=implode(",", $arNews["SECTIONS"]);?>)
        </li>
        <?if(count($arNews["PRODUCTS"]) > 0):?>
            <ul>
            <?foreach($arNews["PRODUCTS"] as $arProduct):?>
                <li>
                    <?=$arProduct["NAME"]?> -
                    <?=$arProduct["PROPERTY_PRICE_VALUE"]?> -
                    <?=$arProduct["PROPERTY_MATERIAL_VALUE"]?> -
                    <?=$arProduct["PROPERTY_ARTNUMBER_VALUE"]?> -
                    (<?=$arProduct["DETAIL_TEMPLATE_LINK"]?>).php

                </li>
            <?endforeach;?>
            </ul>
        <?endif?>
    <?endforeach;?>
</ul>
<?endif?>