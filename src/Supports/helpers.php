<?php

use Doctrine\DBAL\ConnectionException;
use Illuminate\Support\Str;
use Nsingularity\GeneralModule\Foundation\Controller\Api\Auth;
use Nsingularity\GeneralModule\Foundation\Entities\GeneralUser;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesException;
use Nsingularity\GeneralModule\Foundation\Exceptions\CustomMessagesViewException;
use Nsingularity\GeneralModule\Foundation\Supports\Helper;
use Psr\Http\Message\ResponseInterface;

if (!function_exists('user')) {
    /**
     * @return GeneralUser
     */
    function user()
    {
        return Helper::user();
    }
}

if (!function_exists('customAuth')) {
    /**
     * @return Auth
     */
    function customAuth()
    {
        return Helper::auth();
    }
}

if (!function_exists('customException')) {
    /**
     * @param $messages
     * @param bool $status
     * @param int $code
     * @param string $errorLocation
     * @param bool $usePlural
     * @param Exception|null $realException
     * @param $forceReport
     * @throws CustomMessagesException
     */
    function customException($messages, $status = false, $code = 200, $errorLocation = "Internal", $usePlural = null, Exception $realException = null, $forceReport = false)
    {
        Helper::customException($messages, $status, $code, $errorLocation, $usePlural, $realException, $forceReport);
    }
}

if (!function_exists('customExceptionView')) {
    /**
     * @param $view
     * @param array $params
     * @param int $code
     * @param Exception|null $realException
     * @param bool $forceReport
     * @param string $messages
     * @throws CustomMessagesViewException
     */
    function customExceptionView($view, $params = [], $code = 200, Exception $realException = null, $forceReport = false, $messages = "")
    {
        Helper::customExceptionView($view, $params, $code, $realException, $forceReport, $messages);
    }
}

if (!function_exists('throwOriginalMessagesException')) {
    /**
     * @param $messages
     * @param int $code
     * @param Exception|null $realException
     * @throws CustomMessagesException
     */
    function throwOriginalMessagesException($messages, $code = 200, Exception $realException = null)
    {
        Helper::throwOriginalMessagesException($messages, $code, $realException);
    }
}


if (!function_exists('get_response_content')) {
    function get_response_content(ResponseInterface $response)
    {
        return Helper::get_response_content($response);
    }
}

if (!function_exists('object_to_array')) {
    function object_to_array($object)
    {
        return Helper::object_to_array($object);
    }
}

if (!function_exists('array_to_object')) {
    function array_to_object(array $array)
    {
        return Helper::array_to_object($array);
    }
}

if (!function_exists('imageUrl')) {
    /**
     * @param $path
     * @return string
     */
    function imageUrl($path)
    {
        return Helper::imageUrl($path);
    }
}

if (!function_exists('toDateOrNull')) {
    /**
     * @param $date
     * @return mixed'
     */
    function toDateOrNull($date)
    {
        return Helper::toDateOrNull($date);
    }
}

if (function_exists('url_preg_replace_keys') == false) {
    function url_preg_replace_keys($inputString, &$replacedObject, $unsetMode = false)
    {
        $result = $inputString;
        $keys   = array_keys($replacedObject);
        foreach ($keys as $key) {
            if (is_array($replacedObject[$key]) == false) {
                $count  = 0;
                $result = preg_replace('/:' . $key . '/', $replacedObject[$key], $result, -1, $count);
                if ($unsetMode == true) {
                    if ($count > 0) {
                        unset($replacedObject[$key]);
                    }
                }
            }
        }
        return $result;
    }
}

if (!function_exists('customValidation')) {
    /**
     * @param array $data
     * @param $rules
     * @param array $messages
     * @return bool
     * @throws CustomMessagesException
     */
    function customValidation(array $data, $rules, $messages = [])
    {
        return Helper::customValidation($data, $rules, $messages);
    }
}

if (!function_exists('customValidationFromRequest')) {
    /**
     * @param $rules
     * @param array $messages
     * @return bool
     * @throws CustomMessagesException
     */
    function customValidationFromRequest($rules, $messages = [])
    {
        return Helper::customValidationFromRequest($rules, $messages);
    }
}

if (!function_exists('strColonReplace')) {
    /**
     * @param $string
     * @param array $replace
     * @return mixed
     */
    function strColonReplace($string, array $replace = [])
    {
        return Helper::strColonReplace($string, $replace);
    }
}

if (!function_exists('readFileText')) {
    /**
     * @param $filePath
     * @return mixed
     */
    function readFileText($filePath)
    {
        return Helper::readFileText($filePath);
    }
}

if (!function_exists('writeFileText')) {
    /**
     * @param $filePath
     * @param $data
     * @return mixed
     */
    function writeFileText($filePath, $data)
    {
        return Helper::writeFileText($filePath, $data);
    }
}

if (!function_exists('readFileTextJson')) {
    /**
     * @param $filePath
     * @return mixed
     */
    function readFileTextJson($filePath)
    {
        return Helper::readFileTextJson($filePath);
    }
}

if (!function_exists('writeFileTextJson')) {
    /**
     * @param $filePath
     * @param array $data
     * @return mixed
     */
    function writeFileTextJson($filePath, array $data)
    {
        return Helper::writeFileTextJson($filePath, $data);
    }
}

if (!function_exists('decimalToDecimal61')) {
    /**
     * @param int $decimal
     * @return mixed
     */
    function decimalToDecimal61(int $decimal)
    {
        return Helper::decimalToDecimal61($decimal);
    }
}


if (!function_exists('decimal61ToDecimal')) {
    /**
     * @param string $decimal61
     * @return int
     */
    function decimal61ToDecimal(string $decimal61)
    {
        return Helper::decimal61ToDecimal($decimal61);
    }
}

if (!function_exists('replaceNonAlphanumeric')) {
    function replaceNonAlphanumeric($replace, $string)
    {
        return Helper::replaceNonAlphanumeric($replace, $string);
    }
}

if (!function_exists('replaceMultipleCharToSingleChar')) {
    function replaceMultipleCharToSingleChar($char, $string)
    {
        return Helper::replaceMultipleCharToSingleChar($char, $string);
    }
}

if (!function_exists('startsWith')) {
    function startsWith($haystack, $needle)
    {
        return Helper::startsWith($haystack, $needle);
    }
}

if (!function_exists('createHashId')) {
    /**
     * @param $entityClassName
     * @param int $digit
     * @return string
     */
    function createHashId($entityClassName, $digit = 8)
    {
        return Helper::createHashId($entityClassName, $digit);
    }
}

if (!function_exists('toArrayOrNot')) {
    /**
     * @param $object
     * @param string $toArray
     * @param $include
     * @return mixed
     */
    function toArrayOrNot($object, $toArray = 'default', $include = '')
    {
        return Helper::toArrayOrNot($object, $toArray, $include);
    }
}


if (!function_exists('entityToHashId')) {
    /**
     * @param $entity
     * @param bool $toId
     * @return null
     */
    function entityToHashId($entity, $toId = false)
    {
        return Helper::entityToHashId($entity, $toId);
    }
}

if (!function_exists('entityToArray')) {
    /**
     * @param $entity
     * @param string $arrayType
     * @param $include
     * @return null
     */
    function entityToArray($entity, $arrayType = "default", $include = [])
    {
        return Helper::entityToArray($entity, $arrayType, $include);
    }
}

if (!function_exists('listEntityToListArray')) {
    /**
     * @param $listEntity
     * @param string $arrayType
     * @param $include
     * @return array
     */
    function listEntityToListArray($listEntity, $arrayType = "default", $include = "")
    {
        return Helper::listEntityToListArray($listEntity, $arrayType, $include);
    }
}

if (!function_exists('listEntityToCustomArray')) {
    /**
     * @param $listEntity
     * @param $functionCallback
     * @return array
     */
    function listEntityToCustomArray($listEntity, $functionCallback)
    {
        return Helper::listEntityToCustomArray($listEntity, $functionCallback);
    }
}

if (!function_exists('doctrineTransaction')) {
    /**
     * @param $callback
     * @return mixed
     * @throws Exception
     * @throws ConnectionException
     */
    function doctrineTransaction($callback)
    {
        return Helper::doctrineTransaction($callback);
    }
}

if (!function_exists('arrayToXml')) {
    /**
     * @param $array
     * @param $root
     * @return string
     */
    function arrayToXml($array, $root)
    {
        return Helper::arrayToXml([$root => $array]);
    }
}

if (!function_exists('dateOrEmpty')) {
    /**
     * @param $date
     * @return bool
     */
    function dateOrEmpty($date)
    {
        return Helper::dateOrEmpty($date);
    }
}


if (!function_exists('antiXss')) {
    /**
     * @param $input
     * @return array|mixed
     */
    function antiXss($input)
    {
        return Helper::antiXss($input);
    }
}

if (!function_exists('coreHtmlTagAllow')) {
    /**
     * @param $input
     * @return array|mixed
     */
    function coreHtmlTagAllow($input)
    {
        return Helper::coreHtmlTagAllow($input);
    }
}


if (!function_exists('removeEmoji')) {
    /**
     * @param $input
     * @return array|mixed
     */
    function removeEmoji($input)
    {
        return Helper::removeEmoji($input);
    }
}

if (!function_exists('moveToLast')) {
    /**
     * @param $array
     * @param $num
     * @param string $type
     * @return array
     */
    function moveToLast($array, $num, $type = 'text')
    {
        return Helper::moveToLast($array, $num, $type);
    }
}

if (!function_exists('validateDate')) {
    /**
     * @param $date
     * @param string $format
     * @return bool
     */
    function validateDate($date, $format = "Y-m-d")
    {
        return Helper::validateDate($date, $format);
    }
}

if (!function_exists('getIpLocation')) {
    /**
     * @param $ip
     * @return mixed
     */
    function getIpLocation($ip)
    {
        return Helper::getIpLocation($ip);
    }
}

if (!function_exists('str_random')) {
    /**
     * @param $length
     * @return string
     */
    function str_random($length)
    {
        return Str::random($length);
    }
}
