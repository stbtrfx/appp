<?php

use Illuminate\Support\Facades\Config;

function get_default_lang(){
    return   Config::get('app.locale');
}

function sendmessage( $token, $title , $body)
{

    $token = $token;
    $from = "AAAAJYLIae8:APA91bHrEnv9auHUaDCgaFGf_gm-_3vS50uffFk_6mrJt0o1LkkgZlolBn13dENC8LGLN_SYWA-sScQRcnnyztQeTtjWv-YDEFjwcAuje3k3b0mAOvbFlisx7jm3dVmg2x05Cas6VP2L";
    $msg = array
            (
            'body'     => $body,
            'title'    => $title,
            'receiver' => 'erw',
            'icon'     => "https://image.flaticon.com/icons/png/512/270/270014.png",/*Default Icon*/
            'vibrate'	=> 1,
            'sound'		=> "http://commondatastorage.googleapis.com/codeskulptor-demos/DDR_assets/Kangaroo_MusiQue_-_The_Neverwritten_Role_Playing_Game.mp3",
            );

    $fields = array
            (
                'to'        => $token,
                'notification'  => $msg
            );

    $headers = array
            (
                'Authorization: key=' . $from,
                'Content-Type: application/json'
            );
    //#Send Reponse To FireBase Server
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch );
    curl_close( $ch );
    return $result;
}

