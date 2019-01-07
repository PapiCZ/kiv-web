<?php

namespace App\Validators;

use Core\Validator\Validator;
use Core\Validator\ValidatorReport;

class ArticleValidator extends Validator
{
    public function rules(): array
    {
        return [
            'title'          => 'required',
            'perex'          => 'required|maxlen:255',
            'image'          => 'required|file_maxsize:2000000',
            'content'        => 'required',
            'document_files' => function ($files) {
                for ($i = 1; $i < count($files['tmp_name']); $i++) {
                    if ($files['size'][$i] > pow(10, 7)) {
                        return new ValidatorReport(false, [vmessage('file_maxsize', vfield('document_files'), 10)]);
                    }
                }

                return new ValidatorReport(true);
            },
        ];

    }
}
