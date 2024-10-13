<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Request class for validating the uploaded address files.
 * 
 * Ensures that both client and listing data files are provided
 * during the upload process and handles validation errors.
 * 
 * @author Abhiram Das
 * @contact abhiramdas2525@gmail.com
 */
class UploadAddressesRequest extends FormRequest
{
    /**
     * Get the validation rules for the request.
     * 
     * @author Abhiram Das
     * @return array<string, string> The validation rules for the uploaded files.
     */
    public function rules(): array
    {
        return [
            'client_data' => 'required|file',
            'listing_data' => 'required|file',
        ];
    }

    /**
     * Custom error messages for validation failures.
     * 
     * @author Abhiram Das
     * @return array<string, string> The custom error messages for validation rules.
     */
    public function messages(): array
    {
        return [
            'client_data.required' => 'The client data file is required.',
            'listing_data.required' => 'The listing data file is required.',
        ];
    }
}
