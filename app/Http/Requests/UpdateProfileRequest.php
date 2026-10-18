<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    // Input "skills" berupa teks dipisah koma -> array (dirapikan, tanpa duplikat)
    protected function prepareForValidation(): void
    {
        if (is_string($this->input('skills'))) {
            $items = collect(explode(',', $this->input('skills')))
                ->map(fn ($s) => trim((string) preg_replace('/\s+/u', ' ', $s)))
                ->filter()
                ->unique(fn ($s) => mb_strtolower($s))
                ->values()
                ->all();

            $this->merge(['skills' => $items]);
        }
    }

    public function rules(): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            // Disimpan sebagai "Jurusan · Kampus", jadi wajib berpasangan dan tidak boleh memuat "·"
            'jurusan' => ['nullable', 'string', 'max:100', 'required_with:kampus', 'not_regex:/·/u'],
            'kampus' => ['nullable', 'string', 'max:100', 'required_with:jurusan', 'not_regex:/·/u'],
            'photo' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];

        // Keahlian hanya untuk freelancer
        if ($this->user()->is_freelancer) {
            $rules['skills'] = ['nullable', 'array', 'max:8'];
            $rules['skills.*'] = ['string', 'max:30'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama wajib diisi.',
            'bio.max' => 'Bio maksimal 1000 karakter.',
            'jurusan.required_with' => 'Jurusan wajib diisi kalau kampus diisi.',
            'kampus.required_with' => 'Kampus wajib diisi kalau jurusan diisi.',
            'jurusan.not_regex' => 'Jurusan tidak boleh memuat tanda "·".',
            'kampus.not_regex' => 'Kampus tidak boleh memuat tanda "·".',
            'skills.max' => 'Keahlian maksimal 8 item.',
            'skills.*.max' => 'Setiap keahlian maksimal 30 karakter.',
            'photo.mimes' => 'Foto harus berformat JPG, PNG, atau WEBP.',
            'photo.max' => 'Ukuran foto maksimal 2 MB.',
            'photo.uploaded' => 'Foto gagal diunggah. Pastikan ukurannya maksimal 2 MB.',
        ];
    }
}
