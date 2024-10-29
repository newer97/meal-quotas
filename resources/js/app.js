import "./bootstrap";

import { Html5QrcodeScanner } from "html5-qrcode";

window.html5QrcodeScanner = new Html5QrcodeScanner(
    "reader",
    {
        fps: 10,
        qrbox: {
            width: 250,
            height: 250,
        },
    },
    /* verbose= */
    true
);
