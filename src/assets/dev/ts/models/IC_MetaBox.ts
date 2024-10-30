import * as jQuery from "jquery";
import { Window } from "../interfaces";
declare let window: Window;

 /**
 * @since   1.0.0
 * @version 1.0.0
 */
 export class IC_MetaBox {
    // region FIELDS AND CONSTANTS

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @private
     */
    private static instance: IC_MetaBox;

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    readonly $metabox: JQuery;

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    readonly $comments_wrapper: JQuery;

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    readonly $textarea: JQuery;

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    readonly $overlay: JQuery;

    // endregion

    // region CONSTRUCTORS

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    private constructor() {
        this.$metabox          = jQuery( '#dws-internal-comments' );
        this.$comments_wrapper = this.$metabox.find( '.dws-internal-comments' );
        this.$textarea         = this.$metabox.find( '#new-internal-comment' );
        this.$overlay          = this.$metabox.find( '.dws-internal-comments-overlay' );

        this.init();
    }

    // endregion

    // region METHODS

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    public static get_instance(): IC_MetaBox {
        if ( ! IC_MetaBox.instance ) {
            IC_MetaBox.instance = new IC_MetaBox();
        }

        return IC_MetaBox.instance;
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @protected
     */
    protected init(): void {
        const instance = this;

        this.$metabox.on( 'click', 'button.save-insert-internal-comment', function( e ) {
            const comment = instance.$textarea.val();

            if ( 'string' !== typeof comment || comment.trim().length === 0 ) {
                alert( window.dws_ic_metabox_vars.empty_comment_msg );
                return;
            }

            e.preventDefault();
            instance.insert_comment( comment );
        } );
        this.$metabox.on( 'click', '.dws-internal-comment__actions .internal-comment-action ', function( e ) {
            const $target          = jQuery( e.target ),
                action           = $target.data( 'action' ),
                nonce            = $target.data( 'nonce' ),
                $comment_wrapper = $target.parents( 'li.dws-internal-comment' ),
                comment_id       = parseInt( $comment_wrapper.attr( 'rel' ) );

            if ( 'string' === typeof action && action.trim().length > 0 ) {
                window.wp.hooks.doAction( 'dws_ic.metabox.action', action, comment_id, nonce, e, $target, $comment_wrapper, instance );
                window.wp.hooks.doAction( 'dws_ic.metabox.action.' + action, comment_id, nonce, e, $target, $comment_wrapper, instance );
            }
        } );

        window.wp.hooks.addAction( 'dws_ic.metabox.action', 'dws/internal-comments/metabox', this.comment_action_click_handler, 10 );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   action
     * @param   comment_id
     * @param   nonce
     * @param   e
     *
     * @protected
     */
    protected comment_action_click_handler( action: string, comment_id: number, nonce: string, e: JQuery.ClickEvent ) {
        switch ( action ) {
            case 'trash':
                e.preventDefault();
                IC_MetaBox.get_instance().trash_comment( comment_id, nonce );
                break;
        }
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     */
    public refresh_comments() {
        const instance = this;
        instance.$overlay.show();

        window.dws_ic_ajax.make_request(
            'GET', 'dws_ic_get_comments_list_output',
            { post_id : window.dws_ic_metabox_vars.post_id, },
            {
                success( data: any ) { instance.$comments_wrapper.html( data ); },
                always() { instance.$overlay.hide(); },
            }
        );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   content
     */
    public insert_comment( content: string ) {
        const instance = this;
        instance.$overlay.show();

        window.dws_ic_ajax.insert_comment(
            window.dws_ic_metabox_vars.post_id,
            window.dws_ic_metabox_vars.insert_comment_nonce,
            { content: content },
            {
                success() { instance.$textarea.val( '' ); instance.refresh_comments(); },
                always() { instance.$overlay.hide(); },
            },
            instance
        );
    }

    /**
     * @since   1.0.0
     * @version 1.0.0
     *
     * @param   comment_id
     * @param   nonce
     */
    public trash_comment( comment_id: number, nonce: string ) {
        const instance = this;
        instance.$overlay.show();

        window.dws_ic_ajax.trash_comment( comment_id, nonce, {
            success() { instance.refresh_comments(); },
            always() { instance.$overlay.hide(); },
        }, instance );
    }

    // endregion
}