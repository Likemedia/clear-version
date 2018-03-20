<?php

namespace App\Http\Controllers;

use App\Models\GoodsPhoto;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Input;
use Intervention\Image\Facades\Image;



class FileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function upload() {

        if(Input::file())
        {
            $response = [];
            $key = 0;
            $uploadPath = Input::get('uploadPath');
            if(!is_null(Input::get('gallery-id'))){
                $uploadPath = 'gallery';
                foreach(Input::file() as $singleFile){
                    foreach($singleFile as $file){
                        $extension = $file->getClientOriginalExtension(); // getting image extension
                        $fileName = md5(time()) . rand(11111111,99999999).'.'.$extension; // renameing image
                        switch(strtolower($file->getClientOriginalExtension())){
                            case 'jpg':
                            case 'png':
                            case 'jpeg':{
                                $fileType = 'img';
                                $destinationPath = 'upfiles/'.$uploadPath;
                                break;
                            }
                            default : {
                                $destinationPath = 'upfiles';
                                $fileType = 'other';
                                break;
                            }
                        }

                        $file->move($destinationPath, $fileName);

                        // create folder if this don't exist
                        if(!File::exists($destinationPath.'/s')){
                            File::makeDirectory($destinationPath.'/s');
                        }
                        if(!File::exists($destinationPath.'/m')){
                            File::makeDirectory($destinationPath.'/m');
                        }
                        // end create folder if this don't exist

                        Image::make($destinationPath.'/'.$fileName)->resize(200,150)->save($destinationPath.'/s/'.$fileName);
                        Image::make($destinationPath.'/'.$fileName)->resize(500,450)->save($destinationPath.'/m/'.$fileName);

                        $response['fileName'][$key] = $fileName;
                        $response['fileType'][$key] = $fileType;
                        $response['url'][$key] = '/'.$destinationPath.'/'.$fileName;
                        $key++;

                        $maxPosition = GetMaxPosition('goods_foto');
                        $data = [
                            'goods_item_id' => Input::get('gallery-id'),
                            'img' => $fileName,
                            'position' => $maxPosition + 1,
                            'active' => 1
                        ];

                        GoodsPhoto::create($data);
                    }
                }
                return response()->json([
                    'status' => true,
                    'messages' => ['Save'],
                    'redirect' => urlForLanguage($this->lang()['lang'], 'itemsphoto/'.Input::get('gallery-id'))
                ]);
            }else{
                foreach(Input::file() as $singleFile)
                {
                    $extension = $singleFile->getClientOriginalExtension(); // getting image extension
                    $fileName = md5(time()) . rand(11111111,99999999).'.'.$extension; // renameing image
                    switch(strtolower($singleFile->getClientOriginalExtension())){
                        case 'jpg':
                        case 'png':
                        case 'jpeg':{
                            $fileType = 'img';
                            $destinationPath = 'upfiles/'.$uploadPath;
                            break;
                        }
                        default : {
                            $destinationPath = 'upfiles';
                            $fileType = 'other';
                            break;
                        }
                    }

                    $singleFile->move($destinationPath, $fileName);

                    // create folder if this don't exist
                    if(!File::exists($destinationPath.'/s')){
                        File::makeDirectory($destinationPath.'/s');
                    }
                    if(!File::exists($destinationPath.'/m')){
                        File::makeDirectory($destinationPath.'/m');
                    }
                    // end create folder if this don't exist

                    Image::make($destinationPath.'/'.$fileName)->resize(200,150)->save($destinationPath.'/s/'.$fileName);
                    Image::make($destinationPath.'/'.$fileName)->resize(500,450)->save($destinationPath.'/m/'.$fileName);

                    $response['fileName'][$key] = $fileName;
                    $response['fileType'][$key] = $fileType;
                    $response['url'][$key] = '/'.$destinationPath.'/'.$fileName;
                    $key++;
                }
                return response()->json([
                    $response,
                    'success'=>true
                ]);
            }
        }

    }
}