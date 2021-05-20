<?php

namespace App\Http\Controllers;

use App\News;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ChiefChoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if ((auth()->user()->role != 'Manager' && auth()->user()->role != 'Developer') || auth()->user()->cant('chief-choice')) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
            );
            session()->flash('message.time', '15');
            return back();
        }

        $chief_choices = News::chiefChoice();

        $response = [];

        foreach ($chief_choices as $chief_choice) {
            $separator = $chief_choice->secondary_title ? '؛' : '';
            $response[] = [
                'id'    => $chief_choice->news_id,
                'slug'  => $chief_choice->slug,
                'title' => "{$chief_choice->main_title} {$separator} {$chief_choice->secondary_title}",
                'image' => Storage::url($chief_choice->image),
            ];
        }

        return response()->json($response);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'id'   => 'required',
            'slug' => 'required',
        ]);

        if ($validate->fails()) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                $validate->errors()
                         ->first()
            );
            session()->flash('message.time', '15');
            return back();
        }

        if ((auth()->user()->role != 'Manager' && auth()->user()->role != 'Developer') || auth()->user()->cant('chief-choice')) {
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'با عرض پوزش از شما، شما دسترسی لازم برای مشاهده ی این صفحه را ندارید.'
            );
            session()->flash('message.time', '15');
            return back();
        }

        $chief_choices = DB::table('chief_choices')->count();

        if ($chief_choices == 3 && !isset($request->delete_item)){
            session()->flash('message.type', 'error');
            session()->flash(
                'message.content',
                'حداکثر تعداد مجاز برای ستون پیشنهاد سردبیر ۳ مطلب است'
            );
            session()->flash('message.time', '15');
            return back();
        }

        $news = News::where('id',$request->id)->where('slug',$request->slug)->firstOrFail();

        $checkChiefChoiceAlreadySelected = DB::table('chief_choices')->where([
          'slug'    => $news->slug,
          'news_id' => $news->id
        ])->first();

        try {
            if (is_null($checkChiefChoiceAlreadySelected)) {
                DB::beginTransaction();
                DB::table('chief_choices')
                  ->Insert([
                      'news_id'         => $news->id,
                      'slug'            => $news->slug,
                      'main_title'      => $news->main_title,
                      'secondary_title' => $news->secondary_title,
                      'created_at'      => Carbon::now(),
                      'updated_at'      => Carbon::now(),
                  ]);
                if ($request->delete_item) {
                    DB::table('chief_choices')
                      ->where([
                          'slug' => $request->delete_item,
                      ])
                      ->delete();
                }
                DB::commit();
            }
        } catch (Exception $e) {
            DB::rollBack();
            $message = "store chief choice fails because of: {$e->getMessage()} on line : {$e->getLine()} in file: {$e->getFile()}";
            Log::error($message);
            return abort(500, $message);
        }

        session()->flash('message.type', 'success');
        session()->flash(
            'message.content',
            'تغییرات با موفقیت انجام شد!'
        );
        session()->flash('message.time', '15');

        return back();
    }
}
