/**
 * Implements frontend editing.
 */

(function (Drupal, once) {

  // Global variables
  let sidebarWidth = 30;
  let sidebarFullWidth = 70;

  // Callback for click function on an editable element.
  const editingClick = function (e) {
    e.preventDefault();
    // Setup container.
    // Frontend-editing sidebar and full widths.
    const wideClassWidth = sidebarFullWidth + '%';
    const sidebarClassWidth = sidebarWidth + '%';

    let editContainer = document.getElementById('editing-container');
    if (!editContainer) {
      editContainer = document.createElement('div');
      editContainer.id = 'editing-container';
      editContainer.classList.add('editing-container', 'editing-container--loading');
      document.body.append(editContainer);
      editContainer.style.width = sidebarClassWidth;
    }
    else {
      editContainer.innerHTML = '';
    }
    // Setup width toggle button.
    const editWideClass = 'editing-container--wide';
    let widthToggle = document.createElement('button');
    widthToggle.type = 'button';
    widthToggle.className = 'editing-container__toggle';
    widthToggle.addEventListener('click', function (e) {
      if (editContainer.classList.contains(editWideClass)) {
        editContainer.classList.remove(editWideClass);
        editContainer.style.width = sidebarClassWidth;
      }
      else {
        editContainer.classList.add(editWideClass);
        editContainer.style.width = wideClassWidth;
      }
    });
    // Setup close button.
    let editClose = document.createElement('button');
    editClose.className = 'editing-container__close';
    editClose.type = 'button';
    editClose.addEventListener('click', function (e) {
      editContainer.remove();
    });

    // Populate container.
    editContainer.appendChild(widthToggle);
    editContainer.appendChild(editClose);
    // Load editing iFrame and append.
    const iframe = document.createElement('iframe');
    iframe.onload = function () {
      editContainer.classList.remove('editing-container--loading');
    };
    editContainer.appendChild(iframe);
    iframe.src = e.target.href;
  }

  /**
   * Ajax command feReloadPage.
   *
   * Reloads the page in case ajax content update failed.
   *
   * @param ajax
   * @param response
   * @param status
   */
  Drupal.AjaxCommands.prototype.feReloadPage = function (ajax, response, status) {
    if (status === 'success') {
      // Reload the page.
      window.location.reload();
    }
  }

  /**
   * Add callback for sidebar tray and add listeners to actions.
   */
  Drupal.behaviors.frontendEditing = {
    attach: function (context, settings) {
      sidebarFullWidth = settings.frontend_editing.full_width;
      sidebarWidth = settings.frontend_editing.sidebar_width;
      const actionsElements = once('frontend-editing-processed', '.frontend-editing-actions', context);
      // Find all elements with frontend editing action buttons and attach event listener.
      actionsElements.forEach(function (actionsElement) {

        actionsElement.addEventListener('mouseover', function () {
          const container = actionsElement.closest('.frontend-editing');
          if (container) {
            container.classList.add('frontend-editing--outline');
          }
        });

        actionsElement.addEventListener('mouseout', function () {
          const container = actionsElement.closest('.frontend-editing');
          if (container) {
            container.classList.remove('frontend-editing--outline');
          }
        });

        actionsElement.childNodes.forEach(function (editingElement, i) {
          if (editingElement.classList.contains('frontend-editing-open-sidebar')) {
            // Add selector for auto update content if exists.
            if (actionsElement.dataset.entityType !== 'paragraph') {
              const fieldWrapper = actionsElement.closest('.frontend-editing-field-wrapper');
              if (fieldWrapper && fieldWrapper.dataset.frontendEditing) {
                editingElement.href = editingElement.href + '?selector=' + fieldWrapper.dataset.frontendEditing;
              }
            }
            editingElement.addEventListener('click', editingClick);
          }
        });
      });
    }
  };

})(Drupal, once);
