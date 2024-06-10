<?php

namespace XackiGiFF\PremiumManager\public\view;

class MPEPremiumManagerPublicDisplay
{
    public function __construct()
    {
        //TODO
    }

    public static function get_template($template): string
    {
        switch ($template) {
            case 'standart':
                $template = "
<div id='not-paid-for'>
    <div class='label'>Уровень доступа</div>
    <div>
        <div class='textbox'>
            <div class='standart'>Standart</div>
        </div>
    </div><a href='{link}' class='button orange-btn-p w-button'>{link_text}</a></div>
";
                break;
            case 'premium':
                $template = "
<div id='paid'>
    <div>
        <div class='label'>Уровень доступа</div>
        <div class='input-pr'>
            <div class='premium-tarif'>Premium</div>
        </div>
    </div>
    <div class='label'>Дата истечения доступа</div>
    <div class='textbox'>
        <div><span class='date-itog'>{date}</span> (осталось {remaining_days} дней)</div>
    </div>
</div>
";
                break;
            default:
                $template = '';
                break;
        }
        return $template;
    }
}