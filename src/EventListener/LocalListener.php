<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class LocaleListener implements EventSubscriberInterface
{
    private $availableLocales;

    public function __construct(array $availableLocales)
    {
        $this->availableLocales = $availableLocales;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();
        $acceptLanguage = $request->headers->get('Accept-Language');

        if ($acceptLanguage) {
            $preferredLocale = $this->getPreferredLocale($acceptLanguage);
            $request->setLocale($preferredLocale ?: 'en');
        } else {
            $request->setLocale('en');
        }
    }

    private function getPreferredLocale($acceptLanguage)
    {
        $languages = explode(',', $acceptLanguage);

        foreach ($languages as $language) {
            $languageCode = substr($language, 0, 2);
            if (in_array($languageCode, $this->availableLocales)) {
                return $languageCode;
            }
        }

        return null;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}
