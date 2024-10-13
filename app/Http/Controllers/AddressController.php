<?php

namespace App\Http\Controllers;

use App\Services\AddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\UploadAddressesRequest;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Controller for handling file uploads, address comparisons, and CSV exports.
 * 
 * @author Abhiram Das
 * @contact abhiramdas2525@gmail.com
 */
class AddressController extends Controller
{
    protected AddressService $addressService;

    public function __construct(AddressService $addressService)
    {
        $this->addressService = $addressService;
    }

    /**
     * Display the upload form.
     * 
     * @author Abhiram Das
     * @return \Illuminate\View\View
     */
    public function uploadForm(): \Illuminate\View\View
    {
        return view('upload');
    }

    /**
     * Handle the upload of client and listing addresses.
     * 
     * @author Abhiram Das
     * @param UploadAddressesRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function uploadAddresses(UploadAddressesRequest $request): \Illuminate\Http\RedirectResponse
    {
        try {
            $clientData = iterator_to_array($this->readFileInLines($request->file('client_data')->getRealPath()));
            $listingData = iterator_to_array($this->readFileInLines($request->file('listing_data')->getRealPath()));

            $this->addressService->uploadAddresses($clientData, $listingData);

            return redirect()->route('compare', ['type' => 'string']);
        } catch (\Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to upload addresses. Please try again.');
        }
    }

    /**
     * Compare addresses using string or fuzzy matching.
     * 
     * @author Abhiram Das
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\RedirectResponse
     */
    public function compare(Request $request): \Illuminate\View\View|\Illuminate\Http\RedirectResponse
    {
        $type = $request->query('type', 'string');

        try {
            $matches = match ($type) {
                'string' => $this->addressService->stringMatch(),
                'fuzzy' => $this->addressService->fuzzyMatch(),
                default => abort(400, "Invalid matching type."),
            };

            session(['matches' => $matches]);
            return view('compare', compact('matches', 'type'));
        } catch (\Exception $e) {
            Log::error('Comparison failed: ' . $e->getMessage());
            return back()->with('error', 'Comparison failed. Please try again.');
        }
    }

    /**
     * Export the comparison results as a CSV file.
     * 
     * @author Abhiram Das
     * @return StreamedResponse|\Illuminate\Http\RedirectResponse
     */
    public function exportCSV(): StreamedResponse|\Illuminate\Http\RedirectResponse
    {
        try {
            $matches = session('matches', []);

            return Response::stream(function () use ($matches) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Client Address', 'Listing Address', 'Match Type', 'Similarity']);

                foreach ($matches as $match) {
                    fputcsv($file, [
                        $match['client_address'],
                        $match['listing_address'],
                        $match['match_type'],
                        $match['similarity'],
                    ]);
                }

                fclose($file);
            }, 200, [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="address_matches.csv"',
            ]);
        } catch (\Exception $e) {
            Log::error('CSV export failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to export CSV. Please try again.');
        }
    }

    /**
     * Read a file line-by-line using a generator to handle large files efficiently.
     * 
     * @author Abhiram Das
     * @param string $path
     * @return \Generator
     * @throws \RuntimeException If the file cannot be opened.
     */
    private function readFileInLines(string $path): \Generator
    {
        $handle = fopen($path, 'r');
        if (!$handle) {
            throw new \RuntimeException("Failed to open file at $path");
        }

        while (($line = fgets($handle)) !== false) {
            yield trim($line);
        }

        fclose($handle);
    }
}
