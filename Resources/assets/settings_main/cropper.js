window.addEventListener('load', function()
{

    var logo = document.getElementById('logo');
    var image = document.getElementById('image');
    var input = document.getElementById('input');
    //var $progress = $('.progress');
    //var $progressBar = $('.progress-bar');
    //var $alert = $('.alert');
    //var $modal = $('#modal');
    var cropper;

    var $modalLogo = document.getElementById('modal');
    var $closeModal = new bootstrap.Modal($modalLogo);


    input.addEventListener('change', function(e)
    {

        /* Делаем AJAX запрос для формы */
        const request = new XMLHttpRequest();

        /*  Получаем из ссылки адрес запроса */
        var urlForm = this.dataset.href;

        /* Указываем метод соединения GET и путь к файлу на сервере */
        request.open('GET', urlForm);
        /* Указываем заголовки для сервера */
        //request.setRequestHeader('Content-Type', 'application/x-www-form-url');
        request.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

        /* Получаем ответ от сервера на запрос*/
        request.addEventListener("readystatechange", function(evemnt)
        {
            /* request.readyState - возвращает текущее состояние объекта XHR(XMLHttpRequest) */
            if(request.readyState === 4 && request.status === 200)
            {
                /* Заполняе модальное окно формой */
                var modl = document.getElementById('modal');
                modl.innerHTML = request.responseText;

                $closeModal.show();

                image = document.getElementById('image');


                var files = e.target.files;
                var done = function(url)
                {
                    input.value = '';
                    image.src = url;
                    //$alert.hide();
                    //$modal.modal('show');
                };

                var reader;
                var file;
                var url;

                if(files && files.length > 0)
                {
                    file = files[0];

                    //if (URL) {
                    //    console.log(URL);

                    //    done(URL.createObjectURL(file));
                    //} else
                    if(FileReader)
                    {

                        reader = new FileReader();

                        reader.addEventListener('load', function(event)
                        {

                            done(reader.result);

                        }, false);

                        reader.readAsDataURL(file);
                    } else if(URL)
                    {
                        done(URL.createObjectURL(file));
                    }
                }

                /* получаем форму по имени */
                let $settings_admin_main_logo = document.forms.namedItem("form");

                if(!$settings_admin_main_logo)
                {
                    return;
                }

                /* Отправка фотмы сохранения */
                $settings_admin_main_logo.addEventListener('submit', function(event)
                {
                    event.preventDefault();

                    var initialAvatarURL;
                    var canvas;

                    /*  Получаем из формы адрес запроса */
                    const $url = this.getAttribute('action');

                    let formData = new FormData($settings_admin_main_logo); // создаём объект, по желанию берём данные формы <form>

                    if(cropper)
                    {

                        canvas = cropper.getCroppedCanvas({
                            minWidth: 20,
                            maxWidth: 200,
                            height: 50,
                        });


                        canvas.toBlob(function(blob)
                        {

                            /* Вставляем новый файл Cropie-аватарку */
                            formData.append('logoCrop', blob);

                            /* Создаём объект класса XMLHttpRequest */
                            //request = new XMLHttpRequest();

                            /* Указываем метод соединения GET и адрес запроса загрузки файла */
                            request.open('POST', $url, true);


                            /* Получаем ответ от сервера на запрос*/
                            request.addEventListener("readystatechange", function(evemnt)
                            {
                                /* request.readyState - возвращает текущее состояние объекта XHR(XMLHttpRequest) */
                                if(request.readyState === 4 && request.status === 200)
                                {
                                    /* Парсим ответ JSON */
                                    const result = JSON.parse(request.responseText);

                                    if(result.status == 200)
                                    {
                                        /* получаем адрес CDN по атрибуту data-img и  */
                                        /*let cdn = $modalСoverBodyCdn.dataset.img.split('img', 1);*/

                                        /* обновляем обложку в профиле */
                                        /*let userCover = document.getElementById('userCover');
                                         userCover.src = cdn+'img/'+$modalСoverBodyCdn.dataset.id+'/'+ result.name+'.png';*/

                                        /* Заолняем скрытое поле названием файла обложки */
                                        /*let hiddenUserCover = document.getElementById('profile_coverProfile');
                                         hiddenUserCover.value = result.name;*/

                                        /* обновляем логотип в форме */
                                        initialAvatarURL = logo.src;
                                        logo.src = canvas.toDataURL();

                                        console.log(result);

                                    } else
                                    {
                                        /* Ошибка о загрузке файла */
                                        alert('Error upload file cover');
                                        /* Выводим текст ошибки в консоль */
                                        console.log(result.message);


                                    }

                                }

                                /* Закрываем модальное окно после отправки файла */
                                $closeModal.hide();

                                return false;
                            });

                            /* Отправляем запрос с файлом */
                            request.send(formData);

                        });

                    }

                });


            }
        });

        /*Выполняем запрос*/
        request.send();


    });


    $modalLogo.addEventListener('shown.bs.modal', function()
    {

        var setWidth = 200;
        var setHeight = 50;

        var minCroppedWidth = setHeight;
        var maxCroppedWidth = setWidth;

        var minCroppedHeight = setHeight;
        var maxCroppedHeight = setHeight;

        cropper = new Cropper(image, {

            data: {
                width: (minCroppedWidth + maxCroppedWidth) / 2,
                height: (minCroppedHeight + maxCroppedHeight) / 2,
            },

            crop: function(event)
            {
                var width = event.detail.width;
                var height = event.detail.height;

                if(
                    width < minCroppedWidth
                    || height < minCroppedHeight
                    || width > maxCroppedWidth
                    || height > maxCroppedHeight
                )
                {
                    cropper.setData({
                        width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
                        height: Math.max(minCroppedHeight, Math.min(maxCroppedHeight, height)),
                    });
                }

                //data.textContent = JSON.stringify(cropper.getData(true));
            },
        });

    });

    /* сбрасываем модальное окно при закрытии */
    $modalLogo.addEventListener('hidden.bs.modal', function()
    {
        cropper.destroy();
        cropper = null;
        $modalLogo.innerHTML = '';
    });

    /*$modal.on('shown.bs.modal', function () {


     }).on('hidden.bs.modal', function () {
     cropper.destroy();
     cropper = null;
     });*/


});
  