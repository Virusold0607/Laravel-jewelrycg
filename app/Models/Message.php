<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $guarded = [];

    protected $fillable = [
        "user_id",
        "conversation_id",
        "message",
        "is_seen"
    ];
    public function seenAll($userId, $conversationId)
    {
        return $this->whereUserId($userId)->whereConversationId($conversationId)->update(["is_seen"=>1]);
    }

    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function conversation()
    {
        return $this->belongsTo(User::class,'conversation_id');
    }

    public function getAll($id)
    {
        return $this->whereUserId($id)->get();
    }
}
