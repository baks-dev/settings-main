<?php
/*
 *  Copyright 2023.  Baks.dev <admin@baks.dev>
 *
 *  Permission is hereby granted, free of charge, to any person obtaining a copy
 *  of this software and associated documentation files (the "Software"), to deal
 *  in the Software without restriction, including without limitation the rights
 *  to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 *  copies of the Software, and to permit persons to whom the Software is furnished
 *  to do so, subject to the following conditions:
 *
 *  The above copyright notice and this permission notice shall be included in all
 *  copies or substantial portions of the Software.
 *
 *  THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 *  IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 *  FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL THE
 *  AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 *  LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 *  OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 *  THE SOFTWARE.
 */

declare(strict_types=1);

namespace BaksDev\Settings\Main\Event;


use BaksDev\Core\Type\Locale\Locale;
use BaksDev\Products\Category\Repository\AllCategory\AllCategoryInterface;
use BaksDev\Products\Category\Repository\AllCategoryByMenu\AllCategoryByMenuInterface;
use BaksDev\Settings\Main\Repository\SettingsMain\SettingsMainInterface;
use Symfony\Component\Cache\Adapter\ApcuAdapter;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;

//use App\Repository\ConferenceRepository;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Twig\Environment;

final class SettingsHeadersSubscriber implements EventSubscriberInterface
{
    private $twig;

    private SettingsMainInterface $getSettingsMain;

    public function __construct(Environment $twig, SettingsMainInterface $getSettingsMain)
    {
        $this->twig = $twig;
        $this->getSettingsMain = $getSettingsMain;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => ['onRequestEvent', -2],
        ];
    }

    /** Метод применяет настройки заголовков title, description, keywords, tags */
    public function onRequestEvent(RequestEvent $event): void
    {
        $data = $this->getSettingsMain->getSettingsMainAssociative($event->getRequest()->getHost(), $event->getRequest()->getLocale());

        if ($data) {

            $globals = $this->twig->getGlobals();
            $baks_settings = $globals['baks_settings'] ?? [];
            $this->twig->addGlobal('baks_settings', array_replace_recursive($baks_settings, ['headers' => $data]));

        }
    }

}