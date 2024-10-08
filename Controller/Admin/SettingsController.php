<?php
/*
 *  Copyright 2022.  Baks.dev <admin@baks.dev>
 *
 *  Licensed under the Apache License, Version 2.0 (the "License");
 *  you may not use this file except in compliance with the License.
 *  You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 *  Unless required by applicable law or agreed to in writing, software
 *  distributed under the License is distributed on an "AS IS" BASIS,
 *  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *  See the License for the specific language governing permissions and
 *   limitations under the License.
 *
 */

namespace BaksDev\Settings\Main\Controller\Admin;

use BaksDev\Core\Controller\AbstractController;
use BaksDev\Core\Listeners\Event\Security\RoleSecurity;
use BaksDev\Settings\Main\Entity as EntitySettingsMain;
use BaksDev\Settings\Main\Repository\SettingsMainUpdate\SettingsMainUpdateInterface;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\SettingsMainDTO;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\SettingsMainForm;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\SettingsMainHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Routing\Attribute\Route;

#[AsController]
#[RoleSecurity('ROLE_SETTINGS_MAIN')]
final class SettingsController extends AbstractController
{

    #[Route('/admin/settings/main', name: 'admin.settings', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        SettingsMainHandler $settingsMainHandler,
        SettingsMainUpdateInterface $SettingsMainUpdate,
    ): Response
    {
        $SettingsMainDTO = new SettingsMainDTO();

        $Event = $SettingsMainUpdate->get();
        if($Event)
        {
            $Event->getDto($SettingsMainDTO);
        }

        $form = $this->createForm(SettingsMainForm::class, $SettingsMainDTO);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid() && $form->has('settings_main'))
        {
            $this->refreshTokenForm($form);

            $SettingsMain = $settingsMainHandler->handle($SettingsMainDTO);

            if($SettingsMain instanceof EntitySettingsMain\SettingsMain)
            {
                $this->addFlash('admin.page', 'admin.success.update', 'settings.main');

                return $this->redirectToRoute('settings-main:admin.settings');
            }

            $this->addFlash('danger', 'admin.danger.update', 'settings.main', $SettingsMain);

            return $this->redirectToRoute('settings-main:admin.settings');
        }

        return $this->render(
            [
                'data' => null,
                'form' => $form->createView(),
            ],
        );
    }
}
