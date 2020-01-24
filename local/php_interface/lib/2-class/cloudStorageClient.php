<?php

namespace Asdrubael\S3;

use Aws\S3\S3Client;
use \Bitrix\Main\Data\Cache;

class CloudStorageClient
{

    private $key = '0CUQ6V8D9GPM27PVW079';
    private $secret = '9nlanw7fsjOvBuzrlq4n64VoUoEC7W21fWktSnrA';
    private $endpoint = 'https://io.activecloud.com';
    private $urlTemplate = 'https://{{bucket}}.io.activecloud.com/{{path}}';
    private $region = "";
    private $s3Client = null;
    private $cacheTime = 3600;
//    private $destination = [
//        'detail picture' => 'dp',
//        'preview picture' => 'pp'
//    ];

    public function __construct()
    {
        \Bitrix\Main\Loader::includeModule('kocmo.exchange');

        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ],
            'endpoint' => $this->endpoint
        ]);
    }

    public function getFilePath($bucket, array $param = [])
    {

        if (empty($param['id'])) {
            return '';
        }

//        if(empty($param['file_name'])){
//            $param['file_name'] = 'DETAIL_PICTURE';
//        }

        if(isset($param['strict'])){
            $strict = boolval($param['strict']);
        }
        else{
            $strict = true;
        }

        $src = "";
        $pictures = $this->getAllBucketPictures($bucket, $param['id']);

        $filterPictures = $this->getFilteredPictures($pictures, [
            'id' => $param['id'],
            'resolution' => isset($param['resolution']) ? $param['resolution'] : '',
            'strict' => $strict,
            'file_name' => $param['file_name']
        ]);

        //pr($filterPictures, 14);
        if (is_array($filterPictures) && count($filterPictures) == 1) {

            $pic = current($filterPictures);
            $src = $this->getUrlFromTemplate(['bucket' => $bucket, 'path' => $pic['Key']]);

        } elseif (is_array($filterPictures) && count($filterPictures) > 1) {
            $pic = $this->getMaxResolutionPic($filterPictures);
            $src = $this->getUrlFromTemplate(['bucket' => $bucket, 'path' => $pic['Key']]);
        } else {
            return $src;
        }

        return $src;
    }

    private function getAllBucketPictures($bucket, $prefix)
    {
        $cache = Cache::createInstance();

        if ($cache->initCache($this->cacheTime, $bucket . $prefix)) {

            $content = $cache->getVars();
            //pr($content, 14);
            return $content;
        }
        elseif ($cache->startDataCache()) {

            $list = $this->s3Client->listObjects(['Bucket' => $bucket, "Prefix" => $prefix]);
            $content = $list->get('Contents');

            if( count($content) ) {

                $pics = [];
                foreach($content as $pic){
                    $pics[$pic['Key']] = ['Key' => $pic['Key'], "Size" => $pic['Size']];
                }
                $content = $pics;//array_column($content, NULL, 'Key');
                $cache->endDataCache($content);

                return $content;
            }
            else{
                $cache->abortDataCache();
            }

        }
        return [];
    }

    private function getFilteredPictures(array $pictures, array $filter = [])
    {

        if (!count($pictures)) {
            return $pictures;
        }

        if( (empty($filter["width"]) || empty($filter["height"])) && !empty($filter['resolution']) ){
            preg_match("#(\d+)x(\d+)#", $filter['resolution'], $resolutionMatch);

            if(empty($filter["width"]) && !empty($resolutionMatch[1]) ){
                $filter["width"] = $resolutionMatch[1];
            }
            if(empty($filter["height"]) && !empty($resolutionMatch[2]) ){
                $filter["height"] = $resolutionMatch[2];
            }
        }

        $pictures = array_filter($pictures, function ($value) use ($filter) {

            $pathInfo = $this->parsePath($value['Key']);

            if (is_array($pathInfo)) {

                if ($pathInfo['id'] == $filter['id']) {

                    if($filter['strict']) {
                        if (!empty($filter['resolution'])) {
                            if ($filter['resolution'] == $pathInfo['resolution']) {
                                return true;
                            } else {
                                return false;
                            }
                        } else {
                            return true;
                        }
                    }
                    else{

                        if( empty($filter["width"]) || empty($filter["height"]) ){
                            return false;
                        }
                        if($pathInfo["width"] > $pathInfo["height"]){
                            $side = "width";
                        }
                        elseif($pathInfo["width"] == $pathInfo["height"]){
                            $side = "both";
                        }
                        else{
                            $side = "height";
                        }

                        if (!empty($filter[$side])) {

                            if ($filter[$side] == $pathInfo[$side]) {

                                return true;
                            } else {
                                return false;
                            }
                        }
                        elseif($side == 'both'){

                            if ( ($filter["width"] == $pathInfo["width"]) || ($filter["height"] == $pathInfo["height"]) ) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                        else {
                            return true;
                        }
                    }
                } else {
                    return false;
                }
            } else {
                return false;
            }
        });

        if (count($pictures) && !empty($filter['file_name']) ) {
            $pictures = array_filter($pictures, function ($value) use ($filter){

                $pathInfo = $this->parsePath($value['Key']);

                if($pathInfo['fileName'] === $filter['file_name']){
                    return true;
                }
                return false;
            });
        }
        //pr($pictures, 14);
        return $pictures;
    }

    private function parsePath($path)
    {

        preg_match("#([\s\S]+)\/([\s\S]+)\/([\s\S]+)#", $path, $matches);

        if (count($matches)) {
            preg_match("#(\d+)x(\d+)#", $path, $resolutionMatch);

            return [
                "id" => $matches[1],
                "resolution" => $matches[2],
                "fileName" => $matches[3],
                "width" => $resolutionMatch[1],
                "height" => $resolutionMatch[2],
            ];
        }

        return false;
    }

    private function getUrlFromTemplate(array $data)
    {

        if (empty($data['bucket']) || empty($data['path'])) {
            return "";
        }

        return str_replace(['{{bucket}}', '{{path}}'], [$data['bucket'], $data['path']], $this->urlTemplate);
    }

    private function getMaxResolutionPic(array $pictures)
    {

        $maxKey = false;
        $maxWidth = 0;

        foreach ($pictures as $key => $pic) {

            if ($maxKey === false) {

                $maxKey = $key;
                $pathInfo = $this->parsePath($pic['Key']);
                $maxWidth = explode('x', $pathInfo['resolution'])[0];
            } else {

                $pathInfo = $this->parsePath($pic['Key']);
                $width = explode('x', $pathInfo['resolution'])[0];

                if ($width > $maxWidth) {
                    $maxKey = $key;
                    $maxWidth = $width;
                }
            }
        }

        return $pictures[$maxKey];
    }

    public function putObject($bucket, $key, $body, $acl = 'public-read')
    {
        $result = $this->s3Client->putObject([
            'Bucket' => $bucket,
            'Key' => $key,
            'Body' => $body,//$file,
            'ACL' => $acl,
            'ContentType' => 'image/png',
            //'ContentLength' => $content['length'],
        ]);

        return $result;
    }

    public function deleteObjects($bucket, $prefix, array $keys = []){

        $pictures = $this->getAllBucketPictures($bucket, $prefix);

        if(count($pictures)){

            $objects = [];

            foreach($pictures as $pic){

                $fileName = $this->getFileName($pic['Key']);

                if(is_string($fileName) && count($keys) && in_array($fileName, $keys)){
                    $objects[] = ["Key" => $pic['Key']];
                }
            }

            $this->s3Client->deleteObjects([
                "Bucket" => $bucket,
                "Delete" => [
                    'Objects' => $objects
                ]
            ]);
        }
    }

    private function getFileName($key){

        if(!is_string($key)){
            return false;
        }

        preg_match("#\/([^\/]+)$#", $key, $matches);

        if(isset($matches[1])){
            return $matches[1];
        }
        return false;
    }
}