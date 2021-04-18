<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Простой компонент - 97");
?><?$APPLICATION->IncludeComponent(
	"exam2:simplecomp.exam-97", 
	".default", 
	array(
		"PRODUCTS_IBLOCK_ID" => "",
		"COMPONENT_TEMPLATE" => ".default",
		"NEWS_IBLOCK_ID" => "1",
		"PROPERTY_AUTHOR" => "AUTHOR",
		"PROPERTY_AUTHOR_TYPE" => "UF_AUTHOR_TYPE",
		"CACHE_TYPE" => "A",
		"CACHE_TIME" => "36000000",
		"CACHE_GROUPS" => "Y"
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>