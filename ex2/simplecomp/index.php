<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp.exam", 
	".default", 
	array(
		"PRODUCTS_IBLOCK_ID" => "2",
		"COMPONENT_TEMPLATE" => ".default",
		"NEWS_BLOCK_ID" => "1",
		"PRODUCTS_IBLOCK_PROPERTY" => "UF_NEWS_LINK",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>