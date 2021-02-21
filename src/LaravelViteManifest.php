<?php

namespace OhSeeSoftware\LaravelViteManifest;

use Illuminate\Support\Facades\App;

class LaravelViteManifest
{
    private $manifest = null;

    public function embed(string $entry): string
    {
        $this->manifest = $this->getManifest();

        return $this->jsImports($entry)
            . $this->jsPreloadImports($entry)
            . $this->cssImports($entry);
    }

    private function getManifest(): array
    {
        $content = file_get_contents(public_path('dist/manifest.json'));
        return json_decode($content, true);
    }

    private function jsImports(string $entry): string
    {
        $url = App::environment('local')
            ? $this->localAsset($entry)
            : $this->productionAsset($entry);

        if (!$url) {
            return '';
        }
        return "<script type=\"module\" crossorigin src=\"$url\"></script>";
    }

    private function jsPreloadImports(string $entry): string
    {
        if (App::environment('local')) {
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
        $manifest = $this->manifest;

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
        if (App::environment('local')) {
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
        $manifest = $this->manifest;

        if (!empty($manifest[$entry]['css'])) {
            foreach ($manifest[$entry]['css'] as $file) {
                $urls[] = asset('/dist/' . $file);
            }
        }
        return $urls;
    }

    private function localAsset(string $entry): string
    {
        return asset($entry);
    }

    private function productionAsset(string $entry): string
    {
        $manifest = $this->manifest;

        if (!isset($manifest[$entry])) {
            return '';
        }

        return asset('/dist/' . $manifest[$entry]['file']);
    }
}
