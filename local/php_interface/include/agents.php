<?php
function CheckUserCount()
{
    $date = new DateTime();
    $date = \Bitrix\Main\Type\DateTime::createFromTimestamp($date->getTimestamp());
    $lastDate = COption::GetOptionString("main", "last_date_agent_checkUserCount");

    if ($lastDate)
    {
        $arFilter = ["DATE_REGISTER_1" => $lastDate];
    }
    else
    {
        $arFilter = [];
    }
    $arUsers = [];
    $by = "DATE_REGISTER";
    $order = "ASC";
    $rsUser = CUser::GetList(
        $by,
        $order,
        $arFilter
    );
    while ($user = $rsUser->Fetch())
    {
        $arUsers[] = $user;
    }

    if (!$lastDate)
    {
        $lastDate = $arUsers[0]["DATE_REGISTER"];
    }

    $dateDifference = intval(abs(strtotime($lastDate) - strtotime($date->toString())));
    $days = round($dateDifference / (3600 * 24));

    $countUsers = count($arUsers);

    $by = "ID";
    $order = "ASC";
    $rsAdmins = CUser::GetList(
        $by,
        $order,
        ["GROUPS_ID" => 1]
    );
    while ($admin = $rsAdmins->Fetch())
    {
        CEvent::Send(
            "COUNT_REGISTERED_USERS",
            "s1",
            [
                "EMAIL_TO" => $admin["EMAIL"],
                "COUNT_USERS" => $countUsers,
                "COUNT_DAYS" => $days,
            ],
            "Y",
            "86"
        );
    }

    COption::SetOptionString("main", "last_date_agent_checkUserCount", $date->toString());

    return "CheckUserCount();";
}