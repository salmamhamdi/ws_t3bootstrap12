
import DocumentService from "@typo3/core/document-service.js";
import FormEngineIconSuggest from "@wapplersystems/ws_t3bootstrap/form-engine-icon-suggest.js";

class SelectIconElement {
  constructor(e, options) {
    this.element = null, this.options = options, DocumentService.ready().then((() => {
      this.element = document.getElementById(e), this.registerEventHandler(), this.registerSuggest()
    }))
  }

  registerEventHandler() {

  }

  registerSuggest() {
    let e;
    null !== (e = this.element.closest(".t3js-formengine-field-item").querySelector(".t3-form-suggest")) && new FormEngineIconSuggest(e)
  }
}

export default SelectIconElement;
