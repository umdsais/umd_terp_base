/**
 * @file
 * Generic hash-open behavior for UMD web components.
 *
 * When the page URL contains a hash (e.g. #accordion-item-123), this behavior
 * finds the matching element by ID and sets data-visual-open="true" on it.
 * Any UMD web component that supports the data-visual-open observed attribute
 * (e.g. umd-element-accordion-item, umd-element-tab-item) will respond by
 * opening/expanding with animation.
 *
 * To enable for a component, add an id attribute to the element in its Twig
 * template and attach this library:
 *   {{ attach_library('umd_terp_base/element-hash-open') }}
 *
 * Also responds to hashchange events, so in-page anchor links work as well.
 */

(function (Drupal) {
  'use strict';

  function openElementFromHash() {
    const hash = window.location.hash;
    if (!hash) return;

    const target = document.querySelector(hash);
    if (!target) return;

    target.setAttribute('data-visual-open', 'true');
    target.scrollIntoView({ behavior: 'smooth', block: 'start' });
  }

  Drupal.behaviors.umdElementHashOpen = {
    attach(context, settings, once) {
      once('umd-element-hash-open', 'html', context).forEach(() => {
        openElementFromHash();
        window.addEventListener('hashchange', openElementFromHash);
      });
    },
  };
}(Drupal));
