<?php

namespace DeepWebSolutions\Plugins\InternalComments;

use DWS_IC_Deps\DeepWebSolutions\Framework\Core\AbstractPluginFunctionality;
use DWS_IC_Deps\DeepWebSolutions\Framework\Helpers\DataTypes\Integers;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\Actions\SetupHooksTrait;
use DWS_IC_Deps\DeepWebSolutions\Framework\Utilities\Hooks\HooksService;

\defined( 'ABSPATH' ) || exit;

/**
 * Manipulates WP queries to hide our internal comments from the frontend and other unwanted areas.
 *
 * @since   1.0.0
 * @version 1.0.0
 * @author  Antonius Hegyes <a.hegyes@deep-web-solutions.com>
 */
class Query extends AbstractPluginFunctionality {
	// region TRAITS

	use SetupHooksTrait;

	// endregion

	// region INHERITED METHODS

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	protected function get_di_container_children(): array {
		return array( Query\ScopedHooks::class );
	}

	/**
	 * {@inheritDoc}
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 */
	public function register_hooks( HooksService $hooks_service ): void {
		$hooks_service->add_filter( 'comments_clauses', $this, 'exclude_internal_comments_from_query' );
		$hooks_service->add_filter( 'comment_feed_where', $this, 'exclude_internal_comments_from_feed_where' );

		$hooks_service->add_filter( 'get_comments_number', $this, 'exclude_internal_comments_from_post_count', 9999, 2 );
		$hooks_service->add_filter( 'wp_count_comments', $this, 'wp_count_comments', 9999, 2 );
	}

	// endregion

	// region HOOKS

	/**
	 * Excludes internal comments from queries and RSS.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   array   $clauses    A compacted array of comment query clauses.
	 *
	 * @return  array
	 */
	public function exclude_internal_comments_from_query( array $clauses ): array {
		$clauses['where'] .= ( $clauses['where'] ? ' AND ' : '' ) . " comment_type != 'dws_internal' ";
		return $clauses;
	}

	/**
	 * Exclude internal comments from queries and RSS.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @param   string  $where  The WHERE clause of the query.
	 *
	 * @return  string
	 */
	public function exclude_internal_comments_from_feed_where( string $where ): string {
		return $where . ( $where ? ' AND ' : '' ) . " comment_type != 'dws_internal' ";
	}

	/**
	 * Exclude internal comments from post comments count.
	 *
	 * @since   1.0.0
	 * @version 1.1.0
	 *
	 * @param   string|int  $count      A string representing the number of comments a post has, otherwise 0.
	 * @param   int         $post_id    The ID of the post whose comments are being counted.
	 *
	 * @return  int
	 */
	public function exclude_internal_comments_from_post_count( $count, int $post_id ): int {
		global $wpdb;

		if ( 'trash' === \get_post_status( $post_id ) ) {
			$ic_total = \count( dws_ic_get_internal_comments_from_trash_meta( $post_id, '1', true ) );
		} else {
			$where_clause = $wpdb->prepare( 'WHERE comment_type = "dws_internal" AND comment_approved = "1" AND comment_post_ID = %d', $post_id );
			$ic_total     = Integers::maybe_cast( $wpdb->get_var( "SELECT COUNT(*) FROM $wpdb->comments $where_clause" ), 0 ); // phpcs:ignore WordPress.DB
		}

		$count = Integers::maybe_cast( $count, 0 );
		return \max( $count - $ic_total, 0 );
	}

	/**
	 * Attempt to take over the WP comment count logic and subtract all internal comments from said logic.
	 * Inspired by WooCommerce's logic for hiding order note comments.
	 *
	 * @since   1.0.0
	 * @version 1.0.0
	 *
	 * @SuppressWarnings(PHPMD.CyclomaticComplexity)
	 *
	 * @param   array|object    $count      An empty array or an object containing comment counts.
	 * @param   int             $post_id    Restrict the comment counts to the given post. Default 0, which indicates that
	 *                                      comment counts for the whole site will be retrieved.
	 *
	 * @return  object
	 */
	public function wp_count_comments( $count, int $post_id ): object {
		global $wpdb;

		// Either use the other plugins' count or retrieve it ourselves.
		if ( empty( $count ) ) {
			$count = \get_comment_count( $post_id );
		} else {
			$count = (array) $count;
		}

		// Get the stats of our own internal comments.
		$where_clause = 'WHERE comment_type = "dws_internal"';
		if ( $post_id > 0 ) {
			$where_clause .= $wpdb->prepare( ' AND comment_post_ID = %d', $post_id );
		}

		// phpcs:disable
		$ic_totals = (array) $wpdb->get_results(
			"SELECT comment_approved, COUNT(*) AS total
			FROM $wpdb->comments
			$where_clause
			GROUP BY comment_approved",
			ARRAY_A
		);
		// phpcs:enable

		// Subtract the internal comments from the count or replace the count with the internal comments count.
		$is_other_query = \apply_filters( $this->get_hook_tag( 'is_other_query' ), true );

		foreach ( $ic_totals as $row ) {
			switch ( $row['comment_approved'] ) {
				case 'trash':
					if ( $is_other_query ) {
						$count['trash'] -= $row['total'];
					} else {
						$count['trash'] = $row['total'];
					}

					break;
				case 'post-trashed':
					if ( $is_other_query ) {
						$count['post-trashed'] -= $row['total'];
					} else {
						$count['post-trashed'] = $row['total'];
					}

					break;
				case 'spam':
					if ( $is_other_query ) {
						$count['spam']           -= $row['total'];
						$count['total_comments'] -= $row['total'];
					} else {
						$count['spam']           = $row['total'];
						$count['total_comments'] = $row['total'];
					}

					break;
				case '1':
					if ( $is_other_query ) {
						$count['approved']       -= $row['total'];
						$count['total_comments'] -= $row['total'];
						$count['all']            -= $row['total'];
					} else {
						$count['approved']       = $row['total'];
						$count['total_comments'] = $row['total'];
						$count['all']            = $row['total'];
					}

					break;
				case '0':
					if ( $is_other_query ) {
						if ( isset( $count['awaiting_moderation'] ) ) {
							$count['awaiting_moderation'] -= $row['total'];
						}
						$count['total_comments'] -= $row['total'];
						$count['all']            -= $row['total'];
					} else {
						$count['awaiting_moderation'] = $row['total'];
						$count['total_comments']      = $row['total'];
						$count['all']                 = $row['total'];
					}

					break;
				default:
					break;
			}
		}

		if ( isset( $count['awaiting_moderation'] ) ) {
			$count['moderated'] = $count['awaiting_moderation'];
			unset( $count['awaiting_moderation'] );
		}

		return (object) $count;
	}

	// endregion
}
