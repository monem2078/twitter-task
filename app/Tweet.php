<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tweet extends Model
{
    protected $fillable = ['body_ar', 'body_en'];

    protected $dates = ['created_at'];

    public static function rules()
    {
        return array(
            'body_ar' => 'required|max:140',
            'body_en' => 'required|max:140',
        );

    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public static function messages()
    {
        return [
            'body_ar.required' => trans('validation.body_ar_required'),
            'body_ar.max' => trans('validation.body_ar_max'),
            'body_en.required' => trans('validation.body_en_required'),
            'body_en.max' => trans('validation.body_en_max')
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
