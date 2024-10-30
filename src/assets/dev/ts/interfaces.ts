import { Hooks } from "@wordpress/hooks/build-types";
import { IC_AJAX } from "./models/IC_AJAX";
import {IC_MetaBox} from "./models/IC_MetaBox";

/**
 * @since   1.0.0
 * @version 1.0.0
 */
export interface Window {
    wp: {
        hooks: Hooks;
        [x: string]: any;
    };
    dws_ic_ajax: IC_AJAX;
    dws_ic_ajax_vars: any;
    dws_ic_metabox: IC_MetaBox;
    dws_ic_metabox_vars: any;
    [x: string]: any;
}

/**
 * @since   1.0.0
 * @version 1.0.0
 */
export interface IC_AJAX_Callbacks {
    success?( data: any ): void;
    error?( data: any ): void;
    fail?( jqXHR: JQuery.jqXHR, textStatus: JQuery.Ajax.ErrorTextStatus ): void;
    always?(): void;
}
