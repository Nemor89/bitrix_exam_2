<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
---
<br>
<p><b><?=GetMessage("SIMPLECOMP_EXAM2_CAT_TITLE_71")?></b></p>

<?if(count($arResult["CLASSIF"]) > 0):?>
    <ul>
    <?foreach($arResult["CLASSIF"] as $arClassif):?>
        <li>
            <b>
                <?=$arClassif["NAME"]?>
                <?if(count($arClassif["ELEMENTS"]) > 0):?>
                    <ul>
                        <?foreach($arClassif["ELEMENTS"] as $arItems):?>
                            <li>
                                <?=$arItems["NAME"]?> -
                                <?=$arItems["PROPERTY"]["PRICE"]["VALUE"]?> -
                                <?=$arItems["PROPERTY"]["MATERIAL"]["VALUE"]?> -
                                <?=$arItems["PROPERTY"]["ARTNUMBER"]["VALUE"]?>
                                <a href="<?=$arItems['DETAIL_PAGE_URL']?>">ссылка на детальный просмотр</a>
                            </li>
                        <?endforeach;?>
                    </ul>
                <?endif;?>
            </b>
        </li>
    <?endforeach;?>
    </ul>
<?endif;?>
