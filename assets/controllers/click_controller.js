import { Controller } from "@hotwired/stimulus";

export default class extends Controller{

    count = 0;
    static targets = ['count'];

    incrementar() {
        this.count++;
        this.countTarget.innerHTML = this.count;
    }
}