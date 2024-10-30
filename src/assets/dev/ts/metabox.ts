import jQuery from "jquery";
import { Window } from "./interfaces";
import { IC_MetaBox } from "./models/IC_MetaBox";
declare let window: Window;

jQuery( function ( $ ) {
    // Instantiate the metabox singleton class and start listening to events.
    window.dws_ic_metabox = IC_MetaBox.get_instance();
} );
