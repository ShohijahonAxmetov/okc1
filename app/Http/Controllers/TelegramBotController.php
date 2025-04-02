<?php

namespace App\Http\Controllers;

use App\Models\bots\loyalty_card\TelegramFeedback;
use App\Models\bots\loyalty_card\TelegramUser;
use App\Models\bots\loyalty_card\Message;
use App\Services\TelegramBot;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class TelegramBotController extends Controller
{
    protected TelegramBot $bot;

    function __construct(TelegramBot $bot)
    {
        $this->bot = $bot;
    }

    public function index()
    {
        return view('app.integrations.bot.index');
    }

    public function messages()
    {
        $messages = Message::query()
            ->latest()
            ->paginate(24);

        return view('app.integrations.bot.messages.index', compact('messages'));
    }

    public function message(Message $message)
    {
        return view('app.integrations.bot.messages.show', compact('message'));
    }

    public function messageCreate()
    {
        return view('app.integrations.bot.messages.create');
    }

    public function sendMessage(Request $request)
    {
        // dd($this->getAllUsersTelegramIds());
        $request->validate([
            'text' => 'required',
            'files' => 'array',
            'files.*' => 'nullable|file|max:1024'
        ]);

        if ($request->hasFile('files')) {
            $media = [];
            foreach($request->file('files') as $file) {

                $fileName = Str::random(12).'.'.$file->extension();
                $folder = '/upload/bot/'.date('Y-m-d');
                $file->move(public_path($folder), $fileName);

                $media[] = url('/').$folder.'/'.$fileName;
            }
        }

        $successRes = [];
        foreach(['5839440880', '5115247163'] as $userTelegramId) {
            $res = $this->bot->send($userTelegramId, $request->input('text'), isset($request->file('files')[0]) ? $media : []);

            if ($res->first()) $successRes = $res;
        }

        $this->saveMessageLog($request, $successRes->first() !== null ? $successRes : $res, $media ?? []);

        return redirect()->route('integrations.bot.messages')->with(['success' => true]);
    }

    public function realSend(Message $message)
    {
        foreach($this->getAllUsersTelegramIds() as $userTelegramId) {
            $this->bot->send($userTelegramId, $message->text, isset($message->photos[0]) ? $message->photos->pluck('path')->toArray() : [], false);
        }

        return redirect()->route('integrations.bot.messages')->with(['success' => true]);
    }

    public function users()
    {
        $users = TelegramUser::query()
            ->latest()
            ->paginate(24);

        return view('app.integrations.bot.users', compact('users'));
    }

    public function feedback()
    {
        $feedbacks = TelegramFeedback::query()
            ->whereNotNull('phone_number')
            ->whereNotNull('updated_at')
            ->latest()
            ->paginate(24);

        return view('app.integrations.bot.feedback', compact('feedbacks'));
    }

    private function saveMessageLog(Request $request, $res, array $media = [])
    {
        $messageLog = Message::create([
            'text' => $request->input('text'),
            'res' => $res->toArray()
        ]);
        if ($request->hasFile('files')) {
            foreach($media as $photo) {
                $messageLog->photos()->create(['path' => $photo]);
            }
        }
    }

    private function getAllUsersTelegramIds(): array
    {
        $users = TelegramUser::query()
            ->select('telegram_id')
            ->get()
            ->pluck('telegram_id')
            ->toArray();

        return $users;
    }
}
