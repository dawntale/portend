<?php

namespace App\Http\Controllers;

use App\Media;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Resource;
use Intervention\Image\Facades\Image as Intervention;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
	private $photos_path;
    private $thumbnail_path;

    public function __construct(Media $media)
    {
    	$this->middleware('auth');
    	$this->photos_path = base_path('storage/app/public');
        $this->thumbnail_path = base_path('storage/app/public/thumbnail');
    	$this->media = $media;
    }

    public function upload(Request $request)
    {
    	$photos = $request->file('file');

        if (!is_array($photos)) {
            $photos = [$photos];
        }

        if (!is_dir($this->photos_path)) {
            mkdir($this->photos_path, 0777);
        }

        if (!is_dir($this->thumbnail_path)) {
            mkdir($this->thumbnail_path, 0777);
        }

        for ($i = 0; $i < count($photos); $i++) {
            $thumbnail = 250;
            $random_numb = str_random(5);

            $photo = $photos[$i];

            // Slice file name from the extension
            $slice_name = str_before($photo->getClientOriginalName(), $photo->getClientOriginalExtension());

            // Create original name slug from slice_name
            $original_name = str_slug($slice_name, '-');

            // Create save name from original slug, 6 random string and added original extension
            $save_name = $original_name . '-' . $random_numb . '.' . $photo->getClientOriginalExtension();

            $thumbnail_name = asset('storage/app/public/thumbnail/' . $save_name);

            $image = Intervention::make($photo);

            $resolution = $image->width() . ' x ' .$image->height();

            $filesize = $image->filesize();

            Intervention::make($photo)
                ->resize($thumbnail, null, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                })
                ->save($this->thumbnail_path . '/' . $save_name, 70);

            // Move to the directory
            $photo->move($this->photos_path, $save_name);

            $upload = new $this->media;
            $upload->file = $save_name;
            $upload->media_type = 'image';
            $upload->thumbnail = $thumbnail_name;
            $upload->title = $original_name;
            $upload->resolution = $resolution;
            $upload->filesize = $filesize;
            $upload->save();
        }
    }

    public function mediaIndex()
    {
        $images = $this->media->orderBy('created_at', 'desc')->paginate(12);
 
        return Resource::collection($images);
    }

    public function mediaPut(Request $request)
    {
        $image = $this->media->findOrFail($request->id);

        $image->id = $request['id'];
        $image->title = $request['title'];
        $image->caption = $request['caption'];
        $image->alt = $request['alt'];
        $image->description = $request['description'];

        if($image->save()){
            return new Resource($image);
        }
    }

    public function mediaDelete($id)
    {
        $image = $this->media->findOrFail($id);

        $file_path = $this->photos_path . '/' . $image->file;
        $thumbnail_path = $this->thumbnail_path . '/' . $image->file;

        if (file_exists($file_path)) {
            unlink($file_path);
        }

        if (file_exists($thumbnail_path)) {
            unlink($thumbnail_path);
        }

        $image->delete();
    }

    public function mediaImport(Request $request)
    {
        $media = new $this->media;

        $media->file = $request['import_file'];
        $media->media_type = $request['import_type'];
        $media->title = $request['import_title'];
        $media->thumbnail = $request['import_thumbnail'];
        $media->embed_code = $request['import_embed'];

        if($media->save()){
            return new Resource($media);
        }
    }
}
