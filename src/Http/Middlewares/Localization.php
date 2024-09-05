<?php

namespace Codivores\LaravelModularApi\Http\Middlewares;

use Closure;
use Codivores\LaravelModularApi\Exceptions\LocalizationLocaleUnsupportedException;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (config('modular-api.features.localization.enabled')) {
            $locale = $this->locale($this->fromHeader($request));

            app()->setLocale($locale);

            // Set header in Response.
            $response = $next($request);
            $response->headers->set(config('modular-api.features.localization.request_header'), $locale);

            return $response;
        }

        return $next($request);
    }

    private function fromHeader(Request $request): string
    {
        return $request->hasHeader(config('modular-api.features.localization.request_header'))
            ? $request->header(config('modular-api.features.localization.request_header'))
            : config('app.locale');
    }

    private function locale(string $requestHeaderLocale): string
    {
        $localeList = config('modular-api.features.localization.locales');

        if (! is_array($localeList)) {
            $localeList = explode(',', $localeList);
        }

        $requestLocaleList = explode(',', $requestHeaderLocale);
        foreach ($requestLocaleList as $requestLocale) {
            $locale = explode(';', $requestLocale)[0];

            if (in_array($locale, $localeList)) {
                return $locale;
            }

            if (Str::contains($locale, '-')) {
                $baseLocale = explode('-', $locale)[0];
                if (in_array($baseLocale, $localeList)) {
                    return $baseLocale;
                }
            }
        }

        throw new LocalizationLocaleUnsupportedException;
    }
}
