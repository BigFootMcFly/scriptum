window.zaj="ZAJ";


window.serializePointerEvent = function(e) {
    return {
        ctrl: e.ctrlKey,
        shift: e.shiftKey,
        alt: e.altKey,
        meta: e.metaKey,
        button: e.button,
        x: e.clientX,
        y: e.clientY
    }
}

PointerEvent.prototype.serializePointerEvent = function() {
    let e = this;
    return {
        ctrl: e.ctrlKey,
        shift: e.shiftKey,
        alt: e.altKey,
        meta: e.metaKey,
        button: e.button,
        x: e.clientX,
        y: e.clientY
    }

}