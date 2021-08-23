<?php

namespace App\Services\Configs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

/**
 * Trait TypeTrait.
 *
 * @package App\Services\Configs
 */
trait TypeTrait
{
    protected string $fileName;

    /**
     * @param string $type
     *
     * @throws \Throwable
     * @return string
     */
    private function getFileFromType(string $type): string
    {
        $this->validateType($type);

        $this->fileName = match ($type) {
            'btp' => 'btp.json',
            'iteba' => 'iteba.json',
            'hrms' => 'hrms.json',
            'tracing' => 'tracing.json',
            default => null,
        };

        return $this->fileName;
    }

    /**
     * @param string $type
     *
     * @throws \Throwable
     */
    private function validateType(string $type)
    {
        $validator = Validator::make(['type' => $type], [
            'type' => ['required', Rule::in('hrms', 'btp', 'iteba', 'tracing')],
        ], [
            'type.in' => 'Invalid Type Detected!',
        ], [
            'type' => 'Type',
        ]);

        throw_if($validator->fails(), new ValidationException($validator));
    }
}
