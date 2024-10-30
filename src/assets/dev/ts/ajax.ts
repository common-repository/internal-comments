import jQuery from "jquery";
import { Window } from "./interfaces";
import { IC_AJAX } from "./models/IC_AJAX";
declare let window: Window;

jQuery( function () {
    // Instantiate the AJAX singleton class.
    window.dws_ic_ajax = IC_AJAX.get_instance();
} );
