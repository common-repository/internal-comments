import { IC_AJAX_Callbacks, Window } from "../interfaces";
import * as jQuery from "jquery";
declare let window: Window;

/**
 * @since   1.0.0
 * @version 1.1.0
 */
export class IC_AJAX {
    // region FIELDS AND CONSTANTS

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @private
     */
    private static instance: IC_AJAX;

    // endregion

    // region CONSTRUCTORS

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    private constructor() {}

    // endregion

    // region METHODS

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    public static get_instance(): IC_AJAX {
        if ( ! IC_AJAX.instance ) {
            IC_AJAX.instance = new IC_AJAX();
        }

        return IC_AJAX.instance;
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   method
     * @param   action
     * @param   data
     * @param   callbacks
     * @param   filter_args
     */
    public make_request( method: string, action: string, data: object, callbacks: IC_AJAX_Callbacks = {}, ...filter_args: any[] ) {
        jQuery.ajax(
            window.ajaxurl,
            {
            method : method,
            data   : window.wp.hooks.applyFilters( action.replace( 'dws_ic_', 'dws_ic.ajax.' ) + '.data', {
                action : action,
                ...data
            }, ...filter_args )
        } )
            .done( function( data ) {
                if ( data.success ) {
                    if ( undefined !== callbacks.success ) {
                        callbacks.success( data.data );
                    }
                } else {
                    if ( undefined === callbacks.error ) {
                        alert( data.data );
                    } else {
                        callbacks.error( data.data );
                    }
                }
            } )
            .fail( function( jqXHR, textStatus ) {
                console.log( jqXHR, textStatus );

                if ( undefined === callbacks.fail ) {
                    const error_message = jqXHR.status + ': ' + jqXHR.statusText;
                    alert( window.dws_ic_ajax_vars.ajax_fail_msg.replace( '%error_msg%', error_message ) );
                } else {
                    callbacks.fail( jqXHR, textStatus );
                }
            } )
            .always( function() {
                if ( undefined !== callbacks.always ) {
                    callbacks.always();
                }
            } );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   post_id
     * @param   nonce
     * @param   data
     * @param   callbacks
     * @param   filter_args
     */
    public insert_comment( post_id: number, nonce: string, data: object,  callbacks: IC_AJAX_Callbacks, ...filter_args: any[] ) {
        this.make_request( 'POST', 'dws_ic_insert_comment', {
            post_id  : post_id,
            _wpnonce : nonce,
            ...data
        }, callbacks, ...filter_args );
    }

    /**
     * @since   1.1.0
     * @version 1.1.0
     *
     * @param   post_id
     * @param   callbacks
     * @param   filter_args
     */
    public get_comments( post_id: number, callbacks: IC_AJAX_Callbacks, ...filter_args: any[] ) {
        this.make_request( 'GET', 'dws_ic_get_comments', {
            post_id  : post_id,
            _wpnonce : window.dws_ic_ajax_vars.get_comments_nonce,
        }, callbacks, ...filter_args );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   comment_id
     * @param   callbacks
     * @param   filter_args
     */
    public get_comment( comment_id: number, callbacks: IC_AJAX_Callbacks, ...filter_args: any[] ) {
        this.make_request( 'GET', 'dws_ic_get_comment', {
            comment_id : comment_id,
            _wpnonce   : window.dws_ic_ajax_vars.get_comments_nonce,
        }, callbacks, ...filter_args );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   comment_id
     * @param   nonce
     * @param   data
     * @param   callbacks
     * @param   filter_args
     */
    public update_comment( comment_id: number, nonce: string, data: object, callbacks: IC_AJAX_Callbacks, ...filter_args: any[] ) {
        this.make_request( 'POST', 'dws_ic_update_comment', {
            comment_id : comment_id,
            _wpnonce   : nonce,
            ...data,
        }, callbacks, ...filter_args );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   comment_id
     * @param   nonce
     * @param   callbacks
     * @param   filter_args
     */
    public trash_comment( comment_id: number, nonce: string, callbacks: IC_AJAX_Callbacks, ...filter_args: any[] ) {
        this.make_request( 'POST', 'dws_ic_trash_comment', {
            comment_id : comment_id,
            _wpnonce   : nonce,
        }, callbacks, ...filter_args );
    }

    // endregion
}
