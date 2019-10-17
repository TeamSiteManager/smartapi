<?php
/**
 * Created by PhpStorm.
 * User: SiteManager
 * Date: 17/10/2019
 * Time: 10:20
 * SMART API EXAMPLE
 */

//these credentials can be found inside your Project  > Code > Smart Content
$clientId = 'xxxxxxxxxxxxxx';
$clientSecret = 'xxxxxxxxxxxxxx';
$groupID = '1'; //this groupID can be found when editing a smart group

//step 1 get access token

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.sitemn.gr/token/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'scope' => 'smart',
    'grant_type' => 'client_credentials',
]);

$response = curl_exec($ch);
$data = json_decode($response, true);

$accessToken = $data['access_token'];

//step 2 additional data to insert into site (JSON)
//this step is optional: do not use this if you don't want to insert extra information

$arr = array(
    'properties' => array(
        array(
            'property' => 'name',
            'value' => 'John Doe'
        ),
        array(
            'property' => 'company',
            'value' => 'SiteManager'
        ),
        array(
            'property' => 'country',
            'value' => 'Belgium'
        ),
        array(
            'property' => 'job_role',
            'value' => 'CTO'
        ),
        array(
            'property' => 'website',
            'value' => 'https://www.sitemanager.io'
        )

    )
);
$post_json = json_encode($arr);

//step 3 create smarttoken with optional additional data

$ch = curl_init();
$endpoint = 'https://api.sitemn.gr/smarttoken/?access_token=' . $accessToken;

curl_setopt($ch, CURLOPT_URL, $endpoint);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, [
    'jsdata' => $post_json,
    'groupID' => $groupID,
]);

$response = curl_exec($ch);
$data = json_decode($response, true);


//step 4 login to website using the smarttoken
if ($data["status"]["type"] == "success")
{

    $smarttoken = $data['logintoken'];
    $project = $data['project'];

    $redirectURL = 'https://www.mywebsite.com/?smt=' . $smarttoken;

    //example only
    echo " redirect <a href='" . $redirectURL . "' target='_blank'>redirect</a>";

} else {

    echo $data["status"]["message"];

}

?>