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

/* кнопка Добавить номер */
let $addButtonPhone = document.getElementById('phone_addCollection');

/* Блок для новой коллекции КАТЕГОРИИ */
let $blockCollectionPhone = document.getElementById('phone_collection');

if($addButtonPhone)
{

    /* Добавляем новую коллекцию */
    $addButtonPhone.addEventListener('click', function()
    {
        let $emptyCollectionPhone = document.getElementById('empty_phone_collection');
        $emptyCollectionPhone.classList.add('d-none');

        /* получаем прототип коллекции  */
        let newForm = $addButtonPhone.dataset.prototype;
        let index = $addButtonPhone.dataset.index * 1;

        /* Замена '__name__' в HTML-коде прототипа
         вместо этого будет число, основанное на том, сколько коллекций */
        newForm = newForm.replace(/__name__/g, index);

        /* Вставляем новую коллекцию */
        let div = document.createElement('div');
        div.classList.add('item-collection-phone')
        div.innerHTML = newForm;
        $blockCollectionPhone.append(div);

        /* Увеличиваем индекс */
        $addButtonPhone.dataset.index = (index + 1).toString();
        $emptyCollectionPhone.style.setProperty("display", "none", "important");

        /* Удаляем при клике колекцию СЕКЦИЙ */
        div.querySelector('.del-item-phone').addEventListener('click', function()
        {

            this.closest('.item-collection-phone').remove();
            /* Уменьшаем индекс */

            $addButtonPhone.dataset.index = ($addButtonPhone.dataset.index - 1).toString();

            if($addButtonPhone.dataset.index > 0)
            {
                $emptyCollectionPhone.style.setProperty("display", "none", "important");
            } else
            {
                $emptyCollectionPhone.style.display = 'flex';
            }

        });
    });

    /* добавить событие на удаление ко всем существующим элементам формы в блок с классом .del-item */
    let $delItemPhone = $blockCollectionPhone.querySelectorAll('.del-item-phone');

    /* Удаляем при клике колекцию СЕКЦИЙ */
    $delItemPhone.forEach(function(item)
    {

        item.addEventListener('click', function()
        {
            item.closest('.item-collection-phone').remove();

            /* Уменьшаем индекс */
            $addButtonPhone.dataset.index = ($addButtonPhone.dataset.index - 1).toString();

            let $emptyCollectionPhone = document.getElementById('empty_phone_collection');

            if($addButtonPhone.dataset.index > 0)
            {
                $emptyCollectionPhone.style.setProperty("display", "none", "important");
            } else
            {
                $emptyCollectionPhone.style.display = 'flex';
            }
        });
    });

}

