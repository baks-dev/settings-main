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


/* кнопка Добавить ссылку */
let $addButtonSocial = document.getElementById('social_addCollection');

/* Блок для новой коллекции КАТЕГОРИИ */
let $blockCollectionSocial = document.getElementById('social_collection');

if($addButtonSocial)
{

    /* Добавляем новую коллекцию */
    $addButtonSocial.addEventListener('click', function()
    {
        let $emptyCollectionSocial = document.getElementById('empty_social_collection');
        $emptyCollectionSocial.classList.add('d-none');

        /* получаем прототип коллекции  */
        let newForm = $addButtonSocial.dataset.prototype;
        let index = $addButtonSocial.dataset.index * 1;

        /* Замена '__name__' в HTML-коде прототипа
         вместо этого будет число, основанное на том, сколько коллекций */
        newForm = newForm.replace(/__name__/g, index);

        /* Вставляем новую коллекцию */
        let div = document.createElement('div');
        div.classList.add('item-collection-social')
        div.innerHTML = newForm;
        $blockCollectionSocial.append(div);

        /* Увеличиваем индекс */
        $addButtonSocial.dataset.index = (index + 1).toString();
        $emptyCollectionSocial.style.setProperty("display", "none", "important");

        /* Удаляем при клике колекцию СЕКЦИЙ */
        div.querySelector('.del-item-social').addEventListener('click', function()
        {
            this.closest('.item-collection-social').remove();
            /* Уменьшаем индекс */
            $addButtonSocial.dataset.index = ($addButtonSocial.dataset.index - 1).toString();

            if($addButtonSocial.dataset.index > 0)
            {
                $emptyCollectionSocial.style.setProperty("display", "none", "important");
            } else
            {
                $emptyCollectionSocial.style.display = 'flex';
            }
        });
    });

    /* добавить событие на удаление ко всем существующим элементам формы в блок с классом .del-item */
    let $delItemSocial = $blockCollectionSocial.querySelectorAll('.del-item-social');

    /* Удаляем при клике колекцию СЕКЦИЙ */
    $delItemSocial.forEach(function(item)
    {
        item.addEventListener('click', function()
        {
            item.closest('.item-collection-social').remove();

            /* Уменьшаем индекс */
            let index = $addButtonSocial.dataset.index * 1;
            $addButtonSocial.dataset.index = (index - 1).toString();

            let $emptyCollectionSocial = document.getElementById('empty_social_collection');

            if($addButtonSocial.dataset.index > 0)
            {
                $emptyCollectionSocial.style.setProperty("display", "none", "important");
            } else
            {
                $emptyCollectionSocial.style.display = 'flex';
            }
        });
    });

}
