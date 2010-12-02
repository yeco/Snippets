/**
	simple script that resizes its parent iframe by getting its id.
**/

function resizeFrame() {
    parent.document.getElementById(getID()).style.height = document.body.scrollHeight;
	// window.top.console.log(getID())
}

function getID()
 {
    var _t;
    if (window.frameElement) {
        _t = window.frameElement;
    } else if (window.top) {
        _t = window.top;
        var _u = location.href;
        var iFs = _t.document.getElementsByTagName('iframe');
        var x,
        i = iFs.length;
        while (i--) {
            x = iFs[i];
            if (x.src && x.src == _u) {
                _t = x;
                break;
            }
        }
    }
    if (_t) {
        return _t.id;
    } else {
        }
}

window.onload = resizeFrame;

