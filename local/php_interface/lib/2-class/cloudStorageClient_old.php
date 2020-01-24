<?php
//namespace Asdrubael\S3;
//
//use Aws\S3\S3Client;
//
//class CloudStorageClient{
//
//    private $key = '0CUQ6V8D9GPM27PVW079';
//    private $secret = '9nlanw7fsjOvBuzrlq4n64VoUoEC7W21fWktSnrA';
//    private $endpoint = 'https://io.activecloud.com';
//    private $urlTemplate = 'https://{{bucket}}.io.activecloud.com/{{path}}';
//    private $region = "";
//    private $s3Client = null;
//    private $destination = [
//        'detail picture' => 'dp',
//        'preview picture' => 'pp'
//    ];
//
//    public function __construct()
//    {
//        $this->s3Client = new S3Client([
//            'version' 	=> 'latest',
//            'region'  	=> $this->region,
//            'credentials' => [
//                'key'	=> $this->key,
//                'secret' => $this->secret,
//            ],
//            'endpoint' => $this->endpoint
//        ]);
//    }
//
//    public function getDetailPicture($bucket, array $param = []){
//
//        $param['destination'] = $this->destination['detail picture'];
//        return $this->getFilePath($bucket, $param);
//    }
//
//    public function getPreviewPicture($bucket, array $param = []){
//        $param['destination'] = $this->destination['preview picture'];
//        return $this->getFilePath($bucket, $param);
//    }
//
//    public function getFilePath($bucket, array $param){
//
//        if(empty($param['destination'])){
//            return '';
//        }
//
//        $src = "";
//        $pictures = $this->getAllBucketPictures($bucket);
//        $filterPictures = $this->getFilteredPictures($pictures, [
//            'destination' => $param['destination'],
//            'resolution' => isset($param['resolution']) ? $param['resolution'] : '',
//
//        ]);
//        // pr($pictures, 14);
//        if( is_array($filterPictures) && count($filterPictures) == 1 ){
//
//            $pic = current($filterPictures);
//            $src = $this->getUrlFromTemplate(['bucket' => $bucket, 'path' => $pic['Key']]);
//
//        }
//        elseif( is_array($filterPictures) && count($filterPictures) > 1 ){
//            $pic = $this->getMaxResolutionPic($filterPictures);
//            $src = $this->getUrlFromTemplate(['bucket' => $bucket, 'path' => $pic['Key']]);
//        }
//        else{
//            return "";
//        }
//        return $src;
//    }
//
//    private function getAllBucketPictures($bucket){
//
//        $list = $this->s3Client->listObjects(['Bucket' => $bucket]);
//        return array_column($list->get('Contents'), NULL, 'Key');
//    }
//
//    private function getFilteredPictures(array $pictures, array $filter = []){
//
//        if( !count($pictures) ){
//            return $pictures;
//        }
//
//        if( empty($filter['destination']) ){
//
//            return false;
//        }
//        else{
//            $pictures = array_filter($pictures, function($value) use ($filter){
//
//                $pathInfo = $this->parsePath($value['Key']);
//
//                if( is_array($pathInfo) ){
//
//                    if($pathInfo['destination'] == $filter['destination']){
//
//                        if( !empty($filter['resolution']) ){
//                            if($filter['resolution'] == $pathInfo['resolution']){
//                                return true;
//                            }
//                            else{
//                                return false;
//                            }
//                        }
//                        else{
//                            return true;
//                        }
//                    }
//                    else{
//                        return false;
//                    }
//                }
//                else{
//                    return false;
//                }
//            });
//        }
//
//        return $pictures;
//    }
//
//    private function parsePath($path){
//
//        preg_match("#([\s\S]+)\/([\s\S]+)\/([\s\S]+)#", $path, $matches);
//
//        if(count($matches)){
//
//            return [
//                "destination" => $matches[1],
//                "resolution" => $matches[2],
//                "fileName" => $matches[3]
//            ];
//        }
//
//        return false;
//    }
//
//    private function getUrlFromTemplate(array $data){
//
//        if(empty($data['bucket']) || empty($data['path']) ){
//            return "";
//        }
//
//        return str_replace(['{{bucket}}', '{{path}}'], [$data['bucket'], $data['path']], $this->urlTemplate);
//    }
//
//    private function getMaxResolutionPic(array $pictures){
//
//        $maxKey = false;
//        $maxWidth = 0;
//
//        foreach($pictures as $key => $pic){
//
//            if($maxKey === false){
//
//                $maxKey = $key;
//                $pathInfo = $this->parsePath($pic['Key']);
//                $maxWidth = explode('x', $pathInfo['resolution'])[0];
//            }
//            else{
//
//                $pathInfo = $this->parsePath($pic['Key']);
//                $width = explode('x', $pathInfo['resolution'])[0];
//
//                if($width > $maxWidth){
//                    $maxKey = $key;
//                    $maxWidth = $width;
//                }
//            }
//        }
//
//        return $pictures[$maxKey];
//    }
//
//    private function putObject($bucket, $key, $body, $acl = 'public-read'){
//
//        $result = $this->s3Client->putObject([
//            'Bucket' => $bucket,
//            'Key'	=> $key,
//            'Body'   => $body,//$file,
//            'ACL'    => $acl,
//        ]);
//
//        return $result;
//    }
//}