<?php

class WebsiteKernel extends \Sprungbrett\App\Kernel
{
    protected function detectContext(): string
    {
        return 'website';
    }

    protected function getContext(): string
    {
        return $this->context;
    }

    protected function setContext(string $context): void
    {
        $this->context = $context;
    }
}
