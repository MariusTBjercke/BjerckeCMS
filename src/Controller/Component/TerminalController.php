<?php

declare(strict_types=1);

namespace App\Controller\Component;

class TerminalController extends AbstractComponent {
    public string $browser;

    public function __construct() {
        $this->template = 'components/_terminal.html.twig';
        $this->date = date('D M j H:i:s');
        $this->browser = $this->getBrowser();
    }

    private function getBrowser(): string {
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $browser = 'Unknown';
        if (false !== stripos($user_agent, 'MSIE')) {
            $browser = 'Internet Explorer';
        } elseif (false !== stripos($user_agent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (false !== stripos($user_agent, 'Chrome')) {
            $browser = 'Chrome';
        } elseif (false !== stripos($user_agent, 'Safari')) {
            $browser = 'Safari';
        } elseif (false !== stripos($user_agent, 'Opera')) {
            $browser = 'Opera';
        } elseif (false !== stripos($user_agent, 'Netscape')) {
            $browser = 'Netscape';
        }
        return $browser;
    }
}
