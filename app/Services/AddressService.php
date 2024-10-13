<?php

namespace App\Services;

use App\Models\ClientAddress;
use App\Models\ListingAddress;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Service for handling address-related operations such as uploads, string matching, and fuzzy matching.
 * 
 * @author Abhiram Das
 * @contact abhiramdas2525@gmail.com
 */
class AddressService
{
    /**
     * Upload addresses into the database.
     * 
     * @param array $clientData The array of client addresses.
     * @param array $listingData The array of listing addresses.
     * @return void
     */
    public function uploadAddresses(array $clientData, array $listingData): void
    {
        $now = Carbon::now();

        $clientAddresses = array_reduce($clientData, function ($carry, $line) use ($now) {
            $addressData = $this->prepareAddressData($line, $now);
            if ($addressData) {
                $carry[] = $addressData;
            }
            return $carry;
        }, []);

        $listingAddresses = array_reduce($listingData, function ($carry, $line) use ($now) {
            $addressData = $this->prepareAddressData($line, $now);
            if ($addressData) {
                $carry[] = $addressData;
            }
            return $carry;
        }, []);

        if (empty($clientAddresses) && empty($listingAddresses)) {
            return;
        }

        DB::transaction(function () use ($clientAddresses, $listingAddresses) {
            if (!empty($clientAddresses)) {
                ClientAddress::insert($clientAddresses);
            }

            if (!empty($listingAddresses)) {
                ListingAddress::insert($listingAddresses);
            }
        });
    }

    /**
     * Prepare address data for database insertion.
     * 
     * @param string $line The address line to be processed.
     * @param Carbon $now The timestamp for the entry.
     * @return array|null The formatted address data or null if the address is empty.
     */
    private function prepareAddressData(string $line, Carbon $now): ?array
    {
        $address = trim($line);

        return empty($address) ? null : [
            'address' => $address,
            'created_at' => $now,
            'updated_at' => $now,
        ];
    }

    /**
     * Perform exact string matching between client and listing addresses.
     * 
     * @return array The array of matching addresses.
     */
    public function stringMatch(): array
    {
        return DB::table('client_addresses as c')
            ->join('listing_addresses as l', 'c.address', '=', 'l.address')
            ->select(
                'c.address as client_address',
                'l.address as listing_address',
                DB::raw("'Exact Match' as match_type"),
                DB::raw("100 as similarity")
            )
            ->get()
            ->map(fn($item) => (array) $item)
            ->toArray();
    }

    /**
     * Perform fuzzy matching between client and listing addresses using Levenshtein distance.
     * 
     * @return array The array of fuzzy matched addresses with similarity scores.
     */
    public function fuzzyMatch(): array
    {
        $threshold = config('constants.FUZZY_MATCH_THRESHOLD');

        $fuzzyMatches = [];
        $clientAddresses = ClientAddress::pluck('address');
        $listingAddresses = ListingAddress::pluck('address');

        foreach ($clientAddresses as $client) {
            foreach ($listingAddresses as $listing) {
                $levDistance = levenshtein($client, $listing);
                $maxLen = max(strlen($client), strlen($listing));

                if ($maxLen > 0) {
                    $similarity = (1 - $levDistance / $maxLen) * 100;

                    if ($similarity >= $threshold) {
                        $fuzzyMatches[] = [
                            'client_address' => $client,
                            'listing_address' => $listing,
                            'match_type' => 'Fuzzy Match',
                            'similarity' => round($similarity, 2),
                        ];
                    }
                }
            }
        }

        return $fuzzyMatches;
    }
}
