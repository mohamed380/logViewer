<?php

namespace App\Controllers;

use SplFileObject;

class LogController 
{
    public const PER_PAGE = 2;

    public function index(): string
    {
        try {
            $parts = parse_url($_SERVER['REQUEST_URI']);
            $query = [];
            parse_str($parts['query'], $query);
            
            if(!isset($query['file']) || !file_exists($query['file']))
                throw new \Exception("File ". $query['file'] ." not found", 401);
                
            $data = $this->getData($query['file'], $query['page']??1);

            $response = [
                'success' => true,
                'data' => $data,
                'code' => 200
            ];

            return json_encode($response);

        }catch(\Exception $exception){
            $response = [
                'success' => false,
                'data' => [],
                'message' => $exception->getMessage(),
                'code' => $exception->getCode()
            ];
            return json_encode($response);
        }

    }

    private function getData(string $fileDir, string|int $page): array
    {
        $data = [];
        if($page == 'last') {
            $fileCount = $this->getFileLinesCount($fileDir);
            if($fileCount == 0) return [];
            $lastPageCount = $fileCount % self::PER_PAGE;
            if($lastPageCount == 0) $skip = $fileCount - self::PER_PAGE;
            if($lastPageCount > 0) $skip = $fileCount - $lastPageCount;
            $page = ceil($fileCount / self::PER_PAGE);
        }else {
            $page = $page <= 1 ? 1 : $page;
            $skip = $page == 1 ? 0 : ($page - 1) * self::PER_PAGE;
        }
        
        $fp = new SplFileObject($fileDir);
        $fp->seek($skip);
        $pointer = 0;

        while (!$fp->eof() && $pointer < self::PER_PAGE) {
            $data[] = $fp->fgets();
            $pointer++;
        }

        return ['logs' => $data, 'page' => $page];
    }

    private function getFileLinesCount(string $fileDir)
    {
        $linecount = 0;
        $handleFile = fopen($fileDir, "r");
      
        while(!feof($handleFile)){
          $line = fgets($handleFile, 4096);
          $linecount = $linecount + substr_count($line, PHP_EOL);
        }
      
        fclose($handleFile);
      
        return $linecount;
    }
}
