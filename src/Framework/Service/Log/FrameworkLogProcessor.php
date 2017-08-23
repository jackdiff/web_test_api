<?php

namespace Framework\Service\Log;

use Symfony\Component\HttpFoundation\RequestStack;

class FrameworkLogProcessor {
    private $requestStack;
    private $cachedClientIp = null;

    public function __construct(RequestStack $requestStack) {
        $this->requestStack = $requestStack;
    }

    public function __invoke(array $record) {

        // In Webprocessor, extras.ip is proxy server's IP, so we add more extra field to get actual origin IP
        $record['extra']['client_ip'] = $this->cachedClientIp ? $this->cachedClientIp : 'Unknown';

        // Return if we already know client's IP
        if ($record['extra']['client_ip'] !== 'Unknown') {
            return $record;
        }

        // Ensure we have a request (maybe we're in a console command)
        if (!$request = $this->requestStack->getCurrentRequest()) {
            return $record;
        }

        // If we do, get the client's IP, and cache it for later.
        $this->cachedClientIp         = $request->getClientIp();
        $record['extra']['client_ip'] = $this->cachedClientIp;

        return $record;
    }
}
