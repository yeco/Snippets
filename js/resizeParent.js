/**
	simple script that resizes its parent iframe by getting its id.
**/

function resizeFrame() {
    parent.document.getElementById(getID()).style.height = document.body.scrollHeight;
	// window.top.console.log(getID())
}

function getID()
 {
    var myTop;
    if (window.frameElement) {
        myTop = window.frameElement;
    } else if (window.top) {
        myTop = window.top;
        var myURL = location.href;
        var iFs = myTop.document.getElementsByTagName('iframe');
        var x,
        i = iFs.length;
        while (i--) {
            x = iFs[i];
            if (x.src && x.src == myURL) {
                myTop = x;
                break;
            }
        }
    }
    if (myTop) {
        return myTop.id;
    } else {
        }
}

window.onload = resizeFrame;

