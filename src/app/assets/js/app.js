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
      let btn = this; // 'let' を使用
      this.classList.add('--load');
      this.disabled = true;
      if (this.type == 'submit') {
        this.closest("form").submit();
      }
      // 一定時間後に --load クラスを削除する
      setTimeout(function() {
        btn.classList.remove('--load');
        btn.disabled = false;
      }, 3000);
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

    /**
   * ツールチップの制御
   * 
   * 
   * 
   * 
   * 
   */
    var tooltipBtns = document.querySelectorAll('.js-tooltip');
    for (var _i11 = 0; _i11 < tooltipBtns.length; _i11++) {
      var tooltipBtn = tooltipBtns[_i11];
      tooltipBtn.addEventListener('mouseover', function () {
        this.classList.add('is-lock');
        var text = this.querySelector('.text');
        text.style.display = 'block';
        var rect = text.getBoundingClientRect();
        var right = rect.right + 20;
        var windowWidth = window.innerWidth;
        if (right > windowWidth) {
          this.classList.add('is-fixed');
        }
      });
      tooltipBtn.addEventListener('mouseout', function () {
        this.classList.remove('is-lock');
        this.classList.remove('is-fixed');
        var text = this.querySelector('.text');
        text.style.display = 'none';
      });
    }

    /**
     * テーブルソートの定義
     * 
     * 
     * 
     * 
     * 
     */

    var sortTables = document.querySelectorAll('.js-sortable');

    for (var _i12 = 0; _i12 < sortTables.length; _i12++) {
        var table = sortTables[_i12];
    
        var draggedRow = null;
    
        table.addEventListener('dragstart', (event) => {
            const target = event.target;

            const focusedElement = document.activeElement;

            if (target.contains(focusedElement) && 
                (focusedElement.tagName === 'INPUT' || focusedElement.tagName === 'TEXTAREA')) {
                event.preventDefault();
                return;
            }

            draggedRow = event.target.closest('tr');
            if (draggedRow) {
                draggedRow.classList.add('dragging');
            }
        });
    
        table.addEventListener('dragend', (event) => {
            if (draggedRow) {
                draggedRow.classList.remove('dragging');
                draggedRow = null;
            }
        });
    
        table.addEventListener('dragover', (event) => {
            event.preventDefault();
            const targetRow = event.target.closest('tr');
            if (targetRow && targetRow !== draggedRow) {
                targetRow.classList.add('drag-over');
            }
        });
    
        table.addEventListener('dragleave', (event) => {
            const targetRow = event.target.closest('tr');
            if (targetRow) {
                targetRow.classList.remove('drag-over');
            }
        });
    
        table.addEventListener('drop', (event) => {
            event.preventDefault();
            const targetRow = event.target.closest('tr');
            if (targetRow && targetRow !== draggedRow) {
                targetRow.classList.remove('drag-over');
                const tbody = table.querySelector('tbody');
                if (tbody) {
                    tbody.insertBefore(draggedRow, targetRow.nextSibling);
                }
            }
        });
    }
});