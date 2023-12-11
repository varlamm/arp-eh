<?php

use Xcelerate\Models\CompanySetting;
use Xcelerate\Models\Currency;
use Xcelerate\Models\CustomField;
use Xcelerate\Models\Setting;
use Illuminate\Support\Str;

/**
 * Get company setting
 *
 * @param $company_id
 * @return string
 */
function get_company_setting($key, $company_id)
{
        return CompanySetting::getSetting($key, $company_id);
    
}

/**
 * Get app setting
 *
 * @param $company_id
 * @return string
 */
function get_app_setting($key)
{
         return Setting::getSetting($key);
    
}

/**
 * Get page title
 *
 * @param $company_id
 * @return string
 */
function get_page_title($company_id)
{
    $routeName = Route::currentRouteName();

    $pageTitle = null;
    $defaultPageTitle = 'Xcelerate - Self Hosted Invoicing Platform';

    if ($routeName === 'customer.dashboard') {
        $pageTitle = CompanySetting::getSetting('customer_portal_page_title', $company_id);

        return $pageTitle ? $pageTitle : $defaultPageTitle;
    }

    $pageTitle = Setting::getSetting('admin_page_title');

    return $pageTitle ? $pageTitle : $defaultPageTitle;
    
}

/**
 * Set Active Path
 *
 * @param $path
 * @param string $active
 * @return string
 */
function set_active($path, $active = 'active')
{
    return call_user_func_array('Request::is', (array)$path) ? $active : '';
}

/**
 * @param $path
 * @return mixed
 */
function is_url($path)
{
    return call_user_func_array('Request::is', (array)$path);
}

/**
 * @param string $type
 * @return string
 */
function getCustomFieldValueKey(string $type)
{
    switch ($type) {
        case 'Input':
            return 'string_answer';

        case 'TextArea':
            return 'string_answer';

        case 'Phone':
            return 'number_answer';

        case 'Url':
            return 'string_answer';

        case 'Number':
            return 'number_answer';

        case 'Dropdown':
            return 'string_answer';

        case 'Switch':
            return 'boolean_answer';

        case 'Date':
            return 'date_answer';

        case 'Time':
            return 'time_answer';

        case 'DateTime':
            return 'date_time_answer';

        default:
            return 'string_answer';
    }
}

/**
 * @param string $type
 * @return string
 */
function getCompanyFieldValueKey(string $type)
{
    switch ($type) {
        case 'Input':
            return 'text';

        case 'Price':
            return 'price';

        case 'TextArea':
            return 'text';

        case 'Phone':
            return 'number';

        case 'Url':
            return 'text';

        case 'Number':
            return 'number';

        case 'Dropdown':
            return 'text';

        case 'Switch':
            return 'boolean';

        case 'Date':
            return 'date';

        case 'Time':
            return 'time';

        case 'DateTime':
            return 'date_time';

        default:
            return 'text';
    }
}

/**
 * @param $money
 * @return formated_money
 */
function format_money_pdf($money, $currency = null)
{
    $money = $money / 100;

    if (! $currency) {
        $currency = Currency::findOrFail(CompanySetting::getSetting('currency', 1));
    }

    $format_money = number_format(
        $money,
        $currency->precision,
        $currency->decimal_separator,
        $currency->thousand_separator
    );

    $currency_with_symbol = '';
    if ($currency->swap_currency_symbol) {
        $currency_with_symbol = $format_money.'<span style="font-family: DejaVu Sans;">'.$currency->symbol.'</span>';
    } else {
        $currency_with_symbol = '<span style="font-family: DejaVu Sans;">'.$currency->symbol.'</span>'.$format_money;
    }

    return $currency_with_symbol;
}

/**
 * @param $string
 * @return string
 */
function clean_slug($model, $title, $id = 0)
{
    // Normalize the title
    $slug = Str::upper('CUSTOM_'.$model.'_'.Str::slug($title, '_'));

    // Get any that could possibly be related.
    // This cuts the queries down by doing it once.
    $allSlugs = getRelatedSlugs($model, $slug, $id);

    // If we haven't used it before then we are all good.
    if (! $allSlugs->contains('slug', $slug)) {
        return $slug;
    }

    // Just append numbers like a savage until we find not used.
    for ($i = 1; $i <= 10; $i++) {
        $newSlug = $slug.'_'.$i;
        if (! $allSlugs->contains('slug', $newSlug)) {
            return $newSlug;
        }
    }

    throw new \Exception('Can not create a unique slug');
}

function getRelatedSlugs($type, $slug, $id = 0)
{
    return CustomField::select('slug')->where('slug', 'like', $slug.'%')
        ->where('model_type', $type)
        ->where('id', '<>', $id)
        ->get();
}

function respondJson($error, $message)
{
    return response()->json([
        'error' => $error,
        'message' => $message
    ], 422);
}

function zohoCurlRequest($access_token, $url, $parameters, $method, $headers)
{
    $curl_pointer = curl_init();
    $curl_options = array();
    
    foreach ($parameters as $key=>$value){
        $url = $url.$key."=".$value."&";
    }

    $curl_options[CURLOPT_URL] = $url;
    $curl_options[CURLOPT_RETURNTRANSFER] = true;
    $curl_options[CURLOPT_HEADER] = 1;
    $curl_options[CURLOPT_CUSTOMREQUEST] = $method;
    $headersArray = array();
    $headersArray[] = "Authorization". ":" . "Zoho-oauthtoken ".$access_token;
    $headersArray[] = "If-Modified-Since".":"."2023-08-12T17:59:50+05:30";
    $curl_options[CURLOPT_HTTPHEADER]=$headersArray;
    
    curl_setopt_array($curl_pointer, $curl_options);
    
    $result = curl_exec($curl_pointer);
    $responseInfo = curl_getinfo($curl_pointer);
    curl_close($curl_pointer);
    list ($headers, $content) = explode("\r\n\r\n", $result, 2);
    if(strpos($headers," 100 Continue")!==false){
        list( $headers, $content) = explode( "\r\n\r\n", $content , 2);
    }
    $headerArray = (explode("\r\n", $headers, 50));
    $headerMap = array();
    foreach ($headerArray as $key) {
        if (strpos($key, ":") != false) {
            $firstHalf = substr($key, 0, strpos($key, ":"));
            $secondHalf = substr($key, strpos($key, ":") + 1);
            $headerMap[$firstHalf] = trim($secondHalf);
        }
    }

    $jsonResponse = json_decode($content, true);
    if ($jsonResponse == null && $responseInfo['http_code'] != 204) {
        list ($headers, $content) = explode("\r\n\r\n", $content, 2);
        $jsonResponse = json_decode($content, true);
    }

    return $jsonResponse;
}