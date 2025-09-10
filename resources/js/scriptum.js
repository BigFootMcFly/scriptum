window.scriptum={
    version: "0.3.dev"
};

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
