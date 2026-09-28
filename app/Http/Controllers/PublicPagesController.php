<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PublicPagesController extends Controller
{
    public function banks(Request $request)
    {
        $divisions = config('bank_locations');
        $data = $request->validate(['division' => ['nullable', Rule::in(array_keys($divisions))]]);
        $selected = $data['division'] ?? '';
        $banks = Hospital::where('status', 'approved')->orderBy('hospital_name')->get();
        // The existing city field stores the district. Include common legacy spellings.
        $normalize = static fn ($text) => mb_strtolower(trim(str_replace(['’', "'"], '', $text ?? '')));
        $aliases = ['chittagong'=>'chattogram','comilla'=>'cumilla','barisal'=>'barishal','jessore'=>'jashore','bogra'=>'bogura','chapainawabganj'=>'nawabganj'];
        foreach ($banks as $bank) {
            $city = $normalize($bank->city);
            $city = $aliases[$city] ?? $city;
            $bank->division_label = null;
            foreach ($divisions as $division => $districts) {
                if (in_array($city, array_map($normalize, $districts), true)) {
                    $bank->division_label = $division;
                    break;
                }
            }
        }
        if ($selected !== '') $banks = $banks->where('division_label', $selected);
        return view('pages.banks', compact('banks', 'divisions', 'selected'));
    }
}
