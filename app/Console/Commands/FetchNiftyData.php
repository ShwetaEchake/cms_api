<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use League\Csv\Reader;
use League\Csv\Writer;
use App\Models\NiftyFifty;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\NiftyFiftyImport;


class FetchNiftyData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'nifty:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        $url = 'https://www.nseindia.com/api/equity-stockIndices?csv=true&index=NIFTY%2050&selectValFormat=crores';

        try {
            // Initialize cURL session
            $ch = curl_init();

            // Set options for first request (homepage hit to get cookies)
            curl_setopt($ch, CURLOPT_URL, 'https://www.nseindia.com');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true); // Get headers including cookies
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36');

            // Execute request to get cookies
            $homepageResponse = curl_exec($ch);
            preg_match_all('/^Set-Cookie:\s*([^;]*)/mi', $homepageResponse, $cookies);
            $cookieString = implode('; ', $cookies[1]);

            // Set options for actual CSV request
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, false); // Don't need headers this time
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept: text/csv',
                'Referer: https://www.nseindia.com/',
                'Cookie: ' . $cookieString,
            ]);

            // Execute the CSV request
            $csvResponse = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            curl_close($ch);

            if ($httpCode !== 200) {
                \Log::error('Failed to fetch NSE CSV via cURL', ['status' => $httpCode]);
                return;
            }


            // Clear old files in the folder (delete all files in 'exports' directory)
            $directory = 'exports/';
            $files = Storage::disk('public')->files($directory);
            foreach ($files as $file) {
                Storage::disk('public')->delete($file);
            }
            // delete previous files


            // Save to file
            $fileName = 'nifty50_' . now()->format('Ymd_His') . '.csv';
            $filePath = 'exports/' . $fileName;

            if (!Storage::disk('public')->exists('exports')) {
                Storage::disk('public')->makeDirectory('exports');
            }

            Storage::disk('public')->put($filePath, $csvResponse);

             \Log::info("NSE CSV downloaded successfully (cURL): {$filePath}");

             $this->processCsvAndSaveToDatabase($filePath);

        } catch (\Exception $e) {
            \Log::error('Exception fetching NSE CSV via cURL', ['error' => $e->getMessage()]);
        }
    }


    public function processCsvAndSaveToDatabase($filePath)
    {
        // Import the data from the CSV file
        Excel::import(new NiftyFiftyImport, storage_path("app/public/{$filePath}"));

        \Log::info("CSV data saved to database successfully.");
    }


}
