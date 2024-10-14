<?php

namespace App\Services;

use App\Models\Flyer;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\Storage;

class FlyerService
{

    /**
     * @param $filename
     *
     * @return array
     * @throw \Exception
     */
    public function getFileFromS3AndSaveInDb($filename): array
    {

        $validFlyers = array();
        $invalidFlyers = array();

        $content = Storage::disk('s3')->get($filename);
        $json    = json_decode($content, true);
        foreach ($json['CrawlerRequest']['data']['flyers'] as $flyer) {
            try {
                $flyerObject = new Flyer([
                    'id'          => $flyer['Flyer']['id'],
                    'title'       => $flyer['Flyer']['title'],
                    'url'         => $flyer['Flyer']['url'],
                    'start_date'  => $flyer['Flyer']['start_date'],
                    'end_date'    => $flyer['Flyer']['end_date'],
                    'flyer_url'   => $flyer['Flyer']['flyer_url'],
                    'flyer_files' => json_encode($flyer['Flyer']['flyer_files'])
                ]);
                $flyerObject->save();
                $validFlyers[] = $flyer['Flyer']['id'];
            } catch (UniqueConstraintViolationException $e){
                $invalidFlyers[] = $flyer['Flyer']['id'];
            }
        }

        return [
            'validFlyers' => $validFlyers,
            'invalidFlyers' => $invalidFlyers
        ];
    }

}
