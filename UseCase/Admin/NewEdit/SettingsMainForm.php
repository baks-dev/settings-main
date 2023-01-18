<?php
/*
 * Copyright (c) 2022.  Baks.dev <admin@baks.dev>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *  http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit;

use App\Module\Dictionary\Color\Type\Color;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\Phone\SettingsMainPhoneForm;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\Seo\SettingsMainSeoForm;
use BaksDev\Settings\Main\UseCase\Admin\NewEdit\Social\SettingsMainSocialForm;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SettingsMainForm extends AbstractType
{
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        
        $builder->add('color', ChoiceType::class, [
          'required' => false,
          'placeholder' => 'placeholder.color',
          'choices' => Color::cases(),
          'choice_value' => function (?Color $color)
          {
              return $color?->getValue();
          },
          'choice_label' => function (?Color $color)
          {
              return $color?->getValue();
          },
          'translation_domain' => 'dictionary.color'
        ]);
        
		
        $builder->add('seo', CollectionType::class, [
          'entry_type' => SettingsMainSeoForm::class,
          'entry_options' => ['label' => false],
          'label' => false,
          'allow_add' => true,
        ]);
        
        $builder->add('phone', CollectionType::class, [
          'entry_type' => SettingsMainPhoneForm::class,
          'entry_options' => ['label' => false],
          'label' => false,
          'allow_add' => true,
          'allow_delete' => true,
        ]);
        
        $builder->add('social', CollectionType::class, [
          'entry_type' => SettingsMainSocialForm::class,
          'entry_options' => ['label' => false],
          'label' => false,
          'allow_add' => true,
          'allow_delete' => true,
        ]);
        
        /* Сохранить ******************************************************/
        $builder->add
        (
          'settings_main',
          SubmitType::class,
          [
            'label' => 'btn.save',
            'label_html' => true,
            'attr' => ['class' => 'btn-primary'],
            'translation_domain' => 'messages'
          ]);
        
    }
    
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults
        (
          [
            'data_class' => SettingsMainDTO::class,
            'method' => 'POST',
          ]);
    }
    
}
