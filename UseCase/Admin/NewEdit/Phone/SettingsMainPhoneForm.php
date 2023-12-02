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

namespace BaksDev\Settings\Main\UseCase\Admin\NewEdit\Phone;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class SettingsMainPhoneForm extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options): void
    {
		/* TextType */
		//$builder->add('name', TextType::class);
		
		$builder->add('icon', ChoiceType::class, [
			'required' => false,
			'choices' => [
				'МТС' => 'МТС',
				'Beeline' => 'Beeline',
				'Megafon' => 'megafon',
				'Tele2' => 'tele2',
				'Yota' => 'yota',
			],
		]);
		
		$builder->add('phone', TelType::class, []);
		$builder->add('title', TextType::class, ['required' => false, 'label' => false]);
		
		$builder->add
		(
			'delete',
			ButtonType::class,
			[
				'label_html' => true,
				'attr' =>
					['class' => 'btn btn-danger del-item-phone'],
			]
		);
		
	}
	
	
	public function configureOptions(OptionsResolver $resolver): void
    {
		$resolver->setDefaults
		(
			[
				'data_class' => SettingsMainPhoneDTO::class,
			]
		);
	}
	
}
