"use strict";

window.addEventListener('DOMContentLoaded', function () {
  /**
   * ボタンアクション
   * 
   * - type=[submit] の場合は、submit() を実行
   * 
   * 
   * 
   */
  var actionBtn = document.querySelectorAll('.js-btnAction');
  for (var i = 0; i < actionBtn.length; i++) {
    actionBtn[i].addEventListener('click', function (e) {
      this.classList.add('--load');
      this.disabled = true;
      if (this.type == 'submit') {
        this.closest("form").submit();
      }
    });
  }
  var deleteBtn = document.querySelectorAll('.js-btnDelete');
  for (var _i = 0; _i < deleteBtn.length; _i++) {
    deleteBtn[_i].addEventListener('click', function (e) {
      this.classList.add('--load');
      this.disabled = true;
      if (this.type == 'submit') {
        e.stopPropagation();
        e.preventDefault();
        var result = window.confirm('データを削除しますか？');
        if (result) {
          this.closest("form").submit();
        } else {
          this.disabled = false;
          this.classList.remove('--load');
          return false;
        }
      }
    });
  }
  var messageBtn = document.querySelectorAll('.js-message');
  for (var _i2 = 0; _i2 < messageBtn.length; _i2++) {
    messageBtn[_i2].firstElementChild.addEventListener('click', function (e) {
      var messageBox = this.closest(".js-message");
      if (messageBox) {
        messageBox.remove();
      }
    });
  }

  /**
   * ファイル参照プレビュー
   * 
   * 
   * 
   * 
   * 
   */
  var filePreview = document.querySelectorAll('.js-filePreview');
  for (var _i3 = 0; _i3 < filePreview.length; _i3++) {
    filePreview[_i3].addEventListener('change', function (e) {
      var file = e.target.files[0];
      var fileNameObj = this.closest(".c-fileInpu").querySelector('.c-fileInpu__preview');
      console.log(fileNameObj);
      fileNameObj.textContent = file.name;
    });
  }
});