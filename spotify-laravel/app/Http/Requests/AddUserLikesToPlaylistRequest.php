<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddUserLikesToPlaylistRequest extends FormRequest
{
    public function rules()
    {
        return [
            'likes' => 'required|array',
            'likes.*.id' => 'required|string',
        ];
    }
}
