<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleUpdateRequest extends FormRequest
{
    public string $title;

    public string $articleContent;

    public string $authorUuid;

    public function authorize(): bool
    {
        return true;
    }

    protected function passedValidation(): void
    {
        $this->title = $this->string('title');
        $this->articleContent = $this->string('article_content');
        $this->authorUuid = $this->string('author_uuid');
    }

    /**
     * @return array<string, string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'article_content' => 'required|string',
            'author_uuid' => 'required|uuid|exists:bloggers,uuid',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'The title field is required.',
            'title.max' => 'The title may not be greater than 255 characters.',
            'article_content.required' => 'The article content field is required.',
            'author_uuid.required' => 'The author is required.',
            'author_uuid.exists' => 'The selected author is invalid.',
        ];
    }
}
