<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CompetitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return TRUE;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'competition' => 'required|in:khaligefars,premier_league,calcio,bundesliga,laliga,uefa_champions_league,europe_league,afc_champions_league,europe_nations_league,uefa_euro,afc_asian_cup,loshampione,eredivisie,azadegan,world_cup,stars_league',
            'season'      => 'required',
        ];
    }
}
