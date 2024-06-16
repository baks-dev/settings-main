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


document.getElementById('settings_main_form_color').addEventListener('change', function()
{
    console.log(this.value);
    document.getElementById('color').value = '#' + this.value;
});


// image = document.getElementById('crop_image');
//
//
// var setWidth = 200;
// var setHeight = 50;
//
// var minCroppedWidth = setHeight;
// var maxCroppedWidth = setWidth * 2;
//
// var minCroppedHeight = setHeight;
// var maxCroppedHeight = setHeight * 2;
//
// cropper = new Cropper(image, {
//
//     data: {
//         width: (minCroppedWidth + maxCroppedWidth) / 3,
//         height: (minCroppedHeight + maxCroppedHeight) / 3,
//     },
//
//     crop: function (event) {
//         var width = event.detail.width;
//         var height = event.detail.height;
//
//         if (
//             width < minCroppedWidth
//             || height < minCroppedHeight
//             || width > maxCroppedWidth
//             || height > maxCroppedHeight
//         ) {
//             cropper.setData({
//                 width: Math.max(minCroppedWidth, Math.min(maxCroppedWidth, width)),
//                 height: Math.max(minCroppedHeight, Math.min(maxCroppedHeight, height)),
//             });
//         }
//
//         //data.textContent = JSON.stringify(cropper.getData(true));
//     },
// });
