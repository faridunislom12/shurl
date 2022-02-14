<?php

namespace App\Http\Controllers;

use App\Mail\ConfirmMail;
use App\Models\Application;
use App\Models\Url;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Artisaninweb\SoapWrapper\SoapWrapper;
use SoapClient;


class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Get all model columns for list.
     *
     */
    function get_all_columns($model)
    {
        $all_columns = [];
        foreach ($model->attributes() as $attribute) {
            $all_columns[] = (object)["label" => $attribute['label'], "field" => $attribute['field'], "type" => "string", "filterOptions" => $attribute['filterOptions'] ?? ''];
        }
//        $all_columns[] = (object)["label" => 'Дата создания', "field" => 'created_at', 'type' => 'date', 'dateInputFormat' => "yyyy-MM-dd'T'HH:mm:ss.SSSSSS'Z'", 'dateOutputFormat' => 'dd-MM-yyyy HH:mm'];
        $all_columns[] = (object)["label" => 'Действия', "field" => 'actions', "sortable" => false];

        return $all_columns;
    }

    function shorten_link($long)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $short = '';
        for ($i = 0; $i < 8; $i++) {
            $short .= $characters[rand(0, strlen($characters))];
        }
        if(Url::where('short', $short)->first()) {
         $short = 'duble';
        }
        return $short;
    }

}
