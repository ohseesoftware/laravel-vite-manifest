<?php

namespace OhSeeSoftware\LaravelViteManifest;

use Illuminate\Support\Facades\App;

class LaravelViteManifest
{
    private $manifestCache = null;

    private $devServerIsRunning = false;
    
    public function __construct(){
        try{
            $this->devServerIsRunning = file_get_contents(public_path('hot')) === 'dev';
        }
        catch(\Exception $e){}
    }
    

    public function embed(string $entry): string
    {
        return $this->client()
            . $this->jsImports($entry)
            . $this->jsPreloadImports($entry)
            . $this->cssImports($entry);
    }

    private function getManifest(): array
    {
        if ($this->manifestCache) {
            return $this->manifestCache;
        }

        $content = file_get_contents(public_path('dist/manifest.json'));
        $this->manifestCache = json_decode($content, true);

        return $this->manifestCache;
    }

    private function client(): string
    {
        if($this->devServerIsRunning){
            $url = 'http://localhost:3000/@vite/client';
            return "<script type=\"module\" src=\"$url\"></script>";
        }
        return "";
    }

    private function jsImports(string $entry): string
    {
        $url = $this->devServerIsRunning
        ? $this->localAsset($entry)
        : $this->productionAsset($entry);

        if (!$url) {
            return '';
        }
        return "<script type=\"module\" src=\"$url\"></script>";
    }

    private function jsPreloadImports(string $entry): string
    {
        if ($this->devServerIsRunning) {
            return '';
        }

        $res = '';
        foreach ($this->preloadUrls($entry) as $url) {
            $res .= "<link rel=\"modulepreload\" href=\"$url\">";
        }
        return $res;
    }

    private function preloadUrls(string $entry): array
    {
        $urls = [];
        $manifest = $this->getManifest();

        if (!empty($manifest[$entry]['imports'])) {
            foreach ($manifest[$entry]['imports'] as $imports) {
                $urls[] = asset('/dist/' . $manifest[$imports]['file']);
            }
        }
        return $urls;
    }

    private function cssImports(string $entry): string
    {
        // not needed on dev, it's inject by Vite
        if ($this->devServerIsRunning) {
            return '';
        }

        $tags = '';
        foreach ($this->cssUrls($entry) as $url) {
            $tags .= "<link rel=\"stylesheet\" href=\"$url\">";
        }
        return $tags;
    }

    private function cssUrls(string $entry): array
    {
        $urls = [];
        $manifest = $this->getManifest();

        if (!empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $file) {
                $urls[] = asset('/dist/' . $file);
            }
        }
        return $urls;
    }

    private function localAsset(string $entry): string
    {
        if($this->devServerIsRunning){
            return "http://localhost:3000/{$entry}";
        }
        return asset($entry);
    }

    private function productionAsset(string $entry): string
    {
        $manifest = $this->getManifest();

        if (!isset($manifest[$entry])) {
            return '';
        }

        return asset('/dist/' . $manifest[$entry]['file']);
    }
}
