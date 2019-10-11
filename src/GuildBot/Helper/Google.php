<?php

namespace GuildBot\Helper;

use Google\Spreadsheet\DefaultServiceRequest;
use Google\Spreadsheet\Exception\SpreadsheetNotFoundException;
use Google\Spreadsheet\ServiceRequestFactory;
use Google\Spreadsheet\Spreadsheet;
use Google\Spreadsheet\SpreadsheetService;
use Monolog\Logger;
use Psr\Log\LoggerAwareTrait;

class Google
{
    use LoggerAwareTrait;

    /**
     * @var \Google_Client
     */
    private $client;

    /**
     * @var
     */
    private $sheet;

    /**
     * @var \Google_Service_Sheets
     */
    private $service;

    /**
     * @var \Google_Service_Drive
     */
    private $drive;

    /**
     * @param Logger|null $logger
     *
     * @throws \Google_Exception
     */
    public function connect(Logger $logger = null)
    {
        if ($logger !== null) {
            $this->setLogger($logger);
        }
        $client = new \Google_Client;
        $client->setAuthConfig(__DIR__ . '/../../../config/PanicMode-1ce4444c6676.json');
        $client->addScope([\Google_Service_Drive::DRIVE, \Google_Service_Sheets::DRIVE]);
        $client->setApplicationName("PanicMode Guildbot");
        $client->setLogger($this->logger);
        $client->setScopes(
            [
                'https://www.googleapis.com/auth/spreadsheets',
                'https://www.googleapis.com/auth/drive',
            ]
        );
        $service = new \Google_Service_Sheets($client);
        $drive   = new \Google_Service_Drive($client);

        $this->client  = $client;
        $this->service = $service;
        $this->drive   = $drive;
    }

    /**
     * @param $sheetId
     *
     * @return \Google\Spreadsheet\Spreadsheet
     * @throws SpreadsheetNotFoundException
     */
    public function getSheet($sheetId)
    {

        if ($this->client->isAccessTokenExpired()) {
            $this->client->fetchAccessTokenWithAssertion();
        }
        $this->logger->debug('google client', ['token' => $this->client->getAccessToken(), 'client' => $this->client]);
        $accessToken = $this->client->getAccessToken();

        $serviceRequest = new DefaultServiceRequest($accessToken['access_token']);
        ServiceRequestFactory::setInstance($serviceRequest);

        $spreadsheetService = new SpreadsheetService;
        $spreadsheetFeed    = $spreadsheetService->getSpreadsheetFeed();

        return $spreadsheetFeed->getById('https://spreadsheets.google.com/feeds/spreadsheets/private/full/' . $sheetId);
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param string      $name
     *
     * @return \Google\Spreadsheet\Worksheet
     * @throws \Google\Spreadsheet\Exception\WorksheetNotFoundException
     */
    public function getWorkSheet(Spreadsheet $spreadsheet, $name)
    {
        $worksheetFeed = $spreadsheet->getWorksheetFeed();

        return $worksheetFeed->getByTitle($name);
    }

    /**
     * @param Spreadsheet $spreadsheet
     * @param string      $oldTitle
     * @param string      $newTitle
     *
     * @return \Google\Spreadsheet\Worksheet
     * @throws \Google\Spreadsheet\Exception\WorksheetNotFoundException
     */
    public function renameWorkSheet(Spreadsheet $spreadsheet, $oldTitle, $newTitle)
    {
        $worksheetFeed = $spreadsheet->getWorksheetFeed();

        $worksheet = $worksheetFeed->getByTitle($oldTitle);
        $worksheet->update($newTitle);

        return $worksheet;
    }

    /**
     * @param string $sheetId
     * @param string $newTitle
     *
     * @return Spreadsheet
     * @throws SpreadsheetNotFoundException
     */
    public function renameSheet($sheetId, $newTitle)
    {
        $sheet = $this->service->spreadsheets->get($sheetId);
        $properties = $sheet->getProperties();
        $properties->setTitle($newTitle);

        $requests = [
            // Change the spreadsheet's title.
            new \Google_Service_Sheets_Request([
                'updateSpreadsheetProperties' => [
                    'properties' => [
                        'title' => $newTitle
                    ],
                    'fields' => 'title'
                ]
            ]),
        ];
        $batchUpdateRequest = new \Google_Service_Sheets_BatchUpdateSpreadsheetRequest([
            'requests' => $requests
        ]);

        $this->service->spreadsheets->batchUpdate($sheetId, $batchUpdateRequest);

        return $this->getSheet($sheetId);
    }

    /**
     * @param $title
     *
     * @return \Google_Service_Sheets_Spreadsheet
     */
    public function createSheet($title)
    {
        $properties = new \Google_Service_Sheets_SpreadsheetProperties;
        $properties->setTitle($title);
        $newSheet = new \Google_Service_Sheets_Spreadsheet;
        $newSheet->setProperties($properties);

        $newSheet      = $this->service->spreadsheets->create($newSheet);
        $newPermission = new \Google_Service_Drive_Permission;
        $newPermission->setType('anyone');
        $newPermission->setRole('writer');
        $optParams = ['sendNotificationEmail' => false];
        $this->drive->permissions->create($newSheet->spreadsheetId, $newPermission, $optParams);

        return $newSheet;
    }

    /**
     * @param $originId
     *
     * @return \Google_Service_Sheets_Spreadsheet
     */
    public function copySheet($originId)
    {
        $oldSheet = $this->service->spreadsheets->get($originId);
        $title    = $oldSheet->getProperties()
            ->getTitle();

        $copiedFile = new \Google_Service_Drive_DriveFile;
        $properties = new \Google_Service_Sheets_SpreadsheetProperties;
        $properties->setTitle('Copy of ' . $title);
        $copiedFile->setProperties($properties);
        $copiedFile = $this->drive->files->copy($originId, $copiedFile);

        // delete old file
        $this->drive->files->delete($originId);

        $newPermission = new \Google_Service_Drive_Permission;
        $newPermission->setType('anyone');
        $newPermission->setRole('writer');
        $optParams = ['sendNotificationEmail' => false];
        $this->drive->permissions->create($copiedFile->getId(), $newPermission, $optParams);

        return $this->service->spreadsheets->get($copiedFile->getId());
    }
}