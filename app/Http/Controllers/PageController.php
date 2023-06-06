<?php

namespace App\Http\Controllers;

use App\Models\{
    PageImage,
    Page,
};
use DB;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class PageController extends Controller
{
    protected $languages = ['ru', 'uz'];

    public function contacts()
    {
        $data = Page::where('id', 1)
            ->with('images')
            ->first();

        return view('app.pages.contacts', [
            'data' => $data,
            'languages' => $this->languages
        ]);
    }

    public function about()
    {
        $data = Page::where('id', 2)
            ->with('images')
            ->first();

        return view('app.pages.about', [
            'data' => $data,
            'languages' => $this->languages
        ]);   
    }

    public function delivery()
    {
        $data = Page::where('id', 3)
            ->with('images')
            ->first();

        return view('app.pages.delivery', [
            'data' => $data,
            'languages' => $this->languages
        ]);
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        $page = Page::find($id);

        if(!$page) return back()->with([
            'message' => 'Page not found'
        ]);

        DB::beginTransaction();
        try {
            $data['video'] = null;
            if($request->hasFile('video')) {
                $video = $request->file('video');
                $video_name = Str::random(12).'.'.$video->extension();
                $saved_img = $video->move(public_path('/upload/pages'), $video_name);
                $data['video'] = $video_name;
            }

            $page->update([
                'main_text' => [
                    'ru' => $data['main_text_ru'],
                    'uz' => $data['main_text_uz']
                ],
                'text' => [
                    'ru' => $data['text_ru'],
                    'uz' => $data['text_uz']
                ],
                'meta_title' => [
                    'ru' => $data['meta_title_ru'],
                    'uz' => $data['meta_title_uz']
                ],
                'meta_desc' => [
                    'ru' => $data['meta_desc_ru'],
                    'uz' => $data['meta_desc_uz']
                ],
                'meta_keywords' => [
                    'ru' => $data['meta_keywords_ru'],
                    'uz' => $data['meta_keywords_uz']
                ],
                'video' => $data['video']
            ]);

            $page->images()->delete();
            if(isset($data['dropzone_images'])) {
                foreach($data['dropzone_images'] as $img) {
                    PageImage::create([
                        'page_id' => $page->id,
                        'img' => $img
                    ]);
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();

            return back()->with([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }

        return back()->with([
            'success' => true,
            'message' => 'Успешно сохранен'
        ]);
    }
}
