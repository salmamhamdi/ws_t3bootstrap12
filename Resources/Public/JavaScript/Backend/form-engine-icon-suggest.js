/*
 * This file is part of the TYPO3 CMS project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 * The TYPO3 project - inspiring people to share!
 */
import "@wapplersystems/ws_t3bootstrap/form-engine/element/suggest/icon-result-container.js";
import DocumentService from "@typo3/core/document-service.js";
import FormEngine from "@typo3/backend/form-engine.js";
import RegularEvent from "@typo3/core/event/regular-event.js";
import DebounceEvent from "@typo3/core/event/debounce-event.js";
import AjaxRequest from "@typo3/core/ajax/ajax-request.js";

class FormEngineIconSuggest {
  constructor(e) {
    this.currentRequest = null, this.handleKeyDown = e => {
      if ("ArrowDown" !== e.key) "Escape" === e.key && (e.preventDefault(), this.resultContainer.hidden = !0); else {
        e.preventDefault();
        JSON.parse(this.resultContainer.getAttribute("results"))?.length > 0 && (this.resultContainer.hidden = !1);
        this.resultContainer.querySelector("wapplersystems-backend-formengine-suggest-icon-result-item")?.focus()
      }
    }, this.element = e, DocumentService.ready().then((() => {
      this.initialize(e), this.registerEvents()
    }))
  }

  initialize(e) {
    const t = e.closest(".t3-form-suggest-container");
    this.resultContainer = document.createElement("wapplersystems-backend-formengine-suggest-icon-result-container"), this.resultContainer.hidden = !0, t.append(this.resultContainer)
  }

  registerEvents() {
    new RegularEvent("wapplersystems:formengine:icon-chosen", (e => {
      let placeholder = document.querySelector('input[name="' + this.element.dataset.field + '[placeholder]"]');
      let valueField = document.querySelector('input[name="' + this.element.dataset.field + '"]');
      let iconField = placeholder.parentNode.parentNode.querySelector(".input-group-addon");
      valueField.value = e.detail.element.value;
      placeholder.value = e.detail.element.class;
      iconField.innerHTML = e.detail.element.svg;

        FormEngine.Validation.markFieldAsChanged(document.querySelector('input[name="' + this.element.dataset.field + '"]')),
        this.resultContainer.hidden = !0
    })).bindTo(this.resultContainer), new RegularEvent("focus", (() => {
      JSON.parse(this.resultContainer.getAttribute("results"))?.length > 0 && (this.resultContainer.hidden = !1)
    })).bindTo(this.element), new RegularEvent("blur", (e => {
      "wapplersystems-backend-formengine-suggest-icon-result-item" !== e.relatedTarget?.tagName.toLowerCase() && (this.resultContainer.hidden = !0)
    })).bindTo(this.element), new DebounceEvent("input", (e => {
      this.currentRequest instanceof AjaxRequest && this.currentRequest.abort();
      const t = e.target;
      t.value.length < parseInt(t.dataset.minchars, 10) || (this.currentRequest = new AjaxRequest(TYPO3.settings.ajaxUrls.icon_suggest), this.currentRequest.post({
        value: t.value,
        signature: t.dataset.signature,
        collections: t.dataset.collections
      }).then((async e => {
        const t = await e.raw().text();
        this.resultContainer.setAttribute("results", t), this.resultContainer.hidden = !1
      })))
    })).bindTo(this.element), new RegularEvent("keydown", this.handleKeyDown).bindTo(this.element)
  }
}

export default FormEngineIconSuggest;
